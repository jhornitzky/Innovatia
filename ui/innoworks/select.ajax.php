<?
require_once("thinConnector.php");
require_once("select.ui.php");
import("idea.service");

if (isset($_GET) && $_GET != '') {
	switch ($_GET['action']) {
		case "getSelection":
			renderSelectDefault($_SESSION['innoworks.ID']);
			break;
		case "getPublicSelection":
			renderSelectPublic();
			break;
		case "getSelectionForGroup":
			renderSelectForGroup($_GET['actionId'],$_SESSION['innoworks.ID']);
			break;
		case "getAddSelect":
			renderAddSelectIdea($_SESSION['innoworks.ID']);
			break;
		case "getAddSelectForGroup":
			renderAddSelectIdeaForGroup($_GET['groupId'], $_SESSION['innoworks.ID']);
			break;
		case "getSelectForIdea":
			renderIdeaSelect($_GET['actionId'], $_SESSION['innoworks.ID']);
			break;
	}
}

if (isset($_POST) && $_POST != '') {
	switch ($_POST['action']) {
		case "createSelection":
			echo "Add selection... ";
			$opts = $_POST;
			unset($opts['action']);
			renderServiceResponse(createIdeaSelect($opts));
			echo $resp;
			break;
		case "deleteSelection":
			echo "Deleting selection... ";
			renderServiceResponse(deleteIdeaSelect(array("selectionId" => $_POST['selectionId'])));
			break;
		case "updateSelection":
			echo "Updating selection... ";
			$opts = $_POST;
			unset($opts['action']);
			renderServiceResponse(updateIdeaSelect($opts));
			break;	
	}
}
?>