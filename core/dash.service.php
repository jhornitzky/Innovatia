<?
require_once("innoworks.connector.php");

function getDashIdeas($user) {
	return dbQuery("SELECT * FROM Ideas WHERE userId='$user' ORDER BY createdTime LIMIT 5");
}

function getDashCompare($user) {
	return dbQuery("SELECT Ideas.title, RiskEvaluation.*  FROM RiskEvaluation, Ideas 
	WHERE RiskEvaluation.ideaId = Ideas.ideaId AND Ideas.userId='$user' LIMIT 5");
}

function getDashSelect($userid) {
	return dbQuery("SELECT Ideas.*, Selections.selectionId FROM Ideas, Selections WHERE userId = '".$userid."' AND Selections.ideaId =  Ideas.ideaId LIMIT 3");
}
?>