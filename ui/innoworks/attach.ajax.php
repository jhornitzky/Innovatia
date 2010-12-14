<?
require_once("thinConnector.php");

if (isset($_POST['action'])) {
	switch ($_POST['action']) {
		case "dpChange":
			import("attach.service");
			$opts = $_POST;
			unset($opts['action']);
			echo 'Updating attachment... ';
			renderServiceResponse(updateAttachment($opts));
			break;
	}
}
?>
