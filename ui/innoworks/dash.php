<? 
require_once("thinConnector.php");
import("dash.service");
?>
<div style="width:100%; vertical-align:bottom;">
<div class="fixed-left">
	<h2 id="pgName">Dashboard</h2>
	<div class="bordRight">
	<p><a href="javascript:showGroups();">Groups</a><br/><span>Share your ideas</span></p>
	<p><a href="javascript:showProfile();">Profiles</a><br/><span>Find expert innovators</span></p>
	<p><a href="javascript:showNotes();">Notes</a><br/><span>Communicate in real-time</span></p>
	<p><a href="javascript:showSearch();">Search</a><br/><span>Find ideas, people or groups</span></p>
	<p><a href="javascript:showTimelines();">Timelines</a><br/><span>Review your schedule</span></p>
	<p><a href="javascript:showReports();">Reports</a><br/><span>View statistics for innoworks</span></p>
	</div>
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
			if ($ideas && dbNumRows($ideas) > 0 ) {
			while ($idea = dbFetchObject($ideas)) {?>
				<p><a href="javascript:showIdeaDetails('<?= $idea->ideaId?>');"><?= $idea->title ?></a></p>
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
				<p><a href="javascript:showIdeaDetails('<?= $item->ideaId?>');"><?= $item->title ?></a></p>
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
				<p><a href="javascript:showIdeaDetails('<?= $selection->ideaId?>');"><?= $selection->title?></a></p>
				<?}
			} else {
				echo "<p>No selections yet</p>";
			}?>
		</div>
	</div>
	</div>
	</div>
</div>