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
<!-- <link href="<?= $serverRoot?>ui/style/innoworks.css" rel="stylesheet"
	type="text/css"> -->
<link href="<?= $serverRoot?>ui/style/login.css" rel="stylesheet"
	type="text/css">
<script type="text/javascript">
$(document).ready( function () { 
	showIdeas();
});
</script>
</head>
<body>

<div id="headSurround">
<div id="head">
<div id="leftAlignMenu" style="float:left; position:relative; ">
<table style="vertical-align: middle; margin-top: 0.7em">
	<tr>
		<td>
			<img id="logo" src="<?= $serverRoot?>ui/style/kubu.png" />
		</td>
	</tr>
</table>
</div>
<div id="rightAlignMenu"><? require_once("login.html"); ?></div>
</div>
</div>

<div id="loginContent">
<div style="width:100%; font-size:1em; padding:0.2em; text-align:left; ">
	<div style="float:left;  width:560px; text-align:left; font-size:22px;">
		<p>Innoworks is an open innovation tool that allows you to collect and refine your ideas with others.</p>
		<noscript>You must have javascript enabled to use Innoworks</noscript>
	</div>
</div>

<div style="width:100%; font-size:1em; padding:0.2em; text-align:left; clear:both ">

<ul id="submenu">
	<li id="ideaInnolnk"><a href="#" onClick="showIdeas();">Innovations</a></li>
	<li id="aboutlnk"><a href="javascript:showAbout();">Features</a></li>
	<li id="reglnk"><a href="javascript:registerUser();">Register now</a></li>
	<!-- <li id="searchlnk"><a href="#" onClick="showSearch();">Find</a></li>
	<li id="innovatorslnk"><a href="#" onClick="showInnovators();">Innovators</a></li>-->
	<!-- <li><span style="font-size:0.8em;">Find</span> </li>
	<li><div style="border:1px solid #444444; float:left; width:10em;">
		<input id="searchInput" type="text" name="input" style="font-size:1.0em; width:100%;border:none"/>
		<input type="submit" value="Search" style="display:none;" />
	</div></li> -->
</ul>

<div id="ajaxContent" class="ui-corner-all">
	<div id="AjaxForm" style="width: 100%; float: left; position: relative; font-size: 0.8em;"></div>
</div>
</div>

</div>

</body>
</html>
