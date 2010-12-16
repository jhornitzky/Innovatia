<?
require_once(dirname(__FILE__) . "/../pureConnector.php");
require_once(dirname(__FILE__) . "/search.ui.php");
import("search.service");

if (isset($_GET['action'])) {
	$limit = 20;
	if (isset($_GET['limit']))
		$limit = $_GET['limit'];
	
	switch ($_GET['action']) { 
		case "getSearchIdeas":
			renderSearchIdeas($_SESSION['innoworks.ID'], $limit);
			break;
		case "getSearchProfiles":
			renderSearchProfiles($_SESSION['innoworks.ID'], $limit);
			break;
		case "getSearchGroups":
			renderSearchGroups($_SESSION['innoworks.ID'], $limit);
			break;
		case "getSearchDefault":
			renderSearchDefault($_SESSION['innoworks.ID'], $_GET);
			break;
	}
}
?>