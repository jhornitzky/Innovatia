<?
require_once(dirname(__FILE__) . "/../thinConnector.php");
require_once(dirname(__FILE__) . "/groups.ui.php");
import("group.service");

if (isset($_GET['action'])) {
	$limit = 20;
	if (isset($_GET['limit']))
		$limit = $_GET['limit'];
	switch ($_GET['action']) {
		case "getGroups":
			renderGroupDefault($_SESSION['innoworks.ID']);
			break;
		case "getOtherGroupsForUser":
			renderOtherGroupsForUser($_SESSION['innoworks.ID'], $limit);
			break;
		case "getGroupDetails":
			renderGroupDetails($_GET['actionId']);
			break;
		case "getGroupSummary":
			renderGroupSummary($_GET['actionId']);
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

if (isset($_POST['action'])) {
	switch ($_POST['action']) {
		case "addGroup":
			echo "Creating Group.. ";
			$opts = array('title' => $_POST['title'], 'userId' => $_SESSION['innoworks.ID'], 'createdTime' => date_create()->format('Y-m-d H:i:sP'));
			unset($opts['action']);
			renderServiceResponse(createGroup($opts));
			break;
		case "updateGroup":
			echo "Updating Group.. ";
			$opts = $_POST;
			unset($opts['action']);
			renderServiceResponse(updateGroup($opts));
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
			renderServiceResponse(linkIdeaToGroup($_POST['groupId'],$_POST['ideaId']));
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
		case "editIdeaToGroup":
			echo "Adding edit permissions... "; 
			renderServiceResponse(assignEditToGroup($_POST['ideaId'],$_POST['groupId'],$_SESSION['innoworks.ID']));
			break;
		case "rmEditIdeaToGroup":
			echo "Removing edit permissions... "; 
			renderServiceResponse(assignRemoveFromGroup($_POST['ideaId'],$_POST['groupId'],$_SESSION['innoworks.ID']));
			break;
	}
}
?>