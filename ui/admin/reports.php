<?php
require_once('thinConnector.php');
$curDate = getdate();
?>
<html>
<head>
<? require_once("head.php"); ?>
<style>
html,body {
	margin-bottom:2em;
	font-family:arial;
}

div {
	width:100%;
	float:left;
	text-align:center;
}
</style>
</head>
<body>
<span>Data for year <?= $curDate['year'] ?></span><br/>
<span style="font-size:0.85em;">Current week of year  is <b><?= date('W') ?></b></span>
<div>
<h2>new ideas per week</h2>
<img src="reportImages.php?action=ideas&year=<?= $curDate['year'] ?>"/>
</div>
<div>
<h2>new users per week</h2>
<img src="reportImages.php?action=users&year=<?= $curDate['year'] ?>"/>
</div>
<div>
<h2>new groups per week</h2>
<img src="reportImages.php?action=groups&year=<?= $curDate['year'] ?>"/>
</div>
<div>
<h2>views per week</h2>
<img src="reportImages.php?action=views&year=<?= $curDate['year'] ?>"/>
</div>
</body>
</html>