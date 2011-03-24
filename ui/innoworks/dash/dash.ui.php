<?
require_once(dirname(__FILE__) . "/../pureConnector.php");
import("dash.service");
import("note.service");
import("user.service");

function renderDefaultDash($userid) {
	global $serverRoot;
	$limit = 4;
	$notes = getAllIncomingNotes($_SESSION['innoworks.ID'], "LIMIT $limit");
	$noOfIdeas = countQuery("SELECT COUNT(*) FROM Ideas WHERE userId='".$_SESSION['innoworks.ID']."'");
	$noOfSelectedIdeas = countQuery("SELECT COUNT(*) FROM Selections, Ideas WHERE Ideas.userId='".$_SESSION['innoworks.ID']."' and Ideas.ideaId = Selections.ideaId");
	$noOfGroupsCreated = countQuery("SELECT COUNT(*) FROM Groups WHERE userId='".$_SESSION['innoworks.ID']."'");
	$noOfGroupsIn = countQuery("SELECT COUNT(*) FROM GroupUsers WHERE userId='".$_SESSION['innoworks.ID']."'");
	markNotesAsRead($_SESSION['innoworks.ID']);
	renderTemplate('dash.default', get_defined_vars());
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