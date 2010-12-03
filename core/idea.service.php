<?
/**
 * Functions for retrieving and adding users to the database
 */
require_once("innoworks.connector.php");

function getPublicIdeas() {
	return dbQuery("SELECT Ideas.*, Users.username FROM Ideas, Users WHERE Ideas.isPublic = '1' AND Ideas.userId = Users.userId ORDER BY createdTime");
}

function getIdeas($userid) {
	return dbQuery("SELECT Ideas.*, Users.username FROM Ideas, Users WHERE Ideas.userId = '".$userid."' AND Users.userId = Ideas.userId ORDER BY createdTime DESC");
}

function getProfileIdeas($userid) {
	$sql = "SELECT Ideas.* FROM Ideas WHERE Ideas.userId='$userid' AND Ideas.isPublic='1' UNION SELECT Ideas.* FROM Ideas, GroupIdeas, Groups, GroupUsers WHERE GroupUsers.userId = '$user' AND GroupUsers.groupId = Groups.groupId AND Groups.groupId = GroupIdeas.groupId AND GroupIdeas.ideaId = Ideas.ideaId";
	return dbQuery($sql);
}

function getAddIdeas($userid) {
	return dbQuery("SELECT * FROM Ideas WHERE userId = '".$userid."'");
}

function getIdeaDetails($ideaId) {
	return dbQuery("SELECT Ideas.*, Users.username FROM Ideas, Users WHERE ideaId = '".$ideaId."'  AND Users.userId = Ideas.userId");
}

function getSelectedIdeas($userid) {
	return dbQuery("SELECT Ideas.*, Selections.*, Users.username FROM Ideas, Selections, Users WHERE Ideas.userId = '".$userid."' AND Selections.ideaId =  Ideas.ideaId AND Users.userId = Ideas.userId");
}

function getPublicSelectedIdeas() {
	return dbQuery("SELECT Ideas.*, Selections.*, Users.username FROM Ideas, Selections, Users WHERE Ideas.isPublic = '1' AND Selections.ideaId =  Ideas.ideaId AND Users.userId = Ideas.userId");
}

function getSelectedIdeasForGroup($groupid,$userid) {
	return dbQuery("SELECT Ideas.*, Selections.*, Users.username FROM Ideas, Selections, GroupIdeas, Users WHERE Ideas.ideaId = GroupIdeas.ideaId AND GroupIdeas.groupId = '$groupid' AND Selections.ideaId =  Ideas.ideaId AND Users.userId = Ideas.userId");
}

function getIdeaSelect($ideaId, $userid) {
	return dbQuery("SELECT Selections.* FROM Ideas, Selections WHERE Selections.ideaId =  Ideas.ideaId AND Ideas.ideaId = '$ideaId'");
}

function createIdea($opts) {
	return genericCreate("Ideas", $opts);
}

function updateIdeaDetails($opts) {
	$where = array("ideaId", "userId");
	return genericUpdate("Ideas", $opts, $where);
}

function deleteIdea($opts) {
	return genericDelete("Ideas", $opts);
}

function createIdeaSelect($opts) {
	return genericCreate("Selections", $opts);
}

function updateIdeaSelect($opts) {
	$where = array("ideaId", "userId", "selectionId");
	return genericUpdate("Selections", $opts, $where);
}

function deleteIdeaSelect($opts) {
	return genericDelete("Selections", $opts);
}

function getFeaturesForIdea($id) {
	return dbQuery("SELECT * FROM Features WHERE ideaId = ".$id);
}

function createFeature($opts) {
	return genericCreate("Features", $opts);
}

function deleteFeature($id) {
	return genericDelete("Features", array("featureId"=>$id));
}

function getRolesForIdea($id) {
	return dbQuery("SELECT * FROM Roles WHERE ideaId = ".$id);
}

function createRole($opts) {
	return genericCreate("Roles", $opts);
}

function deleteRole($id) {
	return genericDelete("Roles", array("roleId"=>$id));
}

