<?
require_once(dirname(__FILE__) . "/../thinConnector.php");
require_once("profile.ui.php");
import("user.service");

if (isset($_GET['action'])) {
	$limit = 20;
	if (isset($_GET['limit']))
		$limit = $_GET['limit'];
	switch ($_GET['action']) {
		case "getProfile":
			renderProfileDefault($_SESSION['innoworks.ID']);
			break;
		case "getOtherProfiles":
			renderProfileDefault();
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