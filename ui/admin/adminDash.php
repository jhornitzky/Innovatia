<? require_once("thinConnector.php"); ?>
<html>
<head>
<? require_once("head.php"); ?>
</head>
<body>
<h3>Stats on everybody</h3>
<p>Number of ideas : <? echo countQuery("SELECT COUNT(*) FROM Ideas"); ?></p>
<p>Number of selected ideas : <? echo countQuery("SELECT COUNT(*) FROM Selections"); ?></p>
<p>Selected idea ratio : <? echo countQuery("SELECT COUNT(*) FROM Selections")/countQuery("SELECT COUNT(*) FROM Ideas"); ?></p>
<p>Number of groups : <? echo countQuery("SELECT COUNT(*) FROM Groups"); ?></p>
<p>Number of innovators : <? echo countQuery("SELECT COUNT(*) FROM Users"); ?></p>
</body>
</html>