function getIdeaFeatureEvaluationsForIdea($id) {
	return dbQuery("SELECT IdeaFeatureEvaluations.* FROM IdeaFeatureEvaluations, Ideas WHERE IdeaFeatureEvaluations.ideaId = Ideas.ideaId AND Ideas.ideaId = '$id'");
}

function getFeatureEvaluationForIdea($id) {
	return dbQuery("SELECT Features.feature, FeatureEvaluation.* FROM FeatureEvaluation, Ideas, Features,IdeaFeatureEvaluations WHERE FeatureEvaluation.featureId = Features.featureId AND Features.ideaId = IdeaFeatureEvaluations.ideaId AND FeatureEvaluation.ideaFeatureEvaluationId = IdeaFeatureEvaluations.ideaFeatureEvaluationId AND Ideas.ideaId = Features.ideaId AND IdeaFeatureEvaluations.ideaFeatureEvaluationId='$id' ORDER BY FeatureEvaluation.score DESC");
}

function getCommentsForIdea($id) {
	return dbQuery("SELECT * FROM Comments WHERE ideaId = '$id'");
}

function createComment($opts) {
	return genericCreate("Comments", $opts);
}

function deleteComment($id) {
	return genericDelete("Comments", array("commentId"=>$id));
}

function createFeatureEvaluation($opts) {
	return genericCreate("IdeaFeatureEvaluations", $opts);
}

function updateIdeaFeatureEvaluation($opts) {
	$where = array("ideaFeatureEvaluationId");
	return genericUpdate("IdeaFeatureEvaluations", $opts, $where);
}

function updateFeatureEvaluation($opts) {
	$where = array("featureEvaluationId");
	return genericUpdate("FeatureEvaluation", $opts, $where);
}

function deleteFeatureEvaluation($id) {
	return genericDelete("IdeaFeatureEvaluations", array("ideaFeatureEvaluationId"=>$id));
}

function createFeatureItem($opts) {
	return genericCreate("FeatureEvaluation", $opts);
}

function updateFeatureItem($opts) {
	$where = array("featureEvaluationId");
	return genericUpdate("FeatureEvaluation", $opts, $where);
}

function deleteFeatureItem($id) {
	return genericDelete("FeatureEvaluation", array("featureEvaluationId"=>$id));
}

function updateFeature($opts) {
	$where = array("featureId");
	return genericUpdate("Features", $opts, $where);
}

function updateRole($opts) {
	$where = array("roleId");
	return genericUpdate("Roles", $opts, $where);
}

function createAttachmentFS($destFileName) {
	global $usersRoot;
	$destFilePath = $usersRoot.$destFileName; 
	logDebug("MOVING FILE TO: ".$_SERVER['DOCUMENT_ROOT'].$destFilePath);
	if(move_uploaded_file($_FILES['userfile']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$destFilePath)) {
		chmod($_SERVER['DOCUMENT_ROOT'].$destFilePath, 0444);
		return true;
	} else {
		echo "Error [" . $_FILES['userfile']['error'] . "] uploading file...";
		return false;
	}
}

function createAttachmentDb($destFileName) {
	$fileName = $_FILES['userfile']['name'];
	$tmpName  = $_FILES['userfile']['tmp_name'];
	$fileSize = $_FILES['userfile']['size'];
	$fileType = $_FILES['userfile']['type'];

	if(!get_magic_quotes_gpc())
		$fileName = addslashes($fileName);
	
	$query;
	if (isset($_POST['groupId']))
		$query = "INSERT INTO Attachments (groupId, title, path, type, size, userId) VALUES ('".$_POST['groupId']."','$fileName', '$destFileName', '$fileType', '$fileSize', '".$_SESSION['innoworks.ID']."')";
	else if (isset($_POST['ideaId']))
		$query = "INSERT INTO Attachments (ideaId, title, path, type, size, userId) VALUES ('".$_POST['ideaId']."','$fileName', '$destFileName', '$fileType', '$fileSize', '".$_SESSION['innoworks.ID']."')";
	return dbQuery($query);
}

