<?php

/**
 * Plugin Name:       Bachega
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Plugin handle planilha.
 * Version:           0.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * License:           Private
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       DEV-PROGRAMMER-plugin
 * Domain Path:       /languages
 */

define("URL_BACHEGA", plugins_url().'/Bachega');
define("RDIR_BACHEGA", ABSPATH . 'wp-content/plugins/Bachega/');

require 'vendor/autoload.php';
require 'inc/hooks.php';

use Firebase\JWT\JWT;
$payload = array(
    "iat" => 1356999524,
    "nbf" => 1357000000,
    "ABSPATH" => ABSPATH . 'wp-content/uploads/'
);

$exec_keys = JWT::encode($payload, "AdicioneSenha");
define("EXEC_ENCRYPT", $exec_keys);

function menu_unsub_page_callback()
{

    require_once 'header.php';
    if (isset($_GET['page']) and $_GET['page'] == 'exc-main')  require RDIR_BACHEGA . 'pages/main.php';
    if (count($_GET) == 1) require RDIR_BACHEGA . 'pages/upload.php';
    
    if (isset($_GET['list'])) {
        switch ($_GET['list']) {
            case 'upload':
                require RDIR_BACHEGA . 'pages/upload.php';
                break;
                case 'ajax':
                require RDIR_BACHEGA . 'pages/ajax.php';
                break;
            default:
                require_once '404.php';
                break;
        }
    }
    require_once 'footer.php';
}
