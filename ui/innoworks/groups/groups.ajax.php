<?
require_once(dirname(__FILE__) . "/../thinConnector.php");
require_once(dirname(__FILE__) . "/groups.ui.php");
import("group.service");

if (isset($_GET['action'])) {
	$limit = 10;
	if (isset($_GET['limit']))
		$limit = $_GET['limit'];
	switch ($_GET['action']) {
		case "getGroups":
			renderGroupDefault($_SESSION['innoworks.ID']);
			break;
		case "getGroupsForCreatorUser":
			renderGroupsForCreatorUser($_SESSION['innoworks.ID'], $limit);
			break;
		case "getPartOfGroupsForUser":
			renderPartOfGroupsForUser($_SESSION['innoworks.ID'], $limit);
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
			isset($_GET['actionId']) ? $actionId = $_GET['actionId'] : $actionId = '';
			isset($_GET['criteria']) ? $criteria = $_GET['criteria'] : $criteria = '';
			renderAddUser($actionId, $_SESSION['innoworks.ID'], $criteria);
			break;
		case "getAddUserItems":
			renderAddUserItems($_GET['criteria'], $limit);
			break;
		case "getAddIdea":
			isset($_GET['actionId']) ? $actionId = $_GET['actionId'] : $actionId = '';
			isset($_GET['criteria']) ? $criteria = $_GET['criteria'] : $criteria = '';
			renderAddIdea($actionId, $_SESSION['innoworks.ID'], $criteria);
			break;
		case "getAddIdeaItems":
			renderAddIdeaItems($_GET['criteria'], $limit);
			break;
		case "getPublicAddIdea":
			renderPublicAddIdea($_GET['actionId'], $_SESSION['innoworks.ID'], $_GET['criteria']);
			break;
		case "getPublicAddIdeaItems":
			renderPublicAddIdeaItems($_GET['criteria'], $limit);
			break;
		case "getShareForIdea":
			renderIdeaShare($_GET['actionId'], $_SESSION['innoworks.ID']);
			break;
		case "getIdeaGroupsForUser":
			renderIdeaGroupsListForUser($_SESSION['innoworks.ID']);
			break;
		case "getIdeaGroupItemsForUser":
			renderIdeaGroupItemsForUser($_SESSION['innoworks.ID'], $limit);
			break;
		case "getGroupPreview":
			renderGroupPreview($_GET['actionId'], $_SESSION['innoworks.ID']);
			break;
		case "getPublicGroupPreview":
			renderPublicPreview($_SESSION['innoworks.ID']);
			break;
		case "getPrivateGroupPreview":
			renderPrivatePreview($_SESSION['innoworks.ID']);
			break;
		case "getGroupDetailsTab":
			renderGroupDetailsTab($_GET['actionId']);
			break;
		case "getGroupIdeateTab":
			renderGroupIdeateTab($_GET['actionId']);
			break;
		case "getGroupCompareTab":
			renderGroupCompareTab($_GET['actionId']);
			break;
		case "getGroupSelectTab":
			renderGroupSelectTab($_GET['actionId']);
			break;
		case "getPublicDefault":
			renderPublic();
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