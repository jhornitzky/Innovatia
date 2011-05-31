<div class="summaryContainer">
<span class="summaryActions"><a href="javascript:printIdea('<?= $ideaUrl ?>')">Print</a> <a href="javascript:showIdeaDetails('<?= $ideaId?>');">Edit</a> </span>
<table>
	<tr>
		<td><img
			src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=ideaImg&actionId=<?= $ideaId ?>"
			style="width: 3em; height: 3em;" /></td>
		<td>
			<h1><?= $idea->title ?></h1> 
		</td>
	</tr>
</table>
<p class="smallInfo"><b>By</b> <?= getDisplayUsername($idea->userId);?> <b>Created</b> <?= $idea->createdTime?> <b>Updated</b> <?= $idea->lastUpdateTime?></p>
<p style="color: #444"><?= $idea->proposedService?></p>
<div class="ideaDetailBit">
<?renderGenericInfoFormOnlyPopulated(null, $idea, array("proposedService","ideaId","userId", "title", "createdTime", "lastUpdateTime","isPublic",'username', 'firstName', 'lastName'));?>
</div>
<h2>Role(s)</h2>
<?renderIdeaRoles($ideaId, false);?>
<h2>Feature(s)</h2>
<?renderIdeaFeatures($ideaId, false);?>
<h2>Feature Evaluation(s)</h2>
<?renderIdeaFeatureEvaluationsForIdea($ideaId, false);?>
<h2>Risk Evaluation(s)</h2>
<?renderIdeaRiskEval($ideaId, $_SESSION['innoworks.ID']);?>
<h2>Comment(s)</h2>
<?renderCommentsForIdea($ideaId, $_SESSION['innoworks.ID']);?>
</div>