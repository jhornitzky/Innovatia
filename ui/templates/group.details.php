<div class='clearfix' style="height:6em; overflow:hidden;">
	<div class="overlay" style="color:#AAA; position:absolute; opacity:0.1; height:8em;">
		<img src="retrieveImage.php?action=groupImg&actionId=<?=$group->groupId?>" style="height: 100%;" /> 
	</div>
	<div class="lefter">
		<h1 style="font-size:3.5em;">
			<?= $group->title ?>
		</h1>
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
			<p style="font-size: 0.8em; margin: 0; padding: 0; color:#AAA">
				Share this group with a friend <a href="<?= $shareUrl ?>">here</a>
			</p>
			<div style="float:left;cursor:pointer" onclick="printGroup()">
				<img src="<?= $serverRoot?>ui/style/social/printIcon.jpg" style="width:32px; height:32px"/>
			</div>
			<? renderTemplate('shareBtns', null); ?>
		</div>
	</div>
</div>