<? 
require_once(dirname(__FILE__) . "/../pureConnector.php"); 
import("user.service");
 
function renderProfileDefault($user) {
	global $serverUrl, $serverRoot;
	$limit = 8;
	$userDetails = getUserInfo($user);
	$shareUrl = $serverUrl . $serverRoot . "ui/innoworks/viewer.php?user=" . $_SESSION['innoworks.ID'];
	$noOfIdeas = countQuery("SELECT COUNT(*) FROM Ideas WHERE userId='".$_SESSION['innoworks.ID']."'");
	$noOfSelectedIdeas = countQuery("SELECT COUNT(*) FROM Selections, Ideas WHERE Ideas.userId='".$_SESSION['innoworks.ID']."' and Ideas.ideaId = Selections.ideaId");
	$noOfGroupsCreated = countQuery("SELECT COUNT(*) FROM Groups WHERE userId='".$_SESSION['innoworks.ID']."'");
	$noOfGroupsIn = countQuery("SELECT COUNT(*) FROM GroupUsers WHERE userId='".$_SESSION['innoworks.ID']."'");
	
	if($noOfIdeas > 0) 
		$percentOfIdeas = round($noOfSelectedIdeas/$noOfIdeas, 2) * 100; 
	else 
		$percentOfIdeas = 0;
	
	renderTemplate('profile.default', get_defined_vars());
} 

function renderProfileDetails($user) {
	$userDetails = getUserInfo($user);
	renderTemplate('profile.details', get_defined_vars());
}

function renderOtherProfiles($user, $limit) {
	$profiles = getSimilarUserProfiles($user, "LIMIT $limit");
	$profileCountTemp = dbFetchArray(countGetSimilarUserProfiles($user));
	$profileCount = $profileCountTemp[0];
	if ($profiles && dbNumRows($profiles) > 0) { 
		while ($profile = dbFetchObject($profiles)) {
			import('user.service');
			renderTemplate('profile.other', get_defined_vars());
		}
		if ($profileCount > dbNumRows($profiles)) {
			//FIXME add load more here
		}
	} else {?>
		<div class="tiny">No similar profiles</div>
	<?}
}

function renderSummaryProfile($userId) {
	global $serverUrl, $uiRoot; 
	import("idea.service");
	import("user.service");
	$userDetails = getUserInfo($userId);
	$ideas = getProfileIdeas($userId, "LIMIT 500");
	$countIdeas = countGetProfileIdeas($userId);
	$groups = getUserGroups($userId, "LIMIT 100");
	$countGroups = countGetUserGroups($userId);
	renderTemplate('profileSummary', get_defined_vars());
}

function renderProfileBook() {
	import('user.service');
	import('search.service');
	$profiles = getSearchPeople('', $_SESSION['innoworks.ID'], '');
	while($profile = dbFetchObject($profiles)) {
		renderTemplate('search.profileItem', array('user' => $profile));
	}
}
?>