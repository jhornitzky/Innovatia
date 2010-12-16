<?
require_once(dirname(__FILE__) . "/../pureConnector.php");
import("group.service");

function renderGroupDefault($user) {
	$limit = 20;
	$groups = getGroupsForCreatorUser($user);
	if ($groups && dbNumRows($groups) > 0 ) {
		while ($group = dbFetchObject($groups)) {
			renderGroup($groups,$group);
		}
	} 
	$groups = getPartOfGroupsForUser($user);
	if ($groups && dbNumRows($groups) > 0 ) {
		while ($group = dbFetchObject($groups)) {
			renderPartOfGroups($groups,$group);
		}
	} 
	echo "<div>";
	renderOtherGroupsForUser($user, $limit);
	echo "</div>";
}

function renderOtherGroupsForUser($user, $limit) {
	$groups = getOtherGroupsForUser($user, "LIMIT $limit");
	$groupsCount = countGetOtherGroups($user);
	if ($groups && dbNumRows($groups) > 0 ) {
		while ($group = dbFetchObject($groups)) {
			renderOtherGroup($groups,$group);
		} 
		if ($groupsCount > dbNumRows($groups)) {?>
			<a href="javascript:logAction()" onclick="loadResults(this, {action:'getOtherGroupsForUser', limit:'<?= ($limit + 20) ?>'})">Load more</a>
		<?}
	}
}

function renderGroup($groups, $group) {
	echo "<div class='itemHolder hoverable'>";
	echo '<img src="retrieveImage.php?action=groupImg&actionId='.$group->groupId.'" style="width:3em; height:3em"/><br/>';
	echo "<a href='javascript:logAction()' onclick='updateForGroup(\"".$group->groupId."\",\"".$group->title."\")'>" . $group->title . "</a><br/>";
	echo "<input type='button' onclick='deleteGroup(" . $group->groupId .")' value=' - ' alt='Delete group' />";
	echo "</div>";
}

function renderPartOfGroups($groups, $group) {
	echo "<div class='itemHolder hoverable'>";
	echo '<img src="retrieveImage.php?action=groupImg&actionId='.$group->groupId.'" style="width:3em; height:3em"/><br/>';
	echo "<a href='javascript:logAction()' onclick='updateForGroup(\"".$group->groupId."\",\"".$group->title."\")'>" . $group->title . "</a><br/>";
	echo "<input type='button' onclick='currentGroupId=$group->groupId; delUserFromCurGroup(" . $_SESSION['innoworks.ID'] .")' value=' Leave ' alt='Leave group' />";
	echo "</div>";
}

function renderOtherGroup($groups, $group) {
	echo "<div class='itemHolder hoverable'>";
	echo '<img src="retrieveImage.php?action=groupImg&actionId='.$group->groupId.'" style="width:3em; height:3em"/><br/>';
	echo "<a href='javascript:logAction()' onclick='updateForGroup(\"".$group->groupId."\",\"".$group->title."\")'>" . $group->title . "</a></div>";
}

