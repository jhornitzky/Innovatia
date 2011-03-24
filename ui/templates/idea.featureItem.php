<tr id="featureitemform_<?= $featureItem->featureEvaluationId ?>">
<? if ($canEdit) {
	renderGenericUpdateRowWithRefData($featureItems, $featureItem, array("featureId","featureEvaluationId","groupId", "userId","ideaFeatureEvaluationId", "score"), "FeatureEvaluation", "renderFeatureEvaluationItemCallback");?>
	<?} else {
		renderGenericInfoRow($featureItems, $featureItem, array("featureId","featureEvaluationId","groupId", "userId","ideaFeatureEvaluationId", "score"), "FeatureEvaluation", null);?>
		<?}?>
	<td class="totalCol"><span class="itemTotal">0</span> <? if ($canEdit) {?><input
		type="button"
		onclick="deleteFeatureItem('<?= $featureItem->featureEvaluationId ?>')"
		value=" - " /> <? } ?></td>
</tr>
