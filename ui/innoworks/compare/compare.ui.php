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
		require_once(dirname(__FILE__) . "/compare.ui.riskTable.php");
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
	$featuresTotal = getFeatureEvaluationTotalForIdea($riskItem->ideaId, $_SESSION['innoworks.ID']);?>
	<tr id="riskform_<?= $riskItem->riskEvaluationId ?>">
		<?renderGenericUpdateRowWithRefData($riskItems, $riskItem, array("ideaId","riskEvaluationId","groupId", "userId", "score", "createdTime", "lastUpdateTime"), "RiskEvaluation","renderRiskItemCallbackRow");?>
		<td class="totalCol">
			<span class="itemTotal" title="Risk evaluation score">0</span> |
			<span class="featureTotal" title="Feature evaluation score"><?= $featuresTotal ?></span>
		</td>
	</tr>
<?}

function renderRiskItemCallbackRow($key, $value, $riskItem) {
	import("user.service");
	global $serverRoot, $serverUrl, $uiRoot;
	$name = getDisplayUsername($riskItem->userId);
	if ($key == "idea") {?>
		<td class="headCol">
			<div class="hoverable">
				<div style="float:left; position:relative">
				<img src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=ideaImg&actionId=<?= $riskItem->ideaId?>" style="width:2.5em; height:2.5em;"/>
				</div>
				<span class="itemName">
					<a href="javascript:logAction()" onclick="showIdeaSummary('<?= $riskItem->ideaId?>')"><?= $value ?></a>
				</span><br/>
				<img src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=userImg&actionId=<?= $riskItem->userId?>" style="width:1em; height:1em;"/>
				<?= $name ?>
				<input type="button" onclick="deleteRisk('<?= $riskItem->riskEvaluationId ?>');" title="Delete this risk item" value=" - "/>
				<input type="hidden" name="riskEvaluationId" value="<?= $riskItem->riskEvaluationId ?>"/>
			</div>
		</td>
		<?return true;
	} else {
		return false;
	}
}

function renderAddRiskIdea($actionId, $user, $criteria) {
	$limit = 20;
	global $uiRoot;?>
	<p>Select a <b>private</b> idea to add to comparison</p>
	<div style="width:100%; clear:both; height:2.5em;">
	<form id="popupAddSearch" onsubmit="findAddRiskIdeas(); return false;">
		<div style="border: 1px solid #444444; position: relative; float: left; clear:right">
			<table cellpadding="0" cellspacing="0">
			<tr>
			<td><input type="text"  name="criteria" value="<?= $searchTerms ?>" style="border: none" /></td>
			<td><img src="<?= $uiRoot."style/glass.png"?>" onclick="findAddRiskIdeas()" style="width:30px; height:24px; margin:2px;cursor:pointer"/></td>
			</tr>
			</table>
			<input id="searchBtn" type="submit" value="Search" style="display:none;"/>
		</div>
	</form>
	</div>
	<div>
	<?renderAddRiskIdeaItems($criteria, $limit);?>
	</div>
<?}

function renderAddRiskIdeaItems($criteria, $limit) {
	global $uiRoot;
	import("search.service");
	$ideas = getSearchIdeasByUser($criteria, $_SESSION['innoworks.ID'], array(), "LIMIT $limit");
	$countIdeas = countGetSearchIdeasByUser($criteria, $_SESSION['innoworks.ID'], array(), "LIMIT $limit");
	if ($ideas && dbNumRows($ideas) > 0) {
		while ($idea = dbFetchObject($ideas)) {?>
			<div class='itemHolder clickable' onclick="addRiskItem(<?= $idea->ideaId?>);" style="height:2.5em;"> 
				<div class="lefter" style="padding:0.1em;">
					<img src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=ideaImg&actionId=<?= $idea->ideaId?>" style="width:2.25em;height:2.25em;"/>
				</div>
				<div class="lefter">
					<?= $idea->title ?><br/>
					<img src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=userImg&actionId=<?= $idea->userId ?>" style="width:1em;height:1em;"/>
					<span style="color:#666"><?= getDisplayUsername($idea->userId)?></span>
				</div>
			</div>
		<?}
		if ($countIdeas > dbNumRows($ideas)) {?>
			<a class="loadMore" href="javascript:logAction()" onclick="loadResults(this,{action:'getAddRiskIdeaItems', limit: '<?= $limit + 20; ?>'})">Load more</a>
		<?}
	} else {?>
		<p>No ideas found</p>
	<?}
}

