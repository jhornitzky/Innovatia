<? 
require_once("thinConnector.php"); 
?>
<html>
<head>
	<title>innoWorks admin</title>
	<script type="text/javascript" src="<?= $serverRoot?>ui/scripts/jQuery-Min.js"></script>
	<script type="text/javascript">
		function logAction() {}
			
		function showAnnouncement() {
			$(".adminContent iframe").attr("src", "announcement.php");
		}
		
		function showUsers() {
			$(".adminContent iframe").attr("src", "users.php");
		}
		
		function showTables() {
			$(".adminContent iframe").attr("src", "tables.php");
		}

		function showAdminDash() {
			$(".adminContent iframe").attr("src", "adminDash.php");
		}	
		
		function showLDAP() {
			$(".adminContent iframe").attr("src", "ldap.php");
		}	
	</script>
	<link href="<?= $serverRoot?>ui/style/style.css" rel="stylesheet" type="text/css" />
	<style>
		html, body {
			text-align:left;
		}
		
		#head a {
			color:#FFF;
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
	<a href="javascript:logAction()" onclick="showAdminDash()">ADash</a>	
	<a href="javascript:logAction()" onclick="showAnnouncement()">Announcements</a>	
	<!-- <a href="javascript:logAction()" onclick="showUsers()">Users</a>-->
	<a href="javascript:logAction()" onclick="showTables()">Tables</a>
	<a href="javascript:logAction()" onclick="showLDAP()">LDAP</a>
	<div style="position:absolute; top:18px; color:#AAA; left:235px; font-weight:bold; font-size:1.5em;">admin</div>
</div>
</div>
<div class="adminContent">
	<iframe src="adminDash.php"></iframe>
</div>
</body>
</html>