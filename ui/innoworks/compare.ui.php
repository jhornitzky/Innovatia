<? 
require_once("thinConnector.php");  

function renderDefault($user) {
	$riskItems = dbQuery("SELECT Ideas.title, RiskEvaluation.*  FROM RiskEvaluation, Ideas 
	WHERE RiskEvaluation.ideaId = Ideas.ideaId AND Ideas.userId=$user");
	
	echo "<table>";
	renderGenericHeader($riskItems, array("ideaId","riskEvaluationId","groupId", "userId"));
	while ($riskItem = dbFetchObject($riskItems)) {
		renderRiskItem($riskItems, $riskItem);
	}
	echo "</table>";
} 

function renderRiskItem($riskItems, $riskItem) {?>
	<tr id="riskform_<?= $riskItem->riskEvaluationId ?>">
		<?renderGenericUpdateRowWithRefData($riskItems, $riskItem, array("ideaId","riskEvaluationId","groupId", "userId"), "RiskEvaluation");?>
		<td>
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