<?
require_once(dirname(__FILE__) . "/../pureConnector.php");
import("group.service");

function renderGroupDefault($user) {
	$limit = 5;?>
	<div class="treeMenu">
	<div class='itemHolder headBorder' style='background-color:#EEE'>Leader<br/><span>Groups that you created</span></div>
	<div style="padding-bottom:1.0em; font-size:0.9em">
		<? renderGroupsForCreatorUser($user, $limit); ?>
	</div>
	<div class='itemHolder headBorder' style='background-color:#EEE'>Part of<br/><span>Groups that you are in</span></div>
	<div style="padding-bottom:1.0em; font-size:0.9em">
		<? renderPartOfGroupsForUser($user, $limit); ?>
	</div>
	<div class='itemHolder headBorder' style='background-color:#EEE'>Other<br/><span>More groups to join</span></div>
	<div style="padding-bottom:1.0em; font-size:0.9em">
		<? renderOtherGroupsForUser($user, $limit); ?>
	</div>
	</div>
<?}

function renderGroupsForCreatorUser($user, $limit) {
	$groups = getGroupsForCreatorUser($user, "LIMIT $limit");
	$groupsCount = countGetGroupsForCreatorUser($user);
	if ($groups && dbNumRows($groups) > 0 ) {
		while ($group = dbFetchObject($groups)) {
			renderGroupItem($group);
		} 
		if ($groupsCount > dbNumRows($groups)) {?>
			<a class="loadMore" href="javascript:logAction()" onclick="loadResults(this, {action:'getGroupsForCreatorUser', limit:'<?= ($limit + 20) ?>'})">Load more</a>
		<?}
	} else {?>
		<span>None</span>
	<?}
}

function renderPartOfGroupsForUser($user, $limit) {
	$groups = getPartOfGroupsForUser($user, "LIMIT $limit");
	$groupsCount = countGetPartOfGroupsForUser($user);
	if ($groups && dbNumRows($groups) > 0 ) {
		while ($group = dbFetchObject($groups)) {
			renderGroupItem($group);
		} 
		if ($groupsCount > dbNumRows($groups)) {?>
			<a class="loadMore" href="javascript:logAction()" onclick="loadResults(this, {action:'getPartOfGroupsForUser', limit:'<?= ($limit + 20) ?>'})">Load more</a>
		<?}
	} else {?>
		<span>None</span>
	<?}
}

function renderOtherGroupsForUser($user, $limit) {
	import("user.service");
	$groups = getOtherGroupsForUser($user, "LIMIT $limit");
	$groupsCount = countGetOtherGroups($user);
	if ($groups && dbNumRows($groups) > 0 ) {
		while ($group = dbFetchObject($groups)) {
			renderGroupItem($group);
		} 
		if ($groupsCount > dbNumRows($groups)) {?>
			<a class="loadMore" href="javascript:logAction()" onclick="loadResults(this, {action:'getOtherGroupsForUser', limit:'<?= ($limit + 20) ?>'})">Load more</a>
		<?}
	} else {?>
		<span>None</span>
	<?}
}

