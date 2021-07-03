<?php

/**
 * Plugin Name:       Pagame
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Plugin de pagamento.
 * Version:           0.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * License:           Private
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       xxxxxxxxxxxxxxxx
 * Domain Path:       /PT-BR
 */

define("URL_PAGAME", plugins_url().'/pagame');
define("RDIR_PAGAME", ABSPATH . 'wp-content/plugins/pagame/');

require 'vendor/autoload.php';
require 'hooks.php';

use Firebase\JWT\JWT;
$payload = array(
    "iat" => 1356999524,
    "nbf" => 1357000000,
    "ABSPATH" => ABSPATH . 'wp-content/uploads/'
);

$exec_keys = JWT::encode($payload, "mudar1234");
define("EXEC_ENCRYPT", $exec_keys);

function menu_unsub_page_callback()
{

    require_once 'header.php';
    if (isset($_GET['page']) and $_GET['page'] == 'exc-main')  require RDIR_PAGAME . 'pages/main.php';
    //if (count($_GET) == 1) require RDIR_PAGAME . 'pages/boleto.php'; run this action btn fixed
    
    if (isset($_GET['list'])) {
        switch ($_GET['list']) {
            case 'upload':
                require RDIR_PAGAME . 'pages/boleto.php';
                break;
            default:
                require_once '404.php';
                break;
        }
    }
    
    require_once 'footer.php';
}
