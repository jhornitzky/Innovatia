<?
require_once("thinConnector.php");
require_once("groups.ui.php");
import("group.service");

if (isset($_GET) && $_GET != '') {
	switch ($_GET['action']) {
		case "getGroups":
			renderDefault();
			break;
		case "getGroupDetails":
			renderDetails($_GET['actionId']);
			break;
		case "getAddUser":
			renderAddUser($_GET['actionId'], $_SESSION['innoworks.ID']);
			break;
		case "getAddIdea":
			renderAddIdea($_GET['actionId'], $_SESSION['innoworks.ID']);
			break;
	}
}

if (isset($_POST) && $_POST != '') {
	switch ($_POST['action']) {
		case "addGroup":
			echo "Creating Group.. ";
			$opts = array('title' => $_POST['title'], 'userId' => $_SESSION['innoworks.ID']);
			unset($opts['action']);
			$group = createGroup($opts);
			echo "Response Code: ".$group;
			break;
		case "deleteGroup":
			echo "Deleting Group... ";
			$group = deleteGroup($_POST['groupId'],$_SESSION['innoworks.ID']);
			echo "Response Code: ".$group;
			break;
		case "linkUserToGroup":
			echo "Creating Link.. ";
			$group = linkGroupToUser($_POST['groupId'],$_POST['userId']);
			echo "Response Code: ".$group;
			break;
		case "linkIdeaToGroup":
			echo "Creating Link... ";
			$group = linkIdeaToGroup($_POST['groupId'],$_POST['ideaId']);
			echo "Response Code: ".$group;
			break;
		case "unlinkUserToGroup":
			echo "Unlink.. ";
			$group = unlinkGroupToUser($_POST['groupId'],$_POST['userId']);
			echo "Response Code: ".$group;
			break;
		case "unlinkIdeaToGroup":
			echo "Unlink... "; 
			$group = unlinkIdeaToGroup($_POST['groupId'],$_POST['ideaId']);
			echo "Response Code: ".$group;
			break;
	}
}
?>