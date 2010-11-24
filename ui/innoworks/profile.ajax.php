<?
require_once("thinConnector.php");
require_once("profile.ui.php");
import("user.service");

if (isset($_GET['action'])) {
	switch ($_GET['action']) {
		case "getProfile":
			renderDefault();
			break;
		case "getProfileSummary":
			renderSummaryProfile($_GET['actionId']);
			break;
	}
}

if (isset($_POST['action'])) {
	switch ($_POST['action']) {
		case "updateProfile":
			echo "Updating Profile... ";
			$opts = $_POST;
			$opts['userId'] = $_SESSION['innoworks.ID'];
			unset($opts['action']); 
			renderServiceResponse(updateUser($opts));
			break;
	}
}
?>