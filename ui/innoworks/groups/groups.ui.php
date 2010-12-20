<?
require_once(dirname(__FILE__) . "/../pureConnector.php");
import("group.service");

function renderGroupDefault($user) {
	$limit = 20;?>
	<h4>My Groups</h4>
	<div>
		<? renderGroupsForCreatorUser($user, $limit); ?>
	</div>
	<h4>Groups Im part of</h4>
	<div>
		<? renderPartOfGroupsForUser($user, $limit); ?>
	</div>
	<h4>Other groups</h4>
	<div>
		<? renderOtherGroupsForUser($user, $limit); ?>
	</div>
<?}

function renderGroupsForCreatorUser($user, $limit) {
	$groups = getGroupsForCreatorUser($user, "LIMIT $limit");
	$groupsCount = countGetGroupsForCreatorUser($user);
	if ($groups && dbNumRows($groups) > 0 ) {
		while ($group = dbFetchObject($groups)) {
			echo "<div class='itemHolder hoverable'>";
			echo '<img src="retrieveImage.php?action=groupImg&actionId='.$group->groupId.'" style="width:3em; height:3em"/><br/>';
			echo "<a href='javascript:logAction()' onclick='updateForGroup(\"".$group->groupId."\",\"".$group->title."\")'>" . $group->title . "</a><br/>";
			echo "<input type='button' onclick='deleteGroup(" . $group->groupId .")' value=' - ' alt='Delete group' />";
			echo "</div>";
		} 
		if ($groupsCount > dbNumRows($groups)) {?>
			<a href="javascript:logAction()" onclick="loadResults(this, {action:'getGroupsForCreatorUser', limit:'<?= ($limit + 20) ?>'})">Load more</a>
		<?}
	}
}

function renderPartOfGroupsForUser($user, $limit) {
	$groups = getPartOfGroupsForUser($user, "LIMIT $limit");
	$groupsCount = countGetPartOfGroupsForUser($user);
	if ($groups && dbNumRows($groups) > 0 ) {
		while ($group = dbFetchObject($groups)) {
			echo "<div class='itemHolder hoverable'>";
			echo '<img src="retrieveImage.php?action=groupImg&actionId='.$group->groupId.'" style="width:3em; height:3em"/><br/>';
			echo "<a href='javascript:logAction()' onclick='updateForGroup(\"".$group->groupId."\",\"".$group->title."\")'>" . $group->title . "</a><br/>";
			echo "<input type='button' onclick='currentGroupId=$group->groupId; delUserFromCurGroup(" . $_SESSION['innoworks.ID'] .")' value=' Leave ' alt='Leave group' />";
			echo "</div>";
		} 
		if ($groupsCount > dbNumRows($groups)) {?>
			<a href="javascript:logAction()" onclick="loadResults(this, {action:'getPartOfGroupsForUser', limit:'<?= ($limit + 20) ?>'})">Load more</a>
		<?}
	}
}

