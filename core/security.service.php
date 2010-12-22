<?
function hasAccessToIdea($ideaId, $userId) { 
	if ($_SESSION['innoworks.isAdmin']) 
		return true;
	$numRows = dbNumRows(dbQuery("SELECT Ideas.* FROM Ideas WHERE Ideas.userId = '$userId' AND Ideas.ideaId = '$ideaId' UNION SELECT Ideas.* FROM Ideas, GroupIdeas, Groups, GroupUsers WHERE GroupUsers.userId = '$userId' AND GroupUsers.groupId = Groups.groupId AND Groups.groupId = GroupIdeas.groupId AND GroupIdeas.ideaId = $ideaId"));
	if ($numRows > 0)
		return true;
	else
		return false;
}

function hasEditAccessToIdea($ideaId, $userId) { 
	if ($_SESSION['innoworks.isAdmin']) 
		return true;
	$numRows = dbNumRows(dbQuery("SELECT Ideas.* FROM Ideas WHERE Ideas.userId = '$userId' AND Ideas.ideaId = '$ideaId' UNION SELECT Ideas.* FROM Ideas, GroupIdeas, Groups, GroupUsers WHERE GroupUsers.userId = '$userId' AND GroupUsers.groupId = Groups.groupId AND Groups.groupId = GroupIdeas.groupId AND GroupIdeas.ideaId = '$ideaId' AND GroupIdeas.canEdit = 1"));
	if ($numRows > 0)
		return true;
	else
		return false;
}

function hasCreatorAccessToGroup($groupId, $userId) { 
	if ($_SESSION['innoworks.isAdmin']) 
		return true;
	import("group.service");
	$group = getGroupDetails($groupId);
	if ($userId == $group->userId)
		return true;
	else
		return false;
}

function hasAccessToGroup($groupId, $userId) {
	if ($_SESSION['innoworks.isAdmin']) 
		return true;
	import("group.service");
	$numRows = dbNumRows(dbQuery("SELECT Groups.* FROM Groups, GroupUsers WHERE Groups.groupId = GroupUsers.groupId AND GroupUsers.userId = '$userId' AND GroupsUser.isApproved = 1 UNION SELECT * FROM Groups WHERE userId = '$userId'"));
	if ($numRows > 0)
		return true;
	else
		return false;
}

function hasEditAccessToComment($commentId, $userId) { 
	if ($_SESSION['innoworks.isAdmin']) 
		return true;
	$numRows = dbNumRows(dbQuery("SELECT * FROM Comments WHERE userId = '$userId' AND commentId = '$commentId'"));
	if ($numRows > 0)
		return true;
	else
		return false;
}
?>