<? 
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
	src="<?= $serverRoot?>ui/scripts/jQuery_UI-Min.js"></script>
<script type="text/javascript"
	src="<?= $serverRoot?>ui/scripts/dojo/dojo.js" djConfig="parseOnLoad: true"></script>

<link href="<?= $serverRoot?>ui/scripts/cssjQuery.css" rel="stylesheet"
	type="text/css">
<link rel="stylesheet" type="text/css" href="<?= $serverRoot?>ui/scripts/dijit/themes/tundra/tundra.css"
        />
<link href="<?= $serverRoot?>ui/style/style.css" rel="stylesheet"
	type="text/css">
	
<script type="text/javascript">
//////// VARS //////////
var currentIdeaId;
var currentGroupId;
var targets = {"ideas": "ideas.ajax.php",  "groups": "groups.ajax.php",  
		"compare": "compare.ajax.php", "reports": "reports.ajax.php"};

/////// START UP ///////
dojo.require("dijit.Dialog");
dojo.require("dijit.form.Button");
dojo.require("dijit.layout.TabContainer");
dojo.require("dijit.layout.ContentPane");

$(document).ready(function() {
	//Loading animation for all ajax operations
	$("body").ajaxStart(function() {
		$("#logo").hide();		
		$("#ajaxLoader").show();
	});
	$("body").ajaxStop(function() {
		$("#ajaxLoader").hide();
		$("#logo").show();		
	});
	showIdeas();
	showIdeaGroupsForUser();
});

//////////// MENU ///////////

function showIdeaGroupsForUser() {
	$.get("ideas.ajax.php?action=getIdeaGroupsForUser", function (data) {
		$("#ideaGroupsList").html(data);
	});
}

function showDefaultIdeas() {
	currentGroupId = null;
	getIdeas();
}

function getIdeas() {
	if (currentGroupId == null) {
		$.get("ideas.ajax.php?action=getIdeas", function (data) {
			$("#ideasList").html(data);
		});
	} else { 
		$.get("ideas.ajax.php?action=getIdeasForGroup&groupId="+currentGroupId, function (data) {
			$("#ideasList").html(data);
		});
	}
	showIdeaGroupsForUser();
}

function getReports() {
	$.get("reports.ajax.php?action=getReportDetails", function (data) {
		$("#reportDetails").html(data);
		$.get("reports.ajax.php?action=getReportGraphs", function (data) {
			$("#reportList").html(data);
		});
	});
}

function getGroups() {
	$.get("groups.ajax.php?action=getGroups", function (data) {
		$("#groupsList").html(data);
		showIdeaGroupsForUser();
	});
}

function getCompare() {
	$.get("compare.ajax.php?action=getComparison", function (data) {
		$("#compareList").html(data);
	});
}

function showIdeasForGroup(gId) {
	currentGroupId = gId;
	showIdeas();
}

function showIdeas() {
	$(".tabBody").hide();
	$("#ideaTab").show();
	getIdeas();
}

function showReports() {
	$(".tabBody").hide();
	$("#reportTab").show();
	getReports();
}	

function showGroups() {
	$(".tabBody").hide();
	$("#groupTab").show();
	getGroups();
}

function showCompare() {
	$(".tabBody").hide();
	$("#compareTab").show();
	getCompare();
}

/////// COMMON FUNCTIONS ///////

function showDetails(id) {
	$("#" + id).toggle();
}

function logout() {
	window.location.href = "<?=$serverRoot?>?logOut=1";
}

function showResponses(selector, data, timeout) {
	$(selector).html(data);
	$(selector).show();
	if (timeout) {
		window.setTimeout('hideResponses("'+selector+'")', 5000);
	} 
}

function hideResponses(selector) {
	$(selector).empty();
	$(selector).hide();
}

function genericAdd(selector) {
	$.post("ideas.ajax.php", $("#"+selector).serialize(), function(data) {
		showResponses("#ideaResponses", data, true);
		getIdeas();
	});	
}

function genericDelete(target, id) {
	$.post("ideas.ajax.php", {actionId:id, action:target}, function(data) {
		showResponses("#ideaResponses", data, true);
		getIdeas();
	});		
}

function genericFormUpdate(target, element) {}

function genericFieldUpdate(target, element) {}

/////// IDEA FUNCTIONS /////////
function addIdea() {
	$.post("ideas.ajax.php", $("#addIdeaForm").serialize(), function(data) {
		showResponses("#ideaResponses", data, true);
		getIdeas();
	});
}

function deleteIdea(iId) {
	$.post("ideas.ajax.php", {action:"deleteIdea", ideaId:iId}, function (data) {
		showResponses("#ideaResponses", data, true);
		getIdeas();
	});
}

