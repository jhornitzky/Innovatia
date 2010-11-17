<?
/**
 * Functions for retrieving and adding users to the database
 */
require_once("innoworks.connector.php");

function getPublicIdeas() {
	return dbQuery("SELECT Ideas.*, Users.username FROM Ideas, Users WHERE Ideas.isPublic = '1' AND Ideas.userId = Users.userId ORDER BY createdTime");
}

function getIdeas($userid) {
	return dbQuery("SELECT Ideas.*, Users.username FROM Ideas, Users WHERE Ideas.userId = '".$userid."' AND Users.userId = Ideas.userId");
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
	return dbQuery("SELECT Features.feature, FeatureEvaluation.* FROM FeatureEvaluation, Ideas, Features,IdeaFeatureEvaluations WHERE FeatureEvaluation.featureId = Features.featureId AND Features.ideaId = IdeaFeatureEvaluations.ideaId AND FeatureEvaluation.ideaFeatureEvaluationId = IdeaFeatureEvaluations.ideaFeatureEvaluationId AND Ideas.ideaId = Features.ideaId AND IdeaFeatureEvaluations.ideaFeatureEvaluationId='$id'");
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

function createAttachmentFS($destFilePath) {
	if(move_uploaded_file($_FILES['userfile']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$destFilePath)) {
		chmod($_SERVER['DOCUMENT_ROOT'].$destFilePath, 0444);
		return true;
	} else {
		echo "Error [" . $_FILES['userfile']['error'] . "] uploading file...";
		return false;
	}
}

function createAttachmentDb($destFilePath) {
	$fileName = $_FILES['userfile']['name'];
	$tmpName  = $_FILES['userfile']['tmp_name'];
	$fileSize = $_FILES['userfile']['size'];
	$fileType = $_FILES['userfile']['type'];

	if(!get_magic_quotes_gpc())
		$fileName = addslashes($fileName);
	
	$query;
	if (isset($_POST['groupId']))
		$query = "INSERT INTO Attachments (groupId, title, path, type, size, userId) VALUES ('".$_POST['groupId']."','$fileName', '$destFilePath', '$fileType', '$fileSize', '".$_SESSION['innoworks.ID']."')";
	else if (isset($_POST['ideaId']))
		$query = "INSERT INTO Attachments (ideaId, title, path, type, size, userId) VALUES ('".$_POST['ideaId']."','$fileName', '$destFilePath', '$fileType', '$fileSize', '".$_SESSION['innoworks.ID']."')";
	return dbQuery($query);
}

function createAttachment($postArray) {
	if ($_FILES['userfile']['size'] > 0) {
		global $usersRoot;
		$info = pathinfo($_FILES['userfile']['name']);
		$newName = $_SESSION['innoworks.ID'] . $_FILES['userfile']['name'] . $info['extension'];
		$destFileName = sha1($newName);
		$destFilePath = $usersRoot.$destFileName; 
		if (createAttachmentDb($destFilePath))
			return createAttachmentFS($destFilePath);
	}
}

function deleteAttachment($id) {
	if(isset($id)) {
		$attach = getAttachmentById($id);
		unlink($_SERVER['DOCUMENT_ROOT'].$attach->path);
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
	if(isset($id)) {
		$attach = getAttachmentById($id);
		header("Content-length: $attach->size");
		header("Content-type: $attach->type");
		header("Content-Disposition: attachment; filename=$attach->title");
		//echo $attach->data; 
		readfile($_SERVER['DOCUMENT_ROOT'].$attach->path);
	}
}

function grantEditToIdea($ideaId, $groupId) {
	return dbQuery("UPDATE GroupUsers SET canEdit = '1' WHERE ideaId = '$ideaId' and groupId = '$groupId'"); 	
}

function revokeEditToIdea($ideaId, $groupId) {
	return dbQuery("UPDATE GroupUsers SET canEdit = '0' WHERE ideaId = '$ideaId' and groupId = '$groupId'"); 	
}

function hasAccessToIdea($ideaId, $userId) { 
	return (dbNumRows(dbQuery("SELECT Ideas.* FROM Ideas WHERE Ideas.userId = '$userId' OR Ideas.isPublic='1' UNION SELECT Ideas.* FROM Ideas, GroupIdeas, Groups, GroupUsers WHERE GroupUsers.userId = '$user' AND GroupUsers.groupId = Groups.groupId AND Groups.groupId = GroupIdeas.groupId AND GroupIdeas.ideaId = Ideas.ideaId")));
}

function hasEditAccessToIdea($ideaId, $userId) { 
	return (dbNumRows(dbQuery("SELECT Ideas.* FROM Ideas WHERE Ideas.userId = '$userId' UNION SELECT Ideas.* FROM Ideas, GroupIdeas, Groups, GroupUsers WHERE GroupUsers.userId = '$user' AND GroupUsers.groupId = Groups.groupId AND Groups.groupId = GroupIdeas.groupId AND GroupIdeas.ideaId = Ideas.ideaId AND GroupIdeas.canEdit = 1")));
}
?>