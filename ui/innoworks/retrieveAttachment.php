<?
require_once("thinConnector.php");
import("idea.service");

if (isset($_GET['action'])) {
	switch ($_GET['action']) {
		case "retrieveAttachment":
			retrieveAttachment($_GET['actionId']); 
			break;
	} 
}
?>