function createAttachment($postArray) {
	if ($_FILES['userfile']['size'] > 0) {
		global $usersRoot;
		$info = pathinfo($_FILES['userfile']['name']);
		$newName = date(DATE_ATOM) . $_SESSION['innoworks.ID'] . $_FILES['userfile']['name'] . $info['extension'];
		$destFileName = sha1($newName);
		if (createAttachmentDb($destFileName))
			return createAttachmentFS($destFileName);
		else 
			return false;
	}
}

function deleteAttachment($id) {
	if(isset($id)) {
		global $usersRoot;
		$attach = getAttachmentById($id);
		unlink($_SERVER['DOCUMENT_ROOT'].$usersRoot.$attach->path);
		return dbQuery("DELETE FROM Attachments WHERE Attachments.attachmentId='$id'");
	}
}

function getAttachmentsForIdea($ideaId) {
	return dbQuery("SELECT * FROM Attachments WHERE ideaId = '$ideaId'");
}

function getAttachmentsForGroup($groupId) {
	return dbQuery("SELECT * FROM Attachments WHERE groupId = '$groupId'");
}

function getAttachmentById($id) {
	return dbFetchObject(dbQuery("SELECT * FROM Attachments WHERE attachmentId = '$id'"));
}

function retrieveAttachment($id) {
	global $usersRoot;
	if(isset($id)) {
		$attach = getAttachmentById($id);
		header("Content-length: $attach->size");
		header("Content-type: $attach->type");
		header("Content-Disposition: attachment; filename=$attach->title");
		//echo $attach->data; 
		readfile($_SERVER['DOCUMENT_ROOT'].$usersRoot.$attach->path);
	}
}

function hasAccessToIdea($ideaId, $userId) { 
	$numRows =dbNumRows(dbQuery("SELECT Ideas.* FROM Ideas WHERE Ideas.userId = '$userId' AND Ideas.ideaId = '$ideaId' UNION SELECT Ideas.* FROM Ideas, GroupIdeas, Groups, GroupUsers WHERE GroupUsers.userId = '$userId' AND GroupUsers.groupId = Groups.groupId AND Groups.groupId = GroupIdeas.groupId AND GroupIdeas.ideaId = $ideaId"));
	if ($numRows > 0 || $_SESSION['innoworks.isAdmin'])
		return true;
	else
		return false;
}

function hasEditAccessToIdea($ideaId, $userId) { 
	$numRows = dbNumRows(dbQuery("SELECT Ideas.* FROM Ideas WHERE Ideas.userId = '$userId' AND Ideas.ideaId = '$ideaId' UNION SELECT Ideas.* FROM Ideas, GroupIdeas, Groups, GroupUsers WHERE GroupUsers.userId = '$userId' AND GroupUsers.groupId = Groups.groupId AND Groups.groupId = GroupIdeas.groupId AND GroupIdeas.ideaId = $ideaId AND GroupIdeas.canEdit = 1"));
	if ($numRows > 0 || $_SESSION['innoworks.isAdmin'])
		return true;
	else
		return false;
}

function getFeatureEvaluationTotalForIdea($ideaId, $userId) {
	$score = dbFetchObject(dbQuery("SELECT AVG(FeatureEvaluation.score) AS score FROM IdeaFeatureEvaluations, FeatureEvaluation WHERE IdeaFeatureEvaluations.ideaId = '$ideaId' AND FeatureEvaluation.ideaFeatureEvaluationId = IdeaFeatureEvaluations.ideaFeatureEvaluationId"));;
	if ($score->score == null)
		return 0;
	else
		return round($score);
}

function getRiskEvaluationTotalForIdea($ideaId, $userId) {
	$score = dbFetchObject(dbQuery("SELECT AVG(score) AS score FROM RiskEvaluation WHERE ideaId = '$ideaId'"));
	if ($score->score == null)
		return 0;
	else
		return round($score); 
}
?>