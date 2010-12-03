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

function createFilterString($filters, $itemArray, $target) {
	$returnStr = '';
	$i = 0;
	foreach ($filters as $key => $value){
		if (in_array($key, $itemArray)) {
			if ($i == 0)
				$returnStr = "AND (" . addSingleFilterString($key, $value, $target);
			else
				$returnStr = $returnStr . " AND " . addSingleFilterString($key, $value, $target);
			
			if ($i == count($filters) - 1)
				$returnStr = $returnStr . ")";
			$i++;
		}
	}
	return $returnStr;
}

function addSingleFilterString($key, $value, $target) {
	switch ($key) {
		case "dateFrom":
			return $target . ".createdTime >= '" . $value . "'";
			break;
		case "dateTo":
			return $target . ".createdTime <= '" . $value . "'";
			break;
	}
	
}

function getSearchIdeas($criteria, $user, $filters) {
	$criteria = cleansePureString($criteria);
	$criteriaString = createCriteriaString($criteria, array("Ideas.title"));
	$filters = cleanseArray($filters);
	$filterString = createFilterString($filters, array("dateFrom","dateTo"), "Ideas"); 
	$sql = "SELECT Ideas.* FROM Ideas WHERE $criteriaString $filterString AND (Ideas.isPublic='1' OR Ideas.userId='$user') UNION SELECT Ideas.* FROM Ideas, GroupIdeas, Groups, GroupUsers WHERE $criteriaString $filterString AND (GroupUsers.userId = '$user' AND GroupUsers.groupId = Groups.groupId AND Groups.groupId = GroupIdeas.groupId AND GroupIdeas.ideaId = Ideas.ideaId) ORDER BY lastUpdateTime DESC";
	return dbQuery($sql);
}

function getSearchGroups($criteria, $user, $filters) {
	$criteria = cleansePureString($criteria);
	$criteriaString = createCriteriaString($criteria, array("title"));
	$filters = cleanseArray($filters);
	$filterString = createFilterString($filters, array("dateFrom","dateTo"), "Groups"); 
	return dbQuery("SELECT * FROM Groups WHERE $criteriaString $filterString ORDER BY lastUpdateTime DESC");
}

function getSearchPeople($criteria, $user, $filters) {
	$criteria = cleansePureString($criteria);
	$criteriaString = createCriteriaString($criteria, array("Users.username", "Users.firstName", "Users.lastName"));
	$filters = cleanseArray($filters);
	$filterString = createFilterString($filters, array("dateFrom","dateTo"), "Users"); 
	
	$sql = "SELECT Users.* FROM Users WHERE $criteriaString $filterString AND isPublic='1' UNION 
	SELECT Users.* FROM Users, GroupUsers WHERE $criteriaString $filterString
	AND (GroupUsers.userId = Users.userId AND GroupUsers.groupId IN 
	(SELECT GroupUsers.groupId FROM Users, GroupUsers, Groups WHERE 
	(Users.userId='$user' AND GroupUsers.userId = Users.userId) OR 
	(Users.userId='$user' AND Groups.userId = Users.userId AND GroupUsers.groupId = Groups.groupId))) ORDER BY lastUpdateTime DESC";
	
	return dbQuery($sql);
}
?>