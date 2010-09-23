<?
require_once("thinConnector.php");
import("note.service");

if (isset($_POST) && $_POST != '') {
	switch ($_POST['action']) {
		case "addNote":
			echo "Creating Note.. ";
			$array = array();
			$array['fromUserId'] = $_SESSION['innoworks.ID'];
			$array['toUserId'] = $_POST['toUserId'];
			$array['noteText'] = $_POST['noteText'];
			$idea = createNote($array);
			echo $idea;
			break;
		case "deleteNote":
			echo "Deleting Item... ";
			$feature = deleteAttachment($_POST['actionId']);
			echo "Response Code: " . $feature;
			break;
	}
}
?>
