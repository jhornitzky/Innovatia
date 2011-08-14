<div class='itemHolder clickable clearfix' onclick="updateForGroup('<?=$group->groupId?>','<?=$group->title?>')">
	<div class="lefter lefterImage">
		<img src="retrieveImage.php?action=groupImg&actionId=<?=$group->groupId?>"/>
	</div>
	<div class="lefter">
		<?= $group->title ?>
	</div>
</div>
