<?
require_once("thinConnector.php");
import("note.service");

if (isset($_POST) && $_POST != '') {
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