function renderGroupItem($group) {?>
	<div class='itemHolder clickable' style="height:2.5em;" onclick="updateForGroup('<?=$group->groupId?>','<?=$group->title?>')">
		<div class="righter righterImage">
			<img src="retrieveImage.php?action=groupImg&actionId='<?=$group->groupId?>'" style="width:2.25em; height:2.25em"/>
		</div>
		<div class='righter'>
			<?= $group->title ?><br/>
			<span><?= getDisplayUsername($group->userId); ?></span>
		</div>
	</div>
<?}

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
		die("Click on a group to see its details");
	
	$userService = new AutoObject("user.service");
	$groupUsers = getUsersForGroup($currentGroupId);
	?>
	<div class='itemHolder headBorder treeMenu' style="height:7em;">
		<div class="lefter lefterImage">
			<img src="retrieveImage.php?action=groupImg&actionId=<?=$group->groupId?>" style="width:5em; height:5em"/>
		</div>
		<div class="lefter">
			<h3><?= $group->title ?></h3>
			<?= getDisplayUsername($group->userId); ?><br/>
			<? if (isset($groupUser)) {?>
				<input type='button' onclick='currentGroupId=<?=$group->groupId?>"; delUserFromCurGroup("<?= $_SESSION['innoworks.ID'] ?>")' value=' Leave ' alt='Leave group' />
			<? } else if ($group->userId == $_SESSION['innoworks.ID']) { ?>
				<input type='button' onclick='deleteGroup("<?= $group->groupId ?>")' value=' - ' alt='Delete group' />
			<? } ?>
		</div>
		<div class="righter" style="padding-left:0.5em;">
			<span class="timestamp">Created <?= $group->createdTime ?></span> | 
			<a href="javascript:logAction()" onclick="printGroup()">Print</a>
			<div style="padding-left:0.25em;">
				<p style="font-size:0.8em;">Share this group with a friend at:<br/> <?= $shareUrl ?></p>
				<?renderTemplate('shareBtns', null) ?>
			</div>
		</div>
	</div>
	<?if ($groupUser->approved == 0 && $groupUser->accepted == 1) {
		echo "<p>You have asked for access to this group, but have not been approved. You can contact the lead " . $userService->getUserInfo($group->userId)->username . ".</p>";
	} else if ($groupUser->approved == 1 && $groupUser->accepted == 0) {
		echo "<p>Do you wish to accept your invitation to this group?</p>";
		echo "<a href='javascript:logAction()' onclick='acceptGroup();'>Yes, I want to join</a> <a href='javascript:logAction()' onclick='refuseGroup();'>No, I don't wannt to join</a>";
	} else if (($groupUser->approved == 1 && $groupUser->accepted == 1) || $group->userId == $_SESSION['innoworks.ID']) {
		if ($groups && (dbNumRows($groups) == 1)) {
			$userService = new AutoObject("user.service");?>
			<ul class="submenu">
				<li class="greybox"><a href="javascript:logAction()" onclick="showGroupComments(this)">Comments</a></li>
				<li class="greybox"><a href="javascript:logAction()" onclick="showGroupSubDetails(this)">Details</a></li>
				<li class="bluebox"><a href="javascript:logAction()" onclick="showGroupIdeate(this)">Ideate</a></li>
				<li class="bluebox"><a href="javascript:logAction()" onclick="showGroupCompare(this)">Compare</a></li>
				<li class="bluebox"><a href="javascript:logAction()" onclick="showGroupSelect(this)">Select</a></li>
			</ul>
			
			<div class="groupInfo" style="margin-top:2em;">
			</div>
		<?} 
	} else {
		echo "<p>You have no access to this group. Do you want to request access to this group?</p>";
		echo "<a href='javascript:logAction()' onclick='requestGroup()'>Yes, I want to join the group</a>";
	}
}

function renderGroupIdeateTab($currentGroupId) {}

function renderGroupCompareTab($currentGroupId) {}

function renderGroupSelectTab($currentGroupId) {}

