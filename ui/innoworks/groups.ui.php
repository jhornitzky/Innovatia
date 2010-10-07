<? 
require_once("thinConnector.php"); 
import("group.service");
 
function renderDefault() {
	
	echo "<div>";
	echo "<h3>My Groups</h3>";
	$groups = getGroupsForCreatorUser($_SESSION['innoworks.ID']);
	if ($groups && dbNumRows($groups) > 0 ) {
		while ($group = dbFetchObject($groups)) {
			renderGroup($groups,$group);
		}
	} else {
		echo "<p>No groups</p>";
	}
	echo "</div>";
	
	echo "<div>";
	echo "<h3>Groups I'm part of</h3>";
	$groups = getPartOfGroupsForUser($_SESSION['innoworks.ID']);
	if ($groups && dbNumRows($groups) > 0 ) {
		while ($group = dbFetchObject($groups)) {
			renderPartOfGroups($groups,$group);
		}
	} else {
		echo "<p>No groups that Im just part of</p>";
	}
	echo "</div>";
	
	echo "<div>";
	echo "<h3>Other Groups</h3>";
	$groups = getOtherGroupsForUser($_SESSION['innoworks.ID']);
	if ($groups && dbNumRows($groups) > 0 ) {
		while ($group = dbFetchObject($groups)) {
			renderOtherGroup($groups,$group);
		}
	} else {
		echo "<p>No other groups</p>";
	}
	echo "</div>";
}  

function renderGroup($groups, $group) {
	echo "<a href='javascript:updateForGroup(\"".$group->groupId."\",\"".$group->title."\")'>". $group->title . "</a>";
	echo "<input type='button' onclick='deleteGroup(" . $group->groupId .")' value=' - ' />";
}

function renderPartOfGroups($groups, $group) {
	echo "<a href='javascript:updateForGroup(\"".$group->groupId."\",\"".$group->title."\")'>". $group->title . "</a>";
}

function renderOtherGroup($groups, $group) {
	echo $group->title;
}

function renderDetails($currentGroupId) {
	$groups = getGroupWithId($currentGroupId, $_SESSION['innoworks.ID']); 
	
	if ($groups && (dbNumRows($groups) == 1)) {
		$group = dbFetchObject($groups); 
		
		echo "<h2>".$group->title."</h2>";
		echo "<h3>Users</h3>";
		if ($group->userId == $_SESSION['innoworks.ID']) echo "<input type='button' value=' + ' onclick='showAddGroupUser()'/>";
		
		$groupUsers = getUsersForGroup($currentGroupId);
		if ($groupUsers && dbNumRows($groupUsers) > 0) {
			echo "<ul>";
			while ($user = dbFetchObject($groupUsers)) {
				echo "<li>" . $user->username;
				if ($group->userId == $_SESSION['innoworks.ID']) echo "<input type='button' value =' - ' onclick='delUserFromCurGroup($user->userId)'/>";
				echo "</li>";
			}
			echo "</ul>";
		} else {
			echo "<p>None</p>";
		}
		
		echo "<h3>Ideas</h3>";
		echo "<input type='button' value=' + ' onclick='showAddGroupIdea()'/>";
		$groupIdeas = getIdeasForGroup($currentGroupId);
		if ($groupIdeas && dbNumRows($groupIdeas) > 0) {
			echo "<ul>";
			while ($idea = dbFetchObject($groupIdeas)) {
				echo "<li>" . $idea->title;
				if ($idea->userId == $_SESSION['innoworks.ID']) echo "<input type='button' value =' - ' onclick='delIdeaFromCurGroup($idea->ideaId)'/>";
				echo "</li>";
			}
			echo "</ul>";
		} else {
			echo "<p>None</p>";
		}
	} else {
		echo "Error. Group Not Found.";
	}
}

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
			echo  "<li><a href='javascript:addIdeaToCurGroup(\"$idea->ideaId\")'>".$idea->title. "</a></li>";
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
	if ($item && dbNumRows($item) > 0) {
		renderGenericInfoForm(array(), dbFetchObject($item), array("riskEvaluationId","groupId","userId","ideaId"));
		echo "Go to <a href='javascript:showGroups(); dijit.byId(\"ideasPopup\").hide()'>Groups</a> to edit data";
	} else {
		echo "<p>No share data for this idea</p>"; 
		echo "Go to <a href='javascript:showGroups(); dijit.byId(\"ideasPopup\").hide()'>Groups</a> to share with group";
	}
}
?>
