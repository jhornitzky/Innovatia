<? 
/**
 * Rendering functions for various comparison activities i.e. Risk / Commercial Evaluation
 */
require_once("thinConnector.php");  

/* RENDER COMPARE ITEMS (RISK EVALUATION) */
function renderDefault($user) {
	renderCommon(getRiskItems($user));
} 

function renderPublicRiskItems() {
	renderCommon(getPublicRiskItems());
} 

function renderComparisonForGroup($groupId) {
	renderCommon(getRiskItemsForGroup($groupId));
}

function renderCommon($riskItems) {
	import("idea.service");
	if ($riskItems && dbNumRows($riskItems) > 0){
		echo "<table id='riskEvaluation'>";
		echo "<thead>";
			renderGenericHeaderWithRefData($riskItems, array("ideaId","riskEvaluationId","groupId", "userId", "score"),"RiskEvaluation","renderRiskItemHeadCallback");
		echo "</thead>";
		echo "<tbody>";
			while ($riskItem = dbFetchObject($riskItems)) {
				renderRiskItem($riskItems, $riskItem);
			}
		echo "</tbody>";
		echo "</table>";
	} else {
		echo "<p>No items for group</p>";
	}
}

function renderRiskItemHeadCallback($key) {
	if ($key == "idea") {?>
		<th class="headcol">
			<?= fromCamelCase($key); ?>
		</th>
		<?return true;
	} else {
		return false;
	}
	
}

function renderRiskItem($riskItems, $riskItem) {
	$featuresTotal = getFeatureEvaluationTotalForIdea($riskItem->ideaId, $_SESSION['innoworks.ID']);?>
	<tr id="riskform_<?= $riskItem->riskEvaluationId ?>">
		<?renderGenericUpdateRowWithRefData($riskItems, $riskItem, array("ideaId","riskEvaluationId","groupId", "userId", "score"), "RiskEvaluation","renderRiskItemCallbackRow");?>
		<td class="totalCol">
			<span class="itemTotal" title="Risk evaluation score">0</span> |
			<span class="featureTotal" title="Feature evaluation score"><?= $featuresTotal ?></span>
			<!-- = <span class="grandTotal" style="font-size:2em; font-weight:bold">0</span> -->
		</td>
	</tr>
<?}

function renderRiskItemCallbackRow($key, $value, $riskItem) {
	import("user.service");
	global $serverRoot, $serverUrl, $uiRoot;
	$name = getUserInfo($riskItem->userId);
	$renderName = '';
	if ($name) 
		$renderName = $name->username;
		
	if ($key == "idea") {?>
		<td class="headCol">
			<div class="hoverable">
			<div style="float:left; position:relative">
			<img src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=ideaImg&actionId=<?= $riskItem->ideaId?>" style="width:2.5em; height:2.5em;"/>
			</div>
				<span class="itemName">
					<a href="javascript:logAction()" onclick="showIdeaSummary('<?= $riskItem->ideaId?>')"><?= $value ?></a>
				</span><br/>
				<?= $renderName ?>
				<a href="javascript:logAction()" onclick="showIdeaReviews('<?= $riskItem->ideaId?>');">Comments</a>
				<input type="button" onclick="deleteRisk('<?= $riskItem->riskEvaluationId ?>');" title="Delete this risk item" value=" - "/>
				<input type="hidden" name="riskEvaluationId" value="<?= $riskItem->riskEvaluationId ?>"/>
			</div>
		</td>
		<?return true;
	} else {
		return false;
	}
}

function renderAddRiskIdea() {
	import("idea.service");
	echo "Select an idea to add to risk evaluation";
	$ideas = getIdeas($_SESSION['innoworks.ID']); 
	if ($ideas && dbNumRows($ideas) > 0) { 
		echo "<ul>";
		while ($idea = dbFetchObject($ideas)) {
			echo  "<li><a href='javascript:addRiskItem(\"$idea->ideaId\")'>".$idea->title. "</a></li>";
		}
		echo "</ul>";
	}
}

function renderAddRiskIdeaForGroup($groupId, $userId) {
	import("group.service");
	echo "Select an idea to add to group risk evaluation";
	$ideas = getIdeasForGroup($groupId); 
	if ($ideas && dbNumRows($ideas) > 0) { 
		echo "<ul>";
		while ($idea = dbFetchObject($ideas)) {
			echo "<li><a href='javascript:addRiskItemForGroup(\"$idea->ideaId\", \"$groupId\")'>".$idea->title. "</a></li>";
		}
		echo "</ul>";
	}
}

function renderIdeaSummary($ideaId) {
	global $serverUrl , $uiRoot;
	import("idea.service");
	$idea = dbFetchObject(getIdeaDetails($ideaId));
	$iv = createIv();
	$ideaEnc = encrypt($ideaId, $iv); 
	$ideaUrl = '&idea=' . base64_url_encode($ideaEnc) . '&iv=' . base64_url_encode($iv);?>
	<table>
	<tr>
	<td><img src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=ideaImg&actionId=<?= $ideaId ?>" style="width:3em; height:3em;"/></td>
	<td> 
	<h3><?= $idea->title ?></h3>
	<a href="javascript:printIdea('<?= $ideaUrl ?>')">Print</a> <a href="javascript:showIdeaDetails('<?= $ideaId?>');">Edit idea</a></td>
	</tr>
	</table>
	<?renderGenericInfoForm(null, $idea, array("ideaId","userId", "title"));
}

function renderIdeaRiskEval($ideaId, $userId) {
	import("idea.service");
	
	$item = getRiskItemForIdea($ideaId,$userId);
	if ($item && dbNumRows($item) > 0) {
		$item = dbFetchObject($item);
		$canEdit = false;
		if (hasEditAccessToIdea($ideaId, $userId) || $_SESSION['innoworks.isAdmin'])
			$canEdit = true;?>
		<form id="ideaRiskEvalDetails">
		<?if ($canEdit) 
			renderGenericUpdateFormWithRefData(array(), $item, array("riskEvaluationId","groupId","userId","ideaId", "score"), "RiskEvaluation");
		else 
			renderGenericInfoForm(array(), $item, array("riskEvaluationId","groupId","userId","ideaId", "score"));?>
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
		while ($comment = dbFetchObject($comments)) {
			echo "<div class='itemHolder'>";
			echo "<span class='title'>".$userService->getUserInfo($comment->userId)->username."</span><span class='timestamp'>$comment->timestamp</span>";
			if ($comment->userId == $uId || $_SESSION['innoworks.isAdmin'])
				echo "<input type='button' onclick='deleteComment(". $comment->commentId .")' value=' - '>";
			echo "<br/>";
			echo $comment->text;
			echo "</div>";
		}
	} else {
		echo "No comments";
	}
}
?>