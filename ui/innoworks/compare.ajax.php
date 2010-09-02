<?
require_once("thinConnector.php");
require_once("compare.ui.php");
import("compare.service");

if (isset($_GET) && $_GET != '') {
	switch ($_GET['action']) {
		case "getComparison":
			renderDefault($_SESSION['innoworks.ID']);
			break;
		case "getAddRisk":
			renderAddRiskIdea($_SESSION['innoworks.ID']);
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
		case "deleteRiskItem":
			echo "Deleting Group... ";
			$resp = deleteRiskItem($_POST['riskEvaluationId'],$_SESSION['innoworks.ID']);
			echo "Response Code: ".$resp;
			break;
		case "updateRiskItem":
			echo "Updating Risk Item... ";
			$opts = $_POST;
			unset($opts['action']);
			unset($opts['title']);
			$resp = updateRiskItem($opts);
			echo "Response Code: ".$resp;
			break;	
	}
}
?>