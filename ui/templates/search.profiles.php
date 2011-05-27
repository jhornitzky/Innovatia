<p><b><?=$countUsers?></b> profile(s)</p>
<?if ($users && dbNumRows($users) > 0){ 
	while ($user = dbFetchObject($users)) { ?>
		<div class='itemHolder clickable'
			onclick="showProfileSummary('<?=$user->userId?>')"
			style="height: 2.5em">
			<div class="lefter" style="padding: 0.1em;">
				<img
					src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=userImg&actionId=<?= $user->userId?>"
					style="width: 2em; height: 2em;" />
			</div>
			<div class="lefter">
			<?= getDisplayUsername($user->userId) ?>
				<br /> <span><?= $user->organization ?> </span>
			</div>
		</div>
	<?}
	if ($countUsers > dbNumRows($users)) {?>
		<a class="loadMore" href="javascript:logAction()"
			onclick="loadResults(this, {action:'getSearchProfiles', limit:'<?= ($limit + 20) ?>'})">Load
			more</a>
	<?}
} else {?>
	<p>No profiles</p>
<?}