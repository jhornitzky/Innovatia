<?require_once("core/innoworks.config.php");?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>innoWorks mobile</title>
<link rel="shortcut icon" href="<?= $serverUrl.$serverRoot?>/favicon.ico" type="image/x-icon" />
<script type="text/javascript" src="<?= $serverRoot?>ui/scripts/jQuery-Min.js"></script>
<script type="text/javascript" src="<?= $serverRoot?>ui/scripts/login.js"></script>
<link href="<?= $serverRoot?>ui/style/mobile.css" rel="stylesheet" type="text/css">
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta name="apple-touch-fullscreen" content="YES" />
</head>
<body>
	<div id="headSurround">
		<div id="head">
			<table style="vertical-align: middle; margin-top: 0.7em">
				<tr>
					<td><img id="logo" src="<?= $serverRoot?>ui/style/kubu.png" />
					</td>
				</tr>
				<tr>
					<td style="color: #FFFFFF; padding-left: 10px;">The open innovation tool for everyone</td>
				</tr>
			</table>
		</div>
	</div>
	<div id="Responses">
		<div id="Wait" class="loadingAnim"></div>
	</div>
	<form id="LoginForm" onSubmit="authenUser(); return false;">
		<label for="iUsername">Username / UTS ID:</label><br /> <input
			id="iUsername" type="text" name="username" size="20" /><br /> <label
			for="iUsername">Password:</label><br /> <input type="password"
			name="password" size="20" /><br /> <input type="submit"
			value="&raquo; Login" />
	</form>
	<p>Want more? Come back on a desktop for the full experience.</p>
	<p><i>Beta version</i></p>
</body>
</html>