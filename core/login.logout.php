<?
require_once("innoworks.connector.php");

session_destroy();

//unset cookies too
setcookie('innoname', '', time()-1000, '/');
setcookie('innohash', '', time()-1000, '/');

header("Location: ".$serverRoot);
?>