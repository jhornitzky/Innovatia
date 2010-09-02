<?
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
}
?>