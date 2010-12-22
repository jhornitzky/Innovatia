<? 
require_once("pureConnector.php");

if (!(isset($_GET['idea']) || isset($_GET['group']) || isset($_GET['profile']))) 
	die();

$print = 'false';
if (isset($_GET['print']) )
	$print = 'true';
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="<?= $serverUrl.$serverRoot?>ui/innoworks/" />
<title>innoWorks viewer</title>
<link rel="shortcut icon" href="<?= $serverUrl.$serverRoot?>/favicon.ico" type="image/x-icon" />
<script type="text/javascript" src="<?= $serverRoot?>ui/scripts/jQuery-Min.js"></script>
<!-- <script type="text/javascript" src="<?= $serverRoot?>ui/scripts/dojo/dojo.js"></script>
<script type="text/javascript" src="<?= $serverRoot?>ui/scripts/dojoLayer.js"></script>-->
<script type="text/javascript" src="<?= $serverRoot?>ui/scripts/common.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $serverRoot?>ui/scripts/dijit/themes/tundra/tundra.css" />
<link href="<?= $serverRoot?>ui/style/style.css" rel="stylesheet" type="text/css" />
<link href="<?= $serverRoot?>ui/style/innoworks.css" rel="stylesheet" type="text/css" />
<style>
body {
	width:800px;
	margin:0 auto;
}

ul {
	padding:0;
	list-style:none;
} 

.summaryActions {
	display:none;
}

.viewSummary input {
	display:none;
}
</style>

<script>
$(document).ready(function() {
	if(<?= $print ?>)
		printThis();
});

function printThis() {
	window.print();
}

function goToInnoworks() {
	window.open("<?= $serverUrl . $serverRoot; ?>");
}
</script>
</head>
<body class="tundra">
<table>
<tr>
<td>
<img src="<?= $serverUrl . $serverRoot ?>ui/style/kubus.png" onclick="goToInnoworks()" style="cursor:pointer"/><br/>
</td>
<td>
<?
$echoBit;
if ( isset($_GET['idea']) ) 
	$echoBit = "Idea";
else if ( isset($_GET['group']) ) 
	$echoBit = "Group";
else if ( isset($_GET['profile']) ) 
	$echoBit = "Profile";
?>
<p class="subheading" style="font-size:50px;padding-top:12px;">
<?= $echoBit?>
</p>
</td>
</tr>
</table>
<hr/>
<div class="viewSummary">
<?
if ( isset($_GET['iv']) && isset($_GET['idea'])) {
	require_once("compare/compare.ui.php");
	$iv = base64_url_decode($_GET['iv']);
	$actionId = decrypt(base64_url_decode($_GET['idea']), $iv);
	renderIdeaSummary($actionId, true);
}else if ( isset($_GET['idea']) ) {
	require_once("compare/compare.ui.php");
	renderIdeaSummary($_GET['idea'], false);
} else if ( isset($_GET['group']) ) {
	require_once("groups/groups.ui.php");
	renderGroupSummary($_GET['group']);
} else if ( isset($_GET['profile']) ) {
	require_once("profile/profile.ui.php");
	renderSummaryProfile($_GET['profile']);
}
?>
</div>
<hr/>
<p style="font-size:0.8em;margin:0; padding:0;">Share this with a friend</p>
<div class="shareBtns" style="margin:0; padding:0;">	
	<img src="<?= $serverRoot?>ui/style/emailbuttonmini.jpg" onclick="openMail('yourFriend@theirAddress.com', 'Check out my idea on innoworks', 'I thought you might like my idea. You can see it at <?= $shareUrl ?>')" />
	<img src="<?= $serverRoot?>ui/style/fb btn.png" onclick="openFace()" />
	<img class="shareLeft" src="<?= $serverRoot?>ui/style/delicious btn.png" onclick="openDeli()" />
	<img class="shareLeft" src="<?= $serverRoot?>ui/style/twit btn.png" onclick="openTweet()"/>
	<img class="shareLeft" src="<?= $serverRoot?>ui/style/blogger btn.png" onclick="openBlog()"/>
</div>
</body>
</html>