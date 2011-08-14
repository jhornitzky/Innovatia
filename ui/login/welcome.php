<?
require_once("core/innoworks.config.php");

//check cookies first
if (cookieLogin()) {
	header('Location: ' . $serverUrl . $serverRoot);
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>innoWorks</title>
<link rel="shortcut icon" href="<?= $serverUrl.$serverRoot?>/favicon.ico" type="image/x-icon" />
<script type="text/javascript" src="<?= $serverRoot?>ui/scripts/jQuery-Min.js"></script>
<script type="text/javascript" src="<?= $serverRoot?>ui/scripts/login.js"></script>
<link href="<?= $serverRoot?>ui/style/style.css" rel="stylesheet" type="text/css">
<link href="<?= $serverRoot?>ui/style/login.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
$(document).ready( function () { 
	showSearch(); //grab search results
	$('#iUsername')[0].focus(); //set focus to login
});
</script>
</head>

<body style="background-image:url('<?= $serverRoot?>ui/style/body.jpg')">
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
<div id="rightAlignMenu">
<form id="LoginForm" onSubmit="authenUser(); return false;">
<table cellpadding="2px" cellspacing="2px">
	<tr>
		<th style="padding-left:3px;">Username / UTS ID:</th>
		<th style="padding-left:3px;">Password:</th>
		<th></th>
	</tr>
	<tr>
		<td><input id="iUsername" type="text" name="username" size="20" /></td>
		<td><input type="password" name="password" size="20" /></td>
		<td rowspan="2"><input style="cursor:pointer; border:none; background-color:transparent; width:48px; height:48px; background-image:url('<?= $serverUrl . $uiRoot ?>style/forward.png'); text-indent:-9999px; margin-top:-25px" type="submit" value="Log In" style="font-weight: bold; font-size:0.9em" /></td>
	</tr>
	<tr>
		<td colspan="3" style="font-size:0.75em; color:#FFF">
			<input type="checkbox" name="remember" style="font-size:0.75em;vertical-align:middle; margin-left:0;"/> 
			Keep me logged in
		</td>
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

<div style="width:975px; margin:0 auto;">
	<div id="loginHeader" style="text-align:left; margin-left:2px; margin-bottom:20px; margin-top:10px;">
		<h1>Build your ideas</h1>
		<p style="margin:0; padding-left:6px; color:#AAA">innoWorks helps you compare, share and make ideas</p>
	</div>
	<div class="clearfix">
		<ul id="submenu" style="clear:both; padding-left:5px; padding-right:5px;">
			<li id="ideaInnolnk" class="blueItem" style="border-bottom-width:1px; border-bottom-style:solid;"><a href="javascript:logAction();" onclick="showSearch();">innovate</a></li> 
			<li id="aboutlnk" class="greenItem" style="border-bottom-width:1px; border-bottom-style:solid;"><a href="javascript:logAction();" onclick="showAbout();">about</a></li>
			<li id="reglnk" class="orangeItem" style="border-bottom-width:1px; border-bottom-style:solid;"><a href="javascript:logAction();" onclick="registerUser();">join us</a></li>
			<li id="downlnk" class="redItem" style="border-bottom-width:1px; border-bottom-style:solid;"><a href="javascript:logAction();" onclick="showDownload();">download</a></li>
		</ul>
	</div>
</div>
<div id="loginContent" class="curvedL" style="margin-top:-2px;">
	<div style="width:100%; font-size:1em; padding:0.2em; padding-left:0.5em; padding-top:0; text-align:left; clear:both ">
		<div id="ajaxContent" style="clear:both">
			<div id="AjaxForm" style="width: 100%; float: left; position: relative; font-size: 0.8em;"></div>
		</div>
	</div>
	<div style="width:100%; font-size:1em; padding:0.2em; text-align:left; ">
		<div style="float:left;  width:560px; text-align:left; font-size:22px;">
			<noscript><span><b>You must have javascript enabled to use innoWorks</b></span></noscript>
		</div>
	</div>
</div>

<div id="footerSpace"></div>
<div id="footerSurround">
	<div id="footer" style="font-size:12px">
		<? renderTemplate('common.footer');?>
		<!-- AddThis Button BEGIN -->
		<!-- <div class="addthis_toolbox addthis_default_style ">
			<a class="addthis_button_preferred_1"></a>
			<a class="addthis_button_preferred_2"></a>
			<a class="addthis_button_preferred_3"></a>
			<a class="addthis_button_preferred_4"></a>
			<a class="addthis_button_compact"></a>
			<a class="addthis_counter addthis_bubble_style"></a>
		</div>
		<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4dd3b8eb2f85586a"></script> -->
		<!-- AddThis Button END -->
	</div>
</div>
</body>
</html>
