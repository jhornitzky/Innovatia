<?
function createRiskItem($opts) {
	
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
?>