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
	<li style="margin-right:0.5em">
		<img id="dashlnk" class="menulnk" style="width: 226px; height: 48px;" src="<?= $serverRoot?>ui/style/kubu.png" onclick="showDash(this)"/>
	</li>
	
	<li class="selMenu">
	<div class="marker blue"></div>&nbsp;Ideas<br/>
	<a id="ideaslnk" class="menulnk" href="javascript:showIdeas(this)">Explore</a>
	<a id="comparelnk" class="menulnk" href="javascript:showCompare(this)">Compare</a>
	<a id="selectlnk" class="menulnk" href="javascript:showSelect(this)">Select</a>
	</li>
	
	<li>
	<div class="marker green"></div>&nbsp;Collaborate<br/>
	<a id="groupslnk" class="menulnk" href="javascript:showGroups(this)">Groups</a>
	<a id="profilelnk" class="menulnk" href="javascript:showProfile(this)">Profiles</a>
	<a id="noteslnk" class="menulnk" href="javascript:showNotes(this)">Notes</a>
	</li>
	
	<li>
	<div class="marker orange"></div>&nbsp;Tools<br/>
	<a id="searchlnk" class="menulnk" href="javascript:showSearch(this)">Search</a>
	<a id="timelinelnk" class="menulnk" href="javascript:showTimelines(this)">Timelines</a>
	<a id="reportslnk" class="menulnk" href="javascript:showReports(this)">Reports</a>
	</li>
	
</ul>
</div>
<div id="rightAlignMenu">
<ul class="tabMenu">
	<li style="padding-top:0.05em; ">
	<? if (getUserInfo($_SESSION['innoworks.ID'])->isAdmin == 1) { ?><a href="javascript:logAction()" onclick="openAdmin()" style="font-size:1.0em; color:#EEE">admin&gt;&gt;</a> <? } ?>
	<span style="font-size:0.9em; padding-right:0.3em;">
		<?= $_SESSION['innoworks.username']; ?>
	</span><br/>
	<a href="javascript:logout()">Logout</a><a href="javascript:showFeedback()">Feedback</a></li>
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
<div style="width:100%;">
	<div class="fixed-left">
		<h2 id="pgName">Explore ideas</h2>
	</div>
	<div class="fixed-right" style="padding-top:0.3em;">
		<form id="addIdeaForm" onsubmit="addIdea(this);return false;">
			<span>Add new idea</span> 
			<input id="addIdeaTitle" name="title" type="text" dojoType="dijit.form.TextBox"/> 
			<input type="button" value=" + " title="Add idea" onclick="addIdea(this)"/> 
			<input style="display:none" type="submit" /> 
			<input type="hidden" name="action" value="addIdea" />
		</form>
	</div>
</div> 
<div style="width:100%; clear:left">
	<div class="fixed-left bordRight">
		<span class="ideaGroupsList"></span>
	</div>
	<div class="fixed-right">
		<div id="ideasList"></div>
	</div>
</div>
</div>

<div id="compareTab" class="tabBody">
<div style="width:100%;">
	<div class="fixed-left">
		<h2 id="pgName">Compare ideas</h2>
	</div>
	<div class="fixed-right" style="padding-top:0.3em;">
	<form>
		Click here to add idea to comparison
		<input id="riskItemBtn" type='button' onclick='showAddRiskItem(this)' value=' + ' title="Add an idea to comparison" />
	</form>
	</div>
</div> 
<div style="width:100%; clear:left">
	<div class="fixed-left bordRight">
		<span class="ideaGroupsList"></span>
	</div>
	<div class="fixed-right">
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
<div style="width:100%;">
	<div class="fixed-left">
		<h2 id="pgName">Select ideas</h2>
	</div>
	<div class="fixed-right" style="padding-top:0.3em;">
		<form>
		Click here to select an idea 
		<input type='button' onclick='showAddSelectIdea(this)' value=' + ' title="Select an idea to work on" /></form>
	</div>
</div> 
<div style="width:100%; clear:left;">
	<div class="fixed-left bordRight">
		<span class="ideaGroupsList"></span>
	</div>
	<div class="fixed-right">
		<div id="selectList"></div>
	</div>
</div>
</div>

<div id="profileTab" class="tabBody"></div>

<div id="groupTab" class="tabBody">
<div style="width:100%;">
	<div class="fixed-left">
		<h2 id="pgName">Groups</h2>
	</div>
	<div class="fixed-right" style="padding-top:0.3em;">
		<form id="addGroupForm" onsubmit="addGroup(); return false;">
		<span>Create new group</span> 
		<input name="title" type="text" dojoType="dijit.form.TextBox"/> 
		<input type="submit" value=" + " title="Create a group" /> 
		<input type="hidden" name="action" value="addGroup" /></form>
	</div>
</div> 
<div style="width:100%;">
	<div class="fixed-left bordRight">
		<div id="groupsList" style="padding-right: 5px;">&nbsp;</div>
	</div>
	<div class="fixed-right">
		<div id="groupDetailsCont">
			<div id="groupDetails"><b>Select one of the groups on the left to see its details</b></div>
		</div>
	</div>
</div>
</div>

<div id="noteTab" class="tabBody"></div>

<div id="searchTab" class="tabBody">
	<div class="fixed-left">
		<h2 id="pgName">Search</h2>
	</div>
	<div class="fixed-right" style="padding-top:0.3em;">
		<div id="searchResults"></div>
	</div>
</div>

<div id="timelineTab" class="tabBody"></div>

<div id="reportTab" class="tabBody">
	<div class="fixed-left">
		<h2 id="pgName">Reports</h2>
	</div>
	<div class="fixed-right">
		<div id="reportDetails">Loading reports...</div>
	</div>
</div>

<!-- POPUP DIALOGS -->
<div id="ideasPopup" dojoType="dijit.Dialog">
<div id="ideaPopupResponses" class="responses"></div>
<span class="ideaDetailsOptions" style="position:relative; float:right;">
<a href="javascript:printPopupIdea()">Print</a>
</span>
<table><tr>
<td><img id="popupIdeaImgMain" style="height: 3em; width: 3em;" src=""/></td>
<td style="vertical-align:middle;"><span id="ideaName"></span></td></tr>
</table>

<div id="ideasPopupTabContainer" dojoType="dijit.layout.TabContainer" style="width: 55em; height: 25em;">
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

<div id="commonPopup" dojoType="dijit.Dialog" style="width: 18em; height: 400px;">
	<div id="actionDetails"></div>
</div>

</div>

</body>
</html>