function updateIdeaDetails(formId) {
	$.post("ideas.ajax.php", $(formId).serialize(), function (data) {
		showResponses("#ideaResponses", data, true);
	});
}

///////////////// GROUP ///////////////

function addGroup() {
	$.post("groups.ajax.php", $("#addGroupForm").serialize(), function(data) {
		showResponses("#ideaResponses", data, true);
		getGroups();
	});
}

function deleteGroup(gId) {
	$.post("groups.ajax.php", {action: "deleteGroup", groupId:gId}, function(data) {
		showResponses("#ideaResponses", data, true);
		if (gId == currentGroupId) {
			currentGroupId = null;
			$("#groupDetails").empty();
		}
	});
}

function showGroupDetails() {
	$.get("groups.ajax.php?action=getGroupDetails&actionId="+currentGroupId, function(data) {
		$("#groupDetails").html(data);
	});
}

function updateForGroup(id) {
	currentGroupId = id;
	showGroupDetails();
}

function showAddGroupIdea() {
	$('groupPopup').empty();
	dijit.byId('groupPopup').show();
	$.get("groups.ajax.php?action=getAddIdea", function(data) {
		$("#actionDetails").html(data);
	});
}

function showAddGroupUser() {
	$('groupPopup').empty();
	dijit.byId('groupPopup').show();
	$.get("groups.ajax.php?action=getAddUser", function(data) {
		$("#actionDetails").html(data);
	});
} 

function addUserToCurGroup(id) {
	dijit.byId('groupPopup').hide();
	$.post("groups.ajax.php", {action: "linkUserToGroup", userId:id, groupId:currentGroupId}, function(data) {
		showResponses("#ideaResponses", data, true);
		showGroupDetails();
	});
}

function addIdeaToCurGroup(id) {
	dijit.byId('groupPopup').hide();
	$.post("groups.ajax.php", {action: "linkIdeaToGroup", ideaId:id, groupId:currentGroupId}, function(data) {
		showResponses("#ideaResponses", data, true);
		showGroupDetails();
	});
}

function delUserFromCurGroup(id) {
	$.post("groups.ajax.php", {action: "unlinkUserToGroup", userId:id, groupId:currentGroupId}, function(data) {
		showResponses("#ideaResponses", data, true);
		showGroupDetails();
	});
}

function delIdeaFromCurGroup(id) {
	$.post("groups.ajax.php", {action: "unlinkIdeaToGroup", ideaId:id, groupId:currentGroupId}, function(data) {
		showResponses("#ideaResponses", data, true);
		showGroupDetails();
	});
}

///////////// EVALUATION /////////////
function showAddRiskItem() {
	$('comparePopup').empty();
	dijit.byId('comparePopup').show();
	$.get("compare.ajax.php?action=getAddRisk", function(data) {
		$("#compareDetails").html(data);
	});
} 

function addRiskItem(id) {
	dijit.byId('comparePopup').hide();
	$.post("compare.ajax.php", {action: "createRiskItem", ideaId:id}, function(data) {
		showResponses("#ideaResponses", data, true);
		showCompare();
	});
}


var formArray;
function setFormArrayValue(key,val) {
	formArray[key] = value;
}

function getInputDataFromId(selector) {
	$("#" + selector + " input").each(function(index, formArray) {
		setFormArrayValue($(this).attr('name'),$(this).val());
	});

	alert(formArray['title']);
	
	return formArray;
}


function updateRisk(riskid,riskform){
	form = getInputDataFromId(riskform);
	alert (form);
}

function deleteRisk(){}
</script>

<style>
/* COMMON */
body {
	text-align:left;
}

table, th {
	text-align:left;
}

/* Page header */
.tabMenu {
	text-decoration:none;
	list-style:none;
	text-indent:0px;
}

.tabMenu li {
	float:left;
	position:relative;
	padding-right:10px;
	text-indent:0px;
}

a {
	font-size:0.9em;
}

#head {
	width:100%;
	height:2.0em;
}

#leftAlignMenu {
	top:0px;
	left:0px;
	position: absolute;
}

#rightAlignMenu {
	top:0px;
	right: 0px;
	position: absolute;
}

#ajaxLoader {
	display:none;
	height:20px; 
	width:20px;
}

.addForm {
	background-color:#69F564;
	padding:4px;
}

.idea {
	margin: 5px;
	border-left: 5px solid #CCC;
}

/* CONTENT */
#content { 
	width:98%;
	padding:1%;
}

.formHead {
	background-color:#F2F2F2;
}

.formBody subform {
	padding:4px;
}

