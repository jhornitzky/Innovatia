<div
	id="featureEvaluationContainer_<?= $featureEvaluation->ideaFeatureEvaluationId ?>"
	class="featureEvaluation itemHolder">
	<table class="titleTT">
		<tr>
			<td style="width: 2.5em;"><span class="evalTotal"
				style="font-size: 3em; font-weight: bold"><?=$total?> </span></td>
			<td style="width: 13em;"><span class="title"><?=$featureEvaluation->title?>
			</span><span class="timestamp">by <?= getDisplayUsername($featureEvaluation->userId) ?>
			</span> <br /> <? if ($canEdit) { ?> <input type="button"
				onclick="doAction('action=deleteFeatureEvaluation&actionId=<?= $featureEvaluation->ideaFeatureEvaluationId ?>');getFeatureEvaluationsForIdea();"
				title="Delete feature evaluation" value=" - " /> <?}
				$featureList = getFeaturesForIdea($id);
				if ($featureList && dbNumRows($featureList) > 0 ) {
					if ($canEdit) { ?>
				<div dojoType="dijit.form.DropDownButton">
					<span> Add feature </span>
					<div dojoType="dijit.Menu">
					<? while ($feature = dbFetchObject($featureList)) {?>
						<div dojoType="dijit.MenuItem"
							onClick="addFeatureItem(<?= $feature->featureId ?>,<?= $featureEvaluation->ideaFeatureEvaluationId ?>)">
							<?= $feature->feature ?>
						</div>
						<?}?>
					</div>
				</div> <?}
				}?>
			</td>
			<td> 
				<?if ($canEdit) {?> 
					<textarea dojoType="dijit.form.Textarea"
						onblur="updateFeatureEvalSummary(this, '<?= $featureEvaluation->ideaFeatureEvaluationId?>')">
						<?= $featureEvaluation->summary ?>
					</textarea> 
				<? } else { ?>
					<div>
						<?= $featureEvaluation->summary ?>
					</div> 
				<?}?>
			</td>
		</tr>
	</table>
	<?renderFeatureEvaluationForIdea($featureEvaluation->ideaId, $featureEvaluation->ideaFeatureEvaluationId, $canEdit, $featureList);?>
</div>