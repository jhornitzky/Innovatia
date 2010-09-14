<?
require_once("thinConnector.php");

if (isset($_GET) && $_GET != '') {
	switch ($_GET['action']) {
		case "getSelection":
			renderDefault($_SESSION['innoworks.ID']);
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
		case "deleteRiskItem":
			echo "Deleting selection... ";
			//$resp = deleteRiskItem($_POST['riskEvaluationId'],$_SESSION['innoworks.ID']);
			echo "Response Code: ".$resp;
			break;
	}
}
?>