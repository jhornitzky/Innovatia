<div class='itemHolder clickable clearfix' onclick="updateForGroup('<?=$group->groupId?>','<?=$group->title?>')">
	<div class="lefter">
		<?= $group->title ?> <span>group ideas</span>
	</div>
	<div class="righter righterImage">
		<img src="retrieveImage.php?action=groupImg&actionId=<?=$group->groupId?>" style="width: 1em; height: 1em" />
	</div>
</div>
