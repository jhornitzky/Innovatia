<?
require_once(dirname(__FILE__) . "/../pureConnector.php");
import("group.service");

function renderGroupDefault($user) {
	$limit = 5;
	renderTemplate('group.list', get_defined_vars());
}

function renderGroupsForCreatorUser($user, $limit) {
	$groups = getGroupsForCreatorUser($user, "LIMIT $limit");
	$groupsCount = countGetGroupsForCreatorUser($user);
	if ($groups && dbNumRows($groups) > 0 ) {
		while ($group = dbFetchObject($groups)) {
			renderGroupItem($group);
		} 
		if ($groupsCount > dbNumRows($groups)) {
			renderTemplate('common.loadMore', array('action' => 'getGroupsForCreatorUser', 'limit' => ($limit + 20)));
		}
	} else {?>
		<div class="tiny topBorder">none</div>
	<?}
}

function renderPartOfGroupsForUser($user, $limit) {
	$groups = getPartOfGroupsForUser($user, "LIMIT $limit");
	$groupsCount = countGetPartOfGroupsForUser($user);
	if ($groups && dbNumRows($groups) > 0 ) {
		while ($group = dbFetchObject($groups)) {
			renderGroupItem($group);
		} 
		if ($groupsCount > dbNumRows($groups)) {
			renderTemplate('common.loadMore', array('action' => 'getPartOfGroupsForUser', 'limit' => ($limit + 20)));
		}
	} else {?>
		<div class="tiny topBorder">none</div>
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
		if ($groupsCount > dbNumRows($groups)) {
			renderTemplate('common.loadMore', array('action' => 'getOtherGroupsForUser', 'limit' => ($limit + 20)));
		}
	} else {?>
		<div class="tiny topBorder">none</div>
	<?}
}

function renderGroupItem($group) {
	renderTemplate('group.item', get_defined_vars());
}

