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
<script type="text/javascript" src="<?= $uiRoot?>scripts/base/dojo/dojo.js"></script>
<script type="text/javascript" src="<?= $uiRoot?>scripts/base/InnoBigDialog.js"></script>
<script type="text/javascript" src="<?= $uiRoot?>scripts/base/InnoDialog.js"></script>
<script type="text/javascript" src="<?= $uiRoot?>scripts/base/dojoLayer.js"></script>
<script type="text/javascript" src="<?= $uiRoot?>scripts/innoworks.js"></script>
<script type="text/javascript" src="<?= $uiRoot?>scripts/common.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $uiRoot?>scripts/base/dijit/themes/tundra/tundra.css" />
<link rel="stylesheet" type="text/css" href="<?= $uiRoot?>scripts/base/dojox/layout/resources/ResizeHandle.css" />
<link rel="stylesheet" type="text/css" href="<?= $uiRoot?>scripts/base/dojox/layout/resources/FloatingPane.css" />
<link href="<?= $serverRoot?>ui/style/style.css" rel="stylesheet" type="text/css" />
<link href="<?= $serverRoot?>ui/style/innoworks.css" rel="stylesheet" type="text/css" />

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
var targets = {ideas: "ideas.ajax.php",  groups: "groups.ajax.php",  
		compare: "compare.ajax.php", reports: "reports.ajax.php"};
var isMobile = <?= (isMobile()) ? "true" : "false"; ?>;

/////// START UP ///////
$(document).ready(function() {
	//Show default
	showDash();

	//AJAX methods and setup
	$.ajaxSetup ({  
		cache: false  
	});

	//attach to bottom
	$(window).resize(positionResponse).scroll(positionResponse);
	
	//Start server polling
	window.setTimeout(pollServer, 5000);
	window.setInterval(pollServer, 2*60*1000); //poll every 2 minutes
});

dojo.addOnLoad(function(){	
	/* if (isMobile) {
		dojo.declare("dijit.form.Textarea",null,null);
	} */
	
	//Parse controls
	dojo.parser.parse();
	
	//Setup stuff for tab menus
	dojo.subscribe("ideasPopupTabContainer-selectChild", function(child){
		subscribeForChild(child);
	});
	dojo.subscribe("ideasPopupDetails-selectChild", function(child){
		subscribeForChild(child);
	});
	dojo.subscribe("ideasPopupReview-selectChild", function(child){
		subscribeForChild(child);
	});
});

function openAdmin() {
	window.open(serverRoot + "ui/admin"); 
}
</script>
<!--[if IE]>
	<style>
	html, body {
		font-size:0.95em;
	}
	
	form  {
		margin-top:2px;
	}
	
	#searchTab * .threecol {
		margin-right:2%; 
		width:23%; 
		position:relative; 
		float:left; 
		overflow:auto; 
	}
	
	#timelineTab table, #compareTab table, div.fixed-right #reportDetails  {
		width:750px;
	}
	
	.respSurround {
		background-color:#FFFFFF;
		max-height:3em;
	}

	.respSurround .responses {
		padding-left:10px;
		max-height:3em;
		overflow:hidden;
		width:1000px; 
		margin:auto; 
		color:#000000;
		background:none;
		border:none;
	}
	</style>
<![endif]-->
</head>

<body class="tundra">

