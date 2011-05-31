<? 
require_once("thinConnector.php"); 
?>
<html>
<head>
	<title>innoWorks admin</title>
	<script type="text/javascript" src="<?= $serverRoot?>ui/scripts/jQuery-Min.js"></script>
	<script type="text/javascript">
		function logAction(elem) {}

		function showFrame(url) {
			$(".adminContent iframe").attr("src", url);
		}
	</script>
	<link href="<?= $serverRoot?>ui/style/style.css" rel="stylesheet" type="text/css" />
	<style>
		html, body {
			text-align:left;
		}
		
		#head a {
			color:#FFF;
			margin-right:1em;
		}
		
		.adminContent iframe{
			width:100%;
			height:30em;
			border:none;
			border-top:1px solid #444;
			border-bottom:1px solid #444;
		} 
	</style>
	<link rel="shortcut icon" href="<?= $serverUrl.$serverRoot?>/favicon.ico" type="image/x-icon" />
</head>
<body>
<div id="headSurround">
<div id="head">
	<img id="logo" src="<?= $serverRoot?>ui/style/kubu.png" style="height:48px;width:226px;"/>
	<a href="javascript:logAction()" onclick="showFrame('adminDash.php')">ADash</a>	
	<a href="javascript:logAction()" onclick="showFrame('announcement.php')">Announcements</a>	
	<a href="javascript:logAction()" onclick="showFrame('reports.php')">Reports</a>	
	<a href="javascript:logAction()" onclick="showFrame('actions.php')">Bulk Actions</a>	
	<a href="javascript:logAction()" onclick="showFrame('users.php')">Users</a>
	<a href="javascript:logAction()" onclick="showFrame('tables.php')">Tables</a>
	<a href="javascript:logAction()" onclick="showFrame('ldap.php')">LDAP</a>
	<a href="javascript:logAction()" onclick="showFrame('adminManual.pdf')">Help</a>
	<div style="position:absolute; top:18px; color:#AAA; left:235px; font-weight:bold; font-size:1.5em;">admin</div>
</div>
</div>
<div class="adminContent">
	<iframe src="adminDash.php"></iframe>
</div>
</body>
</html>