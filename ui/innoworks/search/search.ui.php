<?
require_once(dirname(__FILE__) . "/../pureConnector.php");
import("search.service");

function renderSearchDefault($userid, $opts) {
	$limit = 15;

	$searchTerms = '';
	if (isset($_REQUEST['searchTerms']))
	$searchTerms = htmlspecialchars($_REQUEST['searchTerms']);

	renderTemplate('search.default', get_defined_vars());
}

function renderSearchIdeas($userId, $limit) {
	import("user.service");

	$searchTerms = '';
	if (isset($_REQUEST['searchTerms']))
	$searchTerms = htmlspecialchars($_REQUEST['searchTerms']);

	$userId = '';
	if (isset($_SESSION['innoworks.ID']))
	$userId = $_SESSION['innoworks.ID'];

	$filters = array();
	$dateFrom = '';
	$dateTo = '';
	if (isset($_REQUEST['dateFrom']) && !empty($_REQUEST['dateFrom'])){
		$filters['dateFrom'] = $_REQUEST['dateFrom'];
		$dateFrom = htmlspecialchars($_REQUEST['dateFrom']);
	}
	if (isset($_REQUEST['dateTo']) && !empty($_REQUEST['dateTo'])) {
		$filters['dateTo'] = $_REQUEST['dateTo'];
		$dateTo = htmlspecialchars($_REQUEST['dateFrom']);
	}

	$ideas = getSearchIdeas($searchTerms, $userId, $filters, "LIMIT $limit");
	$countIdeas = countGetSearchIdeas($searchTerms, $userId, $filters);
	renderTemplate('search.ideas', get_defined_vars());
}

function renderSearchProfiles($userId, $limit) {
	import("user.service");
	global $serverUrl, $serverRoot, $uiRoot;

	$searchTerms = '';
	if (isset($_REQUEST['searchTerms']))
	$searchTerms = htmlspecialchars($_REQUEST['searchTerms']);

	$userId = '';
	if (isset($_SESSION['innoworks.ID']))
	$userId = $_SESSION['innoworks.ID'];

	$filters = array();
	$dateFrom = '';
	$dateTo = '';
	if (isset($_REQUEST['dateFrom']) && !empty($_REQUEST['dateFrom'])){
		$filters['dateFrom'] = $_REQUEST['dateFrom'];
		$dateFrom = htmlspecialchars($_REQUEST['dateFrom']);
	}
	if (isset($_REQUEST['dateTo']) && !empty($_REQUEST['dateTo'])) {
		$filters['dateTo'] = $_REQUEST['dateTo'];
		$dateTo = htmlspecialchars($_REQUEST['dateFrom']);
	}

	$users = getSearchPeople($searchTerms, $userId, $filters, "LIMIT $limit");
	$countUsers = countGetSearchPeople($searchTerms, $userId, $filters);
	echo "<p><b>".$countUsers."</b> profile(s)</p>";
	renderTemplate('search.profiles', get_defined_vars());
}

function renderSearchGroups($userId, $limit) {
	import("user.service");

	$searchTerms = '';
	if (isset($_REQUEST['searchTerms']))
	$searchTerms = htmlspecialchars($_REQUEST['searchTerms']);

	$userId = '';
	if (isset($_SESSION['innoworks.ID']))
	$userId = $_SESSION['innoworks.ID'];

	$filters = array();
	$dateFrom = '';
	$dateTo = '';
	if (isset($_REQUEST['dateFrom']) && !empty($_REQUEST['dateFrom'])){
		$filters['dateFrom'] = $_REQUEST['dateFrom'];
		$dateFrom = htmlspecialchars($_REQUEST['dateFrom']);
	}
	if (isset($_REQUEST['dateTo']) && !empty($_REQUEST['dateTo'])) {
		$filters['dateTo'] = $_REQUEST['dateTo'];
		$dateTo = htmlspecialchars($_REQUEST['dateFrom']);
	}

	$groups = getSearchGroups($searchTerms, $userId, $filters, "LIMIT $limit");
	$countGroups = countGetSearchGroups($searchTerms, $userId, $filters);
	echo "<p><b>".$countGroups."</b> group(s)</p>";
	renderTemplate('search.groups', get_defined_vars());
}
?>