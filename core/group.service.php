<?
import("innoworks.connector");

function getGroupDetails($gid) {
	return dbFetchObject(dbQuery("SELECT Groups.* FROM Groups WHERE groupId = '$gid'"));
}

function getAllGroupsForUser($user, $limit) {
	return dbQuery("SELECT Groups.* FROM Groups WHERE userId = '$user' UNION SELECT Groups.* FROM GroupUsers, Groups WHERE GroupUsers.userId = '$user' AND GroupUsers.groupId = Groups.groupId GROUP BY Groups.groupId ORDER BY createdTime DESC $limit");
}

function countGetAllGroupsForUser($user) {
	$groupsArray = dbFetchArray(dbQuery("SELECT COUNT(*) FROM (SELECT groupId, title FROM Groups WHERE userId = '$user' UNION SELECT Groups.groupId, Groups.title FROM GroupUsers, Groups WHERE GroupUsers.userId = '$user' AND GroupUsers.groupId = Groups.groupId GROUP BY Groups.groupId) AS joinedGroups"));
	return $groupsArray[0];
}

function getPartOfGroupsForUser($user, $limit) {
	$sql = "SELECT Groups.* FROM Groups, GroupUsers WHERE GroupUsers.groupId = Groups.groupId AND GroupUsers.userId = '$user' AND Groups.userId != '$user' ORDER BY createdTime DESC $limit";
	return dbQuery($sql);
}

function countGetPartOfGroupsForUser($user) {
	$sql = "SELECT COUNT(*) FROM (SELECT Groups.groupId, Groups.title FROM Groups, GroupUsers WHERE GroupUsers.groupId = Groups.groupId AND GroupUsers.userId = '$user' AND Groups.userId != '$user') AS joinedUsers";
	$array = dbFetchArray(dbQuery($sql));
	return $array[0];
}

function getOtherGroupsForUser($user, $limit) {
	$sql = "SELECT Groups.groupId, Groups.title FROM Groups, GroupUsers WHERE GroupUsers.groupId = Groups.groupId AND GroupUsers.userId != '$user' AND Groups.userId != '$user'
	AND Groups.groupId NOT IN (SELECT Groups.groupId FROM Groups, GroupUsers WHERE GroupUsers.groupId = Groups.groupId AND GroupUsers.userId = '$user' GROUP BY Groups.groupId)
	UNION SELECT Groups.groupId, Groups.title FROM Groups WHERE Groups.userId != '$user' AND Groups.groupId 
	NOT IN (SELECT DISTINCT groupId FROM GroupUsers) $limit";
	return dbQuery($sql);
}

function countGetOtherGroups($user) {
	$sql = "SELECT COUNT(*) FROM (SELECT Groups.groupId, Groups.title FROM Groups, GroupUsers WHERE GroupUsers.groupId = Groups.groupId AND GroupUsers.userId != '$user' AND Groups.userId != '$user'
	AND Groups.groupId NOT IN (SELECT Groups.groupId FROM Groups, GroupUsers WHERE GroupUsers.groupId = Groups.groupId AND GroupUsers.userId = '$user' GROUP BY Groups.groupId)
	UNION SELECT Groups.groupId, Groups.title FROM Groups WHERE Groups.userId != '$user' AND Groups.groupId 
	NOT IN (SELECT DISTINCT groupId FROM GroupUsers)) AS joinedGroups";
	$count = dbFetchArray(dbQuery($sql));
	return $count[0];
}

