<?php

/**
 * Pagarme-split WP_Http helper functions
 *
 * PHP version 5
 *
 * @category Integration
 * @package  Pagarme-split/Integration
 * @author   Barradev Consulting <contato@barradev.com>
 * @license  Attribution-ShareAlike https://creativecommons.org/licenses/by-sa/4.0/
 * @version  GIT: $id$
 * @link     https://bitbucket.org/barradev_isquare/woocommerce-pagarme-split
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function debug_request ()
{
    return defined('WP_DEBUG_REQUESTS') ? true : false;
}

add_filter('http_response', 'filter_http_response', 10, 3);
/**
* Hook up the HTTP API response immediately before the response is returned.
*
* @param array  $response HTTP response.
* @param array  $request  HTTP request arguments.
* @param string $url      The request URL.
*
* @return array $response Returns the original response values
*/
function filter_http_response($response, $request, $url)
{
    if (!trigger_requests($url)){
        return $response;
    }
    if (debug_request()) {
        write_log("Request url: {$url}");
        write_log($request);
        // write_log($response);
    }
    $headers = wp_json_encode($request['headers']);
    write_log("Request method: {$request['method']}");
    write_log("Request headers: {$headers}");
    if (count($request['body']) < 100) {
        if (is_array($request['body'])){
            $body = wp_json_encode($request['body']);
        }else{
            $body = $request['body'];
        }
        write_log("Request body: {$body}");
    }
    return $response;
}

function trigger_requests($url)
{
    $cnt_url = substr_count($url, 'recorrente.net/1/transactions');
    if ($cnt_url < 1) {
        if (debug_request()) {
            error_log("Not trigger filter http_api_request, continue normal as request");
            error_log("url: " . $url);
        }
        return false;
    } else {
        return true;
    }
}

