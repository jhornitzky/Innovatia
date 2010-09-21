<?
function createRiskItem($opts) {
	genericCreate("RiskEvaluation",$opts);
}

function createRiskItemForGroup($opts) {
	genericCreate("RiskEvaluation",$opts);
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
	WHERE RiskEvaluation.ideaId = Ideas.ideaId AND Ideas.userId=$user AND RiskEvaluation.groupId IS NULL");
}

function getRiskItemsForGroup($group) {
	return dbQuery("SELECT Ideas.title as 'idea', RiskEvaluation.*  FROM RiskEvaluation, Ideas 
	WHERE RiskEvaluation.ideaId = Ideas.ideaId AND RiskEvaluation.groupId=$group");
}
?>