function getIdeasForUserinGroup($user, $group) {
	return dbQuery("SELECT Ideas.* FROM Ideas, GroupUsers, Groups
	WHERE GroupUsers = '$user' AND GroupIdeas.groupId = '$group' AND 
	GroupIdeas.ideaId = Ideas.ideaId");
}

function getGroupsForCreatorUser($user, $limit) {
	return dbQuery("SELECT * FROM Groups WHERE userId = '$user' ORDER BY createdTime DESC $limit");
}

function countGetGroupsForCreatorUser($user) {
	$array = dbFetchArray(dbQuery("SELECT COUNT(*) FROM Groups WHERE userId = '$user'"));
	return $array[0];
}

function getUsersForGroup($id) {
	return dbQuery("SELECT Users.* FROM Users,Groups,GroupUsers WHERE Groups.groupId=$id AND GroupUsers.groupId=Groups.groupId AND Users.userId = GroupUsers.userId UNION SELECT Users.* FROM Users, Groups WHERE Groups.groupId = '$id' AND Groups.userId = Users.userId");
}

function getIdeasForGroup($id, $user = null, $limit = 'LIMIT 300') {
	return dbQuery("SELECT Ideas.*, Users.username, GroupIdeas.* FROM Ideas, Groups, GroupIdeas, Users WHERE Groups.groupId=$id AND GroupIdeas.groupId=Groups.groupId AND Ideas.ideaId = GroupIdeas.ideaId AND Users.userId = Ideas.userId $limit");
}

function countGetIdeasForGroup($id, $user = null) {
	$array = dbFetchArray(dbQuery("SELECT COUNT(*) FROM Ideas,Groups,GroupIdeas, Users WHERE Groups.groupId='$id' AND GroupIdeas.groupId=Groups.groupId AND Ideas.ideaId = GroupIdeas.ideaId AND Users.userId = Ideas.userId"));
	return $array[0];
}

function getIdeaShareDetails($id) {
	return dbQuery("SELECT * FROM GroupIdeas WHERE ideaId = '$id'");
}

function getGroupsWithIdeaDetails($id) {
	return dbQuery("SELECT Groups.*, GroupIdeas.* FROM Ideas,Groups,GroupIdeas WHERE Ideas.ideaId='$id' AND GroupIdeas.groupId=Groups.groupId AND Ideas.ideaId = GroupIdeas.ideaId");
}

function getRiskEvaluationForGroup($id) {
	return dbQuery("SELECT Ideas.title as Idea, RiskEvaluation.* FROM RiskEvaluation, Ideas,Groups,GroupIdeas WHERE Groups.groupId=$id AND GroupIdeas.groupId=Groups.groupId AND Ideas.ideaId = GroupIdeas.ideaId AND Groups.groupId=RiskEvaluation.groupId");
}

function getGroups() {
	return dbQuery("SELECT * FROM Groups");
}

function getGroupWithId($groupId, $userId) {
	return dbQuery("SELECT Groups.* FROM Groups WHERE Groups.groupId = '".$groupId."'");
}

function getGroupUserEntryWithId($groupId, $userId) {
	return dbQuery("SELECT GroupUsers.* FROM GroupUsers WHERE GroupUsers.groupId = '".$groupId."' AND GroupUsers.userId = '".$userId."'");
}

function createGroup($opts) {
	return genericCreate("Groups",$opts);
}

function updateGroup($opts) {
	$where = array("groupId");
	return genericUpdate("Groups",$opts, $where);
}

function deleteGroup($id, $user) {
	import("note.service");
	createNoteForGroup($_SESSION['innoworks.ID'], $id, "The group " . getGroupDetails($id)->title . " has been deleted");
	$success = dbQuery("DELETE FROM Groups WHERE groupId = '$id' AND userId = '$user'" );
	if ($success) {
		$success = dbQuery("DELETE FROM GroupUsers WHERE groupId = '$id'");
	}
	return $success;
}

function linkIdeaToGroup($groupId, $ideaId) {
	import("note.service");
	createNoteForGroup($_SESSION['innoworks.ID'], $groupId, "An idea has been added to the group " . getGroupDetails($groupId)->title);
	return dbQuery("INSERT INTO GroupIdeas (groupId,ideaId) VALUES ('$groupId','$ideaId')");
}

function unlinkIdeaToGroup($groupId, $ideaId) {
	import("note.service");
	createNoteForGroup($_SESSION['innoworks.ID'],$groupId, "An idea has been removed from the group " . getGroupDetails($groupId)->title);
	return dbQuery("DELETE FROM GroupIdeas WHERE groupId = '$groupId' AND ideaId = '$ideaId'");
}

function groupEntryExists($groupid, $userid) {
	logInfo('Checking for group entry');
	$entries = dbQuery("SELECT * FROM GroupUsers WHERE groupId = '$groupid' AND userId = '$userid'");
	if ($entries != null && dbNumRows($entries) > 0)
	return true;
	else
	return false;
}

function linkGroupToUser($groupid, $userid) {
	if (!groupEntryExists($groupid, $userid))  {
		import("note.service");
		$note = array();
		$note['fromUserId'] = $_SESSION['innoworks.ID'];
		$note['toUserId'] = $userid;
		$note['noteText'] = "You have been asked to join the group " . getGroupDetails($groupid)->title;
		createNote($note);
		return dbQuery("INSERT INTO GroupUsers (groupId,userId,approved) VALUES ('$groupid','$userid',1)");
	} else {
		return approveGroupUser($groupid, $userid);
	}
}

function unlinkGroupToUser($groupid, $userid) {
	import("note.service");
	$note = array();
	$note['fromUserId'] = $_SESSION['innoworks.ID'];
	$note['toUserId'] = $userid;
	$note['noteText'] = "You have been removed from the group " . getGroupDetails($groupid)->title;
	createNote($note);
	return dbQuery("DELETE FROM GroupUsers WHERE groupId = '$groupid' AND userId = '$userid'");
}

function approveGroupUser($groupid, $userid) {
	import("note.service");
	$note = array();
	$note['fromUserId'] = $_SESSION['innoworks.ID'];
	$note['toUserId'] = $userid;
	$note['noteText'] = "You have been approved for the group " . getGroupDetails($groupid)->title;
	createNote($note);
	return dbQuery("UPDATE GroupUsers SET approved=1 WHERE groupId = '$groupid' AND userId = '$userid'");
}

function acceptGroupInvitation($groupid, $userid) {
	import("note.service");
	$note = array();
	$note['fromUserId'] = $_SESSION['innoworks.ID'];
	$note['toUserId'] = getGroupDetails($groupid)->userId;
	$note['noteText'] = "I have joined the group " . getGroupDetails($groupid)->title;
	createNote($note);
	return dbQuery("UPDATE GroupUsers SET accepted=1 WHERE groupId = '$groupid' AND userId = '$userid'");
}

function requestGroupAccess($groupid, $userid) {
	logInfo('HelloRequestingGroup');
	if (!groupEntryExists($groupid, $userid))  {
		import("note.service");
		$note = array();
		$note['fromUserId'] = $_SESSION['innoworks.ID'];
		$note['toUserId'] = getGroupDetails($groupid)->userId;
		$note['noteText'] = "I want to join the group " . getGroupDetails($groupid)->title;
		createNote($note);
		return dbQuery("INSERT INTO GroupUsers (groupId,userId,accepted) VALUES ('$groupid','$userid',1)");
	} else {
		return acceptGroupInvitation($groupid, $userid);
	}
}

function addIdeaToPublic($ideaId, $userId) {
	return dbQuery("UPDATE Ideas SET isPublic=1 WHERE ideaId=$ideaId AND userId=$userId");
}

function assignEditToGroup($ideaId, $groupid, $userid) {
	return dbQuery("UPDATE GroupIdeas SET canEdit=1 WHERE groupId = '$groupid' AND ideaId = '$ideaId'");
}

function assignRemoveFromGroup($ideaId, $groupid, $userid) {
	return dbQuery("UPDATE GroupIdeas SET canEdit=0 WHERE groupId = '$groupid' AND ideaId = '$ideaId'");
}
?>