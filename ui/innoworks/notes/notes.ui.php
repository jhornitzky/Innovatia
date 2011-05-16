<? 
require_once(dirname(__FILE__) . "/../thinConnector.php");
import("note.service");
import("user.service");
import("search.service");

function renderNotesDefault($user) {
	$limit = 20;
	$users = getSearchPeople("",$_SESSION['innoworks.ID'], array());
	$notes = getAllNotes($user, "LIMIT $limit");
	renderTemplate('notes.default', get_defined_vars());
}

function renderNotes($user, $limit) {
	$notes = getAllNotes($user, "LIMIT $limit");
	$countNotes = countGetAllNotes($user);
	renderTemplate('notes', get_defined_vars());
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
	import("user.service");
	$userDetails = getUserInfo($userId);
	renderTemplate('note.userHeader', get_defined_vars());
}
?>