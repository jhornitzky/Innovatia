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
			$array = array();
			$array['fromUserId'] = $_SESSION['innoworks.ID'];
			$array['toUserId'] = getUserIdForUsername($_POST['toUserId']);
			$array['noteText'] = $_POST['noteText'];
			renderServiceResponse(createNote($array));
			break;
	}
}
?>
