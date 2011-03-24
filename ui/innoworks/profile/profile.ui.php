<? 
require_once(dirname(__FILE__) . "/../pureConnector.php"); 
import("user.service");
 
function renderProfileDefault($user) {
	global $serverRoot, $serverUrl;
	$limit = 5;
	
	$userDetails = getUserInfo($user);
	$shareUrl = $serverUrl . $serverRoot . "ui/innoworks/viewer.php?user=" . $_SESSION['innoworks.ID'];?>
	<div style="width:100%;">
		<div class="fixed-left">
			<div class="treeMenu">
				<div class='itemHolder headBorder' style='background-color:#DDD'>Similar profiles</div>
				<div>
					<? renderOtherProfiles($user, $limit) ?>
				</div>
			</div>
		</div>
		<div class="fixed-right">
			<div class='itemHolder headBorder treeMenu' style="height:7em;">
				<div class="lefter lefterImage">
					<img src="retrieveImage.php?action=userImg&actionId=<?= $_SESSION['innoworks.ID'] ?>" style="width:5em;height:5em;"/>
				</div>
				<div class="lefter">
					<h3><?= $userDetails->firstName . ' ' . $userDetails->lastName . ' / ' . $userDetails->username ?></h3>
					<?= $userDetails->organization ?>
				</div>
				<div class="righter" style="padding-left:0.5em;">
					<span class="timestamp">Joined <?= $userDetails->createdTime ?></span> | <a href="javascript:logAction()" onclick="printUser('&profile=<?= $userDetails->userId ?>')"> Print </a></p>
					<p style="font-size:0.8em;margin:0; padding:0;">Share your profile with a friend at:<br/> <?= $shareUrl ?></p>
					<? renderTemplate('shareBtns', null); ?>
				</div>
			</div>
			
			<ul class="submenu">
				<li class="greybox"><a href="javascript:logAction()" onclick="showProfileNotes(this)">Notes</a></li>
				<li class="greybox"><a href="javascript:logAction()" onclick="showProfileSubDetails(this)">Details</a></li>
				<li class="bluebox"><a href="javascript:logAction()" onclick="showProfileIdeate(this)">Ideate</a></li>
				<li class="bluebox"><a href="javascript:logAction()" onclick="showProfileCompare(this)">Compare</a></li>
				<li class="bluebox"><a href="javascript:logAction()" onclick="showProfileSelect(this)">Select</a></li>
			</ul>
			
			<div class="profileInfo" style="margin-top:2em"></div>
		</div>
	</div>
<?} 

function renderProfileDetails($user) {
		$userDetails = getUserInfo($user);?>
				<div style="width:54%; position:relative; float:left; margin-right:2%; border-top:1px solid #DDD;">
				<p><b>Details</b></p>
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
					<p><b>Attachments</b></p>
					<iframe style="width:100%; height:20em; border:none; background:#EEE;" src="attachment.php"></iframe>
				</div>
<?}

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
	} else {?>
		<p>No similar profiles</p>
	<?}
}

function renderOtherProfile($profile) {
	import('user.service');?>
 	<div class="itemHolder clickable" onclick="showProfileSummary('<?=$profile->userId?>')" style="height:2.5em">
 		<div class="righter righterImage">
 			<img src="retrieveImage.php?action=userImg&actionId=<?= $profile->userId ?>" style="width:2em;height:2em"/>
 		</div>
 		<div class="righter">
 			<span><?= getDisplayUsername($profile->userId); ?> </span><br/>
 			<span><?= $profile->organization ?></span>
 		</div>
 	</div>	
<?}

function renderSummaryProfile($userId) {
	global $serverUrl, $uiRoot; 
	import("idea.service");
	import("user.service");
	$userDetails = getUserInfo($userId);
	$ideas = getProfileIdeas($userId, "LIMIT 500");
	$countIdeas = countGetProfileIdeas($userId);
	$groups = getUserGroups($userId, "LIMIT 100");
	$countGroups = countGetUserGroups($userId);?>
	<table>
	<tr>
	<td><img src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=userImg&actionId=<?= $userDetails->userId?>" style="width:3em; height:3em;"/></td>
	<td>
		<h3><?= $userDetails->firstName?> <?= $userDetails->lastName?> / <?= $userDetails->username?></h3>
		<?= $userDetails->organization?> | <? if ($userDetails->isAdmin) { ?><i>admin</i> | <?}?>
		<span class="summaryActions"><a href="javascript:logAction()" onclick="printUser('&profile=<?= $userDetails->userId?>');">Print</a></span>
	</td>
	</tr>
	</table>
	<? if ($userDetails->isPublic == 1 || $_SESSION['innoworks.isAdmin']) { 
		renderGenericInfoForm(null ,$userDetails, array("ideaId", "title","userId", "createdTime", "username", "password", 'firstName', 'lastName', 'isAdmin', 'isExternal', 'sendEmail', 'lastUpdateTime', 'organization', 'isPublic'));
	}?>
	
	<p><b>Idea(s)</b>
	<?if ($ideas && dbNumRows($ideas) > 0 ) {
		while ($idea = dbFetchObject($ideas)) {
			if (hasAccessToIdea($idea->ideaId, $_SESSION['innoworks.ID'])) {?>
			<div class="itemHolder">
				<img src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=ideaImg&actionId=<?= $idea->ideaId?>" style="width:1em; height:1em;"/>
				<a href="javascript:logAction()" onclick="showIdeaSummary('<?= $idea->ideaId?>');"><?=$idea->title?></a>
			</div>
			<?} 
			if (dbNumRows($ideas) == 500) {?>
				<p>Only displaying 500 latest ideas</p>
			<?}
		}
	} else {?>
		<p>None</p>
	<?}?>
	
	<p><b>Group(s)</b></p>
	<?if ($groups && dbNumRows($groups) > 0 ) {
		while ($group = dbFetchObject($groups)) {?>
			<div class="itemHolder">
				<img src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=groupImg&actionId=<?= $group->groupId?>" style="width:1em; height:1em;"/>
				<a href="javascript:logAction()" onclick="showGroupSummary(<?= $group->groupId?>);"><?=$group->title?></a>
			</div>
		<?}
		if (dbNumRows($groups) == 100) {?>
			<p>Only displaying 100 latest groups</p>
		<?}
	} else {?>
		<p>None</p>
	<?}
}?>