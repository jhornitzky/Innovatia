<?
require_once(dirname(__FILE__) . "/../pureConnector.php");
import("dash.service");
import("note.service");
import("user.service");

function renderDefaultDash($userid) {
	global $serverRoot;
	import('search.service');
	$limit = 15;
	$notes = getAllIncomingNotes($_SESSION['innoworks.ID'], "LIMIT $limit");
	markNotesAsRead($_SESSION['innoworks.ID']);
	
	//latest ideas and groups
	$limit = 8; //reduce
	$ideas = getDashIdeas($userid, "LIMIT $limit");
	$groups = getSearchGroups('', $userid, null, 'LIMIT '.$limit);
	
	//counts for tiles
	$countIdeas = countDashIdeas($userid);
	$countItems = countDashCompare($userid);
	$countSelections = countDashSelect($userid);
	import('group.service');
	$countGroups = countGetAllGroupsForUser($userid);
	
	renderTemplate('dash.default', get_defined_vars());
}

function renderDashNotes($userid) {
	$limit = 15;
	$notes = getAllIncomingNotes($_SESSION['innoworks.ID'], "LIMIT $limit");
	renderTemplate('notes.dash', get_defined_vars());
}

function renderDashPublic($userid) {
	global $serverRoot, $serverUrl, $uiRoot;
	$limit = 15;
	$announces = getAnnouncements("LIMIT ".$limit);
	renderTemplate('notes.public', get_defined_vars());
}

function renderInnovateDash($userid) {
	global $serverRoot;
	$limit = 10;
	renderTemplate('innovate.tab', get_defined_vars());
}

function renderDashIdeas($user, $limit) {
	import("user.service");
	$ideas = getDashIdeas($user, "LIMIT $limit");
	$countIdeas = countDashIdeas($user);
	renderTemplate('dash.ideas', get_defined_vars());
}

function renderDashCompare($user, $limit) {
	$items = getDashCompare($user, "LIMIT $limit");
	$countItems = countDashCompare($user);
	renderTemplate('dash.compare',get_defined_vars());
}

function renderDashSelect($user, $limit) {
	$selections = getDashSelect($user, "LIMIT $limit");
	$countSelections = countDashSelect($user);
	renderTemplate('dash.select',get_defined_vars());
}
?>