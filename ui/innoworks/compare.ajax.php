<?
require_once("thinConnector.php");
require_once("compare.ui.php");
import("compare.service");

if (isset($_GET) && $_GET != '') {
	switch ($_GET['action']) {
		case "getComparison":
			renderDefault($_SESSION['innoworks.ID']);
			break;
		case "getPublicComparison":
			renderPublicRiskItems($_SESSION['innoworks.ID']);
			break;
		case "getComparisonForGroup":
			renderComparisonForGroup($_GET['groupId']);
			break;
		case "getAddRisk":
			renderAddRiskIdea($_SESSION['innoworks.ID']);
			break;
		case "getAddRiskForGroup":
			renderAddRiskIdeaForGroup($_GET['groupId'], $_SESSION['innoworks.ID']);
			break;
		case "getIdeaSummary":
			renderIdeaSummary($_GET['actionId']);
			break;
		case "getRiskEvalForIdea":
			renderIdeaRiskEval($_GET['actionId'], $_SESSION['innoworks.ID']);
			break;
	}
}

if (isset($_POST) && $_POST != '') {
	switch ($_POST['action']) {
		case "createRiskItem":
			echo "Creating Risk Item.. ";
			$opts = $_POST;
			unset($opts['action']);
			$risk = createRiskItem($opts);
			echo "Response Code: ".$risk;
			break;
		case "createRiskItemForGroup":
			echo "Creating Risk Item.. ";
			$opts = $_POST;
			unset($opts['action']);
			$risk = createRiskItemForGroup($opts);
			echo "Response Code: ".$risk;
			break;
		case "deleteRiskItem":
			echo "Deleting Group... ";
			$resp = deleteRiskItem($_POST['riskEvaluationId'],$_SESSION['innoworks.ID']);
			echo "Response Code: ".$resp;
			break;
		case "updateRiskItem":
			echo "Updating Risk Item... ";
			$opts = $_POST;
			unset($opts['action']);
			unset($opts['idea']);
			$resp = updateRiskItem($opts);
			echo "Response Code: ".$resp;
			break;	
	}
}
?>