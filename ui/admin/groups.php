<?php 
require_once('thinConnector.php');
import('user.service');
import('idea.service');
import('group.service');
import('note.service');
require($_SERVER['DOCUMENT_ROOT'] . $uiRoot . 'innoworks/compare/compare.ui.php');
require($_SERVER['DOCUMENT_ROOT'] . $uiRoot . 'innoworks/ideas/ideas.ui.php');

function graphItem($date, $text, $class) {
	//calc date diff from up to 6 months in past
	$sixMonthBack = date_create(strtotime('-180 days'));
	$days = calculateDateDiff($date, $sixMonthBack);
				
	//then get the % ie left position
	if(days == 0)
		$left = 100;
	else
		$left = (($sixMonthBack - $days)/$sixMonthBack) * 100;
	?>
	<div class="clearfix" style="width:100%;float:left;height:1em; position:relative;">
		<div class="clearfix" style="left:<?= $left ?>%; font-size:10px; position:absolute; border-left:1px solid #AAA">
			<?= $text ?><span style="color:#AAA"><?= $class ?></span>
		</div>
	</div>
<?}?>

<html>
<head>
<?php require_once 'head.php';?>
<style>
a {
	font-size:1.0em;
}

td, th {
	border:1px solid #AAA;
	padding: 0.5em;
}

th {
	background-color:#AAA;
}

table img {
	width:1.5em; height:1.5em;
}
</style>
</head>

<body class="tundra">
<h1>group listing</h1>
<?
$groups = getAllGroups();
while ($group = dbFetchObject($groups)) {
	//get info for group and condense into list
	$ideas = getIdeasForGroup($group->groupId);
	$groupUsers = getUsersForGroup($group->groupId);
	$comments = getCompareCommentsForGroup($_SESSION['innoworks.ID'],$group->groupId);
	$notes = getIdeasForGroup($group->groupId);
	
	//calc date diff from up to 6 months in past
	$sixMonthBack = date_create(strtotime('-180 days'));
	$days = calculateDateDiff($group->createdTime, $sixMonthBack);
	
	//then get the % ie left position
	if(days == 0)
		$left = 100;
	else
		$left = (($sixMonthBack - $days)/$sixMonthBack) * 100;	
	?>
	<div class="group" style="border:1px solid #EEE; border-left:1px solid #AAA; padding:5px; margin-bottom:20px;">
		<h2><?= $group->title ?></h2>
		<div style="margin-left:5px; width:95%;">
			<span>led by <b><?= getDisplayUsername($group->userId) ?></b></span>
			<span>created <?= $group->createdTime ?></span>
			<span>last <?= $group->lastUpdateTime ?></span>
			<p>
				<div class="tiny">info</div>
				<span style="font-size:0.85em;"><?= $group->description ?></span>
			</p>
			<div class="tiny">activity</div>
			<div class="clearfix" style="width:90%; margin-bottom:10px; border-bottom:1px solid #AAA; position:relative">
				<div style="position:absolute; height:100%;border-right:1px solid red; left:<?= $left ?>%"></div>
				<? 
				while ($idea = dbFetchObject($ideas)) { 
					graphItem($idea->createdTime, $idea->title, 'idea');
				}
				while ($comment = dbFetchObject($comments)) { 
					graphItem($comment->lastUpdateTime, $comment->text, 'comment');
				}
				?>
			</div>
			<div class="tiny">reviews</div>
			<? renderComparisonForGroup($group->groupId); ?>
		</div>
	</div>
<?}?>
</body>
</html>