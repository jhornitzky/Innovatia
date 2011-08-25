<div class="summaryContainer">
<span class="summaryActions"><a href="javascript:printIdea('<?= $ideaUrl ?>')"><img src="<?= $uiRoot . 'style/social/printIcon.jpg'?>"/></a> <a href="javascript:showIdeaDetails('<?= $ideaId?>');"><img src="<?= $uiRoot . 'style/social/edit.jpg'?>"/></a> </span>
<div class="summaryHead curvetr">
	<table>
		<tr>
			<td style="background-color:#FFF"; border-radius:2px; -webkit-border-radius:2px; -moz-border-radius:2px;">
				<? if (!isset($_REQUEST['doc'])) {?>
				<img src="<?= $serverUrl . $uiRoot ?>innoworks/engine.ajax.php?action=ideaImg&actionId=<?= $ideaId ?>" style="width: 3em; height: 3em;" />
				<? } ?>
			</td>
			<td>
				<h1><?= $idea->title ?></h1> 
			</td>
		</tr>
	</table>
	<p class="smallInfo"><b>By</b><? renderTemplate('ideator', array('userId' => $idea->userId));?><b>Created</b> @ <?= prettyDate($idea->createdTime) ?> <b>Updated</b> @ <?= prettyDate($idea->lastUpdateTime) ?></p>
	<p style="color: #444; font-size:0.85em"><?= $idea->proposedService?></p>
</div>
<div class="ideaDetailBit">
<?renderGenericInfoLinesOnlyPopulated(null, $idea, array("proposedService","ideaId","userId", "title", "createdTime", "lastUpdateTime","isPublic",'username', 'firstName', 'lastName'));?>
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