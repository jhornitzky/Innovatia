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

<body>

<div id="head" style="height:2.0em;"><div id="rightAlignMenu"><? require_once("login.html"); ?></div></div>

<div id="loginContent">
<table style="width: 100%; font-size: 1em;" cellspacing="0"
	cellpadding="0">
	<tr>
		<td style="height:1.5em;" colspan="2">
			<div id="Responses"><div id="Wait" class="loadingAnim"></div></div>
		</td>
	</tr>
	<tr>
		<td style="vertical-align: top; width: 30%; min-width: 11em;">
		<table style="vertical-align: middle; margin-top: 0.7em">
			<tr>
				<td><img id="logo" style="width: 90px; height: 55px;" src="<?= $serverRoot?>ui/style/kubu.png" /></td>
				<td><span
					style="font-size: 2.0em; font-weight: bold; padding-left: 0.1em;">Innoworks</span>
				</td>
			</tr>
		</table>
		<noscript>You must have javascript enabled to use Innoworks</noscript>
		<ul id="submenu">
			<li id="aboutlnk"><a href="javascript:showAbout();">What is
			innoworks?</a></li>
			<li id="reglnk"><a href="javascript:registerUser();">Register now</a></li>
			<li id="searchlnk"><a href="#" onClick="showSearch();">Search</a></li>
			<li id="ideaInnolnk"><a href="#" onClick="showIdeas();">Latest ideas</a></li>
			<li id="innovatorslnk"><a href="#" onClick="showInnovators();">Innovators</a></li>
		</ul>
		<ul class="footmenu" style="margin-top:5em;">
			<!-- <li>Innogames</li>  -->
			<li>Beta Version</li>
			<li>Privacy Policy</li>
			<li>Copyright 2010 UTS</li>
		</ul></td>
		<td id="ajaxContent" class="ui-corner-all">
		<div id="AjaxForm"
			style="width: 100%; float: left; position: relative; font-size: 0.8em;"></div>
		</td>
	</tr>
</table>
</div>
</body>

</html>
