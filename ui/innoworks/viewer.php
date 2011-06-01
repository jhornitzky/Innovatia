<? 
require_once("pureConnector.php");

function serializeGet() {
	$serial = '';
	foreach ($_GET as $key => $value) {
		$serial .= '&'.$key.'='.$value;
	}
	return $serial;
}

if (!(isset($_GET['idea']) || isset($_GET['group']) || isset($_GET['profile']))) 
	die();

$print = 'false';
if (isset($_GET['print']) )
	$print = 'true';

$ns = '';
if (isset($_REQUEST['doc'])) {
	$the_filename = 'innoWorks' . $_GET['idea'] . $_GET['group'] . $_GET['profile'] . time();
	header( 'Pragma: public' ); 
	header( 'Content-Type: application/msword' ); 
	header( 'Content-Disposition: attachment; filename="' . $the_filename . '.doc"' );
	$ns = 'xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://www.w3.org/TR/REC-html40"';
}
?>
<html <?= $ns ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="<?= $serverUrl.$serverRoot?>ui/innoworks/" />
<title>innoWorks viewer</title>
<link rel="shortcut icon" href="<?= $serverUrl.$serverRoot?>/favicon.ico" type="image/x-icon" />
<script type="text/javascript" src="<?= $serverRoot?>ui/scripts/jQuery-Min.js"></script>
<script type="text/javascript" src="<?= $serverRoot?>ui/scripts/common.js"></script>
<?if (isMobile()) {?>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta name="apple-touch-fullscreen" content="YES" />
<?}?>
<link rel="stylesheet" type="text/css" href="<?= $serverRoot?>ui/scripts/base/dijit/themes/tundra/tundra.css" />
<link href="<?= $serverRoot?>ui/style/style.css" rel="stylesheet" type="text/css" />
<link href="<?= $serverRoot?>ui/style/innoworks.css" rel="stylesheet" type="text/css" />
<style>
body {
	width:800px;
	margin:0 auto;
	position:relative;
}

ul {
	padding:0;
	list-style:none;
} 

h1, h2, .bluetext {
	color:#006699;
}

.summaryActions {
	display:none;
}

.viewSummary input {
	display:none;
}

.featureEvaluationBit {
	width:100%;
}

.featureEvaluationBit td, .featureEvaluationBit th {
	border:1px solid #AAA;
}

.ideaRoles, .ideaFeatures {
	width: 100%;
}

.ideaRoles td, .ideaFeatures td{
	max-width: 200px;
}

.ideaRoles td, .ideaFeatures td {
	border:1px solid #AAA;
	text-align:left;
	vertical-align:top;
}

 td.totalCol span.itemTotal {
	font-size:2.0em;
}

.ideaDetailBit table td {
	vertical-align: top;
	border:1px solid #AAA;
} 

.compareForce {
	display:none;
}
</style>

<script>
$(document).ready(function() {
	if(<?= $print ?>)
		printThis();
});

function printThis() {
	window.print();
}

function showIdeaSummary(id) {
	window.open("viewer.php?idea="+id);
}

function showProfileSummary(id) {
	window.open("viewer.php?profile="+id);
}

function showGroupSummary(id) {
	window.open("viewer.php?group="+id);
}

function goToInnoworks() {
	window.open("<?= $serverUrl . $serverRoot; ?>");
}
</script>
</head>
<body class="tundra">
<table>
	<tr>
	<td>
	<img src="<?= $serverUrl . $serverRoot ?>ui/style/kubus.png" onclick="goToInnoworks()" style="cursor:pointer"/><br/>
	</td>
	<td class="bluetext">
	<?
	$echoBit;
	if ( isset($_GET['idea']) ) 
		$echoBit = "Idea";
	else if ( isset($_GET['group']) ) 
		$echoBit = "Group";
	else if ( isset($_GET['profile']) ) 
		$echoBit = "Profile";
	?>
	<p class="subheading" style="font-size:50px;padding-top:12px;">
	<?= $echoBit?>
	</p>
	</td>
	</tr>
</table>
<div class="viewSummary">
<?
if ( isset($_GET['iv']) && isset($_GET['idea'])) {
	require_once("compare/compare.ui.php");
	$iv = base64_url_decode($_GET['iv']);
	$actionId = decrypt(base64_url_decode($_GET['idea']), $iv);
	renderIdeaSummary($actionId, true);
}else if ( isset($_GET['idea']) ) {
	require_once("compare/compare.ui.php");
	renderIdeaSummary($_GET['idea'], false);
} else if ( isset($_GET['group']) ) {
	require_once("groups/groups.ui.php");
	renderGroupSummary($_GET['group']);
} else if ( isset($_GET['profile']) ) {
	require_once("profile/profile.ui.php");
	renderSummaryProfile($_GET['profile']);
}
?>
</div>
<div class="disclaimer" style="font-size:10px; color:#AAA; margin-top:20px; border-top:2px solid #DDD;">
	<?if (!isLoggedIn()) {?><b>You may be able to see more of this if you <a href="<? $serverUrl . $serverRoot ?>">log in</a></b><br/><?}?>
	Note that the ideas or other information represented here are copyright of the ideator.<br/>
	You should contact them if you wish to use the idea in any way.
</div>
<div class="viewerShare" style="position:absolute; top:10px; right:0; text-align:right;">
	<? renderTemplate('shareBtns', get_defined_vars()); ?>
	<a  style="border:none" href="viewer.php?doc=true<?= serializeGet(); ?>"><img src="<?= $serverUrl . $serverRoot?>ui/style/word.gif"/></a>
</div>
</body>
</html>