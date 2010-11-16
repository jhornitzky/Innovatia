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
		case "getPublicAddIdea":
			renderPublicAddIdea($_GET['actionId'], $_SESSION['innoworks.ID']);
			break;
		case "getShareForIdea":
			renderIdeaShare($_GET['actionId'], $_SESSION['innoworks.ID']);
			break;
	}
}

if (isset($_POST) && $_POST != '') {
	switch ($_POST['action']) {
		case "addGroup":
			echo "Creating Group.. ";
			$opts = array('title' => $_POST['title'], 'userId' => $_SESSION['innoworks.ID']);
			unset($opts['action']);
			renderServiceResponse(createGroup($opts));
			break;
		case "deleteGroup":
			echo "Deleting Group... ";
			renderServiceResponse(deleteGroup($_POST['groupId'],$_SESSION['innoworks.ID']));
			break;
		case "linkUserToGroup":
			echo "Creating Link.. ";
			renderServiceResponse(linkGroupToUser($_POST['groupId'],$_POST['userId']));
			break;
		case "linkIdeaToGroup":
			echo "Creating Link... ";
			$group = linkIdeaToGroup($_POST['groupId'],$_POST['ideaId']);
			echo "Response Code: ".$group;
			break;
		case "unlinkUserToGroup":
			echo "Unlink.. ";
			renderServiceResponse(unlinkGroupToUser($_POST['groupId'],$_POST['userId']));
			break;
		case "unlinkIdeaToGroup":
			echo "Unlink... "; 
			renderServiceResponse(unlinkIdeaToGroup($_POST['groupId'],$_POST['ideaId']));
			break;
		case "acceptGroup":
			echo "Accept invite... "; 
			renderServiceResponse(acceptGroupInvitation($_POST['actionId'],$_SESSION['innoworks.ID']));
			break;
		case "refuseGroup":
			echo "Refuse invite... "; 
			renderServiceResponse(unlinkGroupToUser($_POST['actionId'],$_SESSION['innoworks.ID']));
			break;
		case "requestGroup":
			echo "Request access... "; 
			renderServiceResponse(requestGroupAccess($_POST['actionId'],$_SESSION['innoworks.ID']));
			break;
		case "approveGroupUser":
			echo "Accept new member... "; 
			renderServiceResponse(approveGroupUser($_POST['actionId'],$_POST['userId']));
			break;
		case "addIdeaToPublic":
			echo "Adding idea to public... "; 
			renderServiceResponse(addIdeaToPublic($_POST['actionId'],$_SESSION['innoworks.ID']));
			break;
	}
}
?>