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
<script type="text/javascript" src="<?= $serverUrl.$uiRoot?>scripts/jQuery-Min.js"></script>
<script type="text/javascript" src="<?= $serverUrl.$uiRoot?>scripts/base/dojo/dojo.js"></script>
<script type="text/javascript" src="<?= $serverUrl.$uiRoot?>scripts/base/InnoBigDialog.js"></script>
<script type="text/javascript" src="<?= $serverUrl.$uiRoot?>scripts/base/InnoDialog.js"></script>
<script type="text/javascript" src="<?= $serverUrl.$uiRoot?>scripts/base/dojoLayer.js"></script>
<script type="text/javascript" src="<?= $serverUrl.$uiRoot?>scripts/innoworks.js"></script>
<script type="text/javascript" src="<?= $serverUrl.$uiRoot?>scripts/common.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $serverUrl.$uiRoot?>scripts/base/dijit/themes/tundra/tundra.css" />
<link rel="stylesheet" type="text/css" href="<?= $serverUrl.$uiRoot?>scripts/base/dojox/layout/resources/ResizeHandle.css" />
<link rel="stylesheet" type="text/css" href="<?= $serverUrl.$uiRoot?>scripts/base/dojox/layout/resources/FloatingPane.css" />
<link href="<?= $serverUrl.$serverRoot?>ui/style/buttons.css" rel="stylesheet" type="text/css" />
<link href="<?= $serverUrl.$serverRoot?>ui/style/style.css" rel="stylesheet" type="text/css" />
<link href="<?= $serverUrl.$serverRoot?>ui/style/innoworks.css" rel="stylesheet" type="text/css" />

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
	/* 
	if (isMobile) {
		dojo.declare("dijit.form.Textarea",null,null);
	} 
	
	djConfig.modulePaths = {dojo:'',dijit:'',dojox:'',inno:''}
	*/
	
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
	<li style="padding:0;">
		<img id="dashlnk" class="menulnk" style="width: 226px; height:48px;" src="<?= $serverRoot?>ui/style/kubu.png" onclick="showDash(this)"  title="Dashboard"/>
	</li>
	<li style="padding:2px; padding-left:0; margin-right:10px; margin-left:-6px;">
		<img src="<?= $serverRoot ?>ui/style/menu/add.png" style="width:25px; height:25px; cursor:pointer;" onclick="showIdeas(); showDefaultIdeas();" alt="Add" title="Add idea"/>
	</li>		
	<li class="bluebox" style="background-image:url('<?= $serverRoot ?>ui/style/menu/ideate.png');">
		<a id="ideaslnk" class="menulnk" href="javascript:showIdeas(this)" title="Add and explore ideas">ideate</a>
	</li>
	<li class="bluebox" style="background-image:url('<?= $serverRoot ?>ui/style/menu/compare.png');" >
		<a id="comparelnk"class="menulnk" href="javascript:showCompare(this)" title="Contrast ideas using structured criteria">compare</a>
	</li>
	<li class="bluebox" style="background-image:url('<?= $serverRoot ?>ui/style/menu/select.png');">
	<a id="selectlnk" class="menulnk" href="javascript:showSelect(this)" title="Select and manage ideas for implementation">select</a>
	</li>
	<li class="greenbox" style="background-image:url('<?= $serverRoot ?>ui/style/menu/group.png');">
		<a id="groupslnk" class="menulnk" href="javascript:showGroups(this)" title="Share and manage ideas with groups">groups</a>
	</li>
	<li class="greenbox" style="background-image:url('<?= $serverRoot ?>ui/style/menu/profile.png');">
		<a id="profilelnk" class="menulnk" href="javascript:showProfile(this)" title="Manage your ideas and information and send notes">profile</a>
	</li>
	<!-- <li class="greenbox" style="background-image:url('<?= $serverRoot ?>ui/style/menu/public.png');">
		<a id="publiclnk" class="menulnk" href="javascript:showPublic(this)" title="View public ideas and announcements">public</a>
	</li>
	<li class="greybox" style="background-image:url('<?= $serverRoot ?>ui/style/menu/search.png');">
		<a id="searchlnk" class="menulnk" href="javascript:showSearch(this)" title="Find ideas, innovators and groups">search</a>
	</li> -->
</ul>
</div>
<div id="rightAlignMenu">
<ul class="tabMenu">
	<li style="padding-top:0.2em; padding-bottom:0.025em;margin-right:0;">
		<div title="<?= getDisplayUsername($_SESSION['innoworks.ID']); ?>">
			<span style="color:#DDD; font-size:0.9em;"><?= getDisplayFirstName($_SESSION['innoworks.ID']); ?></span>
			<img id="profileAvatar" src="retrieveImage.php?action=userImg&actionId=<?= $_SESSION['innoworks.ID'] ?>" title="<?= $_SESSION['innoworks.username']; ?>" style="width:1em;height:1em; vertical-align:middle; border: 1px solid #AAA; border-radius:2px; -moz-border-radius:2px;"/>
		</div>
		<div class="actions">
			<form class="quickSearch" onsubmit="showSearch(this); return false;" style="padding:0; margin:0; margin-right:5px; clear:none; float:left;">
				<input name="searchTerms" class="dijitTextBox" placeHolder="find ideas, people and groups"  style="padding:0; margin:0;"/>
			</form>
			<? if ($_SESSION['innoworks.isAdmin'] == 1) { ?>
			<img src="<?= $serverRoot ?>ui/style/menu/cog.png" onClick="openAdmin(this)" alt="Admin" title="Go to admin"/>
			<?}?>
			<img src="<?= $serverRoot ?>ui/style/menu/feedback.png" onClick="showFeedback(this)" alt="Feedback" title="Leave feedback and report bugs"/>
			<img src="<?= $serverRoot ?>ui/style/menu/help.png" onClick="showHelp(this)" alt="Feedback" title="Get help and info"/>
			<img src="<?= $serverRoot ?>ui/style/menu/logout.png" onClick="logout(this)" alt="Logout" title="Logout and end your session"/>
		</div>
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
	<div id="searchResults" style="padding-top:15px;"></div>
</div>
<div id="timelineTab" class="tabBody"></div>
</div>

<div id="footerSpace"></div>
<div id="footerSurround">
	<div id="footer" style="min-height:3em;">
		<div class="fixed-left">UTS server version</div>
		<div class="fixed-right">&copy; UTS 2011</div>
	</div>
</div>

<!-- POPUP DIALOGS -->
<? renderTemplate('common.popups'); ?>

<!-- RESPONSES -->
<div class="respSurround" style="position:absolute; bottom:0px;">
<div id="ideaResponses" class="responses"></div>
</div>

</body>
</html>