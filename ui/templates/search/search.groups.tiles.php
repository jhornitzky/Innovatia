<div class="tile" style="background-image:url('<?= $serverUrl . $uiRoot?>style/group.png');">
	<h1 onclick="showGroupBook()">
		<?=$countGroups?>
	</h1>
</div>
<?if ($groups && dbNumRows($groups) > 0){
	while ($group = dbFetchObject($groups)) {?>
	<div class="tile clearfix" onclick="showGroupSummary('<?= $group->groupId; ?>')">
		<img src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=groupImg&actionId=<?= $group->groupId?>"/>
		<div class="lefter">
			<?= $group->title; ?>
		</div>
	</div>
	<?}
}?>