.responses {
	border:1px solid #000000;
	display:none;
	padding:5px;
	margin-bottom: 5px;
}

.tabBody {javascript:showCompare()
	display:none;
}

/* tab head */
#ideaTabHead {
	height:1.5em;
}

.formHeadContain {
	position:relative;
	float:left;
}

#ideaGroups {
	background-color:#FFFFFF;
	position:relative;
	float:right;
	padding:3px;
}

span#ideaGroupsList {
	color:#666;
	font-size:0.9em;	
}

div#groupsList, div#groupDetails {
	padding-top:1.5em;
}

.two-column {
	position:relative; float:left; width:48%;min-height:200px;
}

#reportDetails {
	padding:5px;
	border:1px solid #000000;
}

#groupPopup {
	overflow:auto;
	max-height:20em;
}
</style>

</head>
<body class="tundra">

<div id="head">
	<div id="leftAlignMenu">
		<ul class="tabMenu">
			<li><img id="logo" style="height:20px; width:20px;" src="<?= $serverRoot?>ui/style/kubu.png"/>
			<img id="ajaxLoader" src="<?= $serverRoot?>ui/style/ajaxLoader.gif"/></li>
			<li><b>Innoworks</b></li>
			<li><a id="ideaslnk" href="javascript:showIdeas()">Ideas</a></li>
			<li><a id="comparelnk" href="javascript:showCompare()">Compare</a></li>
			<li><a id="groupslnk" href="javascript:showGroups()">Groups</a></li>
			<li><a id="reportslnk" href="javascript:showReports()">Reports</a></li>
		</ul>
	</div>
	<div id="rightAlignMenu">
		<ul class="tabMenu">
			<li>Welcome <?= $_SESSION['innoworks.username']; ?></li>
			<li><a href="mailto:james.hornitzky@gmail.com">feedback</a></li>
			<li><a href="javascript:logout()">logout</a></li>
		</ul>
	</div>
</div>

<div id="content">
<div id="ideaResponses" class="responses ui-corner-all"></div>

<div id="ideaTab" class="tabBody">

	<div id="ideaTabHead" class="tabHead addForm ui-corner-all"> 
		<div class="formHeadContain">
			<form id="addIdeaForm" onsubmit="addIdea(); return false;">
			<span>New idea</span> <input name="title" type="text"></input> <input type="submit" value=" + "/>
			<input type="hidden" name="action" value="addIdea"/>
			</form>
		</div> 
		<div id="ideaGroups" class="ui-corner-all">
			<input type="text" value="Search" disabled="disabled"/><a href="javascript:showDefaultIdeas()">My Ideas</a> 
			Groups <span id="ideaGroupsList">None</span>
		</div>
	</div>

	<div id="ideasList">
	</div>
	
	<div id="ideasPopup" dojoType="dijit.Dialog" title="More about idea">
    <div dojoType="dijit.layout.TabContainer" style="width: 30em; height: 300px;">
        <div dojoType="dijit.layout.ContentPane" title="Reviews">
            No reviews yet
        </div>
        <div dojoType="dijit.layout.ContentPane" title="Feature Evaluation">
            No feature evaluations yet
        </div>
    </div>
</div>
</div>

<div id="reportTab" class="tabBody">
	<div id="reportDetails" class="two-column ui-corner-all" style="padding:10px">
		Select a group
	</div>
	<div id="reportList" class="two-column" style="padding:10px"></div>
</div>

<div id="groupTab" class="tabBody">
	<div id="groupSelect" class="two-column" style="padding:10px">
		<div class="formHeadContain" style="width:100%">
			<form id="addGroupForm" class="addForm ui-corner-all" onsubmit="addGroup(); return false;">
			<span>New Group</span> <input name="title" type="text"></input> <input type="submit" value=" + "/>
			<input type="hidden" name="action" value="addGroup"/>
			</form>
		</div>
		<div id="groupsList">
		</div> 
	</div>
	<div id="groupDetails" class="two-column" style="padding:10px">
		Select a group
	</div>
	<div id="groupPopup" dojoType="dijit.Dialog" title="Group">
    	<div id="actionDetails" dojoType="dijit.layout.ContentPane">
            No actions yet
        </div>
	</div>
</div>

<div id="compareTab" class="tabBody">
	<h2>R-W-W</h2>
	<input type='button' onclick='showAddRiskItem()' value=' + '/>
	<div id="compareList">
		<p>No comparisons yet</p>
	</div>
	
	<div id="comparePopup" dojoType="dijit.Dialog" title="Compare">
    	<div id="compareDetails" dojoType="dijit.layout.ContentPane">
            No actions yet
        </div>
	</div>
</div>
</div>

</body>
</html>
