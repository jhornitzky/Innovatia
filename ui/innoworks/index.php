<?
/**
 * Main view for all logged in innowroks users.
 */
require_once("thinConnector.php");
requireLogin();
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="<?= $serverUrl.$serverRoot?>ui/innoworks/" />
<title>innoWorks</title>
<script type="text/javascript"
	src="<?= $serverRoot?>ui/scripts/jQuery-Min.js"></script>
<script type="text/javascript"
	src="<?= $serverRoot?>ui/scripts/dojo/dojo.js"></script>
<script type="text/javascript"
	src="<?= $serverRoot?>ui/scripts/innoworks.js"></script>
<link href="<?= $serverRoot?>ui/scripts/cssjQuery.css" rel="stylesheet"
	type="text/css" />
<link rel="stylesheet" type="text/css"
	href="<?= $serverRoot?>ui/scripts/dijit/themes/tundra/tundra.css" />
<link href="<?= $serverRoot?>ui/style/style.css" rel="stylesheet"
	type="text/css" />
<link href="<?= $serverRoot?>ui/style/innoworks.css" rel="stylesheet"
	type="text/css" />

<script type="text/javascript">
//////// VARS //////////
var serverRoot = '<?=$serverRoot?>';
var ctime;
var currentIdeaId;
var currentIdeaName;
var currentGroupId;
var currentGroupName;
var formArray; // Temp holder for form value functions
var targets = {"ideas": "ideas.ajax.php",  "groups": "groups.ajax.php",  
		"compare": "compare.ajax.php", "reports": "reports.ajax.php"};
var selectedChild;

/////// START UP ///////
dojo.require("dijit.Dialog");
dojo.require("dijit.form.Button");
dojo.require("dijit.form.ComboBox");
dojo.require("dijit.form.Textarea");
dojo.require("dijit.layout.TabContainer");
dojo.require("dijit.layout.ContentPane");
dojo.require("dijit.Menu");
dojo.require("dojo.parser");

$(document).ready(function() {
	//Loading animation for all ajax operations
	/*
	$("body").ajaxStart(function() {
		$("#logo").attr("src",'<?= $serverRoot?>ui/style/ajaxLoader.gif');		
	});
	$("body").ajaxStop(function() {
		$("#logo").attr("src",'<?= $serverRoot?>ui/style/kubu.png');
	});
	*/
	
	//Show default
	showDash();
});

dojo.addOnLoad(function(){
	//Parse controls
	//dojo.parser.instantiate(dojo.query("#ideasPopup *")); 
	//dojo.parser.instantiate(dojo.query("#commonPopup *")); 
	//dojo.parser.instantiate(dojo.query("#morelnk *")); 
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
	

	//Start server polling
	setInterval(pollServer, 5000);
});

function subscribeForChild(child) {
	if (child.id == "ideaComments") {
		getCommentsForIdea();
		selectedChild = "comments";
	}
	else if (child.id == "ideaFeatureEvaluationList") {
		getFeatureEvaluationsForIdea();
		selectedChild = "featureEvaulation";
	}
	else if (child.id == "ideaMission") {
		getMission("ideaMission",currentIdeaId);
		selectedChild = "mission";
	}
	else if (child.id == "ideaFeatures") {
		getFeaturesForm("ideaFeatures",currentIdeaId);
		selectedChild = "features";
	}
	else if (child.id == "ideaRoles") {
		getRolesForm("ideaRoles",currentIdeaId);
		selectedChild = "roles";
	} 
	else if (child.id == "ideaAttachments") {
		getAttachments("ideaAttachments",currentIdeaId);
		selectedChild = "attachments";
	} 
	else if (child.id == "ideaShare") {
		getShareForIdea();
		selectedChild = "share";
	} 
	else if (child.id == "ideaSelect") {
		getSelectForIdea();
		selectedChild = "select";
	} 
	else if (child.id == "ideaRiskEval") {
		getRiskEvalForIdea("ideaRisks",currentIdeaId);
		selectedChild = "riskEval";
	} 
}

