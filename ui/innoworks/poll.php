<?
require_once("thinConnector.php");
import("note.service");
import("user.service");
$notes = getNewNotes($_SESSION['innoworks.ID']);
if ($notes && dbNumRows($notes) > 0) {
	renderTemplate('noteItem', array('note' => $note));
	markNotesAsRead($_SESSION['innoworks.ID']);
}?>