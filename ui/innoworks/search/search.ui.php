<?
require_once(dirname(__FILE__) . "/../pureConnector.php");
import("search.service");

function renderSearchDefault($userid, $opts) {
	$limit = 15;

	$searchTerms = '';
	if (isset($_GET['searchTerms']))
	$searchTerms = htmlspecialchars($_GET['searchTerms']);

	if (!isset($_GET['searchTerms']) || empty($searchTerms))
	echo "<span>Find innovative ideas, people and groups</span>";
	else
	echo "<span>Found result(s) for search terms</span>";
	renderTemplate('search.default', get_defined_vars());
}

function renderSearchIdeas($userId, $limit) {
	import("user.service");

	$searchTerms = '';
	if (isset($_GET['searchTerms']))
	$searchTerms = htmlspecialchars($_GET['searchTerms']);

	$userId = '';
	if (isset($_SESSION['innoworks.ID']))
	$userId = $_SESSION['innoworks.ID'];

	$filters = array();
	$dateFrom = '';
	$dateTo = '';
	if (isset($_GET['dateFrom']) && !empty($_GET['dateFrom'])){
		$filters['dateFrom'] = $_GET['dateFrom'];
		$dateFrom = htmlspecialchars($_GET['dateFrom']);
	}
	if (isset($_GET['dateTo']) && !empty($_GET['dateTo'])) {
		$filters['dateTo'] = $_GET['dateTo'];
		$dateTo = htmlspecialchars($_GET['dateFrom']);
	}

	$ideas = getSearchIdeas($searchTerms, $userId, $filters, "LIMIT $limit");
	$countIdeas = countGetSearchIdeas($searchTerms, $userId, $filters);
	renderTemplate('search.ideas', get_defined_vars());
}

function renderSearchProfiles($userId, $limit) {
	import("user.service");
	global $serverUrl, $serverRoot, $uiRoot;

	$searchTerms = '';
	if (isset($_GET['searchTerms']))
	$searchTerms = htmlspecialchars($_GET['searchTerms']);

	$userId = '';
	if (isset($_SESSION['innoworks.ID']))
	$userId = $_SESSION['innoworks.ID'];

	$filters = array();
	$dateFrom = '';
	$dateTo = '';
	if (isset($_GET['dateFrom']) && !empty($_GET['dateFrom'])){
		$filters['dateFrom'] = $_GET['dateFrom'];
		$dateFrom = htmlspecialchars($_GET['dateFrom']);
	}
	if (isset($_GET['dateTo']) && !empty($_GET['dateTo'])) {
		$filters['dateTo'] = $_GET['dateTo'];
		$dateTo = htmlspecialchars($_GET['dateFrom']);
	}

	$users = getSearchPeople($searchTerms, $userId, $filters, "LIMIT $limit");
	$countUsers = countGetSearchPeople($searchTerms, $userId, $filters);
	echo "<p><b>".$countUsers."</b> profile(s)</p>";
	renderTemplate('search.profiles', get_defined_vars());
}

function renderSearchGroups($userId, $limit) {
	import("user.service");

	$searchTerms = '';
	if (isset($_GET['searchTerms']))
	$searchTerms = htmlspecialchars($_GET['searchTerms']);

	$userId = '';
	if (isset($_SESSION['innoworks.ID']))
	$userId = $_SESSION['innoworks.ID'];

	$filters = array();
	$dateFrom = '';
	$dateTo = '';
	if (isset($_GET['dateFrom']) && !empty($_GET['dateFrom'])){
		$filters['dateFrom'] = $_GET['dateFrom'];
		$dateFrom = htmlspecialchars($_GET['dateFrom']);
	}
	if (isset($_GET['dateTo']) && !empty($_GET['dateTo'])) {
		$filters['dateTo'] = $_GET['dateTo'];
		$dateTo = htmlspecialchars($_GET['dateFrom']);
	}

	$groups = getSearchGroups($searchTerms, $userId, $filters, "LIMIT $limit");
	$countGroups = countGetSearchGroups($searchTerms, $userId, $filters);
	echo "<p><b>".$countGroups."</b> group(s)</p>";
	renderTemplate('search.groups', get_defined_vars());
}
?>