<h3 style="padding-top:1em;padding-bottom:0.5em;">
	<?=$countGroups?> <span style="color:#AAA">groups</span>
</h3>
<?if ($groups && dbNumRows($groups) > 0){
	while ($group = dbFetchObject($groups)) {?>
<div class='itemHolder clickable clearfix'
	onclick="showGroupSummary('<?= $group->groupId; ?>')"
	style="height: 2.5em">
	<div class="lefter" style="padding: 0.1em;">
		<img
			src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=groupImg&actionId=<?= $group->groupId?>"
			style="width: 2em; height: 2em;" />
	</div>
	<div class="lefter">
		<?= $group->title; ?><br/> 
		<img
			src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=userImg&actionId=<?= $group->userId ?>"
			style="width: 1em; height: 1em;" /> <span><?= getDisplayUsername($group->userId); ?>
		</span>
	</div>
</div>
	<?}
	if ($countGroups > dbNumRows($groups)) {
		renderTemplate('common.loadMore', array('action' => 'getSearchGroups', 'limit' => ($limit + 20)));
	}
}?>