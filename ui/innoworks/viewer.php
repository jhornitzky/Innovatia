<? 
require_once("pureConnector.php");

$action;
$actionId;

if ( isset($_GET['idea']) ) {
	$action='showIdeaSummary';
	$actionId = $_GET['idea'];
} else if ( isset($_GET['group']) ) {
	$action='showGroupSummary';
	$actionId = $_GET['group'];
} else if ( isset($_GET['profile']) ) {
	$action='showProfileSummary';
	$actionId = $_GET['profile'];
}
	
if (!isset($action) )
	die();
	
$print = 'false';
if ( isset($_GET['print']) )
	$print = 'true';
	
$actionString = $action . "('" . $actionId . "', " . $print . ")";
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
	<?= $actionString ?>;
});

function printThis() {
	window.print();
}

function showIdeaSummary(id,print) {
	$("body").load("compare.ajax.php?action=getIdeaSummary&actionId="+id, function () {
		if (print)
			printThis();
	});
}

function showProfileSummary(id,print) {
	$("body").load("profile.ajax.php?action=getProfileSummary&actionId="+id, function () {
		if (print)
			printThis();
	});
}

function showGroupSummary(id,print) {
	$("body").load("groups.ajax.php?action=getGroupSummary&actionId="+id, function () {
		if (print)
			printThis();
	});
}
</script>
</head>
<body class="tundra">
</body>
</html>