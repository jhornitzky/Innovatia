<?
function isAdmin($userId) {
	$rs = dbQuery("SELECT * FROM Users WHERE userId = '$userId' AND isAdmin = 1");
	if ($rs && dbNumRows($rs) > 0)
		return true;
	else
		return false;
}

function hasAccessToIdea($ideaId, $userId) { 
	if (isAdmin($userId)) 
		return true;
	$rs = dbQuery("SELECT * FROM Ideas WHERE isPublic='1' AND ideaId='$ideaId' UNION SELECT Ideas.* FROM Ideas WHERE Ideas.userId = '$userId' AND Ideas.ideaId = '$ideaId' UNION SELECT Ideas.* FROM Ideas, GroupIdeas, Groups, GroupUsers WHERE GroupUsers.userId = '$userId' AND GroupUsers.approved = 1 AND GroupUsers.groupId = Groups.groupId AND Groups.groupId = GroupIdeas.groupId AND GroupIdeas.ideaId = $ideaId");
	if ($rs && dbNumRows($rs) > 0)
		return true;
	else
		return false;
}

function hasEditAccessToIdea($ideaId, $userId) { 
	if (isAdmin($userId)) 
		return true;
	$rs = dbQuery("SELECT Ideas.* FROM Ideas WHERE Ideas.userId = '$userId' AND Ideas.ideaId = '$ideaId' UNION SELECT Ideas.* FROM Ideas, GroupIdeas, Groups, GroupUsers WHERE GroupUsers.userId = '$userId' AND GroupUsers.groupId = Groups.groupId AND Groups.groupId = GroupIdeas.groupId AND GroupIdeas.ideaId = '$ideaId' AND GroupIdeas.canEdit = 1");
	if ($rs && dbNumRows($rs) > 0)
		return true;
	else
		return false;
}

function hasCreatorAccessToGroup($groupId, $userId) { 
	import("group.service");
	$group = getGroupDetails($groupId);
	if ($userId == $group->userId)
		return true;
	else
		return false;
}

function hasAccessToGroup($groupId, $userId) {
	$rs = dbQuery("SELECT Groups.* FROM Groups, GroupUsers WHERE Groups.groupId = GroupUsers.groupId AND GroupUsers.userId = '$userId' AND GroupUsers.groupId = '$groupId' AND GroupUsers.approved = 1 UNION SELECT * FROM Groups WHERE userId = '$userId' AND groupId = '$groupId'");
	if ($rs && dbNumRows($rs) > 0)
		return true;
	else
		return false;
}

function hasEditAccessToComment($commentId, $userId) { 
	if (isAdmin($userId)) 
		return true;
	$rs = dbQuery("SELECT * FROM Comments WHERE userId = '$userId' AND commentId = '$commentId'");
	if ($rs && dbNumRows($rs) > 0)
		return true;
	else
		return false;
}
?>