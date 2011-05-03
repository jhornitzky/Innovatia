<?php
require_once 'thinConnector.php';
if (isset($_REQUEST['action'])) {
	$limit = 20;
	if (isset($_REQUEST['limit']))
		$limit = $_REQUEST['limit'];
	require('search/search.ui.php');
	switch ($_REQUEST['action']) {
		case "getAddForm":
			renderTemplate('idea.addForm');
			break;
		case "getIdeas":
			renderSearchIdeas($_SESSION['innoworks.ID'], $limit);
			break;
		case "getProfiles":
			renderSearchProfiles($_SESSION['innoworks.ID'], $limit);
			break;
		case "getGroups":
			renderSearchGroups($_SESSION['innoworks.ID'], $limit);
			break;
	}
}
?>