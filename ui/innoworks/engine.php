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
<!-- <script type="text/javascript" src="<?= $uiRoot?>scripts/opt/dojo/innoworks/innodojo.js"></script>-->
<script type="text/javascript" src="<?= $uiRoot?>scripts/dojoLayer.js"></script>
<script type="text/javascript" src="<?= $uiRoot?>scripts/innoworks.js"></script>
<script type="text/javascript" src="<?= $uiRoot?>scripts/common.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $uiRoot?>scripts/base/dijit/themes/tundra/tundra.css" />
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
	//AJAX methods and setup
	$.ajaxSetup ({  
		cache: false  
	});  
	/*
	$(document).ajaxStop(function() {
		processQueuedActions();
	});
	*/
	
	//Show default
	showDash();

	//Start server polling
	pollServer();
	setInterval(pollServer, 15000);
});

dojo.addOnLoad(function(){	
	if (isMobile) {
		dojo.declare("dijit.form.Textarea",null,null);
	}
	
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
	<li class="bluebox">
		<a id="ideaslnk" class="menulnk" href="javascript:showIdeas(this)" title="Add and explore ideas">Ideate</a>
	</li>
	<li class="bluebox">
		<a id="comparelnk" class="menulnk" href="javascript:showCompare(this)" title="Contrast ideas using structured criteria">Compare</a>
	</li>
	<li class="bluebox">
	<a id="selectlnk" class="menulnk" href="javascript:showSelect(this)" title="Select and manage ideas for implementation">Select</a>
	</li>
	<li class="greenbox">
		<a id="groupslnk" class="menulnk" href="javascript:showGroups(this)" title="Share ideas with groups">Groups</a>
	</li>
	<li class="greenbox">
		<a id="profilelnk" class="menulnk" href="javascript:showProfile(this)" title="Manage your information">Profiles</a>
	</li>
	<li class="greenbox">
		<a id="noteslnk" class="menulnk" href="javascript:showNotes(this)" title="Review and send private messages">Notes</a>
	</li>
	<li class="greenbox">
		<a id="searchlnk" class="menulnk" href="javascript:showSearch(this)" title="Find ideas, innovators and groups">Search</a>
	</li>
</ul>
</div>
<div id="rightAlignMenu">
<ul class="tabMenu">
	<li class="greybox" style="padding-top:0.25em; padding-bottom:0.25em;">
	<table style="color:#FFF; text-align:right"><tr><td>
	<span style="color:#DDD; font-size:0.85em;"><?= getDisplayUsername($_SESSION['innoworks.ID']); ?></span>
	<br/>
	<div dojoType="dijit.form.DropDownButton" title="More options">
    	<span>
        	Actions
   		</span>
    	<div dojoType="dijit.Menu">
 			<div dojoType="dijit.MenuItem" iconClass="dijitEditorIcon dijitEditorIconViewSource"
   			 onClick="showIdeas(); showDefaultIdeas();">
       			Add idea
    		</div>
    		<? if ($_SESSION['innoworks.isAdmin'] == 1) { ?><div dojoType="dijit.MenuItem" iconClass="dijitEditorIcon dijitEditorIconViewSource"
    		onClick="openAdmin()">
        		Go to admin
    		</div><?}?>
    		<div dojoType="dijit.MenuItem" iconClass="dijitEditorIcon dijitEditorIconViewSource"
    		onClick="showFeedback()">
        		Leave feedback
    		</div>
    		<div dojoType="dijit.MenuSeparator">
    		</div>
  			<div dojoType="dijit.MenuItem" iconClass="dijitEditorIcon dijitEditorIconViewSource"
    		onClick="logout()">
        		Logout
    		</div>
		</div>
	</div>
	</td><td>
	<!-- 
	<? if ($_SESSION['innoworks.isAdmin'] == 1) { ?><a href="javascript:logAction()" onclick="openAdmin()" style="font-size:1.0em; color:#EEE">admin&gt;&gt;</a> <? } ?>
	 -->
	<img id="profileAvatar" src="retrieveImage.php?action=userImg&actionId=<?= $_SESSION['innoworks.ID'] ?>" title="<?= $_SESSION['innoworks.username']; ?>" style="width:2em;height:2em;"/>
	<!-- <span style="font-size:0.9em; padding-right:0.3em;">
		<?= $_SESSION['innoworks.username']; ?>
	</span> -->
	<!-- <br/>
	<a href="javascript:logout()">Logout</a>
	<a href="javascript:showFeedback()">Feedback</a>-->
	</td></tr></table></li>
</ul>
</div>
</div>
</div>

<!-- PAGE CONTENT -->
<div class="respSurround">
<div id="ideaResponses" class="responses"></div>
</div>

<div id="content">

<div id="dashTab" class="tabBody"></div>

<div id="ideaTab" class="tabBody">
<div style="width:100%; clear:left">
	<div class="fixed-left">
		<div class='itemHolder groupPreview'></div>
		<div class="ideaGroupsList"></div>
	</div>
	<div class="fixed-right">
		<form id="addIdeaForm" class="addForm" onsubmit="addIdea(this);return false;">
			<span>Add new idea</span> 
			<input id="addIdeaTitle" name="title" type="text" dojoType="dijit.form.TextBox"/> 
			<input type="button" value=" + " title="Add idea" onclick="addIdea(this)"/> 
			<input style="display:none" type="submit" /> 
			<input type="hidden" name="action" value="addIdea" />
		</form>
		<div id="ideasList"></div>
	</div>
</div>
</div>

<div id="compareTab" class="tabBody">
<div style="width:100%; clear:left">
	<div class="fixed-left">
		<div class='itemHolder groupPreview'></div>
		<div class="ideaGroupsList"></div>
	</div>
	<div class="fixed-right">
		<form class="addForm">
			Click here to add idea to comparison
			<input id="riskItemBtn" type='button' onclick='showAddRiskItem(this)' value=' + ' title="Add an idea to comparison" />
		</form>
		<div id="compareList"></div>
		<div id="compareComments" style="margin-top:1em;">
			<form id="addCompareCommentForm" class="smallAddForm" onsubmit="addCompareComment();return false;">
				<input type="hidden" name="action" value="addComment" />
				Comments <input type="submit" value=" + " /> 
				<textarea name="text" dojoType="dijit.form.Textarea" style="width: 100%"></textarea> 
			</form>
			<div id="compareCommentList">No comments yet</div>
		</div>
	</div>
</div>
</div>

<div id="selectTab" class="tabBody">
<div style="width:100%; clear:left;">
	<div class="fixed-left">
		<div class='itemHolder groupPreview'></div>
		<div class="ideaGroupsList"></div>
	</div>
	<div class="fixed-right">
		<form class="addForm">
		Click here to select an idea 
		<input type='button' onclick='showAddSelectIdea(this)' value=' + ' title="Select an idea to work on" /></form>
		<div id="selectList"></div>
	</div>
</div>
</div>

<div id="profileTab" class="tabBody"></div>

<div id="groupTab" class="tabBody">
<div style="width:100%;">
	<div class="fixed-left">
		<div class='itemHolder headBorder groupPreview'></div>
		<div id="groupsList">&nbsp;</div>
	</div>
	<div class="fixed-right">
	<form id="addGroupForm" class="addForm" onsubmit="addGroup(); return false;">
		<span>Create new group</span> 
		<input name="title" type="text" dojoType="dijit.form.TextBox"/> 
		<input type="submit" value=" + " title="Create a group" /> 
	<input type="hidden" name="action" value="addGroup" /></form>
		<div id="groupDetailsCont">
			<div id="groupDetails"><b>Select one of the groups on the left to see its details</b></div>
		</div>
	</div>
</div>
</div>

<div id="noteTab" class="tabBody"></div>

<div id="searchTab" class="tabBody">
	<div class="fixed-left">
	</div>
	<div class="fixed-right" style="padding-top:0.3em;">
		<div id="searchResults"></div>
	</div>
</div>

<div id="timelineTab" class="tabBody"></div>

<div id="reportTab" class="tabBody">
	<div class="fixed-left">
		<h2 class="pgName">Reports</h2>
	</div>
	<div class="fixed-right">
		<div id="reportDetails">Loading reports...</div>
	</div>
</div>

<!-- POPUP DIALOGS -->
<div id="ideasPopup" dojoType="dijit.Dialog" title="Edit idea">
<div id="ideaPopupResponses" class="responses"></div>
<span class="ideaDetailsOptions" style="position:relative; float:right;">
<a href="javascript:printPopupIdea()">Print</a>
</span>
<table>
<tr>
<td style="vertical-align:top;"><img id="popupIdeaImgMain" style="height: 2em; width: 2em;"/></td>
<td style="vertical-align:top;"><span id="ideaName"></span></td>
</tr>
</table>

<div id="ideasPopupTabContainer" dojoType="dijit.layout.TabContainer" style="width: 55em; height: 28em;">
	<div id="ideasPopupDetails" dojoType="dijit.layout.TabContainer" title="Details" nested="true" iconClass="dijitEditorIcon dijitEditorIconSelectAll">
		<div id="ideaMission" dojoType="dijit.layout.ContentPane" title="Mission" iconClass="dijitEditorIcon dijitEditorIconViewSource"></div>
		<div id="ideaFeatures" dojoType="dijit.layout.ContentPane" title="Features" iconClass="dijitEditorIcon dijitEditorIconViewSource"></div>
		<div id="ideaRoles" dojoType="dijit.layout.ContentPane" title="Roles" iconClass="dijitEditorIcon dijitEditorIconViewSource" ></div>
		<div id="ideaAttachments" dojoType="dijit.layout.ContentPane" title="Attachments" iconClass="dijitEditorIcon dijitEditorIconInsertImage"></div>
	</div>
	<div id="ideasPopupReview" dojoType="dijit.layout.TabContainer" title="Review" nested="true" iconClass="dijitEditorIcon dijitEditorIconSelectAll">
		<div id="ideaComments" dojoType="dijit.layout.ContentPane" title="Comments" iconClass="dijitEditorIcon dijitEditorIconWikiword">
			<div id="addComment">
			<form id="addCommentForm" class="addForm ui-corner-all" onsubmit="addComment();return false;">
				New Comment <input type="submit" value=" + " /> 
				<textarea name="text" dojoType="dijit.form.Textarea" style="width: 100%;"></textarea> 
				<input type="hidden" name="action" value="addComment" />
			</form>
			</div>
			<div id="commentList">No comments yet</div>
		</div>
		<div id="ideaFeatureEvaluationList" dojoType="dijit.layout.ContentPane" title="Feature Evaluation" iconClass="dijitEditorIcon dijitEditorIconInsertTable"></div>
		<div id="ideaRiskEval" dojoType="dijit.layout.ContentPane" title="Risk Evaluation" iconClass="dijitEditorIcon dijitEditorIconInsertTable"></div>
	</div>
	<div id="ideaSelect" dojoType="dijit.layout.ContentPane" title="Select" iconClass="dijitEditorIcon dijitEditorIconSelectAll"></div>
	<div id="ideaShare" dojoType="dijit.layout.ContentPane" title="Share" iconClass="dijitEditorIcon dijitEditorIconCopy"></div>
</div>
</div>

<div id="commonPopup" dojoType="dijit.Dialog" style="width: 18em; height: 20em;">
	<div id="actionDetails"></div>
</div>

</div>

</body>
</html>
