<?
/**
 * Central connector exposing config variables, dbconnections and more
 * Should be included by most pages
 */

//default required libraries
require_once("innoworks.config.php");

if ($serverTestMode) {
	ini_set('error_reporting', 'E_ALL'); 
	ini_set('display_errors', 'On'); 
} else {
	ini_set('error_reporting', 'E_ALL & ~E_DEPRECATED'); //FIXME test this on server
}

//override paths
if ($serverTestMode && $_SERVER['REMOTE_ADDR'] != '127.0.0.1' && $_SERVER['REMOTE_ADDR'] != '::1')  {
	$serverUrl = $_SERVER['SERVER_ADDR'];
}

if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) {
	$serverUrl = 'https://' . $serverUrl;
} else {
	$serverUrl = 'http://' . $serverUrl;
}

//setup zend
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . "/zend/library/");
require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace(dirname(__FILE__)."/zend/library/");

//continue with rest of loaders
require_once("innoworks.util.php");
require_once("page.session.php");
require_once("db.connector.php");
require_once("debug.logger.php");
require_once("ui.component.php");
require_once("security.service.php");
require_once("auto.object.php");
?>