<!-- HEADER BAR -->
<div id="headSurround">
<div id="head">
<div id="leftAlignMenu">
<ul class="tabMenu">
	<li style="margin-right:23px; padding-top:0; padding-bottom:0; padding-right:0">
		<img id="dashlnk" class="menulnk" style="width: 226px; height:48px;" src="<?= $serverRoot?>ui/style/kubu.png" onclick="showDash(this)"  title="Dashboard"/>
	</li>
	<li class="bluebox" style="background-image:url('<?= $serverRoot ?>ui/style/menu/ideate.png');">
		<a id="ideaslnk" class="menulnk" href="javascript:showIdeas(this)" title="Add and explore ideas">Ideate</a>
	</li>
	<li class="bluebox" style="background-image:url('<?= $serverRoot ?>ui/style/menu/compare.png');" >
		<a id="comparelnk"class="menulnk" href="javascript:showCompare(this)" title="Contrast ideas using structured criteria">Compare</a>
	</li>
	<li class="bluebox" style="background-image:url('<?= $serverRoot ?>ui/style/menu/select.png');">
	<a id="selectlnk" class="menulnk" href="javascript:showSelect(this)" title="Select and manage ideas for implementation">Select</a>
	</li>
	<li class="greenbox" style="background-image:url('<?= $serverRoot ?>ui/style/menu/group.png');">
		<a id="groupslnk" class="menulnk" href="javascript:showGroups(this)" title="Share and manage ideas with groups">Groups</a>
	</li>
	<li class="greenbox" style="background-image:url('<?= $serverRoot ?>ui/style/menu/profile.png');">
		<a id="profilelnk" class="menulnk" href="javascript:showProfile(this)" title="Manage your ideas and information and send notes">Profile</a>
	</li>
	<li class="greenbox" style="background-image:url('<?= $serverRoot ?>ui/style/menu/public.png');">
		<a id="publiclnk" class="menulnk" href="javascript:showPublic(this)" title="View public ideas and announcements">Public</a>
	</li>
	<li class="greybox" style="background-image:url('<?= $serverRoot ?>ui/style/menu/search.png');">
		<a id="searchlnk" class="menulnk" href="javascript:showSearch(this)" title="Find ideas, innovators and groups">Search</a>
	</li>
</ul>
</div>
<div id="rightAlignMenu">
<ul class="tabMenu">
	<li style="padding-top:0.2em; padding-bottom:0.025em;margin-right:0;">
	<table style="color:#FFF; text-align:right"><tr><td>
	<span style="color:#DDD; font-size:0.9em;"><?= getDisplayUsername($_SESSION['innoworks.ID']); ?></span>
	<br/>
	<div class="actions">
		<img src="<?= $serverRoot ?>ui/style/menu/add.png" style="width:22px; height:22px;" onClick="showIdeas(); showDefaultIdeas();" alt="Add" title="Add idea"/>
		<? if ($_SESSION['innoworks.isAdmin'] == 1) { ?>
		<img src="<?= $serverRoot ?>ui/style/menu/cog.png" onClick="openAdmin(this)" alt="Admin" title="Go to admin"/>
		<?}?>
		<img src="<?= $serverRoot ?>ui/style/menu/feedback.png" onClick="showFeedback(this)" alt="Feedback" title="Leave feedback and report bugs"/>
		<img src="<?= $serverRoot ?>ui/style/menu/help.png" onClick="showHelp(this)" alt="Feedback" title="Get help and info"/>
		<img src="<?= $serverRoot ?>ui/style/menu/logout.png" onClick="logout(this)" alt="Logout" title="Logout and end your session"/>
	</div>
	</td>
	<td>
		<img id="profileAvatar" src="retrieveImage.php?action=userImg&actionId=<?= $_SESSION['innoworks.ID'] ?>" title="<?= $_SESSION['innoworks.username']; ?>" style="width:2em;height:2em;"/>
	</td>
	</tr>
	</table>
	</li>
</ul>
</div>
</div>
</div>

<!-- PAGE CONTENT -->

<div id="content">
<div id="dashTab" class="tabBody"></div>
<? renderTemplate('ideate.tab'); ?>
<? renderTemplate('compare.tab'); ?>
<? renderTemplate('select.tab'); ?>
<div id="profileTab" class="tabBody"></div>
<? renderTemplate('group.tab'); ?>
<div id="publicTab" class="tabBody"></div>
<div id="searchTab" class="tabBody">
	<div class="fixed-left">
	</div>
	<div class="fixed-right" style="padding-top:0.3em;">
		<div id="searchResults"></div>
	</div>
</div>
<div id="timelineTab" class="tabBody"></div>
</div>

<div class="footer" style="clear:both; padding-top:3em;">

</div>

<!-- POPUP DIALOGS -->
<? renderTemplate('common.popups'); ?>

<!-- RESPONSES -->
<div class="respSurround" style="position:absolute; bottom:0px;">
<div id="ideaResponses" class="responses"></div>
</div>

</body>
</html>