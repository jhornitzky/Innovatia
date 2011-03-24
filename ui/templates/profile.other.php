<div class="itemHolder clickable"
	onclick="showProfileSummary('<?=$profile->userId?>')"
	style="height: 2.5em">
	<div class="righter righterImage">
		<img
			src="retrieveImage.php?action=userImg&actionId=<?= $profile->userId ?>"
			style="width: 2em; height: 2em" />
	</div>
	<div class="righter">
		<span><?= getDisplayUsername($profile->userId); ?> </span><br /> <span><?= $profile->organization ?>
		</span>
	</div>
</div>
