<?
require_once("innoworks.connector.php");

function getAllGroupsForUser($user) {
	return dbQuery("SELECT Groups.groupId, Groups.title FROM Groups, GroupUsers WHERE (GroupUsers.userId = '$user' AND GroupUsers.groupId = Groups.groupId) OR (Groups.userId = $user) GROUP BY Groups.groupId");
}

function getPartOfGroupsForUser($user) {
	return dbQuery("SELECT Groups.groupId, Groups.title FROM Groups, GroupUsers WHERE GroupUsers.userId = '$user' AND GroupUsers.groupId = Groups.groupId AND Groups.userId != $user");
}

function getOtherGroupsForUser($user) {
	return dbQuery("SELECT Groups.groupId, Groups.title FROM Groups, GroupUsers WHERE (Groups.userId != $user AND GroupUsers.userId != '$user' AND GroupUsers.groupId = Groups.groupId)");
}

function getIdeasForUserinGroup($user, $group) {
	return dbQuery("SELECT Ideas.* FROM Ideas, GroupUsers, Groups 
	WHERE GroupUsers = '$user' AND GroupIdeas.groupId = '$group' AND 
	GroupIdeas.ideaId = Ideas.ideaId");
}

function getGroupsForCreatorUser($user) {
	return dbQuery("SELECT * FROM Groups WHERE userId = '$user'");
}

function getUsersForGroup($id) {
	return dbQuery("SELECT Users.userId, Users.username FROM Users,Groups,GroupUsers WHERE Groups.groupId=$id AND GroupUsers.groupId=Groups.groupId AND Users.userId = GroupUsers.userId");
}

function getIdeasForGroup($id) {
	return dbQuery("SELECT Ideas.*, Users.username FROM Ideas,Groups,GroupIdeas, Users WHERE Groups.groupId=$id AND GroupIdeas.groupId=Groups.groupId AND Ideas.ideaId = GroupIdeas.ideaId AND Users.userId = Ideas.userId");
}

function getIdeaShareDetails($id) {
	return dbQuery("SELECT Groups.* FROM Ideas,Groups,GroupIdeas WHERE Ideas.ideaId='$id' AND GroupIdeas.groupId=Groups.groupId AND Ideas.ideaId = GroupIdeas.ideaId");
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
	genericCreate("Groups",$opts);
}

function deleteGroup($id, $user) {
	$success = dbQuery("DELETE FROM Groups WHERE groupId = '$id' AND userId = '$user'" );
	if ($success) {
		$success = dbQuery("DELETE FROM GroupUsers WHERE groupId = '$id'");
	}
	return $success;
}

function linkIdeaToGroup($groupId, $ideaId) {
	$sql = "INSERT INTO GroupIdeas (groupId,ideaId) VALUES ('$groupId','$ideaId')";
	return dbQuery($sql);
}

function unlinkIdeaToGroup($groupId, $groupid) {
	return dbQuery("DELETE FROM GroupIdeas WHERE groupId = '$groupId' AND ideaId = '$groupid'");
}

function linkGroupToUser($groupid, $userid) {
	return dbQuery("INSERT INTO GroupUsers (groupId,userId,approved) VALUES ('$groupid','$userid',1)");
}

function unlinkGroupToUser($groupid, $userid) {
	return dbQuery("DELETE FROM GroupUsers WHERE groupId = '$groupid' AND userId = '$userid'");
}

function approveGroupUser($groupid, $userid) {
	return dbQuery("UPDATE GroupUsers SET approved=1 WHERE groupId = '$groupid' AND userId = '$userid'");
}

function acceptGroupInvitation($groupid, $userid) {
	return dbQuery("UPDATE GroupUsers SET accepted=1 WHERE groupId = '$groupid' AND userId = '$userid'");
}
?>