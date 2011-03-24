<table>
	<tr>
		<td><img
			src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=ideaImg&actionId=<?= $ideaId ?>"
			style="width: 3em; height: 3em;" />
		</td>
		<td>
			<h3>
			<?= $idea->title ?>
			</h3> <?= getDisplayUsername($idea->userId);?> | <span
			class="summaryActions"><a
				href="javascript:printIdea('<?= $ideaUrl ?>')">Print</a> <a
				href="javascript:showIdeaDetails('<?= $ideaId?>');">Edit</a>
		</span>
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
<?renderGenericInfoFormOnlyPopulated(null, $idea, array("proposedService","ideaId","userId", "title", "createdTime", "lastUpdateTime","isPublic",'username'));?>
<p>
	<b>Role(s)</b>
</p>
<?renderIdeaRoles($ideaId, false);?>
<p>
	<b>Feature(s)</b>
</p>
<?renderIdeaFeatures($ideaId, false);?>
<p>
	<b>Comment(s)</b>
</p>
<?renderCommentsForIdea($ideaId, $_SESSION['innoworks.ID']);?>
<p>
	<b>Feature Evaluation(s)</b>
</p>
<?renderIdeaFeatureEvaluationsForIdea($ideaId, false);?>
<p>
	<b>Risk Evaluation(s)</b>
</p>
<?renderIdeaRiskEval($ideaId, $_SESSION['innoworks.ID']);?>