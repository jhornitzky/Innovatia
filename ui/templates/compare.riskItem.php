<tr id="riskform_<?= $riskItem->riskEvaluationId ?>">
<?renderGenericUpdateRowWithRefData($riskItems, $riskItem, array("ideaId","riskEvaluationId","groupId", "userId", "score", "createdTime", "lastUpdateTime"), "RiskEvaluation","renderRiskItemCallbackRow");?>
	<td class="totalCol">
	<span class="itemTotal" title="Risk evaluation score">0</span> | <span class="featureTotal"
		title="Feature evaluation score"><?= $featuresTotal ?>
	</span></td>
</tr>
