<? 
require_once("thinConnector.php");
import("dash.service");
?>
<div style="width:100%; vertical-align:bottom;">
<div class="fixed-left">
	<h2 id="pgName" style="margin-bottom:0.5em;">Dashboard</h2>
	<div class="itemHolder clickable" onclick="showGroups();">Groups<br/><span>Share your ideas</span></div>
	<div class="itemHolder clickable" onclick="showProfile();">Profiles<br/><span>Find expert innovators</span></div>
	<div class="itemHolder clickable" onclick="showNotes();">Notes<br/><span>Communicate in real-time</span></div>
	<div class="itemHolder clickable" onclick="showSearch();">Search<br/><span>Find ideas, people or groups</span></div>
	<div class="itemHolder clickable" onclick="showTimelines();">Timelines<br/><span>Review your schedule</span></div>
	<div class="itemHolder clickable" onclick="showReports();">Reports<br/><span>View statistics for innoworks</span></div>
</div>
<div class="fixed-right">
<div class="dashHeader" style="width:100%; display:none;"><b>Notifications</b></div>
<div id="dashui" class="threeColumnContainer">
	<div class="threecol col1">
		<div class="blue" style="height:1.5em">&nbsp;</div>
		<div class="widget ui-corner-all">
			<h2><a href="javascript:showIdeas();">Explore</a></h2>
			<p class="subhead">Record, manage and explore your ideas to help them take shape and grow.</p>
			<?
			$ideas = getDashIdeas($_SESSION['innoworks.ID']);
			echo dbNumRows($ideas);
			if ($ideas && dbNumRows($ideas) > 0 ) {
			while ($idea = dbFetchObject($ideas)) {?>
				<div onclick="showIdeaDetails('<?= $idea->ideaId?>');" class="itemHolder clickable"><img src="retrieveImage.php?action=ideaImg&actionId=<?= $idea->ideaId ?>" style="width:1em;height:1em;"/><?= $idea->title ?></div>
				<?}
			} else {
				echo "<p>No ideas yet</p>";
			} ?>
		</div>
	</div>
	<div class="threecol col2">
		<div class="green" style="height:1.5em">&nbsp;</div>
		<div class="widget ui-corner-all">
			<h2><a href="javascript:showCompare();">Compare</a></h2>
			<p class="subhead">Contrast and compare your existing ideas and work to improve them.</p>
			<?
			$items = getDashCompare($_SESSION['innoworks.ID']);
			if ($items && dbNumRows($items) > 0 ) {
			while ($item = dbFetchObject($items)) {?>
				<div onclick="showIdeaDetails('<?= $item->ideaId?>');" class="itemHolder clickable"><img src="retrieveImage.php?action=ideaImg&actionId=<?= $item->ideaId ?>" style="width:1em;height:1em;"/><?= $item->title ?></div>
				<?}
			}else {
				echo "<p>No compares yet</p>";
			}?>
		</div>
	</div>
	<div class="threecol col3">
		<div class="orange" style="height:1.5em">&nbsp;</div>
		<div class="widget ui-corner-all">
			<h2><a href="javascript:showSelect();">Select</a></h2>
			<p class="subhead">Choose ideas to work on, and then manage their priorities and tasks.</p>
			<?
			$selections = getDashSelect($_SESSION['innoworks.ID']);
			if ($selections && dbNumRows($selections) > 0 ) {
			while ($selection = dbFetchObject($selections)) {?>
				<div onclick="showIdeaDetails('<?= $selection->ideaId?>');" class="itemHolder clickable"><img src="retrieveImage.php?action=ideaImg&actionId=<?= $selection->ideaId ?>" style="width:1em;height:1em;"/><?= $selection->title ?></div>
				<?}
			} else {
				echo "<p>No selections yet</p>";
			}?>
		</div>
	</div>
	</div>
	</div>
</div>