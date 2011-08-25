<div class="infoBox clickable clearfix" onclick="showProfileSummary('<?=$profile->userId?>')" title="<?= getDisplayUsername($profile->userId); ?> : <?= $profile->organization ?>">
	<img src="engine.ajax.php?action=userImg&actionId=<?= $profile->userId ?>"/>
</div>
<!-- <div class="itemHolder clickable clearfix" onclick="showProfileSummary('<?=$profile->userId?>')">
	<div class="lefter">
		<?= getDisplayUsername($profile->userId); ?> <?= $profile->organization ?>
	</div>
	<div class="righter righterImage">
		<img
			src="engine.ajax.php?action=userImg&actionId=<?= $profile->userId ?>"
			style="width: 1em; height: 1em" />
	</div>
</div>-->