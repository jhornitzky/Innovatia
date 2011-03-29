<?
/**
 * Central connector exposing config variables, dbconnections and more
 * Should be included by most pages
 */

//default required libraries
require_once("innoworks.config.php");
//override paths
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) {
	$serverUrl = 'https://' . $serverUrl;
} else {
	$serverUrl = 'http://' . $serverUrl;
}

//continue with rest of loaders
require_once("innoworks.util.php");
require_once("page.session.php");
require_once("db.connector.php");
require_once("debug.logger.php");
require_once("ui.component.php");
require_once("security.service.php");
require_once("auto.object.php");
?>