add_filter('pre_http_request', 'http_api_request', 5, 3);
/**
* Hook filter pre_http_request and override preempt WP_Http::request
* only changes request if necessary 
*
* Returning a non-false value from the filter will short-circuit the HTTP request and return
* early with that value. A filter should return either:
*
*  - An array containing 'headers', 'body', 'response', 'cookies', and 'filename' elements
*  - A WP_Error instance
*  - boolean false (to avoid short-circuiting the response)
*
* Returning any other value may result in unexpected behaviour.
*
* @since 2.9.0
*
* @param false|array|WP_Error $preempt Whether to preempt an HTTP request's return value. Default false.
* @param array               $r        HTTP request arguments.
* @param string              $url      The request URL.
* 
* @return false|array|WP_Error  Whether to preempt an HTTP request's return value. Default false.
*/
function http_api_request($preempt, $r, $url)
{
    if (!trigger_requests($url)) {
        return false;
    }
    error_log("Trigged filter http_api_request, url: {$url}");
    if ( function_exists( 'wp_kses_bad_protocol' ) ) {
        if ( $r['reject_unsafe_urls'] ) {
            $url = wp_http_validate_url( $url );
        }
        if ( $url ) {
            $url = wp_kses_bad_protocol( $url, array( 'http', 'https', 'ssl' ) );
        }
    }
 
    $arrURL = @parse_url( $url );
 
    if ( empty( $url ) || empty( $arrURL['scheme'] ) ) {
        return new WP_Error('http_request_failed', __('A valid URL was not provided.'));
    }
 
    // if ( $this->block_request( $url ) ) {
    //     return new WP_Error( 'http_request_failed', __( 'User has blocked requests through HTTP.' ) );
    // }
 
    // If we are streaming to a file but no filename was given drop it in the WP temp dir
    // and pick its name using the basename of the $url
    if ( $r['stream'] ) {
        if ( empty( $r['filename'] ) ) {
            $r['filename'] = get_temp_dir() . basename( $url );
        }
 
        // Force some settings if we are streaming to a file and check for existence and perms of destination directory
        $r['blocking'] = true;
        if ( ! wp_is_writable( dirname( $r['filename'] ) ) ) {
            return new WP_Error( 'http_request_failed', __( 'Destination directory for file streaming does not exist or is not writable.' ) );
        }
    }
 
    if ( is_null( $r['headers'] ) ) {
        $r['headers'] = array();
    }
 
    // WP allows passing in headers as a string, weirdly.
    if ( ! is_array( $r['headers'] ) ) {
        $processedHeaders = WP_Http::processHeaders( $r['headers'] );
        $r['headers'] = $processedHeaders['headers'];
    }
 
    // Recorrente.net hack, always json.
    if (is_array($r['body'])) {
        $r['body'] = wp_json_encode($r['body']);
        // if ($r['method'] == 'GET') {
        $r['data_format'] = 'body';
        // }
    }
    // Setup arguments
    $headers = $r['headers'];
    $data = $r['body'];
    $type = $r['method'];
    $options = array(
        'timeout' => $r['timeout'],
        'useragent' => $r['user-agent'],
        'blocking' => $r['blocking'],
        'hooks' => new Requests_Hooks(),
    );
    if (isset($r['data_format'])) {
        $options['data_format'] = $r['data_format'];
    }
 
    if ( $r['stream'] ) {
        $options['filename'] = $r['filename'];
    }
    if ( empty( $r['redirection'] ) ) {
        $options['follow_redirects'] = false;
    } else {
        $options['redirects'] = $r['redirection'];
    }
 
    // Use byte limit, if we can
    if ( isset( $r['limit_response_size'] ) ) {
        $options['max_bytes'] = $r['limit_response_size'];
    }
 
    // If we've got cookies, use and convert them to Requests_Cookie.
    if ( ! empty( $r['cookies'] ) ) {
        $options['cookies'] = WP_Http::normalize_cookies( $r['cookies'] );
    }
 
    // SSL certificate handling
    if ( ! $r['sslverify'] ) {
        $options['verify'] = false;
    } else {
        $options['verify'] = $r['sslcertificates'];
    }
 
    // All non-GET/HEAD requests should put the arguments in the form body.
    if ( 'HEAD' !== $type && 'GET' !== $type ) {
        $options['data_format'] = 'body';
    }
 
    /**
     * Filters whether SSL should be verified for non-local requests.
     *
     * @since 2.8.0
     *
     * @param bool $ssl_verify Whether to verify the SSL connection. Default true.
     */
    $options['verify'] = apply_filters( 'https_ssl_verify', $options['verify'] );
 
    // Check for proxies.
    $proxy = new WP_HTTP_Proxy();
    if ( $proxy->is_enabled() && $proxy->send_through_proxy( $url ) ) {
        $options['proxy'] = new Requests_Proxy_HTTP( $proxy->host() . ':' . $proxy->port() );
 
        if ( $proxy->use_authentication() ) {
            $options['proxy']->use_authentication = true;
            $options['proxy']->user = $proxy->username();
            $options['proxy']->pass = $proxy->password();
        }
    }
 
    try {
        error_log("Options to request:" . var_export($options, true));
        $requests_response = Requests::request( $url, $headers, $data, $type, $options );
 
        // Convert the response into an array
        $http_response = new WP_HTTP_Requests_Response( $requests_response, $r['filename'] );
        $response = $http_response->to_array();
 
        // Add the original object to the array.
        $response['http_response'] = $http_response;
    }
    catch ( Requests_Exception $e ) {
        $response = new WP_Error( 'http_request_failed', $e->getMessage() );
    }
 
    /**
     * Fires after an HTTP API response is received and before the response is returned.
     *
     * @since 2.8.0
     *
     * @param array|WP_Error $response HTTP response or WP_Error object.
     * @param string         $context  Context under which the hook is fired.
     * @param string         $class    HTTP transport used.
     * @param array          $args     HTTP request arguments.
     * @param string         $url      The request URL.
     */
    do_action( 'http_api_debug', $response, 'response', 'Requests', $r, $url );
    if ( is_wp_error( $response ) ) {
        return $response;
    }
 
    if ( ! $r['blocking'] ) {
        return array(
            'headers' => array(),
            'body' => '',
            'response' => array(
                'code' => false,
                'message' => false,
            ),
            'cookies' => array(),
            'http_response' => null,
        );
    }
 
    /**
     * Filters the HTTP API response immediately before the response is returned.
     *
     * @since 2.9.0
     *
     * @param array  $response HTTP response.
     * @param array  $r        HTTP request arguments.
     * @param string $url      The request URL.
     */
    return apply_filters( 'http_response', $response, $r, $url );
}

function http_api_debug_request($response, $arg1, $arg2, $r, $url)
{
    // write_log($response);
    // write_log($arg1);
    // write_log($arg2);
    // write_log($r);
    // write_log($url);

    write_log("Request url: {$url}");
    write_log("Request method: {$r['method']}");
    write_log("Request body: {$r['headers']}");
    write_log("Request body: {$r['body']}");
    write_log($r);
}

// add_action('http_api_debug', 'http_api_debug_request', 10, 5);