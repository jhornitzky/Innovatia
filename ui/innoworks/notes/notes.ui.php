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
}?>