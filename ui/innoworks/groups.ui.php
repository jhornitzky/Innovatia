<?
require_once("thinConnector.php");
import("group.service");

function renderDefault() {
	$groups = getGroupsForCreatorUser($_SESSION['innoworks.ID']);
	if ($groups && dbNumRows($groups) > 0 ) {
		while ($group = dbFetchObject($groups)) {
			renderGroup($groups,$group);
		}
	} 
	$groups = getPartOfGroupsForUser($_SESSION['innoworks.ID']);
	if ($groups && dbNumRows($groups) > 0 ) {
		while ($group = dbFetchObject($groups)) {
			renderPartOfGroups($groups,$group);
		}
	} 
	$groups = getOtherGroupsForUser($_SESSION['innoworks.ID']);
	if ($groups && dbNumRows($groups) > 0 ) {
		while ($group = dbFetchObject($groups)) {
			renderOtherGroup($groups,$group);
		}
	}
}

function renderGroup($groups, $group) {
	echo "<div class='itemHolder clickable' onclick='updateForGroup(\"".$group->groupId."\",\"".$group->title."\")'>";
	echo '<img src="retrieveImage.php?action=groupImg&actionId='.$group->groupId.'" style="width:3em; height:3em"/><br/>';
	echo $group->title . "<br/>";
	echo "<input type='button' onclick='deleteGroup(" . $group->groupId .")' value=' - ' alt='Delete group' />";
	echo "</div>";
}

function renderPartOfGroups($groups, $group) {
	echo "<div class='itemHolder clickable' onclick='updateForGroup(\"".$group->groupId."\",\"".$group->title."\")'>". $group->title . "<br/>";
	echo '<img src="retrieveImage.php?action=groupImg&actionId='.$group->groupId.'" style="width:3em; height:3em"/><br/>';
	echo "<input type='button' onclick='currentGroupId=$group->groupId; delUserFromCurGroup(" . $_SESSION['innoworks.ID'] .")' value=' Leave ' alt='Leave group' />";
	echo "</div>";
}

function renderOtherGroup($groups, $group) {
	echo "<div class='itemHolder clickable' onclick='updateForGroup(\"".$group->groupId."\",\"".$group->title."\")'>";
	echo '<img src="retrieveImage.php?action=groupImg&actionId='.$group->groupId.'" style="width:3em; height:3em"/><br/>';
	echo $group->title . "</div>";
}

