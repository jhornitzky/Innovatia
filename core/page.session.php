<?
if(!isset($_SESSION)) { 
	session_start();
}	

$session = array(); //To hold for values when they get written off
foreach ($_SESSION as $key => $value) {
	global $session;
	$session[$key] = $value;
} 
?>