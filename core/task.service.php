<?
require_once("thinConnector.php");

function getTasksForIdea($id) {
	return dbQuery("SELECT * FROM Tasks WHERE ideaId='$id'");
}
?>