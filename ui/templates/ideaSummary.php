<div class="summaryContainer">
<span class="summaryActions"><a href="javascript:printIdea('<?= $ideaUrl ?>')"><img src="<?= $uiRoot . 'style/social/printIcon.jpg'?>"/></a> <a href="javascript:showIdeaDetails('<?= $ideaId?>');"><img src="<?= $uiRoot . 'style/social/edit.jpg'?>"/></a> </span>
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
<h2>Roles</h2>
<?renderIdeaRoles($ideaId, false);?>
<h2>Features</h2>
<?renderIdeaFeatures($ideaId, false);?>
<h2>Feature Evaluations</h2>
<?renderIdeaFeatureEvaluationsForIdea($ideaId, false);?>
<h2>Risk Evaluations</h2>
<?renderIdeaRiskEval($ideaId, $_SESSION['innoworks.ID']);?>
<h2>Comments</h2>
<?renderCommentsForIdea($ideaId, $_SESSION['innoworks.ID']);?>
</div>