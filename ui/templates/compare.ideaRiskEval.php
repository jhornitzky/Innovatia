<?php
if ($items && dbNumRows($items) > 0) {?>
	<table class='evaluation'>
	<?renderGenericHeaderWithRefData($items,array("ideaId","riskEvaluationId","groupId","userId","score", "createdTime", "lastUpdateTime"),"RiskEvaluation", "renderMeCallback");
	while($item = dbFetchObject($items)) {?>
		<tr>
			<td class="headcol">
				<? renderTemplate('ideator', array('userId' => $item->userId)) ?><br />
				<span style="font-size: 0.8em;"> <? if(isset($item->groupId)) { echo getGroupDetails($item->groupId)->title; } ?></span>
			</td>
			<?renderGenericInfoRow($items,$item,array("title","ideaId","riskEvaluationId","groupId","userId", "score", "createdTime", "lastUpdateTime"), "");	?>
			<td style="font-weight: bold; font-size: 2em"><?= $item->score ?>
			</td>
		</tr>
		<?}?>
	</table>
<?} else {?>
	<p class="nohelp">Comparing an idea gives you insight into how to make things better.</p>
<?}?>
<p style="clear:both;" class="compareForce">
	You must go to <a href='javascript:showCompare(); dijit.byId("ideasPopup").hide()'>Compare</a> to edit data
</p>