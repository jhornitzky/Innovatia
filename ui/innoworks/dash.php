<? 
require_once("thinConnector.php");
import("dash.service");
?>
<div class="dashHeader" style="width:100%; display:none;"><b>Notifications</b></div>
<div id="dashui" class="threeColumnContainer">
	<div class="col1 ui-corner-all" style="margin-right:1%;width:32%;position:relative; float:left; overflow:auto; background-color:#CCFFFF">
		<div class="widget ui-corner-all" style="padding:1%; height:80%;">
			<h2><a href="javascript:showIdeas();">Ideas</a></h2>
			<p>Record, manage and explore your ideas to help them take shape and grow.</p>
			<?
			$ideas = getDashIdeas($_SESSION['innoworks.ID']);
			if ($ideas && dbNumRows($ideas) > 0 ) {
			while ($idea = dbFetchObject($ideas)) {?>
				<p><?= $idea->title ?></p>
				<?}
			} else {
				echo "<p>No ideas yet</p>";
			} ?>
		</div>
	</div>
	<div class="col2 ui-corner-all" style="margin-right:1%;width:32%;position:relative; float:left; overflow:auto; background-color:#CCCCFF">
		<div class="widget ui-corner-all" style="padding:1%; height:65%;">
			<h2><a href="javascript:showCompare();">Compare</a></h2>
			<p>Contrast and compare your existing ideas and work to improve them.</p>
			<?
			$items = getDashCompare($_SESSION['innoworks.ID']);
			if ($items && dbNumRows($items) > 0 ) {
			while ($item = dbFetchObject($items)) {?>
				<p><?= $item->title ?></p>
				<?}
			}else {
				echo "<p>No compares yet</p>";
			}?>
		</div>
	</div>
	<div class="col3 ui-corner-all" style="width:32%; position:relative; float:left; overflow:auto; ">
		<div class="widget ui-corner-all" style="height:50%;width:98%;background-color:#00AADD; padding:1%;">
			<h2><a href="javascript:showSelect();">Select</a></h2>
			<p>Choose ideas to work on, and manage their priorities and tasks.</p>
			<?
			$selections = getDashSelect($_SESSION['innoworks.ID']);
			if ($selections && dbNumRows($selections) > 0 ) {
			while ($selection = dbFetchObject($selections)) {?>
				<p><?= $selection->title ?></p>
				<?}
			} else {
				echo "<p>No selections yet</p>";
			}?>
		</div>
		<!-- <div class="moreInfo ui-corner-all" style="width:98%; padding:1%; background-color:#FFFFFF; opacity:0.95; margin-top:1.0em">
			<h2>More</h2>
			<p><a href="javascript:showGroups();">Groups</a> > Manage who you share your ideas with</p>
			<p><a href="javascript:showReports();">Reports</a> > Check your progress and see how others are doing</p>
		</div>  -->
	</div>
</div>