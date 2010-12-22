<?
require_once(dirname(__FILE__) . "/../pureConnector.php");
import("dash.service");
import("note.service");
import("user.service");

function renderDefaultDash($userid) {
	global $serverRoot;
	$limit = 4;
	$notes = getAllIncomingNotes($_SESSION['innoworks.ID'], "LIMIT $limit");
	$noOfIdeas = countQuery("SELECT COUNT(*) FROM Ideas WHERE userId='".$_SESSION['innoworks.ID']."'");
	$noOfSelectedIdeas = countQuery("SELECT COUNT(*) FROM Selections, Ideas WHERE Ideas.userId='".$_SESSION['innoworks.ID']."' and Ideas.ideaId = Selections.ideaId");
	$noOfGroupsCreated = countQuery("SELECT COUNT(*) FROM Groups WHERE userId='".$_SESSION['innoworks.ID']."'");
	$noOfGroupsIn = countQuery("SELECT COUNT(*) FROM GroupUsers WHERE userId='".$_SESSION['innoworks.ID']."'");?>
<div style="width:100%; vertical-align:bottom;">
<div class="fixed-left">
	<div class="treeMenu">
	<div class="itemHolder headBorder" style="background-color:#EEE">Your statistics</div>
	<div class="itemHolder"><?= $noOfIdeas ?><br/><span>ideas</span></div>
	<div class="itemHolder"><?= $noOfSelectedIdeas ?><br/><span>selected ideas</span></div>
	<div class="itemHolder"><?= $noOfSelectedIdeas/$noOfIdeas ?><br/><span>selected idea ratio</span></div>
	<div class="itemHolder"><?= $noOfGroupsCreated?><br/><span>groups created</span></div>
	<div class="itemHolder"><?= $noOfGroupsIn?><br/><span>groups in</span></div>
	</div>
	<!-- <div class="itemHolder headBorder" style="background-color:#EEE">More options</div>
	<div class="itemHolder clickable" onclick="showGroups();">Groups<br/><span>Share your ideas</span></div>
	<div class="itemHolder clickable" onclick="showProfile();">Profiles<br/><span>Find expert innovators</span></div>
	<div class="itemHolder clickable" onclick="showNotes();">Notes<br/><span>Communicate in real-time</span></div>
	<div class="itemHolder clickable" onclick="showSearch();">Search<br/><span>Find ideas, people or groups</span></div>
	<div class="itemHolder clickable" onclick="showTimelines();">Timelines<br/><span>Review your schedule</span></div>
	<div class="itemHolder clickable" onclick="showReports();">Reports<br/><span>View statistics for innoworks</span></div> -->
	
</div>
<div class="fixed-right">
<div style="width:100%; border:1px solid #AAA; border-left:none; margin-bottom:1em">
<div class="itemHolder" style="background-color:#EEE">Latest notes</div>
<?
	if ($notes && dbNumRows($notes) > 0) {?>
	<? while ($note = dbFetchObject($notes)) { ?>
		<div class="itemHolder">
			<table>
			<tr>	
			<td><img src="retrieveImage.php?action=userImg&actionId=<?= $note->fromUserId ?>" style="width:2em;height:2em;"/>
				</td>
			<td>
				<?= $note->noteText ?><br/>
				<span><?= getDisplayUsername($note->fromUserId)?></span>
			</td>
			</tr>
			</table>
		</div>
	<?}?>
	<? markNotesAsRead($_SESSION['innoworks.ID']);
}
$limit = 8;
?>
</div>
<div id="dashui" class="threeColumnContainer">
	<div class="threecol col1 bluebox" style="border-left:none; width:32%; margin-right:1.5%">
		<div class="widget ui-corner-all">
			<div class="itemHolder" style="background-color:#EEE">
				Ideate<br/>
				<span>Record, manage and explore ideas to help them take shape</span>
			</div>
			<div class="dashResults">
			<? renderDashIdeas($userid, $limit)?>
			</div>
		</div>
	</div>
	<div class="threecol col2 greenbox" style="border-left:none; width:32%; margin-right:1.5%">
		<div class="widget ui-corner-all">
			<div class="itemHolder" style="background-color:#EEE">
				Compare<br/>
				<span>Contrast and compare your existing ideas</span>
			</div>
			<div class="dashResults">
			<? renderDashCompare($userid, $limit);?>
			</div>
		</div> 
	</div>
	<div class="threecol col3 orangebox" style="border-left:none; width:32%; margin-right:0">
		<div class="widget ui-corner-all">
			<div class="itemHolder" style="background-color:#EEE">
				Select<br/>
				<span>Choose ideas to work on and manage their tasks.</span>
			</div>
			<div class="dashResults">
			<? renderDashSelect($userid, $limit);?>
			</div>
		</div>
	</div>
	</div>
	</div>
</div>
<?}