function printIdea() {
	  newWin = window.open("compare.ajax.php?action=getIdeaSummary&actionId=" + currentIdeaId);
	  //newWin.document.write($('#ideasPopup').html());
	  newWin.print();
	  //newWin.close();
}
</script>

</head>
<body class="tundra">

<div id="head">
<div id="leftAlignMenu">
<ul class="tabMenu">
	<li style="height: 3.0em; width: 3.0em; margin-right:0.7em">
		<img id="logo" style="width:100%;height:100%" src="<?= $serverRoot?>ui/style/kubu.png" />
	</li>
	
	<li class="selMenu">
	<img style="height: 1.5em; width: 1.5em;" src="<?= $serverRoot?>ui/style/innovate.png"/>Ideas<br/>
	<a id="dashlnk" class="menulnk" href="javascript:showDash(this)">Dash</a>
	<a id="ideaslnk" class="menulnk" href="javascript:showIdeas(this)">Explore</a>
	<a id="comparelnk" class="menulnk"
		href="javascript:showCompare(this)">Compare</a>
	<a id="selectlnk" class="menulnk"
		href="javascript:showSelect(this)">Select</a>
	</li>
	
	<li>
	<img style="height: 1.5em; width: 1.5em;" src="<?= $serverRoot?>ui/style/collab.png"/>Collaborate<br/>
	<a id="groupslnk" class="menulnk" href="javascript:showGroups(this)">Groups</a>
	<a id="profilelnk" class="menulnk" href="javascript:showProfile(this)">Profiles</a>
	<a id="noteslnk" class="menulnk"
		href="javascript:showNotes(this)">Notes</a>
	</li>
	
	<li>
	<img style="height: 1.5em; width: 1.5em;" src="<?= $serverRoot?>ui/style/tools.png"/>Tools<br/>
	<a id="searchlnk" class="menulnk" href="javascript:showSearch(this)">Search</a>
	<a id="timelinelnk" class="menulnk" href="javascript:showTimelines(this)">Timelines</a>
	<!-- <a id="adminlnk" class="menulnk"
		href="javascript:showAdmin(this)">Admin</a> -->
	<a id="reportslnk" class="menulnk"
		href="javascript:showReports(this)">Reports</a>
	</li>
	
</ul>
</div>

<div id="rightAlignMenu">
<ul class="tabMenu">
	<li><span style="font-size:0.925em;opacity:0.8em;"><?= $_SESSION['innoworks.username']; ?> | Innoworks </span><br/>
	<a href="javascript:logout()">Logout</a><a href="javascript:showFeedback()">Feedback</a></li>
</ul>
</div>
</div>

<div id="content">
<div id="ideaResponses" class="responses ui-corner-all"></div>

<div id="dashTab" class="tabBody"></div>

<div id="ideaTab" class="tabBody">
<div id="ideaTabHead" class=" addForm ui-corner-all">
<h2>Explore <span class="ideaGroupsList"></span> ideas</h2>
<form id="addIdeaForm" onsubmit="addIdea(); return false;">Add new idea <input id="addIdeaTitle" name="title" type="text"></input> <input
	type="submit" value=" + " title="Add idea" /> <input type="hidden"
	name="action" value="addIdea" /></form>

<!-- <div class="rightBox">Search ideas on this page <input type="text"
	value="Show" onclick="$(this).val('')" onkeyup="filterIdeas(this)"
	onchange="filterIdeas(this)" /> </div>-->
</div>

<div id="ideasList"></div>
</div>

<div id="compareTab" class="tabBody"><!-- <h2>R-W-W</h2>
	<p>The R-W-W method phrases key questions around the risks involved with each idea, allowing you to select and rank which ideas you feel are best.</p> -->
<div class="addform ui-corner-all">
<h2>Compare <span class="ideaGroupsList">My</span> ideas</h2>
Click here to add idea to comparison
<input type='button' onclick='showAddRiskItem()' value=' + '
	title="Add an idea to comparison" />
<!-- <div class="ideaGroups ui-corner-all"><a
	href="javascript:showDefaultIdeas()">My Ideas</a> Groups <span
	class="ideaGroupsList">None</span></div>-->
</div>
<div id="compareList">
</div>
</div>

