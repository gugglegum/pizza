<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

//define("APPLICATION_ENV", "development");
//define("APPLICATION_ENV", "production");
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(__DIR__ . '/app'));

defined('PUBLIC_PATH')
    || define('PUBLIC_PATH', __DIR__ . "/public");

defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

ini_set("include_path", implode(PATH_SEPARATOR, array(
    APPLICATION_PATH . "/../library",
)));

require_once APPLICATION_PATH . "/library/Autoloader.php";
$autoloader = new \App\Autoloader();
$autoloader->registerNamespaces(array(
    "App\\Controllers" => APPLICATION_PATH . "/controllers",
    "App\\Models" => APPLICATION_PATH . "/models",
    "App\\Forms" => APPLICATION_PATH . "/forms",
    "App" => APPLICATION_PATH . "/library",
    "Zend" => APPLICATION_PATH . "/../library/Zend",
    "" => APPLICATION_PATH . "/../library",
));
spl_autoload_register(array($autoloader, "autoload"), true);

require_once(APPLICATION_PATH . "/Bootstrap.php");
$bootstrap = new \App\Bootstrap();

$errorHandler = new \App\ErrorHandler($bootstrap);
register_shutdown_function(array($errorHandler, "shutdown"));
