<?while ($idea = dbFetchObject($ideas)) {?>
<div class='itemHolder clickable'
	onclick="addRiskItemForGroup('<?=$idea->ideaId?>');"
	style="height: 2.5em;">
	<div class="lefter" style="padding: 0.1em;">
		<img
			src="<?= $uiRoot ?>innoworks/engine.ajax.php?action=ideaImg&actionId=<?= $idea->ideaId?>"
			style="width: 2.25em; height: 2.25em;" />
	</div>
	<div class="lefter">
	<?= $idea->title ?>
		<br /> <img
			src="<?= $uiRoot ?>innoworks/engine.ajax.php?action=userImg&actionId=<?= $idea->userId ?>"
			style="width: 1em; height: 1em;" /> <span style="color: #666"><?= getDisplayUsername($idea->userId)?>
		</span>
	</div>
</div>
	<?}
	if ($countIdeas > dbNumRows($ideas)) {?>
<a class="loadMore" href="javascript:logAction()"
	onclick="loadResults(this,{action:'getAddRiskIdeaForGroupItems', limit: '<?= $limit + 20; ?>', groupId:'<?=$groupId?>'})">Load
	more</a>
	<?}?>