function renderGroupDetails($currentGroupId) {
	global $serverUrl, $serverRoot, $uiRoot;
	$groups = getGroupWithId($currentGroupId, $_SESSION['innoworks.ID']);
	$groupUserEntry = getGroupUserEntryWithId($currentGroupId, $_SESSION['innoworks.ID']);
	$shareUrl = $serverUrl . $serverRoot . "ui/innoworks/viewer.php?group=" . $currentGroupId;

	$group;
	$groupUser;
	if ($groups && dbNumRows($groups) > 0)
		$group = dbFetchObject($groups);
	if ($groupUserEntry && dbNumRows($groupUserEntry) > 0)
		$groupUser = dbFetchObject($groupUserEntry);

	if ($group == null && $groupUser == null)
		die("No group exists");
	
	$userService = new AutoObject("user.service");?>
	<table>
	<tr>
	<td style="vertical-align:top">
	<img src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=groupImg&actionId=<?= $group->groupId?>" style="width:8em; height:8em;"/>
	</td>
	<td style="width:90%"><h3 style="margin-bottom:0.5em;"><?= $group->title?></h3>
	<div style="margin-bottom:0.5em">
		<span class="title"><?= $userService->getUserInfo($group->userId)->username?></span> 
		<span class="timestamp"><?= $group->createdTime ?></span> | 
		<a href="javascript:logAction()" onclick="printGroup()">Print</a>
		<p style="font-size:0.8em;">Share this group with a friend at:<br/> <?= $shareUrl ?></p>
		<div class="shareBtns">	
			<img src="<?= $serverRoot?>ui/style/emailbuttonmini.jpg" onclick="openMail('yourFriend@theirAddress.com', 'Check out my idea on innoworks', 'I thought you might like my idea. You can see it at <?= $shareUrl ?>')" />
			<img src="<?= $serverRoot?>ui/style/fb btn.png" onclick="openFace()" />
			<img class="shareLeft" src="<?= $serverRoot?>ui/style/delicious btn.png" onclick="openDeli()" />
			<img class="shareLeft" src="<?= $serverRoot?>ui/style/twit btn.png" onclick="openTweet()"/>
			<img class="shareLeft" src="<?= $serverRoot?>ui/style/blogger btn.png" onclick="openBlog()"/>
		</div>
	</div></td>
	</tr>
	</table>
	
	<?if ($groupUser->approved == 0 && $groupUser->accepted == 1) {
		echo "You have asked for access to this group, but have not been approved. You can contact the lead " . $userService->getUserInfo($group->userId)->username . ".";
	} else if ($groupUser->approved == 1 && $groupUser->accepted == 0) {
		echo "Do you wish to accept your invitation to this group?<br/>";
		echo "<a href='javascript:logAction()' onclick='acceptGroup();'>Yes</a> <a href='javascript:logAction()' onclick='refuseGroup();'>No</a> ";
	} else if (($groupUser->approved == 1 && $groupUser->accepted == 1) || $group->userId == $_SESSION['innoworks.ID']) {
		if ($groups && (dbNumRows($groups) == 1)) {
			$userService = new AutoObject("user.service");
			
			echo "<div class='two-column' style='border-top:1px solid #EEE'>";
			echo "<h3>Details</h3>";
			echo "<form id='groupDetailsForm'>";
			if ($group->userId == $_SESSION['innoworks.ID'])
				renderGenericUpdateForm($groups, $group ,array('groupId', 'userId', 'createdTime', 'lastUpdateTime'));
			else 
				renderGenericInfoForm($groups, $group ,array('groupId', 'userId', 'createdTime', 'lastUpdateTime'));
			echo "<input type='hidden' name='action' value='updateGroup'/>";
			echo "<input type='hidden' name='groupId' value='$group->groupId'/>";
			echo "</form>";
			
			echo "<h3>Users";
			if ($group->userId == $_SESSION['innoworks.ID'])
				echo "<input type='button' value=' + ' onclick='showAddGroupUser(this)' alt='Add user to group'/>";
			echo "</h3>";
			$groupUsers = getUsersForGroup($currentGroupId);
			if ($groupUsers && dbNumRows($groupUsers) > 0) {
				echo "<ul>";
				while ($user = dbFetchObject($groupUsers)) {
					echo "<li>
					<img src='". $serverUrl . $uiRoot . "innoworks/retrieveImage.php?action=userImg&actionId=$user->userId' style='width:1em; height:1em;'/><a href='javascript:showProfileSummary(\"$user->userId\")'>$user->username</a>";
					if ($group->userId == $_SESSION['innoworks.ID']) echo "<input type='button' value =' - ' onclick='delUserFromCurGroup($user->userId)' alt='Remove user from group'/>";
					if ($user->approved == 0 && $group->userId == $_SESSION['innoworks.ID']) echo "<input type='button' value ='Approve' onclick='approveGroupUser($user->userId)' alt='Approve user for group'/>";
					echo "</li>";
				}
				echo "</ul>";
			} else {
				echo "<p>None</p>";
			}
			
			echo "<h3>Attachments</h3>";
			echo "<iframe style='width:100%;height:15em; padding:1px; border:1px solid #EEEEEE; background-color:#EEEEEE;' src='attachment.php?groupId=$group->groupId'></iframe>";
			echo "</div>";
			
			echo '<div class="two-column" style="border-top:1px solid #EEE; margin-left:2%; ">';
			echo "<h3>Ideas<input type='button' value=' + ' onclick='showAddGroupIdea(this)' alt='Add an idea to the group'/></h3>";
			$groupIdeas = getIdeasForGroup($currentGroupId);
			if ($groupIdeas && dbNumRows($groupIdeas) > 0) {
				echo "<table style='border: 1px solid #DDD'>";
				echo "<tr><th></th><th title='Allow others to edit this idea'>Edit</th><th></th></tr>";
				while ($idea = dbFetchObject($groupIdeas)) {
					echo "<tr><td>
					<img src='". $serverUrl . $uiRoot . "innoworks/retrieveImage.php?action=ideaImg&actionId=$idea->ideaId' style='width:1em; height:1em;'/>
					<a href=\"javascript:showIdeaDetails('$idea->ideaId')\" >" . $idea->title . "</a></td>";
					if ($idea->userId == $_SESSION['innoworks.ID']){
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
					}
					echo "</tr>";
				}
				echo "</table>";
			} else {
				echo "<p>None</p>";
			}
			echo "</div>";
		} 
	} else {
		echo "<p>You have no access to this group. Do you want to request access to this group?</p>";
		echo "<a href='javascript:logAction()' onclick='requestGroup()'>Yes, I want to join the group</a>";
	}
}

