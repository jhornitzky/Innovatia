<? 
require_once("thinConnector.php"); 
import("user.service");
 
function renderDefault() {
	global $serverRoot;
	$userDetails = getUserInfo($_SESSION['innoworks.ID']);
	$profiles = getSimilarUserProfiles($_SESSION['innoworks.ID']);
	?>
	<div style="width:100%;">
		<div class="fixed-left">
			<h2 id="pgName">Profiles</h2>
			<div class="bordRight" style="padding-right:5px">
			<p>Other profiles like yours</p>
			<?if ($profiles && dbNumRows($profiles) > 0) { 
				while ($profile = dbFetchObject($profiles)) {
					renderOtherProfile($profile);
				}
			} else {
				echo "<p>No similar profiles</p>";
			}?>
			</div>
		</div>
		<div class="fixed-right">
			<div style="width:58%; position:relative; float:left;">
			<img src="retrieveImage.php?action=userImg&actionId=<?= $_SESSION['innoworks.ID'] ?>" style="width:125px;height:125px;"/>
			<h3 style="margin-bottom:0;">Your Profile</h3>
			<form id="profileDetailsForm" onsubmit="updateProfile('profileDetailsForm'); return false;">
				<? renderGenericUpdateForm(null ,$userDetails, array("ideaId", "title","userId", "createdTime", "username", "password", "isAdmin", "lastUpdateTime")); ?>
				<p>Admin: <?= $userDetails->isAdmin ?></p>
				<input type="hidden" name="action" value="updateProfile" /> 
			</form>
			</div>
			<!-- 
			<div style="width:37%; position:relative; float:left;">
			<div class="blue">&nbsp;</div>
			<div style="padding: 1%; ">
			</div>
			</div>
			 -->
		</div>
	</div>
<?} 

function renderOtherProfile($profile) {?>
 	<div class="itemHolder clickable" onclick="showProfileSummary('<?=$profile->userId?>')">
 		<img src="retrieveImage.php?action=userImg&actionId=<?= $profile->userId ?>" style="width:2em;height:2em"/><br/>
 		<span><?=$profile->username?></span><br/>
 		<span><?=$profile->firstname . " " . $profile->lastname?></span><br/>
 		<span><?=$profile->organization?></span>
 	</div>	
<?}

function renderSummaryProfile($userId) {
	global $serverUrl, $uiRoot;
	import("idea.service");
	import("user.service");
	
	$userDetails = getUserInfo($userId);?>
	<table>
	<tr>
	<td><img src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=userImg&actionId=<?= $userDetails->userId?>" style="width:2em; height:2em;"/></td>
	<td><h3><?= $userDetails->username?></h3></td>
	</tr>
	</table>
	<?renderGenericInfoForm(null ,$userDetails, array("ideaId", "title","userId", "createdTime", "username", "password"));
	$ideas = getProfileIdeas($userId);
	echo "<h3>Ideas</h3>";
	if ($ideas && dbNumRows($ideas) > 0 ) {
		while ($idea = dbFetchObject($ideas)) {?>
			<p><a href="javascript:showIdeaDetails('<?= $idea->ideaId?>');"><span class="ideatitle"><?=$idea->title?></span></a></p>
		<?}
	} else {
		echo "<p>No ideas yet</p>";
	}
	
	$groups = getUserGroups($userId);
	echo "<h3>Groups</h3>";
	if ($groups && dbNumRows($groups) > 0 ) {
		while ($group = dbFetchObject($groups)) {?>
			<p><a href="javascript:logAction()" onclick="currentGroupId=<?= $group->groupId?>; showGroups()"><span class="ideatitle"><?=$group->title?></span></a></p>
		<?}
	} else {
		echo "<p>No groups yet</p>";
	}
}?>