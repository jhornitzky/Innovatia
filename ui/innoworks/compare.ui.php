<? 
require_once("thinConnector.php");  

function renderDefault($user) {
	$riskItems = getRiskItems($user);
	if ($riskItems && dbNumRows($riskItems) > 0){
		echo "<table>";
		renderGenericHeader($riskItems, array("ideaId","riskEvaluationId","groupId", "userId"));
		while ($riskItem = dbFetchObject($riskItems)) {
			renderRiskItem($riskItems, $riskItem);
		}
		echo "</table>";?>
		<script type="text/javascript">
			initFormSelectTotals();
		</script>
		<?
	} else {
		echo "<p>No items yet. Add some items from the above. </p>";
	}
} 

function renderRiskItem($riskItems, $riskItem) {?>
	<tr id="riskform_<?= $riskItem->riskEvaluationId ?>">
		<?renderGenericUpdateRowWithRefData($riskItems, $riskItem, array("ideaId","riskEvaluationId","groupId", "userId"), "RiskEvaluation");?>
		<td>
			Score: <span class="itemTotal">0 </span>
			<input type="hidden" name="riskEvaluationId" value="<?= $riskItem->riskEvaluationId ?>"/>
			<input type="button" onclick="updateRisk('<?= $riskItem->riskEvaluationId ?>','riskform_<?= $riskItem->riskEvaluationId ?>')"  value=" U "/>
			<input type="button" onclick="deleteRisk('<?= $riskItem->riskEvaluationId ?>')"  value=" - "/>
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

?>