function renderGroupSummary($currentGroupId) {
	global $serverUrl, $uiRoot;
	$groups = getGroupWithId($currentGroupId, $_SESSION['innoworks.ID']);
	$groupUserEntry = getGroupUserEntryWithId($currentGroupId, $_SESSION['innoworks.ID']);

	$group;
	$groupUser;
	if ($groups && dbNumRows($groups) > 0)
		$group = dbFetchObject($groups);
	if ($groupUserEntry && dbNumRows($groupUserEntry) > 0)
		$groupUser = dbFetchObject($groupUserEntry);

	if ($group == null && $groupUser == null)
		die("No group exists");
	
	$userService = new AutoObject("user.service");
	$shareUrl = $serverUrl . $uiRoot . "innoworks/viewer.php?group=" . $currentGroupId;
	?>
	<table>
	<tr>
	<td>
	<img src="<?= $serverUrl .  $uiRoot ?>innoworks/retrieveImage.php?action=groupImg&actionId=<?= $group->groupId ?>" style="width:4em; height:4em;"/> 
	</td>
	<td><h3><?= $group->title?></h3>
	<div style="margin-bottom:1.0em">
		<span class="title"><?= $userService->getUserInfo($group->userId)->username?></span> 
		<span class="summaryActions"><a href="javascript:logAction()" onclick="printGroup()">Print</a>
		<a href="javascript:logAction()" onclick="currentGroupId=<?= $group->groupId ?>;showGroups();">Edit</a></span>
	</div>
	</td>
	</tr>
	</table>
	<p>Share this group with a friend at:<br/> <?= $shareUrl ?></p>
	<div class="shareBtns">	
		<img src="<?= $uiRoot?>/style/emailbuttonmini.jpg" onclick="openMail('yourFriend@theirAddress.com', 'Check out my idea on innoworks', 'I thought you might like my idea. You can see it at <?= $shareUrl ?>')" />
		<img src="<?= $uiRoot?>/style/fb btn.png" onclick="openFace()" />
		<img class="shareLeft" src="<?= $uiRoot?>/style/delicious btn.png" onclick="openDeli()" />
		<img class="shareLeft" src="<?= $uiRoot?>/style/twit btn.png" onclick="openTweet()"/>
		<img class="shareLeft" src="<?= $uiRoot?>/style/blogger btn.png" onclick="openBlog()"/>
	</div>
	<?
	renderGenericInfoForm($groups, $group ,array('groupId', 'userId', 'createdTime', 'lastUpdateTime'));
	if ($groups && (dbNumRows($groups) == 1)) {
		$userService = new AutoObject("user.service");
			echo "<h3>Ideas</h3>";
			$groupIdeas = getIdeasForGroup($currentGroupId);
			if ($groupIdeas && dbNumRows($groupIdeas) > 0) {
				echo "<ul>";
				while ($idea = dbFetchObject($groupIdeas)) {
					echo "<li>";
					echo "<img src='". $serverUrl . $uiRoot . "innoworks/retrieveImage.php?action=ideaImg&actionId=$idea->ideaId' style='width:1em; height:1em;'/>";
					echo $idea->title . "</li>";
				}
				echo "</ul>";
			} else {
				echo "<p>None</p>";
			}
			
			echo "<h3>Users</h3>";
			$groupUsers = getUsersForGroup($currentGroupId);
			if ($groupUsers && dbNumRows($groupUsers) > 0) {
				echo "<ul>";
				while ($user = dbFetchObject($groupUsers)) {
					echo "<li><img src='". $serverUrl . $uiRoot . "innoworks/retrieveImage.php?action=userImg&actionId=$user->userId' style='width:1em; height:1em;'/>";
					echo "$user->username</li>";
				}
				echo "</ul>";
			} else {
				echo "<p>None</p>";
		}
	} 
}

/* POPUP BOX OPTIONS FOR GROUPS */

function renderAddUser() {
	import("user.service");
	echo "Select a user to add to group";
	$users = getAllUsers();
	if ($users && dbNumRows($users) > 0) {
		echo "<ul>";
		while ($user = dbFetchObject($users)) {
			echo "<li><a href='javascript:addUserToCurGroup(\"$user->userId\")'>".$user->username."</a></li>";
		}
		echo "</ul>";
	}
}

