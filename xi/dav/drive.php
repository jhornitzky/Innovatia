<?php
require(dirname(__FILE__).'/../../core/innoworks.config.php');
require(dirname(__FILE__).'/Zabre/autoload.php');

define('ROOT_USER', 'innoworksAdmin');
define('ROOT_PWD','nexsight321');

//AUTH
$auth = new Sabre_HTTP_BasicAuth();
$result = $auth->getUserPass();

//first try root user and password
if (ROOT_USER == $result[0] && ROOT_PWD == $result[1]) {
	$root = true;
} else {
	$auth->requireLogin();
	die('Need correct login');
}

global $serverRoot;

//SET DOC PATHS
if ($root) 
	$tree = new Sabre_DAV_ObjectTree(new Sabre_DAV_FS_Directory($_SERVER['DOCUMENT_ROOT'].$serverRoot));

//CREATE SERVER
$server = new Sabre_DAV_Server($tree);
$server->setBaseUri($serverRoot.'xi/dav/drive.php/');

//PLUGINS
$lockBackend = new Sabre_DAV_Locks_Backend_FS($_SERVER['DOCUMENT_ROOT'].$serverRoot.'dav/locks');
$lockPlugin = new Sabre_DAV_Locks_Plugin($lockBackend);
$server->addPlugin($lockPlugin);
$server->addPlugin(new Sabre_DAV_Browser_Plugin());
$server->addPlugin(new Sabre_DAV_Mount_Plugin());

//RENDER
$server->exec();
?>