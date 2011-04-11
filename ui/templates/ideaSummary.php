<table>
	<tr>
		<td><img
			src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=ideaImg&actionId=<?= $ideaId ?>"
			style="width: 3em; height: 3em;" /></td>
		<td>
			<h3>
			<?= $idea->title ?>
			</h3> <?= getDisplayUsername($idea->userId);?> | <span
			class="summaryActions"><a
				href="javascript:printIdea('<?= $ideaUrl ?>')">Print</a> <a
				href="javascript:showIdeaDetails('<?= $ideaId?>');">Edit</a> </span>
		</td>
	</tr>
</table>
<p>
	<b>Created</b>
	<?= $idea->createdTime?>
	<br /> <b>Updated</b>
	<?= $idea->lastUpdateTime?>
</p>
<p style="color: #444">
<?= $idea->proposedService?>
</p>
<div class="ideaDetailBit">
<?renderGenericInfoFormOnlyPopulated(null, $idea, array("proposedService","ideaId","userId", "title", "createdTime", "lastUpdateTime","isPublic",'username', 'firstName', 'lastName'));?>
</div>
<p>
	<h2>Role(s)</h2>
</p>
<?renderIdeaRoles($ideaId, false);?>
<p>
	<h2>Feature(s)</h2>
</p>
<?renderIdeaFeatures($ideaId, false);?>
<p>
	<h2>Comment(s)</h2>
</p>
<?renderCommentsForIdea($ideaId, $_SESSION['innoworks.ID']);?>
<p>
	<h2>Feature Evaluation(s)</h2>
</p>
<?renderIdeaFeatureEvaluationsForIdea($ideaId, false);?>
<p>
	<h2>Risk Evaluation(s)</h2>
</p>
<?renderIdeaRiskEval($ideaId, $_SESSION['innoworks.ID']);?>