<? require_once("thinConnector.php"); ?>
<html>
<head>
<? require_once("head.php"); ?>
</head>
<body>
<div style="position:relative; float:left; border-right:1px solid #EEE">
<h3>Application Statistics</h3>
<p>Number of ideas : <? echo countQuery("SELECT COUNT(*) FROM Ideas"); ?></p>
<p>Number of selected ideas : <? echo countQuery("SELECT COUNT(*) FROM Selections"); ?></p>
<p>Selected idea ratio : <? echo countQuery("SELECT COUNT(*) FROM Selections")/countQuery("SELECT COUNT(*) FROM Ideas"); ?></p>
<p>Number of groups : <? echo countQuery("SELECT COUNT(*) FROM Groups"); ?></p>
<p>Number of innovators : <? echo countQuery("SELECT COUNT(*) FROM Users"); ?></p>
</div>
<div style="position:relative; float:left">
<h3>Server information</h3>
<? phpinfo(); ?>
</div>
</body>
</html>