function renderOtherGroupsForUser($user, $limit) {
	$groups = getOtherGroupsForUser($user, "LIMIT $limit");
	$groupsCount = countGetOtherGroups($user);
	if ($groups && dbNumRows($groups) > 0 ) {
		while ($group = dbFetchObject($groups)) {
			echo "<div class='itemHolder hoverable'>";
			echo '<img src="retrieveImage.php?action=groupImg&actionId='.$group->groupId.'" style="width:3em; height:3em"/><br/>';
			echo "<a href='javascript:logAction()' onclick='updateForGroup(\"".$group->groupId."\",\"".$group->title."\")'>" . $group->title . "</a></div>";
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
function renderAddUser($actionId, $user, $criteria) {
	$limit=20;?>
	<form id="popupAddSearch" onsubmit="findUsers();return false;">
		<input type="text" name="criteria"/> 
		<input type="submit" value="Find Users"/>
	</form>
	<p>Select a user to add to group</p>
	<?renderAddUserItems($criteria, $limit);
}


function renderAddUserItems($criteria, $limit) {
	import("search.service");
	$users = getSearchPeople($criteria, $_SESSION['innoworks.ID'], "", "LIMIT $limit");
	$countUsers = countGetSearchPeople($criteria, $_SESSION['innoworks.ID'], "");
	if ($users && dbNumRows($users) > 0) {
		while ($user = dbFetchObject($users)) {?>
			<div><a href='javascript:logAction()' onclick='addUserToCurGroup("<?= $user->userId ?>")'><?= $user->username ?></a></div>
		<?}
		if ($countUsers > dbNumRows($users)) {?>
			<a href="javascript:logAction()" onclick="loadResults(this,{action:'getAddUserItems', limit: '<?= $limit + 20; ?>'})">Load more</a>
		<?}
	} else {?>
		<p>No users found</p>
	<?}
}

function renderAddIdea($actionId, $user, $criteria) {
	$limit=20;?>
	<form id="popupAddSearch" onsubmit="findIdeas();return false;">
		<input id="addIdeaSearchTerms" type="text" name="criteria"/> 
		<input type="submit" value="Find Ideas"/>
	</form>
	<p>Select an idea to add to group</p>
	<div>
	<?renderAddIdeaItems($criteria, $limit);?>
	</div>
<?}

function renderAddIdeaItems($criteria, $limit) {
	import("search.service");
	$ideas = getSearchIdeasByUser($criteria, $_SESSION['innoworks.ID'],"", "LIMIT $limit");
	$countIdeas = countGetSearchIdeasByUser($criteria, $_SESSION['innoworks.ID'],"", "LIMIT $limit");
	if ($ideas && dbNumRows($ideas) > 0) {
		while ($idea = dbFetchObject($ideas)) {?>
			<div><a href='javascript:logAction()' onclick='addIdeaToCurGroup("<?= $idea->ideaId ?>")'><?= $idea->title ?></a></div>
		<?}
		if ($countIdeas > dbNumRows($ideas)) {?>
			<a href="javascript:logAction()" onclick="loadResults(this,{action:'getAddIdeaItems', limit: '<?= $limit + 20; ?>'})">Load more</a>
		<?}
	} else {?>
		<p>No ideas found</p>
	<?}
}

function renderPublicAddIdea($actionId, $user, $criteria) {
	$limit=20;?>
	<form id="popupAddSearch" onsubmit="findPublicIdeas();return false;">
		<input id="addIdeaSearchTerms" type="text" name="criteria"/> 
		<input type="submit" value="Find Ideas"/>
	</form>
	<p>Select an idea to make public</p>
	<div>
	<?renderPublicAddIdeaItems($criteria, $limit);?>
	</div>
<?}

function renderPublicAddIdeaItems($criteria, $limit) {
	import("search.service");
	$ideas = getSearchIdeasByUser($criteria, $_SESSION['innoworks.ID'],"", "LIMIT $limit");
	$countIdeas = countGetSearchIdeasByUser($criteria, $_SESSION['innoworks.ID'],"", "LIMIT $limit");
	if ($ideas && dbNumRows($ideas) > 0) {
		while ($idea = dbFetchObject($ideas)) {?>
			<div><a href='javascript:logAction()' onclick='addIdeaToPublic("<?= $idea->ideaId ?>")'><?= $idea->title ?></a></div>
		<?}
		if ($countIdeas > dbNumRows($ideas)) {?>
			<a href="javascript:logAction()" onclick="loadResults(this,{action:'getPublicAddIdeaItems', limit: '<?= $limit + 20; ?>'})">Load more</a>
		<?}
	} else {?>
		<p>No ideas found</p>
	<?}
}

function renderIdeaShare($ideaId, $userId) {
	global $serverUrl, $serverRoot;
	import("group.service");
	import("idea.service");
	$idea = dbFetchObject(getIdeaDetails($ideaId));
	if ($idea->userId == $_SESSION['innoworks.ID'] || $_SESSION['innoworks.isAdmin']) {
	$groups = getAllGroupsForUser($_SESSION['innoworks.ID'], "LIMIT 200");
	$countGroups = countGetAllGroupsForUser($_SESSION['innoworks.ID']);
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
	<? if ($countGroups > dbNumRows($groups)) {?>
		<p>Displaying only 200 of <?= $countGroups?> your groups. Go to groups or search to manage.</p>
	<? } ?>
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
<?}?>