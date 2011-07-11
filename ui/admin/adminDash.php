<? require_once("thinConnector.php"); ?>
<html>
<head>
<? require_once("head.php"); ?>
</head>
<body>
<div style="position:relative; float:left; border-right:1px solid #EEE">
<h3>Application Statistics</h3>
<h4>IDEAS</h4>
<table style="font-size:1.5em;" cellpadding="5">
<tr><td>Number of ideas</td><td><? echo countQuery("SELECT COUNT(*) FROM Ideas"); ?></td></tr>
<tr><td>Number of public ideas</td><td><? echo countQuery("SELECT COUNT(*) FROM Ideas WHERE isPublic=1"); ?></td></tr>
<tr><td>Public/total ideas %</td><td><? echo floor((countQuery("SELECT COUNT(*) FROM Ideas WHERE isPublic=1")/countQuery("SELECT COUNT(*) FROM Ideas"))*100); ?>%</td></tr>
<tr><td>Number of risk items<br/><span style="font-size:8px">Note there may be multiple risk items per group</span></td><td><? echo countQuery("SELECT COUNT(*) FROM RiskEvaluation"); ?></td></tr>
<tr><td>Number of selections</td><td><? echo countQuery("SELECT COUNT(*) FROM Selections"); ?></td></tr>
<tr><td>Selected/total ideas %</td><td><? echo floor((countQuery("SELECT COUNT(*) FROM Selections")/countQuery("SELECT COUNT(*) FROM Ideas"))*100); ?>%</td></tr>
<tr><td>Average comparison score</td><td><? echo round(countQuery("SELECT AVG(score) FROM RiskEvaluation"), 2); ?></td></tr>
<tr><td>Ideas per user</td><td><? echo round(countQuery("SELECT COUNT(*) FROM Ideas")/countQuery("SELECT COUNT(*) FROM Users"), 2); ?></td></tr>
<tr><td>Max ideas per user</td><td><? echo countQuery("SELECT COUNT(*) AS occ FROM Ideas GROUP BY userId ORDER BY occ DESC LIMIT 1"); ?></td></tr>
</table>
<h4>USERS</h4>
<table style="font-size:1.5em;" cellpadding="5">
<tr><td>Number of users</td><td><? echo countQuery("SELECT COUNT(*) FROM Users"); ?></td></tr>
<tr><td>Comments per user</td><td><? echo round(countQuery("SELECT COUNT(*) FROM Comments")/countQuery("SELECT COUNT(*) FROM Users"),2); ?></td></tr>
<tr><td>Number of groups</td><td><? echo countQuery("SELECT COUNT(*) FROM Groups"); ?></td></tr>
<tr><td>Groups per user</td><td><? echo round(countQuery("SELECT COUNT(*) FROM Groups")/countQuery("SELECT COUNT(*) FROM Users"),2); ?></td></tr>
<tr><td>Max groups per user</td><td><? echo countQuery("SELECT COUNT(*) AS occ FROM Groups GROUP BY userId ORDER BY occ DESC LIMIT 1"); ?></td></tr>
</table>
</div>
<div style="position:relative; float:left">
<h3>Server information</h3>
<? phpinfo(); ?>
</div>
</body>
</html>