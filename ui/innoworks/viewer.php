<? 
require_once("pureConnector.php");

if (!(isset($_GET['idea']) || isset($_GET['group']) || isset($_GET['profile']))) 
	die();

$print = 'false';
if (isset($_GET['print']) )
	$print = 'true';
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="<?= $serverUrl.$serverRoot?>ui/innoworks/" />
<title>innoWorks viewer</title>
<link rel="shortcut icon" href="<?= $serverUrl.$serverRoot?>ui/style/favicon.ico" type="image/x-icon" />
<script type="text/javascript"
	src="<?= $serverRoot?>ui/scripts/jQuery-Min.js"></script>
<script type="text/javascript"
	src="<?= $serverRoot?>ui/scripts/dojo/dojo.js"></script>
<script type="text/javascript"
	src="<?= $serverRoot?>ui/scripts/dojoLayer.js"></script>
<link rel="stylesheet" type="text/css"
	href="<?= $serverRoot?>ui/scripts/dijit/themes/tundra/tundra.css" />
<link href="<?= $serverRoot?>ui/style/style.css" rel="stylesheet"
	type="text/css" />
<link href="<?= $serverRoot?>ui/style/innoworks.css" rel="stylesheet"
	type="text/css" />
<script>
$(document).ready(function() {
	if(<?= $print ?>)
		printThis();
});

function printThis() {
	window.print();
}
</script>
</head>
<body class="tundra">
<?
if ( isset($_GET['iv']) && isset($_GET['idea'])) {
	require_once("compare.ui.php");
	$iv = base64_url_decode($_GET['iv']);
	$actionId = decrypt(base64_url_decode($_GET['idea']), $iv);
	renderIdeaSummary($actionId);
}else if ( isset($_GET['idea']) ) {
	require_once("compare.ui.php");
	renderIdeaSummary($_GET['idea']);
} else if ( isset($_GET['group']) ) {
	require_once("group.ui.php");
	renderSummary($_GET['group']);
} else if ( isset($_GET['profile']) ) {
	require_once("profile.ui.php");
	renderSummaryProfile($_GET['profile']);
}
?>
</body>
</html>