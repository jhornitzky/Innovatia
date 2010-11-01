<?require_once("core/innoworks.config.php");?>
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
<link href="<?= $serverRoot?>ui/style/login.css" rel="stylesheet"
	type="text/css">
<style>
html,body {
	background:#FFFFFF url('ui/style/gearsbg.jpg') no-repeat center center;
}

a, th, td, table {
	color:#FFFFFF;
}

#main {
	float: left;
	width: 440px;
	padding: 10px;
	font-size: 20px;
}

#sidebar {
	float: right;
	width: 230px;
	padding: 10px;
}

.smaller {
	font-size: 18px;
}

div#Responses {
	height:auto;
	width:auto;
	padding:0;
	margin:0;
}

div#footer {
	padding-right: 10px; clear: left; padding-top:3em; width:90%; font-size:12px; text-align:right; float:right;
}

</style>
</head>

<body>
<div class="vAlign_Outer">
<div class="vAlign_Middle">
<div class="vAlign-Inner" style="width: 750px; margin: 0 auto;">

<div class="widgetized" style="min-height: 400px; text-align: left; padding: 15px;">
<img id="logo" src="<?= $serverRoot?>ui/style/kubu.png" />

<div id="main">
<span>Innoworks is an open innovation tool that allows you to collect
and refine your ideas with others.</span><br/><br/>
<table style="font-size: 1.0em;" cellpadding="1.0em" cellspacing="1.0em">
	<tr>
		<td><img style="height: 3em; width: 3em;" src="ui/style/innovate.png" />
		</td>
		<td>Innovate<br />
		<span class="subtext">Record, compare and select ideas through a
		managed simple process</span></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td><img style="height: 3em; width: 3em;" src="ui/style/collab.png" /></td>
		<td>Collaborate<br />
		<span class="subtext">Find and share your ideas with other innovators</span></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td><img style="height: 3em; width: 3em;" src="ui/style/tools.png" /></td>
		<td>
		<p>Get stuff done<br />
		<span class="subtext">Use tools like search and timelines to help you
		implement ideas faster</span></p>
		</td>
	</tr>
</table>
</div>

<div id="sidebar" align="right">
<div id="Responses"></div>
<? require_once("login.html"); ?>
</div>

<div id="footer"> Copyright &copy; UTS 2010 | BETA </div>
</div>

</div>
</div>
</div>
</body>

</html>