function renderGroupDetailsTab($currentGroupId) {
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
		die("Click on a group to see its details");?>
			<div class='two-column' style='border-top:1px solid #EEE'>
			<p><b>Details</b></p>
			<form id='groupDetailsForm'>
			<? if ($group->userId == $_SESSION['innoworks.ID'])
				renderGenericUpdateForm($groups, $group ,array('groupId', 'userId', 'createdTime', 'lastUpdateTime'));
			else 
				renderGenericInfoForm($groups, $group ,array('groupId', 'userId', 'createdTime', 'lastUpdateTime'));
			?>
			<input type='hidden' name='action' value='updateGroup'/>
			<input type='hidden' name='groupId' value='<?=$group->groupId?>'/>
			</form>
			
			<p><b>User(s)
			<?if ($group->userId == $_SESSION['innoworks.ID']) { ?><input type='button' value=' + ' onclick='showAddGroupUser(this)' alt='Add user to group'/><?}?>
			</b></p>
			<?if ($groupUsers && dbNumRows($groupUsers) > 0) {
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
			
			echo "<p><b>Attachments</b></p>";
			echo "<iframe style='width:100%;height:15em; padding:1px; border:1px solid #EEEEEE; background-color:#EEEEEE;' src='attachment.php?groupId=$group->groupId'></iframe>";
			echo "</div>";
			
			echo '<div class="two-column" style="border-top:1px solid #EEE; margin-left:2%; ">';
			echo "<p><b>Ideas<input type='button' value=' + ' onclick='showAddGroupIdea(this)' alt='Add an idea to the group'/></b></p>";
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
			}?>
			</div>
<?}

function renderGroupSummary($currentGroupId) {
	global $serverUrl, $uiRoot;
	$groups = getGroupWithId($currentGroupId, $_SESSION['innoworks.ID']);
	$group;
	if ($groups && dbNumRows($groups) > 0)
		$group = dbFetchObject($groups);
	else
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
		<span><?= $userService->getDisplayUsername($group->userId)?></span>  |
		<span class="summaryActions"><a href="javascript:logAction()" onclick="printGroupSummary('<?= "&group=" . $group->groupId ?>')">Print</a>
		<a href="javascript:logAction()" onclick="currentGroupId=<?= $group->groupId ?>;showGroups();">Edit</a></span>
	</div>
	</td>
	</tr>
	</table>
	<?
	renderGenericInfoForm($groups, $group ,array('groupId', 'userId', 'createdTime', 'lastUpdateTime', 'title'));
	
	if (!hasAccessToGroup($group->groupId, $_SESSION['innoworks.ID'])) 
		die("You have no access to this group");
		
	if ($groups && (dbNumRows($groups) == 1)) {
		$userService = new AutoObject("user.service");
		echo "<p><b>Idea(s)</b></p>";
		$groupIdeas = getIdeasForGroup($currentGroupId, $_SESSION['innoworks.ID'], "LIMIT 500");
		if ($groupIdeas && dbNumRows($groupIdeas) > 0) {
			while ($idea = dbFetchObject($groupIdeas)) {?>
				<div class="itemHolder">
					<img src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=ideaImg&actionId=<?= $idea->ideaId?>" style="width:1em; height:1em;"/>
					<a href="javascript:logAction()" onclick="showIdeaSummary('<?= $idea->ideaId?>');"><?=$idea->title?></a>
					<span><?= $userService->getDisplayUsername($idea->userId); ?></span>
				</div>
			<?}
			if (dbNumRows($groups) > 100) {?>
				<p>Only displaying 500 latest ideas</p>
			<?}
		} else {
			echo "<p>None</p>";
		}
			
		echo "<p><b>User(s)</b></p>";
		$groupUsers = getUsersForGroup($currentGroupId, "LIMIT 100");
		if ($groupUsers && dbNumRows($groupUsers) > 0) {
			while ($user = dbFetchObject($groupUsers)) {?>
				<div class="itemHolder">
					<img src='<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=userImg&actionId=$user->userId' style='width:1em; height:1em;'/>
					<a href="javascript:logAction()" onclick="showProfileSummary('<?= $user->userId ?>')"><?=$user->firstName . ' ' . $user->lastName . ' / ' . $user->username?></a>
				</div>
			<?}
			if (dbNumRows($groups) > 100) {?>
				<p>Only displaying 100 latest users</p>
			<?}
		} else {
			echo "<p>None</p>";
		}
	}
	 
}

