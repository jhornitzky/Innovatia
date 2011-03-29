<? namespace m0229840001301402067; 
require_once("innoworks.connector.php");

function getDashIdeas($user, $limitString) {
	return dbQuery("SELECT * FROM Ideas WHERE userId='$user' ORDER BY lastUpdateTime DESC $limitString ");
}

function countDashIdeas($user) {
	$array = dbFetchArray(dbQuery("SELECT COUNT(*) FROM Ideas WHERE userId='$user'"));
	return $array[0];
}

function getDashCompare($user, $limitString) {
	return dbQuery("SELECT Ideas.title, RiskEvaluation.*  FROM RiskEvaluation, Ideas 
	WHERE RiskEvaluation.ideaId = Ideas.ideaId AND Ideas.userId='$user' ORDER BY lastUpdateTime DESC $limitString");
}

function countDashCompare($user) {
	$array = dbFetchArray(dbQuery("SELECT COUNT(*)  FROM RiskEvaluation, Ideas 
	WHERE RiskEvaluation.ideaId = Ideas.ideaId AND Ideas.userId='$user'"));
	return $array[0];
}

function getDashSelect($userid, $limitString) {
	return dbQuery("SELECT Ideas.*, Selections.selectionId FROM Ideas, Selections WHERE userId = '".$userid."' AND Selections.ideaId =  Ideas.ideaId ORDER BY lastUpdateTime DESC $limitString");
}

function countDashSelect($user) {
	$array = dbFetchArray(dbQuery("SELECT COUNT(*) FROM Ideas, Selections WHERE userId = '".$user."' AND Selections.ideaId =  Ideas.ideaId"));
	return $array[0];
}
?>