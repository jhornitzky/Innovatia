<? 
require_once(dirname(__FILE__) . "/../thinConnector.php");
import("note.service");
import("user.service");
import("search.service");

function renderNotesDefault($user) {
	$limit = 20;
	$users = getSearchPeople("",$_SESSION['innoworks.ID'], array());
	$notes = getAllNotes($user, "LIMIT $limit");
	
	//calc first one
	$latestUser = $_SESSION['innoworks.ID'];
	if (isset($notes) && dbNumRows($notes) > 0) { 
		$notey = dbFetchObject($notes);
		if ($notey->toUserId === $_SESSION['innoworks.ID'])
			$latestUser = $notey->fromUserId;
		else if ($notey['fromUserId'] === $_SESSION['innoworks.ID']) 
			$latestUser = $notey->toUserId;
	}
	dbRowSeek($notes, 0);
	
	renderTemplate('notes.default', get_defined_vars());
}

function renderNotes($user, $limit) {
	$notes = getAllNotes($user, "LIMIT $limit");
	$countNotes = countGetAllNotes($user);
	renderTemplate('notes', get_defined_vars());
	markNotesAsRead($_SESSION['innoworks.ID']);
}

function renderAddNoteUser($actionId, $user, $criteria) {
	global $uiRoot;
	$limit=20;
	renderTemplate('note.addUser', get_defined_vars());
	renderAddNoteUserItems($criteria, $limit);
}

function renderAddNoteUserItems($criteria, $limit) {
	global $uiRoot;
	import("search.service");
	$users = getSearchPeople($criteria, $_SESSION['innoworks.ID'], "", "LIMIT $limit");
	$countUsers = countGetSearchPeople($criteria, $_SESSION['innoworks.ID'], "");
	renderTemplate('note.addUserItems', get_defined_vars());
}

function renderAddNoteUserHeader($userId) {
	logDebug('doRenderNoteUserHeader: ' . $userId);
	if (!isset($userId)) 
		return false;
		
	import("user.service");
	$userDetails = getUserInfo($userId);
	renderTemplate('note.userHeader', get_defined_vars());
}
?>