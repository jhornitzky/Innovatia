<?
require_once(dirname(__FILE__) . "/../thinConnector.php");
require_once(dirname(__FILE__) . "/select.ui.php");
import("idea.service");

if (isset($_GET['action'])) {
	$limit = 20;
	if (isset($_GET['limit']))
		$limit = $_GET['limit'];
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
			renderAddSelectIdea($_GET['actionId'], $_SESSION['innoworks.ID'], $_GET['criteria']);
			break;
		case "getAddIdeaSelectItems":
			renderAddIdeaSelectItems($_GET['criteria'], $limit);
			break;
		case "getAddSelectForGroup":
			renderAddSelectIdeaForGroup($_GET['groupId'], $_SESSION['innoworks.ID']);
			break;
		case "getAddSelectItemsForGroup":
			renderAddIdeaSelectItemsForGroup($_GET['groupId'], $_SESSION['innoworks.ID'], $limit);
			break;
		case "getSelectForIdea":
			renderIdeaSelect($_GET['actionId'], $_SESSION['innoworks.ID']);
			break;
	}
}

if (isset($_POST['action'])) {
	switch ($_POST['action']) {
		case "createSelection":
			echo "Add selection... ";
			$opts = $_POST;
			unset($opts['action']);
			echo renderServiceResponse(createIdeaSelect($opts));
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
		case "updateTask":
			import('task.service');
			echo "Updating task... ";
			$opts = $_REQUEST;
			unset($opts['action']);
			renderServiceResponse(updateOrCreateTask($opts));
			break;	
	}
}
?>