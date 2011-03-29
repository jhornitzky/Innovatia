<?
import("innoworks.connector");

function getTasksForIdea($id) {
	return dbQuery("SELECT * FROM Tasks WHERE ideaId='$id'");
}
?>