<div id="selectTab" class="tabBody">
<div class="addform ui-corner-all">
<h2>Select <span class="ideaGroupsList">My</span> ideas</h2> 
Click here to select one of your own ideas for this group <input type='button' onclick='showAddSelectIdea()' value=' + ' title="Select an idea to work on" />
</div>
<div id="selectList">
</div>
</div>

<!-- MORE TABS -->
<div id="profileTab" class="tabBody"></div>

<div id="groupTab" class="tabBody">
<div id="groupSelect" class="two-column">
<div class="formHeadContain" style="width: 100%">
<form id="addGroupForm" class="addForm ui-corner-all"
	onsubmit="addGroup(); return false;"><span>New Group</span> <input
	name="title" type="text" /> <input type="submit" value=" + "
	title="Create a group" /> <input type="hidden" name="action"
	value="addGroup" /></form>
</div>
<div id="groupsList" style="padding: 10px; margin-top: 1em"></div>
</div>
<div id="groupDetails" class="two-column ui-corner-all"
	style="padding: 10px; border: 1px solid #000000"> << Select one of the groups on the left to see its details</div>
</div>

<div id="noteTab" class="tabBody"></div>

<div id="searchTab" class="tabBody"></div>

<div id="timelineTab" class="tabBody"></div>

<div id="reportTab" class="tabBody">
<div id="reportDetails" class="two-column ui-corner-all"
	style="padding: 10px">Loading reports...</div>
<div id="reportList" class="two-column" style="padding: 10px"></div>
</div>

<div id="adminTab" class="tabBody"></div>

<!-- POPUP DIALOGS -->
<div id="ideasPopup" dojoType="dijit.Dialog">
<span class="ideaOptions" style="position:relative; float:right;">
<a href="javascript:printIdea()">Print</a> </span>
<table><tr>
<td><img style="height: 3em; width: 3em;" src="<?= $serverRoot?>ui/style/innovate.png"/></td>
<td style="vertical-align:middle;"><h2><span id="ideaName"></span></h2></td></tr>
</table>
<div id="ideasPopupTabContainer" dojoType="dijit.layout.TabContainer"
	style="width: 55em; height: 25em;">
	<div id="ideasPopupDetails" dojoType="dijit.layout.TabContainer" title="Details" nested="true">
		<div id="ideaMission" dojoType="dijit.layout.ContentPane" title="Mission"></div>
		<div id="ideaFeatures" dojoType="dijit.layout.ContentPane" title="Features"></div>
		<div id="ideaRoles" dojoType="dijit.layout.ContentPane" title="Roles"></div>
		<div id="ideaAttachments" dojoType="dijit.layout.ContentPane" title="Attachments"></div>
	</div>
	<div id="ideasPopupReview" dojoType="dijit.layout.TabContainer" title="Review" nested="true">
		<div id="ideaComments" dojoType="dijit.layout.ContentPane" title="Comments">
			<div id="addComment">
			<form id="addCommentForm" class="addForm ui-corner-all"
				onsubmit="addComment();return false;">New Comment <input type="submit"
				value=" + " /> <!-- <input type="text" name="text" style="width:100%"/> -->
			<textarea id="textarea2" name="text" dojoType="dijit.form.Textarea"
				style="width: 100%;"></textarea> <input type="hidden" name="action"
				value="addComment" /></form>
			</div>
			<div id="commentList">No comments yet</div>
		</div>
		<div id="ideaFeatureEvaluationList" dojoType="dijit.layout.ContentPane"
			title="Feature Evaluation"></div>
		<div id="ideaRiskEval" dojoType="dijit.layout.ContentPane"
			title="Risk Evaluation"></div>
	</div>
	
	<div id="ideaSelect" dojoType="dijit.layout.ContentPane"
		title="Select"></div>
	
	<div id="ideaShare" dojoType="dijit.layout.ContentPane"
		title="Share"></div>
	
</div>
</div>

<div id="commonPopup" dojoType="dijit.Dialog" style="width: 15em; height: 300px;">
<div id="actionDetails" dojoType="dijit.layout.ContentPane"></div>
</div>

</div>

</body>
</html>
