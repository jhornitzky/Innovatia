<?
require_once("thinConnector.php");
require_once("profile.ui.php");
import("user.service");

if (isset($_GET) && $_GET != '') {
	switch ($_GET['action']) {
		case "getProfile":
			renderDefault();
			break;
	}
}

if (isset($_POST) && $_POST != '') {
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