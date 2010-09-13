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
</head>
<body onLoad="startUp();">
<table width="100%" height="100%" border="0" cellpadding="0"
	cellspacing="0">
	<tr>
		<td valign="middle">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td align="center">
				<table id="MainHolder" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td>
						<table cellpadding="5" height="220" cellspacing="5" border="0"
							style="margin: 25px; height: 220px;">
							<tr>
								<td align="center" valign="middle"><img id="logo" style="height:60px;width:57px;" src="<?= $serverRoot?>ui/style/kubu.png"/></td>
								<td align="center" valign="middle" style="padding-right: 50px;">
									<h2>Innoworks</h2>
									<noscript>You must have javascript enabled to use Innoworks</noscript>
								</td>
								
								<td valign="middle">
									<div id="HoldWidth" style="width: 300px; height: 0px;"></div>
									<div id="Wait">Loading...</div>
									<div id="Responses"></div>
									<div id="AjaxForm"><? include("login.html"); ?></div>
								</td>
							</tr>
						</table>
						</td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>


<!-- <h2>Innoworks</h2>
<div id="Responses"><div id="Wait">Loading...</div></div>
<div id="AjaxForm"><? include("login.html"); ?></div>
</body> -->

</html>