function renderGroupDetails($currentGroupId) {
	import('security.service');
	global $serverUrl, $serverRoot, $uiRoot;
	$groups = getGroupWithId($currentGroupId, $_SESSION['innoworks.ID']);
	$groupUserEntry = getGroupUserEntryWithId($currentGroupId, $_SESSION['innoworks.ID']);
	$shareUrl = $serverUrl . $serverRoot . "ui/innoworks/viewer.php?group=" . $currentGroupId;

	if ($groups && dbNumRows($groups) > 0)
		$group = dbFetchObject($groups);
	if ($groupUserEntry && dbNumRows($groupUserEntry) > 0)
		$groupUser = dbFetchObject($groupUserEntry);

	if (!isset($group) && !isset($groupUser))
		return renderTemplate("group.noSelect");
	
	$userService = new AutoObject("user.service");
	$groupUsers = getUsersForGroup($currentGroupId);	
	renderTemplate('group.details', get_defined_vars());

	if (isset($groupUser) && $groupUser->approved == 0 && $groupUser->accepted == 1) {
		renderTemplate('group.desc', get_defined_vars());
		echo "<p>You have asked for access to this group, but have not been approved. You can contact the lead " . $userService->getUserInfo($group->userId)->username . ".</p>";
	} else if (isset($groupUser) && $groupUser->approved == 1 && $groupUser->accepted == 0) {
		renderTemplate('group.desc', get_defined_vars());
		echo "<p>Do you wish to accept your invitation to this group?</p>";
		echo "<a href='javascript:logAction()' onclick='acceptGroup();'>Yes, I want to join</a> <a href='javascript:logAction()' onclick='refuseGroup();'>No, I don't wannt to join</a>";
	} else if ((isset($groupUser) && $groupUser->approved == 1 && $groupUser->accepted == 1) || isset($group) && $group->userId == $_SESSION['innoworks.ID']) {
		if ($groups && (dbNumRows($groups) == 1)) {
			$userService = new AutoObject("user.service");
			renderTemplate('group.tabControls');
		} 
	} else {
		renderTemplate('group.desc', get_defined_vars());
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
		return renderTemplate('group.noSelect', get_defined_vars());
	
	$groupUsers = getUsersForGroup($currentGroupId);
	renderTemplate('group.detailsTab', get_defined_vars());	
}

function renderGroupSummary($currentGroupId) {
	global $serverUrl, $uiRoot;
	$groups = getGroupWithId($currentGroupId, $_SESSION['innoworks.ID']);
	$group;
	
	if ($groups && dbNumRows($groups) > 0)
		$group = dbFetchObject($groups);
	else
		die("No group exists");

	import('user.service');
	$shareUrl = $serverUrl . $uiRoot . "innoworks/viewer.php?group=" . $currentGroupId;
	$groupIdeas = getIdeasForGroup($currentGroupId, $_SESSION['innoworks.ID'], "LIMIT 500");
	$groupUsers = getUsersForGroup($currentGroupId, "LIMIT 100");
	$hasGroupAccess = hasAccessToGroup($group->groupId, $_SESSION['innoworks.ID']);
	renderTemplate('groupSummary', get_defined_vars());
}

/* POPUP BOX OPTIONS FOR GROUPS */
function renderAddUser($actionId, $user, $criteria) {
	global $uiRoot;
	$limit=20;
	renderTemplate('group.addUser', get_defined_vars());
	renderAddUserItems($criteria, $limit);
}

function renderAddUserItems($criteria, $limit) {
	global $uiRoot;
	import("search.service");
	$users = getSearchPeople($criteria, $_SESSION['innoworks.ID'], "", "LIMIT $limit");
	$countUsers = countGetSearchPeople($criteria, $_SESSION['innoworks.ID'], "");
	renderTemplate('group.addUserItems', get_defined_vars());
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
			<td><input type="text"  name="criteria" value="<?= htmlspecialchars($criteria); ?>" placeholder=" . . . " style="border: none" /></td>
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
		if ($countIdeas > dbNumRows($ideas)) {
			renderTemplate('common.loadMore', array('action' => 'getAddIdeaItems', 'limit' => $limit+20));
		}
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
		if ($countIdeas > dbNumRows($ideas)) {
			renderTemplate('common.loadMore', array('action' => 'getPublicAddIdeaItems', 'limit' => $limit+20));
		}
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
		$shareUrl = $serverUrl . $serverRoot . "ui/innoworks/viewer.php?idea=" . $ideaId;
		renderTemplate('group.ideaShare', get_defined_vars());
	} else {
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
		$shared = true;
	renderTemplate('group.idea', get_defined_vars());
}

function renderIdeaGroupsListForUser($uid) {
	$limit = 5;
	renderTemplate('group.ideaGroupList', get_defined_vars());
}

function renderIdeaGroupItemsForUser($uid, $limit = 20) {
	global $serverRoot;
	import("group.service");
	import("user.service");
	$groups = getAllGroupsForUser($uid, "LIMIT $limit");
	$countGroups = countGetAllGroupsForUser($uid);
	if ($groups && dbNumRows($groups) > 0 ) {
		while ($group = dbFetchObject($groups)) {
			renderTemplate('idea.groupItem', get_defined_vars());
		} if ($countGroups > dbNumRows($groups)) {
			renderTemplate('common.loadMore', array('action' => 'getIdeaGroupItemsForUser', 'limit' => $limit+20));
		}
	}
}

function renderGroupPreview($gid, $uid) {
	import("group.service");
	if (!isset($gid)) 
		die();
	$group = getGroupDetails($gid);
	renderTemplate('group.preview', get_defined_vars());
}

function renderPublicPreview($uid) {
	global $uiRoot;
	renderTemplate('group.publicPreview', get_defined_vars());
}

function renderPrivatePreview($uid) {
	global $uiRoot;
	renderTemplate('group.privatePreview', get_defined_vars());
}

function renderPublic() {
	global $serverRoot, $serverUrl, $uiRoot;
	$limit = 4;
	$announces = getAnnouncements("LIMIT ".$limit);
	renderTemplate('public.default', get_defined_vars());
}?>