/* POPUP BOX OPTIONS FOR GROUPS */
function renderAddUser($actionId, $user, $criteria) {
	global $uiRoot;
	$limit=20;?>
	<p>Select a user to add to group</p>
	<div style="width:100%; clear:both; height:2.5em;">
	<form id="popupAddSearch" onsubmit="findUsers(); return false;">
		<div style="border: 1px solid #444444; position: relative; float: left; clear:right">
			<table cellpadding="0" cellspacing="0">
			<tr>
			<td><input type="text"  name="criteria" value="<?= $searchTerms ?>" placeholder=" . . . " style="border: none" /></td>
			<td><img src="<?= $uiRoot."style/glass.png"?>" onclick="findAddUser()" style="width:30px; height:24px; margin:2px;cursor:pointer"/></td>
			</tr>
			</table>
			<input id="searchBtn" type="submit" value="Search" style="display:none;"/>
		</div>
	</form>
	</div>
	<?renderAddUserItems($criteria, $limit);
}

function renderAddUserItems($criteria, $limit) {
	global $uiRoot;
	import("search.service");
	$users = getSearchPeople($criteria, $_SESSION['innoworks.ID'], "", "LIMIT $limit");
	$countUsers = countGetSearchPeople($criteria, $_SESSION['innoworks.ID'], "");
	if ($users && dbNumRows($users) > 0) {
		while ($user = dbFetchObject($users)) {?>
			<div class='itemHolder clickable' onclick="addUserToCurGroup('<?=$user->userId?>')" style="height:2.5em">
				<div class="lefter" style="padding:0.1em;">
					<img src="<?= $uiRoot ?>innoworks/retrieveImage.php?action=userImg&actionId=<?= $user->userId?>" style="width:2em; height:2em;"/>
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
}

function renderAddIdea($actionId, $user, $criteria) {
	global $uiRoot;
	$limit=20;?>
	<p>Add a <b>private</b> idea to the group</p>
	<div style="width:100%; clear:both; height:2.5em;">
	<form id="popupAddSearch" onsubmit="findIdeas(); return false;">
		<div style="border: 1px solid #444444; position: relative; float: left; clear:right">
			<table cellpadding="0" cellspacing="0">
			<tr>
			<td><input type="text"  name="criteria" value="<?= $searchTerms ?>" placeholder=" . . . " style="border: none" /></td>
			<td><img src="<?= $uiRoot."style/glass.png"?>" onclick="findIdeas();" style="width:30px; height:24px; margin:2px;cursor:pointer"/></td>
			</tr>
			</table>
			<input id="searchBtn" type="submit" value="Search" style="display:none;"/>
		</div>
	</form>
	</div>
	<div>
	<?renderAddIdeaItems($criteria, $limit);?>
	</div>
<?}

function renderAddIdeaItems($criteria, $limit) {
	global $uiRoot;
	import("search.service");
	$ideas = getSearchIdeasByUser($criteria, $_SESSION['innoworks.ID'],"", "LIMIT $limit");
	$countIdeas = countGetSearchIdeasByUser($criteria, $_SESSION['innoworks.ID'],"", "LIMIT $limit");
	if ($ideas && dbNumRows($ideas) > 0) {
		while ($idea = dbFetchObject($ideas)) {?>
			<div class='itemHolder clickable' onclick="addIdeaToCurGroup(<?= $idea->ideaId?>);" style="height:2.5em;"> 
				<div class="lefter" style="padding:0.1em;">
					<img src="<?= $uiRoot ?>innoworks/retrieveImage.php?action=ideaImg&actionId=<?= $idea->ideaId?>" style="width:2.25em;height:2.25em;"/>
				</div>
				<div class="lefter">
					<?= $idea->title ?><br/>
					<img src="<?= $uiRoot ?>innoworks/retrieveImage.php?action=userImg&actionId=<?= $idea->userId ?>" style="width:1em;height:1em;"/>
					<span style="color:#666"><?= getDisplayUsername($idea->userId)?></span>
				</div>
			</div>
		<?}
		if ($countIdeas > dbNumRows($ideas)) {?>
			<a class="loadMore" href="javascript:logAction()" onclick="loadResults(this,{action:'getAddIdeaItems', limit: '<?= $limit + 20; ?>'})">Load more</a>
		<?}
	} else {?>
		<p>No ideas found</p>
	<?}
}

function renderPublicAddIdea($actionId, $user, $criteria) {
	global $uiRoot;
	$limit=20;?>
	<p>Add a <b>private</b> idea to the public space</p>
	<div style="width:100%; clear:both; height:2.5em;">
	<form id="popupAddSearch" onsubmit="findPublicIdeas(); return false;">
		<div style="border: 1px solid #444444; position: relative; float: left; clear:right">
			<table cellpadding="0" cellspacing="0">
			<tr>
			<td><input type="text"  name="criteria" value="<?= $searchTerms ?>" placeholder=" . . . " style="border: none" /></td>
			<td><img src="<?= $uiRoot."style/glass.png"?>" onclick="findPublicIdeas();" style="width:30px; height:24px; margin:2px;cursor:pointer"/></td>
			</tr>
			</table>
			<input id="searchBtn" type="submit" value="Search" style="display:none;"/>
		</div>
	</form>
	</div>
	<div>
	<?renderPublicAddIdeaItems($criteria, $limit);?>
	</div>
<?}

function renderPublicAddIdeaItems($criteria, $limit) {
	global $uiRoot;
	import("search.service");
	$ideas = getSearchIdeasByUser($criteria, $_SESSION['innoworks.ID'],"", "LIMIT $limit");
	$countIdeas = countGetSearchIdeasByUser($criteria, $_SESSION['innoworks.ID'],"", "LIMIT $limit");
	if ($ideas && dbNumRows($ideas) > 0) {
		while ($idea = dbFetchObject($ideas)) {?>
			<div class='itemHolder clickable' onclick="addIdeaToPublic(<?= $idea->ideaId?>);" style="height:2.5em;"> 
				<div class="lefter" style="padding:0.1em;">
					<img src="<?= $uiRoot ?>innoworks/retrieveImage.php?action=ideaImg&actionId=<?= $idea->ideaId?>" style="width:2.25em;height:2.25em;"/>
				</div>
				<div class="lefter">
					<?= $idea->title ?><br/>
					<img src="<?= $uiRoot ?>innoworks/retrieveImage.php?action=userImg&actionId=<?= $idea->userId ?>" style="width:1em;height:1em;"/>
					<span style="color:#666"><?= getDisplayUsername($idea->userId)?></span>
				</div>
			</div>
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
	$groups = getAllGroupsForUser($_SESSION['innoworks.ID'], "LIMIT 100");
	$countGroups = countGetAllGroupsForUser($_SESSION['innoworks.ID']);
	$items = dbFetchAll(getIdeaShareDetails($ideaId));
	$shareUrl = $serverUrl . $serverRoot . "ui/innoworks/viewer.php?idea=" . $ideaId;?>
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
<?}


function renderIdeaGroupsListForUser($uid) {
	$limit = 5;?>
	<!-- <div class='itemHolder headBorder' style='background-color:#EEE'>Change space</div>-->
	<div class='itemHolder clickable headBorder private' onclick="showDefaultIdeas()">Private<br/>
	<span>Ideas that are yours</span></div>
	<div class='itemHolder clickable headBorder public' onclick="showPublicIdeas()">Public<br/>
	<span>Ideas shared by everyone</span></div>
	<div class='groupsHolder'>
		<div class='itemHolder headBorder'>Groups</div>
		<div class='groupsActualHolder' style='font-size:0.9em;'>
			<? renderIdeaGroupItemsForUser($uid, $limit); ?>
		</div>
	</div>
<?}

function renderIdeaGroupItemsForUser($uid, $limit) {
	global $serverRoot;
	import("group.service");
	import("user.service");
	$groups = getAllGroupsForUser($uid, "LIMIT $limit");
	$countGroups = countGetAllGroupsForUser($uid);
	if ($groups && dbNumRows($groups) > 0 ) {
		while ($group = dbFetchObject($groups)) {?>
			<div class='itemHolder clickable' onclick='showIdeasForGroup(<?=$group->groupId?>)' style="height:2.5em">
				<div class="righter righterImage">
					<img src="retrieveImage.php?action=groupImg&actionId=<?=$group->groupId?>" style="width:2.25em; height:2.25em"/><br/>
				</div>
				<div class="righter">
					<?= $group->title ?><br/>
					<span><?= getDisplayUsername($group->userId); ?></span>
				</div>
			</div>
		<?} if ($countGroups > dbNumRows($groups)) ?>
			<a class="loadMore" href="javascript:logAction()" onclick="loadResults(this, {action: 'getIdeaGroupItemsForUser', limit:'<?= $limit + 20; ?>'})">
			Load more</a>
	<?} else {?>
		<div style="margin-bottom:0.5em;">None</div>
	<?}
}

function renderGroupPreview($gid, $uid) {
	import("group.service");
	if (!isset($gid)) 
		die();
	$group = getGroupDetails($gid);?>
	<div style="height:3.5em">
		<div class="righter righterImage">
			<img src="retrieveImage.php?action=groupImg&actionId=<?=$group->groupId?>" style="width:3.5em; height:3.5em"/><br/>
		</div>
		<div class="righter">
			<h3><?= $group->title ?></h3>
			<?= getDisplayUsername($group->userId); ?>
		</div>
	</div>
<?}

function renderPublicPreview($uid) {
	global $uiRoot;?>
	<div style="height:3.5em">
		<div class="righter righterImage">
			<img src="<?= $uiRoot . "style/public.png"?>" style="width:3.5em; height:3.5em"/><br/>
		</div>
		<div class="righter">
			<h3>Public</h3>
			Everyone's shared ideas 
		</div>
	</div>
<?}

function renderPrivatePreview($uid) {
	global $uiRoot;?>
	<div style="height:3.5em">
		<div class="righter righterImage">
			<img src="retrieveImage.php?action=userImg&actionId=<?= $uid ?>" style="width:3.5em; height:3.5em"/><br/>
		</div>
		<div class="righter">
			<h3>Private</h3>
			Ideas created by you
		</div>
	</div>
<?}

function renderPublic() {
	global $serverRoot, $serverUrl, $uiRoot;
	$limit = 10;
	$announces = getAnnouncements("LIMIT 10");?>
	<div style="width:100%;">
		<div class="fixed-left">
			<div class="treeMenu">
				<div class='itemHolder headBorder' style='background-color:#DDD'>Latest announcements</div>
				<div>
				<?if ($announces && dbNumRows($announces)) {
					while($announce = dbFetchObject($announces)) {?>
						<div class="itemHolder" style="font-size:0.85em">
							<?= $announce->text ?><br/>
							<span><?= getDisplayUsername($announce->userId) . " " . $announce->date ?></span>
						</div>
					<?}
				}?>
				</div>
			</div>
		</div>
		<div class="fixed-right">
			<div class='itemHolder headBorder treeMenu' style="height:70px;">
				<div class="lefter">
					<h3 style="padding-left:0.25em;"><img src="<?=$uiRoot?>/style/public.png"/> Public</h3>
				</div>
			</div>
			<ul class="submenu">
				<li class="bluebox"><a href="javascript:logAction()" onclick="showPublicIdeate(this)">Ideate</a></li>
				<li class="bluebox"><a href="javascript:logAction()" onclick="showPublicCompare(this)">Compare</a></li>
				<li class="bluebox"><a href="javascript:logAction()" onclick="showPublicSelect(this)">Select</a></li>
			</ul>
			<div class="publicInfo" style="margin-top:2em"></div>
		</div>
	</div>
<?}

?>