<?
require_once("thinConnector.php");
require_once("select.ui.php");

if (isset($_GET) && $_GET != '') {
	switch ($_GET['action']) {
		case "getSelection":
			renderDefault($_SESSION['innoworks.ID']);
			break;
		case "getAddSelect":
			renderAddRiskIdea($_SESSION['innoworks.ID']);
			break;
		case "getAddSelectForGroup":
			renderAddRiskIdeaForGroup($_GET['groupId'], $_SESSION['innoworks.ID']);
			break;
	}
}

if (isset($_POST) && $_POST != '') {
	switch ($_POST['action']) {
		case "createSelection":
			echo "Add selection.. ";
			$opts = $_POST;
			unset($opts['action']);
			//$risk = createRiskItem($opts);
			echo "Response Code: ".$risk;
			break;
		case "deleteSelection":
			echo "Deleting selection... ";
			//$resp = deleteRiskItem($_POST['riskEvaluationId'],$_SESSION['innoworks.ID']);
			echo "Response Code: ".$resp;
			break;
	}
}
?>