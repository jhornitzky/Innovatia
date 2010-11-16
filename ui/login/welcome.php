<?
require_once("core/innoworks.config.php");
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>innoWorks</title>
<link rel="shortcut icon" href="<?= $serverUrl.$serverRoot?>ui/style/favicon.ico" type="image/x-icon" />
<script type="text/javascript"
	src="<?= $serverRoot?>ui/scripts/jQuery-Min.js"></script>
<script type="text/javascript"
	src="<?= $serverRoot?>ui/scripts/login.js"></script>
<link href="<?= $serverRoot?>ui/style/style.css" rel="stylesheet"
	type="text/css">
<link href="<?= $serverRoot?>ui/style/login.css" rel="stylesheet"
	type="text/css">
	
<script type="text/javascript">
$(document).ready( function () { 
	showSearch();
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
	<tr>
		<td style="color:#FFFFFF; padding-left:10px;">
			The open innovation tool for everyone
		</td>
	</tr>
</table>
</div>
<div id="rightAlignMenu">
<form id="LoginForm" onSubmit="authenUser(); return false;">
<table cellpadding="2px" cellspacing="2px">
	<tr>
		<th style="padding-left:3px;">Username / eMail:</th>
		<th style="padding-left:3px;">Password:</th>
		<th></th>
	</tr>
	<tr>
		<td><input type="text" name="username" size="20" /></td>
		<td><input type="password" name="password" size="20" /></td>
		<td><input type="submit" value="&raquo; Login" style="font-weight: bold;" /></td>
	</tr>
	<tr>
		<td colspan="3">
			<div id="Responses"><div id="Wait" class="loadingAnim"></div></div>
		</td>
	</tr>
</table>
</form>
</div>
</div>
</div>

<div id="loginContent">
<div style="width:100%; font-size:1em; padding:0.2em; text-align:left; ">
	<div style="float:left;  width:560px; text-align:left; font-size:22px;">
		<noscript><span><b>You must have javascript enabled to use Innoworks</b></span></noscript>
	</div>
	<!-- <div>
		<div style="text-align:right; padding-right:10px; float:right; width:300px;">
			<p style="font-size:22px;">
			<span style="font-size:10px;">
				Copyright &copy; UTS 2010 | BETA<br/>
				<br/>
				Privacy<br/>
				<br/>
				More
			</span>
			</p>
		</div>
	</div>-->
</div>

<div style="width:100%; font-size:1em; padding:0.2em; text-align:left; clear:both ">
	<ul id="submenu" style="clear:both ">
		<li id="ideaInnolnk"><div class="marker orange"></div><a href="#" onClick="showIdeas();">Innovations</a></li> 
		<li id="aboutlnk"><div class="marker green"></div><a href="javascript:showAbout();">Features</a></li>
		<li id="reglnk"><div class="marker blue"></div><a href="javascript:registerUser();">Register</a></li>
	</ul>

	<div id="ajaxContent" style="clear:both">
		<div id="AjaxForm" style="width: 100%; float: left; position: relative; font-size: 0.8em;"></div>
	</div>
</div>

</div>

</body>
</html>