function renderDetails($currentGroupId) {
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
	?><h3 style="margin-bottom:0.5em;"><?= $group->title?></h3><?
	if ($groupUser->approved == 0 && $groupUser->accepted == 1) {
		echo "You have asked for access to this group, but have not been approved. You can contact the lead " . $userService->getUserInfo($group->userId)->username . ".";
	} else if ($groupUser->approved == 1 && $groupUser->accepted == 0) {
		echo "You have not accepted your invitation. Click <a href='javascript:logAction()' onclick='acceptGroup();'>here</a> to accept or <a href='javascript:logAction()' onclick='refuseGroup();'>here</a> to turn down the invitation.";
	} else if (($groupUser->approved == 1 && $groupUser->accepted == 1) || $group->userId == $_SESSION['innoworks.ID']) {
		if ($groups && (dbNumRows($groups) == 1)) {
			$userService = new AutoObject("user.service");?>
			<div style="margin-bottom:1.0em"><span class="title"><?= $userService->getUserInfo($group->userId)->username?></span> <span class="timestamp"><?= $group->timestamp ?></span> | <a href="javascript:logAction()" onclick="printGroup()">Print</a></div>
			<?
			echo '<div class="two-column" style="border-top:1px solid #EEE">';
			echo "<h3>Ideas<input type='button' value=' + ' onclick='showAddGroupIdea(this)' alt='Add an idea to the group'/></h3>";
			$groupIdeas = getIdeasForGroup($currentGroupId);
			if ($groupIdeas && dbNumRows($groupIdeas) > 0) {
				echo "<ul>";
				while ($idea = dbFetchObject($groupIdeas)) {
					echo "<li><a href=\"javascript:showIdeaDetails('$idea->ideaId')\" >" . $idea->title . "</a>";
					if ($idea->userId == $_SESSION['innoworks.ID']) echo "<input type='button' value =' - ' onclick='delIdeaFromCurGroup($idea->ideaId)' alt='Remove this idea from the group'/>";
					echo "</li>";
				}
				echo "</ul>";
			} else {
				echo "<p>None</p>";
			}
			echo "</div>";
			
			echo "<div class='two-column' style='margin-left:2%; border-top:1px solid #EEE'>";
			echo "<h3>Users";
			if ($group->userId == $_SESSION['innoworks.ID'])
				echo "<input type='button' value=' + ' onclick='showAddGroupUser(this)' alt='Add user to group'/>";
			echo "</h3>";
			$groupUsers = getUsersForGroup($currentGroupId);
			if ($groupUsers && dbNumRows($groupUsers) > 0) {
				echo "<ul>";
				while ($user = dbFetchObject($groupUsers)) {
					echo "<li><a href='javascript:showProfileSummary(\"$user->userId\")'>$user->username</a>";
					if ($group->userId == $_SESSION['innoworks.ID']) echo "<input type='button' value =' - ' onclick='delUserFromCurGroup($user->userId)' alt='Remove user from group'/>";
					if ($user->approved == 0 && $group->userId == $_SESSION['innoworks.ID']) echo "<input type='button' value ='Approve' onclick='approveGroupUser($user->userId)' alt='Approve user for group'/>";
					echo "</li>";
				}
				echo "</ul>";
			} else {
				echo "<p>None</p>";
			}
			
			echo "<h3>Attachments</h3>";
			echo "<iframe style='width:100%;height:8em;' src='attachment.php?groupId=$group->groupId'></iframe>";
			echo "</div>";
		} 
	} else {
		echo "<p>You have no access to this group. You can request access be clicking <a href='javascript:logAction()' onclick='requestGroup()'>here</a> Please contact the lead ".$userService->getUserInfo($group->userId)->username ." for all other queries.</p>";
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

function renderIdeaRiskEval($ideaId, $userId) {
	import("compare.service");
	$item = getRiskItemForIdea($ideaId,$userId);
	if ($item && dbNumRows($item) > 0) {
		renderGenericInfoForm(array(), dbFetchObject($item), array("riskEvaluationId","groupId","userId","ideaId"));
		echo "Go to <a href='javascript:showCompare(); dijit.byId(\"ideasPopup\").hide()'>Compare</a> to edit data";
	} else {
		echo "<p>No compare data for idea</p>";
		echo "Go to <a href='javascript:showCompare(); dijit.byId(\"ideasPopup\").hide()'>Compare</a> to add comparison data";
	}
}

function renderIdeaShare($ideaId, $userId) {
	import("group.service");
	$item = getIdeaShareDetails($ideaId);
	$groups = getGroupsForCreatorUser($_SESSION['innoworks.ID']);
	$othergroups = getPartOfGroupsForUser($_SESSION['innoworks.ID']);

	if ($item && dbNumRows($item) > 0) {
		renderGenericInfoForm(array(), dbFetchObject($item), array("riskEvaluationId","groupId","userId","ideaId"));
		echo "<a href='javascript:logAction()' onclick=''>Remove from this group</a>"; //FIXME
		echo 'Go to <a href="javascript:logAction()" onclick="showGroups(); dijit.byId(\'ideasPopup\').hide()">Groups</a> to edit data';
	} else {?>
	<p>No share data for this idea</p>
	<p>Share idea with a group:</p>
<ul>
<?
if ($groups && dbNumRows($groups) > 0 ) {
	while ($group = dbFetchObject($groups)) {
		renderIdeaGroupItem($ideaId, $group);
	}
}
if ($othergroups && dbNumRows($othergroups) > 0 ) {
	while ($group = dbFetchObject($othergroups)) {
		renderIdeaGroupItem($ideaId, $group);
	}
}
?>
</ul>
Show <a href='javascript:showGroups(); dijit.byId(\"ideasPopup\").hide()'>Groups</a>
<?}
}

function renderIdeaGroupItem($ideaId, $group) { ?>
<li><a href="javascript:logAction()"
	onclick="addIdeaToGroup('<?= $ideaId ?>','<?= $group->groupId ?>');loadPopupShow()"><?= $group->title ?></a></li>
<?}?>