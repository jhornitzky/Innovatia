<?php 
require_once('thinConnector.php');
import('user.service');
import('idea.service');
require($_SERVER['DOCUMENT_ROOT'] . $uiRoot . 'innoworks/compare/compare.ui.php');
require($_SERVER['DOCUMENT_ROOT'] . $uiRoot . 'innoworks/ideas/ideas.ui.php');
?>
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

.nohelp {
	display:none;
}
</style>
</head>

<body class="tundra">
<h1>ideabook</h1>
<?
$ideas = getAllIdeas();
while ($idea = dbFetchObject($ideas)) {?>
	<div class="idea" style="border:1px solid #EEE; border-left:1px solid #AAA; padding:5px; margin-bottom:20px;">
		<h2><?= $idea->title ?></h2>
		<div style="margin-left:5px">
			<span>by <b><?= getDisplayUsername($idea->userId) ?></b></span>
			<span>created <?= $idea->createdTime ?></span>
			<span>last <?= $idea->lastUpdateTime ?></span>
			<p>
				<div class="tiny">description</div>
				<span style="font-size:0.85em;"><?= $idea->proposedService ?></span>
			</p>
			<div class="tiny">reviews</div>
			<?
			renderFeatureEvaluationTable($idea->ideaId, false);
			renderIdeaRiskEval($idea->ideaId, $_SESSION['innoworks.ID']);
			renderCommentsForIdea($idea->ideaId, $_SESSION['innoworks.ID']);
			?>
		</div>
	</div>
<?}?>
</body>
</html>