<?
/**
 * Rendering functions for various comparison activities i.e. Risk / Commercial Evaluation
 */
require_once(dirname(__FILE__) . "/../pureConnector.php");
import("compare.service");

/* RENDER COMPARE ITEMS (RISK EVALUATION) */
function renderCompareDefault($user) {
	renderCommon(getRiskItems($user));
}

function renderPublicRiskItems() {
	renderCommon(getPublicRiskItems());
}

function renderComparisonForGroup($groupId) {
	if (!hasAccessToGroup($groupId, $_SESSION['innoworks.ID']))
	die("You have no access to this group and therefore cannot view these ideas.");
	renderCommon(getRiskItemsForGroup($groupId));
}

function renderCommon($riskItems) {
	import("idea.service");
	if ($riskItems && dbNumRows($riskItems) > 0){
		renderTemplate('compare.riskTable', array('riskItems' => $riskItems));
	} else {?>
<p>No risk items</p>
	<?}
}

function renderRiskItemHeadCallback($key) {
	if ($key == "idea") {?>
<th class="headcol">Idea</th>
	<?return true;
	} else {
		return false;
	}
}

function renderRiskItem($riskItems, $riskItem) {
	$featuresTotal = getFeatureEvaluationTotalForIdea($riskItem->ideaId, $_SESSION['innoworks.ID']);
	renderTemplate('compare.riskItem', array('riskItems' => $riskItems, 'riskItem' => $riskItem, 'featuresTotal' => $featuresTotal));
}

function renderRiskItemCallbackRow($key, $value, $riskItem) {
	import("user.service");
	$name = getDisplayUsername($riskItem->userId);
	if ($key == "idea") {
		renderTemplate('compare.riskItemCallback', get_defined_vars());
		return true;
	} else {
		return false;
	}
}

function renderAddRiskIdea($actionId, $user, $criteria) {
	$limit = 20;
	return renderTemplate('compare.addRiskIdea', get_defined_vars());
}

function renderAddRiskIdeaItems($criteria, $limit) {
	global $uiRoot;
	import("search.service");
	$ideas = getSearchIdeasByUser($criteria, $_SESSION['innoworks.ID'], array(), "LIMIT $limit");
	$countIdeas = countGetSearchIdeasByUser($criteria, $_SESSION['innoworks.ID'], array(), "LIMIT $limit");
	if ($ideas && dbNumRows($ideas) > 0) {
		renderTemplate('compare.riskIdeaItems', get_defined_vars());
	} else {?>
<p>No ideas found</p>
	<?}
}

function renderAddRiskIdeaForGroup($groupId, $userId) {
	$limit=20;?>
<p>
	Select a <b>group</b> idea to add to group comparison
</p>
<div>
<?renderAddRiskIdeaForGroupItems($groupId, $userId, $limit);?>
</div>
<?}

function renderAddRiskIdeaForGroupItems($groupId, $userId, $limit) {
	$limit = 50; //FIXME
	global $uiRoot;
	import("group.service");
	$ideas = getIdeasForGroup($groupId, $userId, "LIMIT $limit");
	$countIdeas = countGetIdeasForGroup($groupId, $userId);
	if ($ideas && dbNumRows($ideas) > 0) {
		renderTemplate('compare.addRiskItemGroup', get_defined_vars());
	} else {?>
<p>
	No <b>group</b> ideas found
</p>
	<?}
}

function renderIdeaSummary($ideaId, $showAll) {
	global $serverUrl, $uiRoot;
	import("idea.service");
	import("group.service");
	import("user.service");

	$idea = dbFetchObject(getIdeaDetails($ideaId));
	if (!$showAll && !hasAccessToIdea($ideaId, $_SESSION['innoworks.ID']) && $idea->isPublic != 1)
	die("You have no access to view this idea");

	require_once(dirname(__FILE__) . "/../ideas/ideas.ui.php");

	$iv = createIv();
	$ideaEnc = encrypt($ideaId, $iv);
	$ideaUrl = '&idea=' . base64_url_encode($ideaEnc) . '&iv=' . base64_url_encode($iv);
	renderTemplate('ideaSummary', get_defined_vars());
}

function renderIdeaRiskEvalForm($ideaId, $userId) {
	import("idea.service");
	$items = getRiskItemsForIdea($ideaId,$userId);
	renderTemplate('compare.riskEvalForm', array('ideaId' => $ideaId, 'userId' => $userId, 'items' => $items));
}

function renderIdeaRiskEval($ideaId, $userId) {
	global $serverUrl, $uiRoot;
	import("user.service");
	import("idea.service");
	import("group.service");
	$items = getRiskItemsForIdea($ideaId,$userId);
	renderTemplate('compare.ideaRiskEval', array('ideaId' => $ideaId, 'userId' => $userId, 'items' => $items));
}

function renderMeCallback($key) {
	if ($key == "title") {?>
<th class="headcol">Reviewer</th>
	<?return true;
	}
	return false;
}

/* RENDER COMPARE COMMENTS */
function renderCompareComments($uId) {
	import("compare.service");
	renderCommonComments(getCompareComments($uId), $uId);
}

function renderPublicCompareComments($uId) {
	return null;
}

function renderCompareCommentsForGroup($uId, $gId) {
	import("compare.service");
	renderCommonComments(getCompareCommentsForGroup($uId, $gId), $uId);
}

function renderCommonComments($comments, $uId) {
	$userService = new AutoObject("user.service");
	renderTemplate('compare.commonContents', get_defined_vars());
}
?>