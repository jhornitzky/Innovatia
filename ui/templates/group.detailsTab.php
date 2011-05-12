<form id='groupDetailsForm'>
	<?if ($group->userId == $_SESSION['innoworks.ID'])
		renderGenericUpdateForm($groups, $group ,array('groupId', 'userId', 'createdTime', 'lastUpdateTime'));
	else
		renderGenericInfoForm($groups, $group ,array('groupId', 'userId', 'createdTime', 'lastUpdateTime'));?>
	<input type='hidden' name='action' value='updateGroup' /> <input type='hidden' name='groupId' value='<?=$group->groupId?>' />
</form>
		
<div class="two-column" style="border-top:1px solid #EEE; margin-right:2%; width:58%;">
	<p><b>Ideas<input type='button' value=' + ' onclick='showAddGroupIdea(this)' alt='Add an idea to the group'/></b></p>
	<?
	$groupIdeas = getIdeasForGroup($currentGroupId);
	if ($groupIdeas && dbNumRows($groupIdeas) > 0) {?>
		<table style='border: 1px solid #DDD'>
		<tr><th></th><th title='Allow others to edit this idea'>Edit</th><th></th></tr>
		<? while ($idea = dbFetchObject($groupIdeas)) { ?>
			<tr>
				<td>
					<img src='". $serverUrl . $uiRoot . "innoworks/retrieveImage.php?action=ideaImg&actionId=$idea->ideaId' style='width:1em; height:1em;'/>
					<a href="javascript:showIdeaDetails('<?= $idea->ideaId ?>')"> <?= $idea->title ?></a></td>
			<? if ($idea->userId == $_SESSION['innoworks.ID']) {
				if ($idea->canEdit == 1)
				$checked = "checked";
				else
				$checked="";
				echo "<td><input type='checkbox' onclick='toggleGroupEditIdea(this, $idea->ideaId, $idea->groupId)' title='Assign edit access to group' $checked/></td>";
				echo "<td><input type='button' value =' - ' onclick='delIdeaFromCurGroup($idea->ideaId)' title='Remove this idea from the group'/></td>";
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
		<p>None</p>
	<? } ?>
</div>

<div class='two-column' style='border-top: 1px solid #EEE; width:38%;'>
	<p style="font-weight:bold">User(s) 
		<?if ($group->userId == $_SESSION['innoworks.ID']) { ?>
		<input
			type='button' value=' + ' onclick='showAddGroupUser(this)'
			alt='Add user to group' />
		<?}?>
	</p>
	<?if ($groupUsers && dbNumRows($groupUsers) > 0) {?>
		<ul>
		<?while ($user = dbFetchObject($groupUsers)) {?>
			<li>
				<img src='<?= $serverUrl . $uiRoot ."innoworks/retrieveImage.php?action=userImg&actionId=" . $user->userId?>' style='width:1em; height:1em;'/><a href='javascript:showProfileSummary("<?= $user->userId ?>")'><?= getDisplayUsername($user) ?></a>
				<?php if ($group->userId != $user->userId && $group->userId == $_SESSION['innoworks.ID']) {?> <input type='button' value =' - ' onclick='delUserFromCurGroup(<?= $user->userId ?>)' alt='Remove user from group'/>" <?}?>
				<?php if ($group->userId != $user->userId && !hasAccessToGroup($group->groupId, $user->userId) && $group->userId == $_SESSION['innoworks.ID']) { ?> <input type='button' value ='Approve' onclick='approveGroupUser(<?= $user->userId?>)' alt='Approve user for group'/> <?}?>
			</li>
		<?}?>
		</ul>
	<?} else {?>
		<p>None</p>
	<?}?>
		
	<p><b>Attachments</b></p>
	<iframe style='width:100%;height:15em; padding:1px; border:1px solid #EEEEEE; background-color:#EEEEEE;' src='attachment.php?groupId=$group->groupId'></iframe>
</div>