<?if ($groups && dbNumRows($groups) > 0){
	while ($group = dbFetchObject($groups)) {?>
	<div class="tile clearfix" onclick="showGroupSummary('<?= $group->groupId; ?>')">
		<img src="<?= $serverUrl . $uiRoot ?>innoworks/engine.ajax.php?action=groupImg&actionId=<?= $group->groupId?>"/>
		<div class="lefter">
			<?= $group->title; ?>
		</div>
	</div>
	<?}
}?>
<div class="tile" onclick="showBook('group')">
	<div style="color:#AAA">see all</div>
	<h1>
		<?=$countGroups?>
	</h1>
	groups
	<div class="pointer" style="float:right; font-size:80px; color:#AAA; margin-top:-30px;">&raquo;</div>
</div>