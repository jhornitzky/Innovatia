<?
require_once("innoworks.connector.php");

function createNote($opts) {
	return genericCreate("Notes", $opts);
}

function deleteNote($opts) {
	return genericDelete("Notes", $opts);
}

function getAllNotes($user) {
	return dbQuery("SELECT Notes.* FROM Notes WHERE toUserId='$user' OR fromUserId='$user' ORDER BY createdTime DESC");
}

function getAllIncomingNotes($user) {
	return dbQuery("SELECT * FROM Notes WHERE toUserId='$user' ORDER BY createdTime");
}

function getAllSentNotes($user) {
	return dbQuery("SELECT * FROM Notes WHERE fromUserId='$user' ORDER BY createdTime");
}

function getNewNotes($user) {
	return dbQuery("SELECT * FROM Notes WHERE toUserId='$user' AND isRead='0' ORDER BY createdTime");
}

function markNotesAsRead($user) {
	return dbQuery("UPDATE Notes SET isRead='1' WHERE toUserId='$user' AND isRead='0'");
}
?>