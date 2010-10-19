<?
require_once("innoworks.connector.php"); 

function getSearchIdeas($criteria, $user) {
	$criteria = cleansePureString($criteria);
	return dbQuery("SELECT * FROM Ideas WHERE title LIKE '%$criteria%'");
}

function getSearchGroups($criteria, $user) {
	$criteria = cleansePureString($criteria);
	return dbQuery("SELECT * FROM Groups WHERE title LIKE '%$criteria%'");
}

function getSearchPeople($criteria, $user) {
	$criteria = cleansePureString($criteria);
	return dbQuery("SELECT * FROM Users WHERE username LIKE '%$criteria%' OR firstName LIKE '%$criteria%' OR lastName LIKE '%$criteria%'");
}
?>