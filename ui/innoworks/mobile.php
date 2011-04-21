<?
/**
 * Main view for all logged in innoworks users.
 */
require_once("thinConnector.php");
import("mobile.functions");
import("user.service");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="<?= $serverUrl.$serverRoot?>ui/innoworks/" />
<title>innoWorks</title>
<link rel="shortcut icon" href="<?= $serverUrl.$serverRoot?>favicon.ico" type="image/x-icon" />
<script type="text/javascript" src="<?= $uiRoot?>scripts/jQuery-Min.js"></script>
<link href="<?= $serverRoot?>ui/style/style.css" rel="stylesheet" type="text/css" />
<link href="<?= $serverRoot?>ui/style/innoworks.css" rel="stylesheet" type="text/css" />
<? if (isMobile()) {?>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
<meta name="apple-touch-fullscreen" content="YES" />
<? } ?>
<script type="text/javascript">
//////// VARS //////////
var serverRoot = '<?=$serverRoot?>';
var removeString = "Are you sure you wish to remove this item and associated data?";
var ctime;
var currentIdeaId;
var currentIdeaName;
var currentGroupId;
var currentGroupName = "Private";
var currentPersonId;
var currentPersonName;
var formArray; // Temp holder for form value functions

var isMobile = <?= (isMobile()) ? "true" : "false"; ?>;

/////// START UP ///////
$(document).ready(function() {
});

function openAdmin() {
	window.open(serverRoot + "ui/admin"); 
}
</script>
</head>

<body>
<div id="headSurround">
<div id="head">
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
</div>

<div id="content">

</div>

<!-- RESPONSES -->
<div class="respSurround" style="position:absolute; bottom:0px;">
<div id="ideaResponses" class="responses"></div>
</div>

</body>
</html>