function renderDashIdeas($user, $limit) {
	import("user.service");
	$ideas = getDashIdeas($user, "LIMIT $limit");
	$countIdeas = countDashIdeas($user);
	if ($ideas && dbNumRows($ideas) > 0 ) {
		while ($idea = dbFetchObject($ideas)) {?>
			<div onclick="showIdeaDetails('<?= $idea->ideaId?>');" class="itemHolder clickable" style="height:2.5em; overflow:hidden">
				<div class="lefter" style="padding:0.1em;">
					<img src="retrieveImage.php?action=ideaImg&actionId=<?= $idea->ideaId ?>" style="width:2.25em;height:2.25em;"/>
				</div>
				<div class="lefter">
					<?= $idea->title ?><br/>
					<img src="retrieveImage.php?action=userImg&actionId=<?= $idea->userId ?>" style="width:1em;height:1em;"/>
					<span><?= getDisplayUsername($idea->userId);  ?></span>
				</div>
			</div>
		<?}
		if ($countIdeas > dbNumRows($ideas)) {?>
			<a class="loadMore" href="javascript:logAction()" onclick="loadResults(this, {action:'getDashIdeas', limit:'<?= ($limit + 20) ?>'})">Load more</a>		
		<?}
	} else {?>
		<p>No ideas yet</p>
	<?} 
}

function renderDashCompare($user, $limit) {
	$items = getDashCompare($user, "LIMIT $limit");
	$countItems = countDashCompare($user);
	if ($items && dbNumRows($items) > 0 ) {
		while ($item = dbFetchObject($items)) {?>
			<div onclick="showIdeaDetails('<?= $item->ideaId?>');" class="itemHolder clickable" style="height:2.5em; overflow:hidden">
				<div class="lefter" style="padding:0.1em;">
					<img src="retrieveImage.php?action=ideaImg&actionId=<?= $item->ideaId ?>" style="width:2.25em;height:2.25em;"/>
				</div>
				<div class="lefter">
					<?= $item->title ?><br/>
					<img src="retrieveImage.php?action=userImg&actionId=<?= $item->userId ?>" style="width:1em;height:1em;"/>
					<span><?= getDisplayUsername($item->userId); ?></span>
				</div>
			</div>
		<?}
		if ($countItems > dbNumRows($items)) {?>
			<a class="loadMore" href="javascript:logAction()" onclick="loadResults(this, {action:'getDashCompare', limit:'<?= ($limit + 20) ?>'})">Load more</a>		
		<?}
	} else {?>
		<p>No compares yet</p>
	<?}
}

function renderDashSelect($user, $limit) {
	$selections = getDashSelect($user, "LIMIT $limit");
	$countSelections = countDashSelect($user);
	if ($selections && dbNumRows($selections) > 0 ) {
		while ($selection = dbFetchObject($selections)) {?>
			<div onclick="showIdeaDetails('<?= $selection->ideaId?>');" class="itemHolder clickable" style="height:2.5em; overflow:hidden">
				<div class="lefter" style="padding:0.1em;">
					<img src="retrieveImage.php?action=ideaImg&actionId=<?= $selection->ideaId ?>" style="width:2.25em;height:2.25em;"/>
				</div>
				<div class="lefter">
					<?= $selection->title ?><br/>
					<img src="retrieveImage.php?action=userImg&actionId=<?= $selection->userId ?>" style="width:1em;height:1em;"/>
					<span><?= getDisplayUsername($selection->userId);  ?></span>
				</div>
			</div><?}
		if ($countSelections > dbNumRows($selections)) {?>
			<a class="loadMore" href="javascript:logAction()" onclick="loadResults(this, {action:'getDashSelect', limit:'<?= ($limit + 20) ?>'})">Load more</a>		
		<?}
	} else {?>
		<p>No selections yet</p>
	<?}
}
?>