function renderAddIdea() {
	import("idea.service");
	echo "Select an idea to add to group";
	$ideas = getIdeas($_SESSION['innoworks.ID']);
	if ($ideas && dbNumRows($ideas) > 0) {
		echo "<ul>";
		while ($idea = dbFetchObject($ideas)) {
			echo  "<li><a href='javascript:logAction()' onclick='addIdeaToCurGroup(\"$idea->ideaId\")'>".$idea->title. "</a></li>";
		}
		echo "</ul>";
	}
}

function renderPublicAddIdea() {
	import("idea.service");
	echo "Select an idea to add to public";
	$ideas = getIdeas($_SESSION['innoworks.ID']);
	if ($ideas && dbNumRows($ideas) > 0) {
		echo "<ul>";
		while ($idea = dbFetchObject($ideas)) {
			echo  "<li><a href='javascript:logAction()' onclick='addIdeaToPublic(\"$idea->ideaId\")'>".$idea->title. "</a></li>";
		}
		echo "</ul>";
	}
}

function renderIdeaShare($ideaId, $userId) {
	global $serverUrl, $serverRoot;
	import("group.service");
	import("idea.service");
	$idea = dbFetchObject(getIdeaDetails($ideaId));
	if ($idea->userId == $_SESSION['innoworks.ID'] || $_SESSION['innoworks.isAdmin']) {
	$groups = getAllGroupsForUser($_SESSION['innoworks.ID']);
	$items = dbFetchAll(getIdeaShareDetails($ideaId));
	$shareUrl = $serverUrl . $serverRoot . "ui/innoworks/viewer.php?idea=" . $ideaId;
	?>
	<div>
	<div style="width:49%; float:left">
	<table style="border:1px solid #DDD">
	<tr><th>Group</th><th>Viewable</th><th>Editable</th></tr>
	<tr>
		<td style="background-color:#EEE;">Public<br/>
			<span style="font-size:0.85em">Share your idea with everyone</span>
		</td>
		<td style="background-color:#EEE;"><input id="ideaIsPublic" type="checkbox" onclick="togglePublicIdea(this)" <? if ($idea->isPublic == 1) echo "checked"; ?>/></td>
		<td style="background-color:#EEE;"></td>
	</tr>
	<?
	if ($groups && dbNumRows($groups) > 0 ) {
		while ($group = dbFetchObject($groups)) {
			renderIdeaGroupItem($idea, $group, $items);
		}
	}
	?>
	</table>
	</div>
	<div style="width:49%; float:left; clear:right;">
	<p>Show <a href='javascript:showGroups(); dijit.byId("ideasPopup").hide()'>Groups</a></p>
	<p>Share this idea with a friend at:<br/> <?= $shareUrl ?></p>
	<div class="shareBtns">	
		<img src="<?= $serverRoot?>ui/style/emailbuttonmini.jpg" onclick="openMail('yourFriend@theirAddress.com', 'Check out my idea on innoworks', 'I thought you might like my idea. You can see it at <?= $shareUrl ?>')" />
		<img src="<?= $serverRoot?>ui/style/fb btn.png" onclick="openFace()" />
		<img class="shareLeft" src="<?= $serverRoot?>ui/style/delicious btn.png" onclick="openDeli()" />
		<img class="shareLeft" src="<?= $serverRoot?>ui/style/twit btn.png" onclick="openTweet()"/>
		<img class="shareLeft" src="<?= $serverRoot?>ui/style/blogger btn.png" onclick="openBlog()"/>
	</div>
	</div>
	</div>
<?	} else {
		echo "You did not create this idea, and therefore cannot control sharing. If you want edit access to this idea, contact the owner.";
	}
}

function renderIdeaGroupItem($idea, $group, $items) {
	$shared = false;
	$canEdit = false;
	foreach($items as $item) {
		$item = (object) $item;
		if ($item->groupId == $group->groupId) {
			$shared = true;
			if ($item->canEdit == 1) {
				$canEdit = true; 
			}
		}
	}
	if (is_array($items) && in_array($group->groupId, $items))
		$shared = true;?>
	<tr>
	
		<td><img src="retrieveImage.php?action=groupImg&actionId=<?= $group->groupId ?>" style="width:1em;height:1em;"/><?= $group->title ?></td>
		<td><input type="checkbox" onclick="toggleGroupShareIdea(this, <?= $group->groupId ?>)" <? if ($shared) echo "checked"; ?>/></td>
		<td><input type='checkbox' onclick='toggleGroupEditIdea(this, <?= $idea->ideaId ?>, <?= $group->groupId ?>)' alt='Assign edit access to group' <? if ($canEdit) echo "checked"; ?>/></td>
	</tr>
<?}