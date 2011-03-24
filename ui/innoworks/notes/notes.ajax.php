<?
require_once(dirname(__FILE__) . "/../thinConnector.php");
require_once(dirname(__FILE__) . "/notes.ui.php");
import("note.service"); 

if (isset($_GET['action'])) {
	$limit = 20;
	if (isset($_GET['limit']))
		$limit = $_GET['limit'];
	switch ($_GET['action']) {
		case "getNotesDefault":
			renderNotesDefault($_SESSION['innoworks.ID']);
			break;
		case "getNotes":
			renderNotes($_SESSION['innoworks.ID'], $limit);
			break;
	}
}

if (isset($_POST['action'])) {
	switch ($_POST['action']) {
		case "addNote":
			import("user.service");
			echo "Creating Note.. ";
			$data = array();
			$data['fromUserId'] = $_SESSION['innoworks.ID'];
			$data['toUserId'] = $_POST['toUserId'];
			$data['noteText'] = $_POST['noteText'];
			renderServiceResponse(createNote($data));
			break;
		case "sendFeedback":
			import("user.service");
			echo "Sending feedback.. ";
			$data = array();
			$data['fromUserId'] = $_SESSION['innoworks.ID'];
			$data['noteText'] = $_POST['noteText'];
			renderServiceResponse(createFeedbackNotes($data));
			break;
	}
}
?>
