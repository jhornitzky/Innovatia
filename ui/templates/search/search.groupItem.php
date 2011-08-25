<div class='itemHolder clickable clearfix'
	onclick="showGroupSummary('<?= $group->groupId; ?>')"
	style="height: 2.5em">
	<div class="lefter" style="padding: 0.1em;">
		<img
			src="<?= $serverUrl . $uiRoot ?>innoworks/engine.ajax.php?action=groupImg&actionId=<?= $group->groupId?>"
			style="width: 2em; height: 2em;" />
	</div>
	<div class="lefter">
		<?= $group->title; ?><br/> 
		<img
			src="<?= $serverUrl . $uiRoot ?>innoworks/engine.ajax.php?action=userImg&actionId=<?= $group->userId ?>"
			style="width: 1em; height: 1em;" /> <span><?= getDisplayUsername($group->userId); ?>
		</span>
	</div>
</div>