<?
require_once(dirname(__FILE__) . "/compare.ui.php");
import("compare.service");

if (isset($_GET['action'])) {
	$limit = 20;
	if (isset($_GET['limit']))
		$limit = $_GET['limit'];
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
			renderAddRiskIdea($_GET['actionId'], $_SESSION['innoworks.ID'], $_GET['criteria']);
			break;
		case "getAddRiskIdeaItems":
			renderAddRiskIdeaItems('', $limit);
			break;
		case "getAddRiskForGroup":
			renderAddRiskIdeaForGroup($_GET['groupId'], $_SESSION['innoworks.ID']);
			break;
		case "getAddRiskForGroupItems":
			renderAddRiskIdeaForGroupItems($_GET['groupId'], $_SESSION['innoworks.ID'], $limit);
			break;
		case "getIdeaSummary":
			import("idea.service");
			registerIdeaView($_GET['actionId'], $_SESSION['innoworks.ID']);
			renderIdeaSummary($_GET['actionId'], false);
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
			$opts['userId'] = $_SESSION['innoworks.ID'];
			renderServiceResponse(createRiskItem($opts));
			break;
		case "createRiskItemForGroup":
			echo "Creating Risk Item.. ";
			$opts = $_POST;
			unset($opts['action']);
			$opts['userId'] = $_SESSION['innoworks.ID'];
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