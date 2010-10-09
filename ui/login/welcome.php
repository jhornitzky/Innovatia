<?
require_once("core/innoworks.config.php");
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- <base href="<?= $serverUrl.$serverRoot?>ui/login/"/> -->
<title>innoWorks</title>
<script type="text/javascript"
	src="<?= $serverRoot?>ui/scripts/jQuery-Min.js"></script>
<script type="text/javascript"
	src="<?= $serverRoot?>ui/scripts/login.js"></script>
<link href="<?= $serverRoot?>ui/style/style.css" rel="stylesheet"
	type="text/css">
<link href="<?= $serverRoot?>ui/style/innoworks.css" rel="stylesheet"
	type="text/css">
<link href="<?= $serverRoot?>ui/style/login.css" rel="stylesheet"
	type="text/css">
	
<script type="text/javascript">

$(document).ready( function () { 
	showAbout();
});

</script>
</head>

<body onLoad="startUp();">

<div id="head">
<div id="rightAlignMenu"><? require_once("login.html"); ?></div>
</div>

<div id="loginContent">
<table style="width: 100%; font-size: 1em;">
	<tr>
		<td colspan="2" style="height: 1.5em; min-height:48px;">
			<div id="Responses">
				<div id="Wait" class="loadingAnim"></div>
			</div>
		</td>
	</tr>
	<tr>
		<td style="vertical-align:top ;width:45%">
		<h1 style="vertical-align:middle;"><img id="logo" style="width: 1.75em; height: 1.75em"
			src="<?= $serverRoot?>ui/style/kubu.png" /> Innoworks</h1>
		<noscript>You must have javascript enabled to use Innoworks</noscript>
		<ul id="submenu">
			<li><a href="javascript:showAbout();">What is innoworks?</a></li>
			<!-- <li><a href="#" onClick="showIdeas();">Latest ideas</a></li>
			<li><a href="#" onClick="showInnovators();">Innovators</a></li>-->
			<li><a href="javascript:registerUser();">Register now</a></li>
		</ul>
		</td>
		<td style="width:55%">
		<div id="AjaxForm"
			style="width: 100%; float: left; position: relative; font-size: 0.8em;"></div>
		</td>
	</tr>
</table>
</div>

</body>

</html>
