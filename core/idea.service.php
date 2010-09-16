<?
/**
 * Functions for retrieving and adding users to the database
 */
require_once("innoworks.connector.php");

function getIdeas($userid) {
	return dbQuery("SELECT * FROM Ideas WHERE userId = '".$userid."'");
}

function getSelectedIdeas($userid) {
	return dbQuery("SELECT Ideas.*, Selections.selectionId FROM Ideas, Selections WHERE userId = '".$userid."' AND Selections.ideaId =  Ideas.ideaId");
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
	$where = array("ideaId", "userId");
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

function getFeatureEvaluationForIdea ($id) {
	return dbQuery("SELECT Features.feature, FeatureEvaluation.* FROM FeatureEvaluation, Ideas, Features WHERE FeatureEvaluation.featureId = Features.featureId AND Features.ideaId = '$id' AND Ideas.ideaId = Features.ideaId");
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



?>