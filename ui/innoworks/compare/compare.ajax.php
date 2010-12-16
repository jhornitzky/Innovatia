<?
require_once(dirname(__FILE__) . "/compare.ui.php");
import("compare.service");

if (isset($_GET['action'])) {
	switch ($_GET['action']) {
		case "getComparison":
			renderCompareDefault($_SESSION['innoworks.ID']);
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
			import("idea.service");
			registerIdeaView($_GET['actionId'], $_SESSION['innoworks.ID']);
			renderIdeaSummary($_GET['actionId']);
			break;
		case "getRiskEvalForIdea":
			renderIdeaRiskEval($_GET['actionId'], $_SESSION['innoworks.ID']);
			break;
		case "getCompareComments":
			renderCompareComments($_SESSION['innoworks.ID']);
			break;
		case "getPublicCompareComments":
			renderPublicCompareComments($_SESSION['innoworks.ID']);
			break;
		case "getCompareCommentsForGroup":
			renderCompareCommentsForGroup($_SESSION['innoworks.ID'], $_GET['actionId']);
			break;
	}
}

if (isset($_POST['action'])) {
	switch ($_POST['action']) {
		case "createRiskItem":
			echo "Creating Risk Item.. ";
			$opts = $_POST;
			unset($opts['action']);
			$opts['userId'] = $_SESSION['userId'];
			renderServiceResponse(createRiskItem($opts));
			break;
		case "createRiskItemForGroup":
			echo "Creating Risk Item.. ";
			$opts = $_POST;
			unset($opts['action']);
			renderServiceResponse(createRiskItemForGroup($opts));
			break;
		case "deleteRiskItem":
			echo "Deleting Group... ";
			renderServiceResponse(deleteRiskItem($_POST['riskEvaluationId'],$_SESSION['innoworks.ID']));
			break;
		case "updateRiskItem":
			echo "Updating Risk Item... ";
			$opts = $_POST;
			unset($opts['action']);
			unset($opts['idea']);
			$opts['userId'] = $_SESSION['innoworks.ID'];
			renderServiceResponse(updateRiskItem($opts));
			break;	
	}
}
?>