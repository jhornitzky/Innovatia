<? 
/**
 * Rendering functions for various cpomarison activities i.e. Risk/CommercialEvaluation
 */
require_once("thinConnector.php");  

function renderDefault($user) {
	$riskItems = getRiskItems($user);
	if ($riskItems && dbNumRows($riskItems) > 0){
		echo "<table id='riskEvaluation'>";
		renderGenericHeaderWithRefData($riskItems, array("ideaId","riskEvaluationId","groupId", "userId"),"RiskEvaluation");
		while ($riskItem = dbFetchObject($riskItems)) {
			renderRiskItem($riskItems, $riskItem);
		}
		echo "</table>";?>
		<script type="text/javascript">
			initFormSelectTotals('table#riskEvaluation');
		</script>
		<?
	} else {
		echo "<p>No items yet. Add some items by clicking the '+' above. </p>";
	}
} 

function renderPublicRiskItems() {
	$riskItems = getPublicRiskItems();
	if ($riskItems && dbNumRows($riskItems) > 0){
		echo "<table id='riskEvaluation'>";
		renderGenericHeaderWithRefData($riskItems, array("ideaId","riskEvaluationId","groupId", "userId"),"RiskEvaluation");
		while ($riskItem = dbFetchObject($riskItems)) {
			renderRiskItem($riskItems, $riskItem);
		}
		echo "</table>";?>
		<script type="text/javascript">
			initFormSelectTotals('table#riskEvaluation');
		</script>
		<?
	} else {
		echo "<p>No items yet. Add some items by clicking the '+' above. </p>";
	}
} 


function renderComparisonForGroup($groupId) {
	$riskItems = getRiskItemsForGroup($groupId);
	if ($riskItems && dbNumRows($riskItems) > 0){
		echo "<table id='riskEvaluation'>";
		renderGenericHeaderWithRefData($riskItems, array("ideaId","riskEvaluationId","groupId", "userId"),"RiskEvaluation");
		while ($riskItem = dbFetchObject($riskItems)) {
			renderRiskItem($riskItems, $riskItem);
		}
		echo "</table>";?>
		<script type="text/javascript">
			initFormSelectTotals('table#riskEvaluation');
		</script>
		<?
	} else {
	echo "<p>No items for group. Add some items by clicking the '+' above.</p>";
	}
}

function renderRiskItem($riskItems, $riskItem) {?>
	<tr id="riskform_<?= $riskItem->riskEvaluationId ?>">
		<?renderGenericUpdateRowWithRefData($riskItems, $riskItem, array("ideaId","riskEvaluationId","groupId", "userId"), "RiskEvaluation");?>
		<td>
			Score: <span class="itemTotal">0 </span>
			<a href="javascript:showIdeaReviews('<?= $riskItem->ideaId?>');">Comments</a>
			<a href="javascript:showIdeaSummary('<?= $riskItem->ideaId?>');">Summary</a>
			<input type="hidden" name="riskEvaluationId" value="<?= $riskItem->riskEvaluationId ?>"/>
			<input type="button" onclick="updateRisk('<?= $riskItem->riskEvaluationId ?>','riskform_<?= $riskItem->riskEvaluationId ?>')" title="Update this risk item"  value=" U "/>
			<input type="button" onclick="deleteRisk('<?= $riskItem->riskEvaluationId ?>')" title="Delete this risk item" value=" - "/>
		</td>
	</tr>
	
<?}

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
			echo  "<li><a href='javascript:addRiskItemForGroup(\"$idea->ideaId\", \"$groupId\")'>".$idea->title. "</a></li>";
		}
		echo "</ul>";
	}
}

function renderIdeaSummary($ideaId) {?>
<span class="ideaDetailsOptions" style="position:relative; float:right;"><a href="javascript:printIdea('<?= $ideaId?>')">Print</a> </span>
	<?
	import("idea.service");
	$idea = dbFetchObject(getIdeaDetails($ideaId));
	renderGenericInfoForm(null, $idea, null);
	?>
	<a href="javascript:showIdeaDetails('<?= $ideaId?>');">Open</a>
	<?		
}

function renderIdeaRiskEval($ideaId, $userId) {
	import("compare.service");
	$item = getRiskItemForIdea($ideaId,$userId);
	if ($item && dbNumRows($item) > 0) {
		$item = dbFetchObject($item);
		?>
		<form id="ideaRiskEvalDetails" onsubmit="updateRisk('<?= $item->riskEvaluationId ?>','ideaRiskEvalDetails'); return false;">
		<? renderGenericUpdateFormWithRefData(array(), $item, array("riskEvaluationId","groupId","userId","ideaId"), "RiskEvaluation");?>
		<input type="hidden" name="riskEvaluationId" value="<?= $item->riskEvaluationId ?>"/>
		<input type="hidden" name="action" value="updateRiskEval" />
		<input type="submit" value="Update" /></form>
		Go to <a href='javascript:showCompare(); dijit.byId("ideasPopup").hide()'>Compare</a> to edit data
	<?} else {?>
		<p>No compare data for idea</p>
		<p>Add comparison data for idea <a onclick="addRiskItem('<?= $ideaId ?>');loadPopupShow()" href="javascript:logAction()">now</a> </p> 
		Go to <a href='javascript:showCompare(); dijit.byId("ideasPopup").hide()'>Compare</a>
	<?}
}
?>