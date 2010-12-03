<?
require_once("pureConnector.php");
import("attach.service");

if (isset($_GET['action'])) {
	switch ($_GET['action']) {
		case "ideaImg":
			retrieveImageForIdea($_GET['actionId']); 
			break;
		case "groupImg":
			retrieveImageForGroup($_GET['actionId']); 
			break;
		case "userImg":
			retrieveImageForUser($_GET['actionId']); 
			break;
	} 
} 
?>