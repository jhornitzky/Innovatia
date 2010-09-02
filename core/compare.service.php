<?
function createRiskItem($opts) {
	
	genericCreate("RiskEvaluation",$opts);
}

function updateRiskItem($opts) {
	genericUpdate("RiskEvaluation",$opts);
}

function deleteRiskItem($id, $user) {
	$success = dbQuery("DELETE FROM RiskEvaluation WHERE riskId = '$id'" );
	return $success;
}
?>