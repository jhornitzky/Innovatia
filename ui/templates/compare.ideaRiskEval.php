<?php
if ($items && dbNumRows($items) > 0) {?>
<table class='evaluation'>
<?renderGenericHeaderWithRefData($items,array("ideaId","riskEvaluationId","groupId","userId","score", "createdTime", "lastUpdateTime"),"RiskEvaluation", "renderMeCallback");
while($item = dbFetchObject($items)) {?>
	<tr>
		<td class="headcol"><img
			src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=userImg&actionId=<?= $item->userId ?>"
			style="width: 2em; height: 2em;" /> <?= getDisplayUsername($item->userId); ?><br />
			<span style="font-size: 0.8em;"> <? if(isset($item->groupId)) { echo getGroupDetails($item->groupId)->title; } ?></span>
		</td>
		<?renderGenericInfoRow($items,$item,array("title","ideaId","riskEvaluationId","groupId","userId", "score", "createdTime", "lastUpdateTime"), "");	?>
		<td style="font-weight: bold; font-size: 2em"><?= $item->score ?>
		</td>
	</tr>
	<?}?>
</table>
	<?} else {?>
<p>No compare data for idea</p>
	<?}?>
<p class="summaryActions">
	You must go to <a
		href='javascript:showCompare(); dijit.byId("ideasPopup").hide()'>Compare</a>
	to edit data
</p>