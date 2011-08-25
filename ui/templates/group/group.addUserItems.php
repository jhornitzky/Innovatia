<? if ($users && dbNumRows($users) > 0) {
		while ($user = dbFetchObject($users)) {?>
			<div class='itemHolder clickable' onclick="addUserToCurGroup('<?=$user->userId?>')" style="height:2.5em">
				<div class="lefter" style="padding:0.1em;">
					<img src="<?= $uiRoot ?>innoworks/engine.ajax.php?action=userImg&actionId=<?= $user->userId?>" style="width:2em; height:2em;"/>
				</div>
				<div class="lefter">
					<?= getDisplayUsername($user->userId) ?><br/>
					<span><?= $user->interests  ?></span>
				</div>
			</div>
		<?}
		if ($countUsers > dbNumRows($users)) {?>
			<a href="javascript:logAction()" onclick="loadResults(this,{action:'getAddUserItems', limit: '<?= $limit + 20; ?>'})">Load more</a>
		<?}
	} else {?>
		<p>No users found</p>
	<?}