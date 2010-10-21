<?
require_once("innoworks.connector.php"); 

function getSearchIdeas($criteria, $user) {
	$criteria = cleansePureString($criteria);
	$sql = "SELECT Ideas.* FROM Ideas WHERE Ideas.title LIKE '%$criteria%'";
	//$sql = "SELECT Ideas.* FROM Ideas, GroupIdeas, Groups, GroupUsers WHERE Ideas.title LIKE '%$criteria%' AND (Ideas.isPublic='1' OR Ideas.userId='$user' OR (GroupUsers.userId = '$user' AND GroupUsers.groupId = Groups.groupId AND Groups.groupId = GroupIdeas.groupId AND GroupIdeas.ideaId = Ideas.ideaId))";
	logDebug("SEARCH IDEAS: ".$sql);
	return dbQuery($sql);
}

function getSearchGroups($criteria, $user) {
	$criteria = cleansePureString($criteria);
	return dbQuery("SELECT * FROM Groups WHERE title LIKE '%$criteria%'");
}

function getSearchPeople($criteria, $user) {
	$criteria = cleansePureString($criteria);
	return dbQuery("SELECT * FROM Users WHERE username LIKE '%$criteria%' OR firstName LIKE '%$criteria%' OR lastName LIKE '%$criteria%' AND isPublic='1'");
}
?>