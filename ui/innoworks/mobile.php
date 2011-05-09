<?
/**
 * Main view for all logged in innoworks users.
 */
require_once("thinConnector.php");
import("user.service");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="<?= $serverUrl.$serverRoot?>ui/innoworks/" />
<title>innoWorks</title>
<link rel="shortcut icon" href="<?= $serverUrl.$serverRoot ?>favicon.ico" type="image/x-icon" />
<link href="<?= $serverRoot ?>ui/style/style.css" rel="stylesheet" type="text/css">
<link href="<?= $serverRoot ?>ui/style/mobile.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?= $uiRoot ?>scripts/jQuery-Min.js"></script>
<script type="text/javascript" src="<?= $uiRoot ?>scripts/innoworks.js"></script>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta name="apple-touch-fullscreen" content="YES" />
<style>
html, body{
	text-align:left;
}

#content, #menu {
	width: 98%;
	padding:1%;
	clear:both;
	padding-bottom:20px;
}

#menu {
	height:20px;
}

#menu div {
	margin-left:10px;
	margin-right:10px;
	float:left;
	font-size:20px;
	padding:2px;
	color:#FFF;
	cursor:pointer;
}

#menu div img {
	width:30px;
	height:30px;
}

.selected {
	background-color:#999;
}
</style>

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
var isMobile = true;
var currType;

function showSummaryPane(props) {
	window.open('viewer.php?'+props.actionType+'='+props.actionId);
}

/////// START UP ///////
function loadContent(request, elem, type) {
	showLoading("#content");
	$.post("mobile.ajax.php", request, function(data) {
		$("#content").html(data);
	});

	if (elem !== undefined) {
		$(elem).siblings().removeClass('selected');
		$(elem).addClass('selected');
	} 
}

$(document).ready(function() {
	loadContent({action:'getAddForm'}, $('#menu div').first()[0]);
});
</script>

</head>
<body>
	<div id="headSurround">
		<div id="head">
			<div id="menu">
				 <div onclick="loadContent('action=getAddForm', this)"><img src="<?=$uiRoot?>style/menu/add.png"/><br/></div>
				 <div onclick="loadContent('action=getIdeas', this)"><img src="<?=$uiRoot?>style/cube.png"/></div>
				 <div onclick="loadContent('action=getProfiles', this)"><img src="<?=$uiRoot?>style/user.png"/></div>
				 <div onclick="loadContent('action=getGroups', this)"><img src="<?=$uiRoot?>style/group.png"/></div>
				 <div onclick="logout()"><img src="<?=$uiRoot?>style/menu/logout.png"/></div>
			</div>
		</div>
	</div>
	<div id="content"> 
		<div class='loadingAnim'></div>
	</div>
	<!-- RESPONSES -->
	<div class="respSurround" style="position: absolute; bottom: 0px;">
		<div id="ideaResponses" class="responses"></div>
	</div>
</body>
</html>