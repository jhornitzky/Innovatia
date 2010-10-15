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
	return genericUpdate("IdeaFeatureEvaluations", $opts, $where);
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

function createAttachment() {
	if ($_FILES['userfile']['size'] > 0) {
		$fileName = $_FILES['userfile']['name'];
		$tmpName  = $_FILES['userfile']['tmp_name'];
		$fileSize = $_FILES['userfile']['size'];
		$fileType = $_FILES['userfile']['type'];

		$fp      = fopen($tmpName, 'r');
		$content = fread($fp, filesize($tmpName));
		$content = addslashes($content);
		fclose($fp);

		if(!get_magic_quotes_gpc())
		{
			$fileName = addslashes($fileName);
		}

		$query = "INSERT INTO Attachments (ideaId, title, data, type, size ) VALUES ('".$_POST['ideaId']."', '$fileName', '$content', '$fileType', '$fileSize')";
		return dbQuery($query) or die('Error, query failed');
	}
}

function deleteAttachment($id) {
	return dbQuery("DELETE FROM Attachments WHERE Attachments.attachmentId='$id'");
}

function getAttachmentsForIdea($ideaId) {
	return dbQuery("SELECT * FROM Attachments WHERE ideaId = '$ideaId'");
}

function retrieveAttachment($id) {
	if(isset($id)) {
		logDebug("ID IS: " + $id);
		$query = "SELECT * FROM Attachments WHERE attachmentId = '$id'";
		$result = dbQuery($query) or die('Error, query failed');
		$attach = dbFetchObject($result);
		header("Content-length: $attach->size");
		header("Content-type: $attach->type");
		header("Content-Disposition: attachment; filename=$attach->title");
		echo $attach->data; 
		exit;
	}
}
?>