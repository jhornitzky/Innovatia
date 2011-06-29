	<h3 style="margin-top:20px">Info<hr/></h3>
	<form id='groupDetailsForm' style="margin-top:0; padding-top:0;">
		<?if ($group->userId == $_SESSION['innoworks.ID'])
			renderGenericUpdateLines($groups, $group ,array('groupId', 'userId', 'createdTime', 'lastUpdateTime'));
		else
			renderGenericInfoLines($groups, $group ,array('groupId', 'userId', 'createdTime', 'lastUpdateTime'));?>
		<input type='hidden' name='action' value='updateGroup' /> <input type='hidden' name='groupId' value='<?=$group->groupId?>' />
	</form>
	<form class="addForm">
		<h3 style="margin-top:20px">Users 
			<?if ($group->userId == $_SESSION['innoworks.ID']) { ?>
			<input type='button' value=' + ' onclick='showAddGroupUser(this)' alt='Add user to group' />
			<?}?>
		</h3>
	</form>
	<?if ($groupUsers && dbNumRows($groupUsers) > 0) {?>
		<ul>
		<?while ($user = dbFetchObject($groupUsers)) {?>
			<li>
				<img src='<?= $serverUrl . $uiRoot ."innoworks/retrieveImage.php?action=userImg&actionId=" . $user->userId?>' style='width:1em; height:1em;'/><a href='javascript:showProfileSummary("<?= $user->userId ?>")'><?= getDisplayUsername($user) ?></a>
				<?php if ($group->userId != $user->userId && $group->userId == $_SESSION['innoworks.ID']) {?> <img style="width:1em; height:1em;" onclick="delUserFromCurGroup(<?= $user->userId ?>)" src="<?= $uiRoot . 'style/trash.png'?>" alt="Remove user from group"/><?}?>
				<?php if ($group->userId != $user->userId && !hasAccessToGroup($group->groupId, $user->userId) && $group->userId == $_SESSION['innoworks.ID']) { ?> <input type='button' value ='Approve' onclick='approveGroupUser(<?= $user->userId?>)' alt='Approve user for group'/> <?}?>
			</li>
		<?}?>
		</ul>
	<?} else {?>
		<p>None</p>
	<?}?>
	
	<form class="addForm">
		<h3 style="margin-top:20px">
			Ideas<input type='button' value=' + ' onclick='showAddGroupIdea(this)' alt='Add an idea to the group'/>
		</h3>
	</form>
	<?
	$groupIdeas = getIdeasForGroup($currentGroupId);
	if ($groupIdeas && dbNumRows($groupIdeas) > 0) {?>
		<table style='border: 1px solid #DDD'>
		<tr><th></th><th title='Allow others to edit this idea'>Edit</th><th></th></tr>
		<? while ($idea = dbFetchObject($groupIdeas)) { ?>
			<tr>
				<td>
					<img src='<?= $serverUrl . $uiRoot . "innoworks/retrieveImage.php?action=ideaImg&actionId=" . $idea->ideaId ?>' style='width:1em; height:1em;'/>
					<a href="javascript:showIdeaDetails('<?= $idea->ideaId ?>')"> <?= $idea->title ?></a>
				</td>
				<? if ($idea->userId == $_SESSION['innoworks.ID']) {
					if ($idea->canEdit == 1)
					$checked = "checked";
					else
					$checked="";
					echo "<td><input type='checkbox' onclick='toggleGroupEditIdea(this, $idea->ideaId, $idea->groupId)' title='Assign edit access to group' $checked/></td>";
					echo "<td> <img style='width:1em; height:1em;' onclick='delIdeaFromCurGroup($idea->ideaId)' src='" . $uiRoot . "style/trash.png' alt='Remove idea from group'/></td>";
				} else {
					echo "<td>";
					if ($idea->canEdit == 1)
					echo "Y";
					else
					echo "N";
					echo "</td>";
					echo "<td></td>";
				}?>
			</tr>
		<?}?>
		</table>
	<? } else { ?>
		<div class="tiny">None</div>
	<? } ?>

	<h3 style="margin-top:20px">Attachments<hr/></h3>
	<iframe style='width:100%; height:15em; padding:1px; border:1px solid #EEEEEE; background-color:#EEEEEE;' src='attachment.php?groupId=<?=$group->groupId?>'></iframe>