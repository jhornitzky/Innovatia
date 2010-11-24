<?
require_once("innoworks.connector.php"); 

function createCriteriaString($terms, $itemArray) {
	$returnStr = "(";
	$termStrings = explode(" ", $terms);
	$i = 0;
	foreach ($termStrings as $term){
		foreach ($itemArray as $item){
			if ($i == 0)
				$returnStr = $returnStr . $item . " LIKE '%" . $term . "%'";
			else 
				$returnStr = $returnStr . " OR " . $item . " LIKE '%" . $term . "%'";
			$i++;
		}
	}
	return $returnStr . ")";
}

function getSearchIdeas($criteria, $user) {
	$criteria = cleansePureString($criteria);
	$criteriaString = createCriteriaString($criteria, array("Ideas.title"));
	$sql = "SELECT Ideas.* FROM Ideas WHERE $criteriaString AND (Ideas.isPublic='1' OR Ideas.userId='$user') UNION SELECT Ideas.* FROM Ideas, GroupIdeas, Groups, GroupUsers WHERE $criteriaString AND (GroupUsers.userId = '$user' AND GroupUsers.groupId = Groups.groupId AND Groups.groupId = GroupIdeas.groupId AND GroupIdeas.ideaId = Ideas.ideaId)";
	return dbQuery($sql);
}

function getSearchGroups($criteria, $user) {
	$criteria = cleansePureString($criteria);
	$criteriaString = createCriteriaString($criteria, array("title"));
	return dbQuery("SELECT * FROM Groups WHERE $criteriaString");
}

function getSearchPeople($criteria, $user) {
	$criteria = cleansePureString($criteria);
	$criteriaString = createCriteriaString($criteria, array("Users.username", "Users.firstName", "Users.lastName"));
	
	$sql = "SELECT Users.* FROM Users WHERE $criteriaString AND isPublic='1' UNION 
	SELECT Users.* FROM Users, GroupUsers WHERE $criteriaString 
	AND (GroupUsers.userId = Users.userId AND GroupUsers.groupId IN 
	(SELECT GroupUsers.groupId FROM Users, GroupUsers, Groups WHERE 
	(Users.userId='$user' AND GroupUsers.userId = Users.userId) OR 
	(Users.userId='$user' AND Groups.userId = Users.userId AND GroupUsers.groupId = Groups.groupId)))";
	
	return dbQuery($sql);
}
?>