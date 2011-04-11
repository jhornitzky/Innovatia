<? 
require_once(dirname(__FILE__) . "/../pureConnector.php"); 
import("user.service");
 
function renderProfileDefault($user) {
	global $serverUrl, $serverRoot;
	$limit = 5;
	$userDetails = getUserInfo($user);
	$shareUrl = $serverUrl . $serverRoot . "ui/innoworks/viewer.php?user=" . $_SESSION['innoworks.ID'];
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
			renderOtherProfile($profile);
		}
		if ($profileCount > dbNumRows($profiles)) {?>
			<!-- <a href="javascript:logAction()" onclick="loadResults(this, {action:'getOtherProfiles', limit:'<?= ($limit + 20) ?>'})">Load more</a>-->
		<?}
	} else {?>
		<p>No similar profiles</p>
	<?}
}

function renderOtherProfile($profile) {
	import('user.service');
	renderTemplate('profile.other', get_defined_vars());
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