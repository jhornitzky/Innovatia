<?
require_once("thinConnector.php");
import("attach.service");

if (isset($_GET['action'])) {
	switch ($_GET['action']) {
		case "retrieveAttachment":
			retrieveAttachment($_GET['actionId']); 
			break;
	} 
}
?>