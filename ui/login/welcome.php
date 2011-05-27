<?require_once("core/innoworks.config.php");?>
<html xmlns="http://www.w3.org/1999/xhtml">
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
		<td><input class="cupid-blue" type="submit" value="login" style="font-weight: bold; font-size:0.9em" /></td>
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

<div id="loginHeader2" class="clearfix" style="width:100%; padding-top:25px;padding-bottom:20px;">
<div id="loginHeader" style="width:1280px; margin:0 auto;">
<div class="blueCloud" style="width:150px; float:left; height:120px; border-right:2px solid #7DB4FF"></div>
<div style="width:948px; float:left; text-align:left; padding-left:10px; padding-right: 10px; background-color:white; background-color:rgba(255,255,255,0.7); height:100px; padding-top:10px;">
	<h1>Connect your ideas</h1>
	<p style="margin:0">innoWorks lets you save, compare and share ideas with others.</p>
</div>
<div class="orangeCloud" style="width:150px; float:left; height:120px; border-left:2px solid #FFCA6A;"></div>
</div>
</div>

<div id="loginContent">
<div style="width:100%; font-size:1em; padding:0.2em; padding-top:0; text-align:left; clear:both ">
	<ul id="submenu" style="clear:both">
		<li id="ideaInnolnk"><span class="blueItem">[</span><a href="javascript:logAction();" onclick="showSearch();">innovation</a><span class="blueItem">]</span></li> 
		<li id="aboutlnk"><span class="greenItem">[</span><a href="javascript:logAction();" onclick="showAbout();">about</a><span class="greenItem">]</span></li>
		<li id="reglnk"><span class="orangeItem">[</span><a href="javascript:logAction();" onclick="registerUser();">register</a><span class="orangeItem">]</span></li>
		<li id="downlnk"><span class="redItem">[</span><a href="javascript:logAction();" onclick="showDownload();">download</a><span class="redItem">]</span></li>
	</ul>
	<div id="ajaxContent" style="clear:both">
		<div id="AjaxForm" style="width: 100%; float: left; position: relative; font-size: 0.8em;"></div>
	</div>
</div>

<div style="width:100%; font-size:1em; padding:0.2em; text-align:left; ">
	<div style="float:left;  width:560px; text-align:left; font-size:22px;">
		<noscript><span><b>You must have javascript enabled to use Innoworks</b></span></noscript>
	</div>
</div>
</div>

<div id="footerSpace"></div>
<div id="footerSurround">
	<div id="footer">
		<div class="fixed-left">UTS server version<br/><!-- AddThis Button BEGIN -->
			<div class="addthis_toolbox addthis_default_style ">
			<a class="addthis_button_preferred_1"></a>
			<a class="addthis_button_preferred_2"></a>
			<a class="addthis_button_preferred_3"></a>
			<a class="addthis_button_preferred_4"></a>
			<a class="addthis_button_compact"></a>
			<a class="addthis_counter addthis_bubble_style"></a>
			</div>
			<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4dd3b8eb2f85586a"></script>
			<!-- AddThis Button END --></div>
		<div class="fixed-right">
			&copy; UTS 2011
		</div>
	</div>
</div>
</body>
</html>
