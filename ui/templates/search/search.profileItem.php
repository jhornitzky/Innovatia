<div class='itemHolder clickable clearfix'
	onclick="showProfileSummary('<?=$user->userId?>')"
	style="height: 2.5em">
	<div class="lefter" style="padding: 0.1em;">
		<img
			src="<?= $serverUrl . $uiRoot ?>innoworks/engine.ajax.php?action=userImg&actionId=<?= $user->userId?>"
			style="width: 2em; height: 2em;" />
	</div>
	<div class="lefter">
	<?= getDisplayUsername($user->userId) ?>
		<br /> <span><?= $user->organization ?> </span>
	</div>
</div>
