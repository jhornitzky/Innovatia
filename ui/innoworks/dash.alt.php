<? 
require_once("thinConnector.php");
import("dash.service");
?>
<div class="dashHeader" style="width:100%; display:none;"><b>Notifications</b></div>
<div id="dashui" class="threeColumnContainer">
	<div class="threecol col1 ui-corner-all">
		<div class="widget ui-corner-all" style="padding:1%;">
			<div class="orange">
				<h2><a href="javascript:showIdeas();">Explore</a></h2>
			</div>
			<p>Record, manage and explore your ideas to help them take shape and grow.</p>
			<!-- <p><i>Your ideas</i></p> -->
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
	<div class="threecol col2 ui-corner-all">
		<div class="widget ui-corner-all" style="padding:1%;">
			<div class="blue">
				<h2><a href="javascript:showCompare();">Compare</a></h2>
			</div>
			<p>Contrast and compare your existing ideas and work to improve them.</p>
			<!-- <p><i>Your ideas</i></p> -->
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
	<div class="threecol col3 ui-corner-all">
		<div class="widget ui-corner-all" style="height:40%;width:98%;padding:1%;">
			<div class="green">
				<h2><a href="javascript:showSelect();">Select</a></h2>
			</div>
			<p>Choose ideas to work on, and manage their priorities and tasks.</p>
			<!-- <p><i>Your ideas</i></p> -->
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
		<div class="moreInfo ui-corner-all" style="width:98%; padding:1%; opacity:0.95; margin-top:0.5em">
			<div class="grey"><h2>More</h2></div>
			<p><a href="javascript:showGroups();">Groups</a> > Manage who you share your ideas with</p>
			<p><a href="javascript:showProfile();">Profiles</a> > Find other innovators to collaborate with</p>
			<p><a href="javascript:showNotes();">Notes</a> > Communicate with others in real-time</p>
			<p><a href="javascript:showSearch();">Search</a> > Look for ideas, people or groups</p>
			<p><a href="javascript:showTimelines();">Timelines</a>, <a href="javascript:showAdmin();">Admin</a> and <a href="javascript:showReports();">Reports</a>  </p>
		</div>
	</div>
</div>