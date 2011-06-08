<h3 style="padding-top:1em;padding-bottom:0.5em;">
	<?=$countUsers?> <span style="color:#AAA">profiles</span>
</h3>
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
	if ($countUsers > dbNumRows($users)) {
		renderTemplate('common.loadMore', array('action' => 'getSearchProfiles', 'limit' => ($limit + 20)));
	}
}?>