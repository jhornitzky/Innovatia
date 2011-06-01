<?
require_once(dirname(__FILE__) . "/dash.ui.php");
import("dash.service");

if (isset($_GET['action'])) {
	$limit = 20;
	if (isset($_GET['limit']))
		$limit = $_GET['limit'];
		
	switch ($_GET['action']) {
		case "getDashDefault":
			renderDefaultDash($_SESSION['innoworks.ID']);
			break;
		case "getDashIdeas":
			renderDashIdeas($_SESSION['innoworks.ID'],$limit);
			break;
		case "getDashCompare":
			renderDashCompare($_SESSION['innoworks.ID'],$limit);
			break;
		case "getDashSelect":
			renderDashSelect($_SESSION['innoworks.ID'],$limit);
			break;
		case "getInnovate":
			renderInnovateDash($_SESSION['innoworks.ID']);
			break;
		case "getDashNotes":
			renderDashNotes($_SESSION['innoworks.ID']);
			break;
		case "getDashPublic":
			renderDashPublic($_SESSION['innoworks.ID']);
			break;
	}
}
?>
