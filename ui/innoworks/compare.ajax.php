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
			$group = deleteGroup($_POST['groupId'],$_SESSION['innoworks.ID']);
			echo "Response Code: ".$group;
			break;
		case "linkUserToGroup":
			echo "Creating Link.. ";
			$group = linkGroupToUser($_POST['groupId'],$_POST['userId']);
			echo "Response Code: ".$group;
			break;
	}
}
?>