<?
/**
 * ENTRY point for the innoworks application
 * Passes requests to login or to innoworks UI (through ui.core)
 */
require_once("core/innoworks.connector.php");
require_once("core/page.getFunctions.php");
import("user.service");

//All Devices
if(isLoggedIn())
{
	require_once("core/ui.core.php");
}
else
{
	require_once("ui/login/welcome.php"); 
	//require_once("ui/login.slide/welcome.php"); 
}
?>