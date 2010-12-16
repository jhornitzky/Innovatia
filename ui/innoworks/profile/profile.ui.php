<? 
require_once(dirname(__FILE__) . "/../pureConnector.php"); 
import("user.service");

function renderOtherProfiles($user, $limit) {
	$profiles = getSimilarUserProfiles($user, "LIMIT $limit");
	$profileCount = countGetSimilarUserProfiles($user);
	if ($profiles && dbNumRows($profiles) > 0) { 
		while ($profile = dbFetchObject($profiles)) {
			renderOtherProfile($profile);
		}
		if ($profileCount > dbNumRows($profiles)) {?>
			<a href="javascript:logAction()" onclick="loadResults(this, {action:'getOtherProfiles', limit:'<?= ($limit + 20) ?>'})">Load more</a>
		<?}
	} else {
		echo "<p>No similar profiles</p>";
	}
}
 
function renderProfileDefault($user) {
	global $serverRoot, $serverUrl;
	$limit = 20;
	
	$userDetails = getUserInfo($user);
	$shareUrl = $serverUrl . $serverRoot . "ui/innoworks/viewer.php?user=" . $_SESSION['innoworks.ID'];
	?>
	<div style="width:100%;">
		<div class="fixed-left">
			<h2 id="pgName">Profiles</h2>
			<p>Other similar profiles</p>
			<div class="bordRight" style="padding-right:5px">
				<? renderOtherProfiles($user, $limit) ?>
			</div>
		</div>
		<div class="fixed-right">
			<div style="border-top:1px solid #DDD; width:100%">
			<table>
			<tr>
				<td>
					<img src="retrieveImage.php?action=userImg&actionId=<?= $_SESSION['innoworks.ID'] ?>" style="width:8em;height:8em;"/>
				</td>
				<td style="padding-left:0.5em;">
					<h3><?= $userDetails->username ?></h3>
					<p style="font-size:0.9em;padding:0;">Joined <?= $userDetails->createdTime ?> | <a href="javascript:logAction()" onclick="printUser('&profile=<?= $userDetails->userId ?>')"> Print </a></p>
					<p style="font-size:0.8em;margin:0; padding:0;">Share your profile with a friend at:<br/> <?= $shareUrl ?></p>
					<div class="shareBtns" style="margin:0; padding:0;">	
						<img src="<?= $serverRoot?>ui/style/emailbuttonmini.jpg" onclick="openMail('yourFriend@theirAddress.com', 'Check out my idea on innoworks', 'I thought you might like my idea. You can see it at <?= $shareUrl ?>')" />
						<img src="<?= $serverRoot?>ui/style/fb btn.png" onclick="openFace()" />
						<img class="shareLeft" src="<?= $serverRoot?>ui/style/delicious btn.png" onclick="openDeli()" />
						<img class="shareLeft" src="<?= $serverRoot?>ui/style/twit btn.png" onclick="openTweet()"/>
						<img class="shareLeft" src="<?= $serverRoot?>ui/style/blogger btn.png" onclick="openBlog()"/>
					</div>
				</td>
			</tr>
			</table>
			</div>
			
			<div style="width:54%; position:relative; float:left; margin-right:2%; border-top:1px solid #DDD;">
			<h3>Details</h3>
			<form id="profileDetailsForm" onsubmit="updateProfile('profileDetailsForm'); return false;" style="border:1px solid #DDD;" >
				<table style="width:100%">
					<tr>
						<td>Public<br/>
							<span style="font-size:0.85em">Share your profile with everyone</span>
						</td>
						<td><input type="checkbox" onclick="togglePublicProfile(this)" <? if ($userDetails->isPublic == 1) echo "checked"; ?>/></td>
					</tr>
					<tr>
						<td>Send Emails<br/>
							<span style="font-size:0.85em">Allow innoworks to send you updates and notes via email</span>
						</td>
						<td><input type="checkbox" onclick="toggleSendEmail(this)" <? if ($userDetails->sendEmail == 1) echo "checked"; ?>/></td>
					</tr>
					<tr>
						<td colspan="2" style="background-color:#EEE;"> 
						Flags <br/>
							<span style="font-size:0.85em"><? if($userDetails->isAdmin == 1) echo "admin"; ?> <? if($userDetails->isExternal == 1) echo "external"; ?></span>
						</td>
					</tr>
				</table>
				<? renderGenericUpdateForm(null ,$userDetails, array("ideaId", "title","userId", "createdTime", "username", "password", "isAdmin", "lastUpdateTime", "isExternal", "isPublic", "sendEmail")); ?>
				<input type="hidden" name="action" value="updateProfile" /> 
			</form>
			</div>
			<div style="width:40%; position:relative; float:left; border-top:1px solid #DDD;">
				<h3>Attachments</h3>
				<iframe style="width:100%; height:20em; border:none; background:#EEE;" src="attachment.php"></iframe>
			</div>
		</div>
	</div>
<?} 

function renderOtherProfile($profile) {?>
 	<div class="itemHolder clickable" onclick="showProfileSummary('<?=$profile->userId?>')">
 		<img src="retrieveImage.php?action=userImg&actionId=<?= $profile->userId ?>" style="width:2em;height:2em"/><br/>
 		<span><?=$profile->username?> <?=$profile->organization?></span><br/>
 		<span><?=$profile->firstname . " " . $profile->lastname?></span>
 	</div>	
<?}

function renderSummaryProfile($userId) {
	global $serverUrl, $uiRoot;
	import("idea.service");
	import("user.service");
	
	$userDetails = getUserInfo($userId);
	$ideas = getProfileIdeas($userId);
	$groups = getUserGroups($userId);
	?>
	<table>
	<tr>
	<td><img src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=userImg&actionId=<?= $userDetails->userId?>" style="width:2em; height:2em;"/></td>
	<td><h3><?= $userDetails->username?></h3></td>
	</tr>
	</table>
	<span class="summaryActions"><a href="javascript:logAction()" onclick="printUser('&profile=<?= $userDetails->userId?>');">Print</a></span>
	<?renderGenericInfoForm(null ,$userDetails, array("ideaId", "title","userId", "createdTime", "username", "password"));
	echo "<h3>Ideas</h3>";
	if ($ideas && dbNumRows($ideas) > 0 ) {
		while ($idea = dbFetchObject($ideas)) {?>
			<p><img src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=ideaImg&actionId=<?= $idea->ideaId?>" style="width:1em; height:1em;"/><a href="javascript:logAction()" onclick="showIdeaSummary('<?= $idea->ideaId?>');"><span class="ideatitle"><?=$idea->title?></span></a></p>
		<?}
	} else {
		echo "<p>No ideas yet</p>";
	}
	
	echo "<h3>Groups</h3>";
	if ($groups && dbNumRows($groups) > 0 ) {
		while ($group = dbFetchObject($groups)) {?>
			<p><img src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=groupImg&actionId=<?= $group->groupId?>" style="width:1em; height:1em;"/><a href="javascript:logAction()" onclick="showGroupSummary(<?= $group->groupId?>);"><span class="ideatitle"><?=$group->title?></span></a></p>
		<?}
	} else {
		echo "<p>No groups yet</p>";
	}
}?>