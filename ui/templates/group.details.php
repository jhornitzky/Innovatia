<div class='clearfix' style="height:6em;border-bottom:1px solid #DDD; border-top:1px solid #DDD">
	<div class="tiny">currently viewing group...</div> 
	<div class="lefter lefterImage">
		<img
			src="retrieveImage.php?action=groupImg&actionId=<?=$group->groupId?>"
			style="width: 3.5em; height: 3.5em" />
	</div>
	<div class="lefter">
		<h1><?= $group->title ?></h1>
		<? if (isset($groupUser)) {?>
		<input type='button'
			onclick='currentGroupId="<?=$group->groupId?>"; delUserFromCurGroup("<?= $_SESSION['innoworks.ID'] ?>")'
			value='Leave' alt='Leave group' />
			<? } else if ($group->userId == $_SESSION['innoworks.ID']) { ?>
		<input type='button' onclick='deleteGroup("<?= $group->groupId ?>")'
			value='Delete' alt='Delete group' />
			<? } ?>
	</div>
	<div class="righter">
		<div>
			<p style="font-size: 0.8em; margin-top:0" >
				Share this group with a friend <a href="<?= $shareUrl ?>">here</a>
			</p>
			<div style="float:left;cursor:pointer" onclick="printGroup()"><img src="<?= $serverRoot?>ui/style/social/printIcon.jpg" style="width:32px; height:32px"/></div>
			<? renderTemplate('shareBtns', null); ?>
		</div>
	</div>
</div>
