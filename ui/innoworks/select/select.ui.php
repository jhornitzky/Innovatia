<?
require_once(dirname(__FILE__) . "/../thinConnector.php");
import("idea.service");

function renderSelectDefault($userId) {
	$ideas = getSelectedIdeas($userId);
	if ($ideas && dbNumRows($ideas) > 0 ) {
		while ($idea = dbFetchObject($ideas)) {
			renderSelectIdea($ideas,$idea, $userId);
		}
	} else {
		echo "<p>No selections yet</p>";
	}
}

function renderSelectPublic() {
	$ideas = getPublicSelectedIdeas();
	if ($ideas && dbNumRows($ideas) > 0 ) {
		while ($idea = dbFetchObject($ideas)) {
			renderSelectIdea($ideas,$idea, $userId);
		}
	} else {
		echo "<p>No selections yet</p>";
	}
}

function renderSelectForGroup($groupId, $userId) {
	if (!hasAccessToGroup($groupId, $_SESSION['innoworks.ID']))
	die("You have no access to this group and therefore cannot view these ideas.");
	$ideas = getSelectedIdeasForGroup($groupId, $userId);
	if ($ideas && dbNumRows($ideas) > 0 ) {
		while ($idea = dbFetchObject($ideas)) {
			renderSelectIdea($ideas,$idea, $userId);
		}
	} else {
		echo "<p>No selections yet</p>";
	}
}

function renderSelectIdea($ideas,$idea,$user) {
	import("task.service");
	$tasks = getTasksForIdea($idea->ideaId);
	$features = getFeaturesForIdea($idea->ideaId);
	$roles = getRolesForIdea($idea->ideaId);
	$comments = getCommentsForIdea($idea->ideaId);
	$views = getViewsForIdea($idea->ideaId);
	renderTemplate('select.idea', get_defined_vars());
}

function renderAddSelectIdea($actionId, $user, $criteria) {
	$limit=20;
	renderTemplate('select.addIdea', get_defined_vars());
}

function renderAddIdeaSelectItems($criteria, $limit) {
	import("search.service");
	$ideas = getSearchIdeasByUser($criteria, $_SESSION['innoworks.ID'], array(), "LIMIT $limit");
	$countIdeas = countGetSearchIdeasByUser($criteria, $_SESSION['innoworks.ID'], array());
	renderTemplate('select.addItems', get_defined_vars());
}

function renderAddSelectIdeaForGroup($groupId, $userId) {
	$limit=20;
	renderTemplate('select.addGroupIdea', get_defined_vars());
}

function renderAddIdeaSelectItemsForGroup($groupId, $userId, $limit) {
	import("group.service");
	$ideas = getIdeasForGroup($groupId, $userId, "LIMIT $limit");
	$countIdeas = countGetIdeasForGroup($groupId, $userId);
	renderTemplate('select.addGroupItems', get_defined_vars());
}

function renderIdeaSelect($ideaId,$userId) {
	import("idea.service");
	$item = getIdeaSelect($ideaId,$userId);
	renderTemplate('idea.select', get_defined_vars());
}
?>