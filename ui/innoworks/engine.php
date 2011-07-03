<?
/**
 * Main view for all logged in innoworks users.
 */
require_once("thinConnector.php");
import("mobile.functions");
import("user.service");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<!-- <link rel="stylesheet" type="text/css" href="<?= $serverUrl.$uiRoot?>scripts/base/dijit/themes/soria/soria.css" />-->
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
	
	* .threecol {
		margin-right:1.5%; 
		width:23%; 
		position:relative; 
		float:left; 
		overflow:auto; 
	}
	
	* .threecol:last-child {
		margin-right:0;
	}
	
	#timelineTab table, #compareTab table, div.fixed-right #reportDetails  {
		width:750px;
	}
	
	</style>
<![endif]-->
</head>

<body class="tundra">

<!-- HEADER BAR -->
<div id="headSurround">
<div id="head">
<div class="menu clearfix" style="width:100%; text-align:right;">
	<div class="menuHolder clearfix">
		<a href="javascript:showProfile()" style="color:#FFF; font-size:0.9em;"><img src="retrieveImage.php?user=<?= $_SESSION['innoworks.ID']?>" style="width:1em; height:1em; vertical-align:middle"/><?= getDisplayUsername($_SESSION['innoworks.ID']); ?></a>
		<? if ($_SESSION['innoworks.isAdmin'] == 1) { ?>
		<img src="<?= $serverRoot ?>ui/style/menu/cog.png" onClick="openAdmin(this)" alt="Admin" title="Go to admin"/>
		<?}?>
		<img src="<?= $serverRoot ?>ui/style/menu/feedback.png" onClick="showFeedback(this)" alt="Feedback" title="Leave feedback and report bugs"/>
		<img src="<?= $serverRoot ?>ui/style/menu/help.png" onClick="showHelp(this)" alt="Feedback" title="Get help and info"/>
		<img src="<?= $serverRoot ?>ui/style/menu/logout.png" onClick="logout(this)" alt="Logout" title="Logout and end your session"/>
	</div>
</div>

<div style="width:100%; text-align:right;">
<div id="leftAlignMenu" style="clear:left;">
<ul class="tabMenu">
	<li style="padding:0;">
		<img id="dashlnk" class="menulnk" style="width: 211px; height:45px;" src="<?= $serverRoot?>ui/style/kubu.png" onclick="showDash(this)"  title="Dashboard"/>
	</li>
	<li style="padding:2px; padding-left:0; margin-right:21px; margin-left:-6px;">
		<img class="adder" src="<?= $serverRoot ?>ui/style/menu/add.png" style="width:25px; height:25px; cursor:pointer;" onclick="showCreateIdea(this)" alt="Add" title="Add idea"/>
	</li>	
	<li class="bluebox" style="background-image:url('<?= $serverRoot ?>ui/style/menu/ideate.png');">
		<a id="ideaslnk" class="menulnk" href="javascript:showInnovate(this)" title="Add, explore, compare and manage ideas">ideas</a>
	</li>
	<!-- 
	<li class="bluebox" style="background-image:url('<?= $serverRoot ?>ui/style/menu/ideate.png');">
		<a id="ideaslnk" class="menulnk" href="javascript:showIdeas(this)" title="Add and explore ideas">ideate</a>
	</li>
	<li class="bluebox" style="background-image:url('<?= $serverRoot ?>ui/style/menu/compare.png');" >
		<a id="comparelnk"class="menulnk" href="javascript:showCompare(this)" title="Contrast ideas using structured criteria">compare</a>
	</li>
	<li class="bluebox" style="background-image:url('<?= $serverRoot ?>ui/style/menu/select.png');">
	<a id="selectlnk" class="menulnk" href="javascript:showSelect(this)" title="Select and manage ideas for implementation">select</a>
	</li>
	-->
	<li class="greenbox" style="background-image:url('<?= $serverRoot ?>ui/style/menu/group.png');">
		<a id="groupslnk" class="menulnk" href="javascript:showGroups(this)" title="Share and manage ideas with groups">groups</a>
	</li>
	<li class="greenbox" style="background-image:url('<?= $serverRoot ?>ui/style/menu/profile.png');">
		<a id="profilelnk" class="menulnk" href="javascript:showProfile(this)" title="Manage your ideas and information and send notes">profile</a>
	</li>
</ul>
</div>
<div id="rightAlignMenu" style="float:right; padding-top:7px;">
	<form class="quickSearch" onsubmit="showSearch(this); return false;" style="padding:0; margin:0; clear:none;">
		<div style="padding-top:3px; padding-bottom:3px; width:240px; border:1px solid #516070; background-color:#FFF; background-image:url('../style/search.png'); background-position:left top; background-repeat:no-repeat">
			<input name="searchTerms" placeHolder="find ideas and more" style="padding:0; margin:0; background-color:transparent; color:#000; border:none; font-size:1.2em"/>
		</div>
	</form>
</div>
</div>
</div>
</div>

<!-- PAGE CONTENT -->
<div id="content">
<div id="dashTab" class="tabBody"></div>
<div id="ideaMega" class="tabBody">
	<div id="innovateHead" class="fixed-left innovateSub" style="border-bottom:1px solid #7FBF4D; position:relative;"> 
		<div class="tiny">your</div>
		<h1 onclick="showInnovate(this)">
			innovation
		</h1>
		<div class="tiny" style="position:absolute; bottom:5px; right:35px;">process</div>
	</div>
	<div class="fixed-right">
		<div class="threeColumnContainer">
			<div id="ideaHead" onclick="showIdeas(this)" class="threecol innovateSub bluebox" style="border-width:0; border-bottom-width:1px; ">
				<div class="tiny">stage 1</div>
				<h1>ideate</h1>
			</div>
			<div id="compareHead" onclick="showCompare(this)" class="threecol innovateSub greenbox" style="border-width:0; border-bottom-width:1px; ">
				<div class="tiny">stage 2</div>
				<h1>compare</h1>
			</div>
			<div id="selectHead" onclick="showSelect(this)" class="threecol innovateSub orangebox" style="border-width:0; border-bottom-width:1px; ">
				<div class="tiny">stage 3</div>
				<h1>select</h1>
			</div>
		</div>
	</div>
	<div class="clearer"  style="height:1.5em; clear:both"></div>
	<div id="innovateTab" class="tabBody"></div>
	<? renderTemplate('ideate.tab'); ?>
	<? renderTemplate('compare.tab'); ?>
	<? renderTemplate('select.tab'); ?>
</div>
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
		<? renderTemplate('common.footer')?>
	</div>
</div>

<!-- POPUP DIALOGS -->
<? renderTemplate('common.popups'); ?>

<!-- RESPONSES -->
<div class="respSurround" style="position:absolute; bottom:0px;">
<div id="ideaResponses" class="responses" onclick="$(this).parent().hide('slow')"></div>
</div>

</body>
</html>