<?
function createRiskItem($opts) {
	return genericCreate("RiskEvaluation",$opts);
}

function createRiskItemForGroup($opts) {
	return genericCreate("RiskEvaluation",$opts);
}

function updateRiskItem($opts) {
	$where = array("riskEvaluationId");
	return genericUpdate("RiskEvaluation", $opts, $where);
}

function deleteRiskItem($id, $user) {
	$success = dbQuery("DELETE FROM RiskEvaluation WHERE riskEvaluationId = '$id'" );
	return $success;
}

function getRiskItems($user) {
	return dbQuery("SELECT Ideas.title as 'idea', RiskEvaluation.*  FROM RiskEvaluation, Ideas 
	WHERE RiskEvaluation.ideaId = Ideas.ideaId AND Ideas.userId=$user AND (RiskEvaluation.groupId IS NULL OR RiskEvaluation.groupId = '0') ORDER BY RiskEvaluation.score DESC");
}

function getPublicRiskItems() {
	return dbQuery("SELECT Ideas.title as 'idea', RiskEvaluation.*  FROM RiskEvaluation, Ideas 
	WHERE RiskEvaluation.ideaId = Ideas.ideaId AND Ideas.isPublic='1'");
}

function getRiskItemsForGroup($group) {
	return dbQuery("SELECT Ideas.title as 'idea', RiskEvaluation.*  FROM RiskEvaluation, Ideas 
	WHERE RiskEvaluation.ideaId = Ideas.ideaId AND RiskEvaluation.groupId=$group");
}

function getRiskItemForIdea($ideaId, $user) {
	return dbQuery("SELECT RiskEvaluation.*  FROM RiskEvaluation WHERE RiskEvaluation.ideaId = '$ideaId'");
}

function getCompareComments($uId) {
	return dbQuery("SELECT *  FROM Comments WHERE ideaId IS NULL AND groupId IS NULL AND userId=$uId"); 
}

function getPublicCompareComments($uId) {
	return null;
}

function getCompareCommentsForGroup($uId, $groupId) {
	return dbQuery("SELECT * FROM Comments WHERE groupId = $groupId"); 
}
?>