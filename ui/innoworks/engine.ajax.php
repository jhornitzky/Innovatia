<?
/**
 * AJAX HANDLER/CONTROL layer that routes requests
 */
require_once("pureConnector.php");

//Log actions
if (isset($_REQUEST['action']))
	logInfo('[User ' . $_SESSION['innoworks.ID'] . '] '. $_REQUEST['action']);
	
//COMMON actions
if (isset($_REQUEST['action'])) {
	import("attach.service");
	switch ($_REQUEST['action']) {
		case "poll":
			import("note.service");
			import("user.service");
			$notes = getNewNotes($_SESSION['innoworks.ID']);
			if ($notes && dbNumRows($notes) > 0) {
				renderTemplate('noteItem', array('note' => $note));
				markNotesAsRead($_SESSION['innoworks.ID']);
			}
			return null;
			break;
		case "retrieveAttachment":
			retrieveAttachment($_GET['actionId']); 
			return null;
			break;
		case "ideaImg":
			retrieveImageForIdea($_GET['actionId']); 
			return null;
			break;
		case "groupImg":
			retrieveImageForGroup($_GET['actionId']); 
			return null;
			break;
		case "userImg":
			retrieveImageForUser($_GET['actionId']); 
			return null;
			break;
	} 
}

require_once("thinConnector.php");
	
//ORDER BASED ON MOST likely action
require_once("dash/dash.ajax.php");
require_once("ideas/ideas.ajax.php");
require_once("compare/compare.ajax.php");
require_once("groups/groups.ajax.php");
require_once("select/select.ajax.php");
require_once("profile/profile.ajax.php");
require_once("search/search.ajax.php");
require_once("notes/notes.ajax.php");
require_once("attachment/attach.ajax.php");
?>