function renderAddRiskIdeaForGroup($groupId, $userId) {
	$limit=20;?>
	<p>Select a <b>group</b> idea to add to group comparison</p>
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
		while ($idea = dbFetchObject($ideas)) {?>
			<div class='itemHolder clickable' onclick="addRiskItemForGroup('<?=$idea->ideaId?>');" style="height:2.5em;"> 
				<div class="lefter" style="padding:0.1em;">
					<img src="<?= $uiRoot ?>innoworks/retrieveImage.php?action=ideaImg&actionId=<?= $idea->ideaId?>" style="width:2.25em;height:2.25em;"/>
				</div>
				<div class="lefter">
					<?= $idea->title ?><br/>
					<img src="<?= $uiRoot ?>innoworks/retrieveImage.php?action=userImg&actionId=<?= $idea->userId ?>" style="width:1em;height:1em;"/>
					<span style="color:#666"><?= getDisplayUsername($idea->userId)?></span>
				</div>
			</div>
		<?}
		if ($countIdeas > dbNumRows($ideas)) {?>
			<a class="loadMore" href="javascript:logAction()" onclick="loadResults(this,{action:'getAddRiskIdeaForGroupItems', limit: '<?= $limit + 20; ?>', groupId:'<?=$groupId?>'})">Load more</a>
		<?}
	} else {?>
		<p>No <b>group</b> ideas found</p>
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
	?>
	<table>
	<tr>
	<td><img src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=ideaImg&actionId=<?= $ideaId ?>" style="width:3em; height:3em;"/></td>
	<td> 
	<h3><?= $idea->title ?></h3>
	<?= getDisplayUsername($idea->userId);?> | <span class="summaryActions"><a href="javascript:printIdea('<?= $ideaUrl ?>')">Print</a> <a href="javascript:showIdeaDetails('<?= $ideaId?>');">Edit</a></span></td>
	</tr>
	</table>
	<p><b>Created</b> <?= $idea->createdTime?><br/>
	<b>Updated</b> <?= $idea->lastUpdateTime?></p>
	<p style="color: #444"><?= $idea->proposedService?></p>
	<?renderGenericInfoFormOnlyPopulated(null, $idea, array("proposedService","ideaId","userId", "title", "createdTime", "lastUpdateTime","isPublic",'username'));?>
	<p><b>Role(s)</b></p>
	<?renderIdeaRoles($ideaId, false);?>
	<p><b>Feature(s)</b></p>
	<?renderIdeaFeatures($ideaId, false);?>
	<p><b>Comment(s)</b></p>
	<?renderCommentsForIdea($ideaId, $_SESSION['innoworks.ID']);?>
	<p><b>Feature Evaluation(s)</b></p>
	<?renderIdeaFeatureEvaluationsForIdea($ideaId, false);?>
	<p><b>Risk Evaluation(s)</b></p>
	<?renderIdeaRiskEval($ideaId, $_SESSION['innoworks.ID']);?>
<?}

function renderIdeaRiskEvalForm($ideaId, $userId) {
	import("idea.service");
	$items = getRiskItemsForIdea($ideaId,$userId);
	if ($item && dbNumRows($item) > 0) {
		$item = dbFetchObject($item);
		$canEdit = false;
		if (hasEditAccessToIdea($ideaId, $userId) || $_SESSION['innoworks.isAdmin'])
			$canEdit = true;?>
		<form id="ideaRiskEvalDetails">
		<?if ($canEdit) 
			renderGenericUpdateFormWithRefData(array(), $item, array("riskEvaluationId","groupId","userId","ideaId", "score", "createdTime", "lastUpdateTime"), "RiskEvaluation");
		else 
			renderGenericInfoForm(array(), $item, array("riskEvaluationId","groupId","userId","ideaId", "score", "createdTime", "lastUpdateTime"));?>
		<input type="hidden" name="riskEvaluationId" value="<?= $item->riskEvaluationId ?>"/>
		<input type="hidden" name="action" value="updateRiskEval" />
		</form>
		<a href='javascript:showCompare(); dijit.byId("ideasPopup").hide()'>Compare</a>
	<?} else {?>
		<p>No compare data for idea</p>
		<p>Add comparison data for idea <a onclick="addRiskItem('<?= $ideaId ?>');loadPopupShow()" href="javascript:logAction()">now</a> </p> 
		Go to <a href='javascript:showCompare(); dijit.byId("ideasPopup").hide()'>Compare</a>
	<?} 
}

function renderIdeaRiskEval($ideaId, $userId) {
	global $serverUrl, $uiRoot;
	import("user.service");
	import("idea.service");
	import("group.service");
	$items = getRiskItemsForIdea($ideaId,$userId);
	if ($items && dbNumRows($items) > 0) {?>
		<table class='evaluation'>
		<?renderGenericHeaderWithRefData($items,array("ideaId","riskEvaluationId","groupId","userId","score", "createdTime", "lastUpdateTime"),"RiskEvaluation", "renderMeCallback");
		while($item = dbFetchObject($items)) {?>
			<tr>
			<td class="headcol">
			<img src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=userImg&actionId=<?= $userId ?>" style="width:2em; height:2em;"/>
			<?= getUserInfo($userId)->username ?><br/>
			<span style="font-size:0.8em;">
				<? if(isset($item->groupId)) { echo getGroupDetails($item->groupId)->title; } ?>
			</span>
			</td>
			<?renderGenericInfoRow($items,$item,array("title","ideaId","riskEvaluationId","groupId","userId", "score", "createdTime", "lastUpdateTime"), "");	?>
			<td style="font-weight:bold; font-size:2em">
			<?= $item->score ?>
			</td>
			</tr>
		<?}?>
		</table>
	<?} else {?>
		<p>No compare data for idea</p> 
	<?}?>
	<p class="summaryActions">You must go to <a href='javascript:showCompare(); dijit.byId("ideasPopup").hide()'>Compare</a> to edit data</p>
<?}

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
	if ($comments && dbNumRows($comments) > 0) {
		while ($comment = dbFetchObject($comments)) {?>
			<div class='itemHolder'>
			<img src="retrieveImage.php?action=userImg&actionId=<?= $comment->userId ?>" style="width:1em; height:1em;"/>
			<span class='title'><?=$userService->getDisplayUsername($comment->userId)?></span>
			<span class='timestamp'><?=$comment->timestamp?></span>
			<?if ($comment->userId == $uId || $_SESSION['innoworks.isAdmin']) { ?>
				<input type='button' onclick='deleteComment("<?=$comment->commentId?>")' value=' - '>
			<?}?>
			<br/>
			<?=$comment->text;?>
			</div>
		<?}
	} else {
		echo "No comments";
	}
}
?>