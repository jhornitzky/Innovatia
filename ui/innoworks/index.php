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
	src="<?= $serverRoot?>ui/scripts/dojo/dojo.js" djConfig="parseOnLoad: true"></script>

<link href="<?= $serverRoot?>ui/scripts/cssjQuery.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="<?= $serverRoot?>ui/scripts/dijit/themes/tundra/tundra.css"/>
<link href="<?= $serverRoot?>ui/style/style.css" rel="stylesheet"type="text/css"/>
	
<script type="text/javascript">
//////// VARS //////////
var ctime;
var currentIdeaId;
var currentGroupId;
var formArray; // Temp holder for form value functions
var targets = {"ideas": "ideas.ajax.php",  "groups": "groups.ajax.php",  
		"compare": "compare.ajax.php", "reports": "reports.ajax.php"};

/////// START UP ///////
dojo.require("dijit.Dialog");
$("#groupslnk").addClass("selMenu");
dojo.require("dijit.form.Button");
dojo.require("dijit.layout.TabContainer");
dojo.require("dijit.layout.ContentPane");
dojo.require("dijit.Menu");

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
	//Show default
	showIdeas();
});


dojo.addOnLoad(function(){
	//Setup stuff for tab menus
	dojo.subscribe("ideasPopupTabContainer-selectChild", function(child){
		if (child.id == "ideaComments") 
			getCommentsForIdea();
		else if (child.id == "ideaFeatureEvaluationList") 
			getFeatureEvaluationForIdea();
	});
});


//////////// MENU ///////////
function showIdeaReviews(ideaId) { 
	currentIdeaId = ideaId;
	getCommentsForIdea();
	getFeatureEvaluationForIdea();
	dijit.byId('ideasPopup').show();
}

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

function showIdeas(elem) {
	$(".menulnk").parent().removeClass("selMenu");
	$("#ideaslnk").parent().addClass("selMenu");
	getIdeas();
	$(".tabBody").hide();
	$("#ideaTab").show();
}

function showReports(elem) {
	$(".menulnk").parent().removeClass("selMenu");
	$("#reportslnk").parent().addClass("selMenu");
	getReports();
	$(".tabBody").hide();
	$("#reportTab").show();
}	

function showGroups(elem) {
	$(".menulnk").parent().removeClass("selMenu");
	$("#groupslnk").parent().addClass("selMenu");
	getGroups(); 
	$(".tabBody").hide();
	$("#groupTab").show();
}

function showCompare(elem) {
	$(".menulnk").parent().removeClass("selMenu");
	$("#comparelnk").parent().addClass("selMenu");
	getCompare();
	$(".tabBody").hide();
	$("#compareTab").show();
}

/////// COMMON FUNCTIONS ///////

function setFormArrayValue(key,val) {
	formArray[key] = val;
}

function getInputDataFromId(selector) {
	formArray=new Array();
	$("#" + selector + " :input").each(function(index, formArray) {
		if ($(this).attr('name') != null && $(this).attr('name') != '') 
			setFormArrayValue($(this).attr('name'),$(this).val());
	}); 
	return formArray;
}

function getSerializedArray(array) {
	var a = [];
	for (key in array) {
	    a.push(key+"="+array[key]);
	}
	return a.join("&") // a=2&c=1	
}


function showDetails(id) {
	$("#" + id).toggle();
}

function logout() {
	window.location.href = "<?=$serverRoot?>?logOut=1";
}

function showResponses(selector, data, timeout) {
	$(selector).html(data);
	$(selector).slideDown();
	if (timeout) {
		if (ctime != null)
			window.clearTimeout(ctime);
		ctime = window.setTimeout('hideResponses("'+selector+'")', 5000);
	} 
}

function hideResponses(selector) {
	$(selector).slideUp(function () {
		$(selector).empty();
	});
}

function genericAdd(selector) {
	$.post("ideas.ajax.php", $("#"+selector).serialize(), function(data) {
		showResponses("#ideaResponses", data, true);
		//getIdeas();
	});	
}

function genericDelete(target, id) {
	$.post("ideas.ajax.php", {actionId:id, action:target}, function(data) {
		showResponses("#ideaResponses", data, true);
		//getIdeas();
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
	if (confirm("Are you sure you wish to delete this idea?")) {
		$.post("ideas.ajax.php", {action:"deleteIdea", ideaId:iId}, function (data) {
			showResponses("#ideaResponses", data, true);
			getIdeas();
		});
	}
}

function updateIdeaDetails(formId) {
	$.post("ideas.ajax.php", $(formId).serialize(), function (data) {
		showResponses("#ideaResponses", data, true);
	});
}

function showIdeaOptions(element) {
	//$(element).find(".ideaoptions").show();
}

function hideIdeaOptions(element) {
	//$(element).find(".ideaoptions").hide();
}

function updateFeature(id,form, ideaId) {
	formData = getInputDataFromId(form);
	formData['action'] = 'updateFeature';
	$.post("ideas.ajax.php", getSerializedArray(formData), function(data, form, ideaId) {
		showResponses("#ideaResponses", data, true);
	});
}

function getFeatures(formId,actionId) {
	$.get("ideas.ajax.php?action=getFeatures&actionId=" + actionId, function (data) {
		$("#"+formId).html(data);
	});
}

function getRoles(formId,actionId) {
	//alert(formId);
	$.get("ideas.ajax.php?action=getRoles&actionId=" + actionId, function (data) {
		//alert(formId);
		$("#"+formId).html(data);
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
	$('#commonPopup #actionDetails').empty();
	dijit.byId('commonPopup').show();
	$.get("groups.ajax.php?action=getAddIdea", function(data) {
		$("#actionDetails").html(data);
	});
}

function showAddGroupUser() {
	$('#commonPopup #actionDetails').empty();
	dijit.byId('commonPopup').show();
	$.get("groups.ajax.php?action=getAddUser", function(data) {
		$("#actionDetails").html(data);
	});
} 

function addUserToCurGroup(id) {
	dijit.byId('commonPopup').hide();
	$.post("groups.ajax.php", {action: "linkUserToGroup", userId:id, groupId:currentGroupId}, function(data) {
		showResponses("#ideaResponses", data, true);
		showGroupDetails();
	});
}

function addIdeaToCurGroup(id) {
	dijit.byId('commonPopup').hide();
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

///////////// RISK EVALUATION /////////////
function showAddRiskItem() {
	$('#commonPopup #actionDetails').empty();
	dijit.byId('commonPopup').show();
	$.get("compare.ajax.php?action=getAddRisk", function(data) {
		$("#commonPopup #actionDetails").html(data);
	});
} 

function addRiskItem(id) {
	dijit.byId('commonPopup').hide();
	$.post("compare.ajax.php", {action: "createRiskItem", ideaId:id}, function(data) {
		showResponses("#ideaResponses", data, true);
		showCompare();
	});
}
 
function updateRisk(riskid,riskform){
	formData = getInputDataFromId(riskform);
	formData['action'] = 'updateRiskItem';
	$.post("compare.ajax.php", getSerializedArray(formData), function(data) {
		showResponses("#ideaResponses", data, true);
		showCompare();
	});
}

function deleteRisk(riskid){
	$.post("compare.ajax.php", {action: "deleteRiskItem", riskEvaluationId:riskid}, function(data) {
		showResponses("#ideaResponses", data, true);
		showCompare();
	});
}

///////////// REVIEWS /////////////////////

function getCommentsForIdea() {
	$.get("ideas.ajax.php?action=getCommentsForIdea&actionId="+currentIdeaId, function(data) {
		$("#commentList").html(data);
	});
}

function getFeatureEvaluationForIdea() {
	$.get("ideas.ajax.php?action=getFeatureEvaluationForIdea&actionId="+currentIdeaId, function(data) {
		$("#fEvalContent").html(data);
	});
}

function addComment() {
	$.post("ideas.ajax.php", $("#addCommentForm").serialize()+"&ideaId="+currentIdeaId, function(data) {
		showResponses("#ideaResponses", data, true);
		getCommentsForIdea();
	});
}
 
function updateComment(){
}

function deleteComment(cid) {
	$.post("ideas.ajax.php", {action: "deleteComment", commentId:cid}, function(data) {
		showResponses("#ideaResponses", data, true);
		getCommentsForIdea();
	}); 
}

function addFeatureItem(fId) {
	$.post("ideas.ajax.php", {action: "createFeatureItem", featureId: fId}, function(data) {
		showResponses("#ideaResponses", data, true);
		getFeatureEvaluationForIdea();
	});
}
 
function updateFeatureItem(featureItemId,featureForm){
	formData = getInputDataFromId(featureForm);
	formData['action'] = 'updateFeatureItem';
	$.post("ideas.ajax.php", getSerializedArray(formData), function(data) {
		showResponses("#ideaResponses", data, true);
		getFeatureEvaluationForIdea();
	});
}

function deleteFeatureItem(fid){
	$.post("ideas.ajax.php", {action: "deleteFeatureItem", featureEvaluationId:fid}, function(data) {
		showResponses("#ideaResponses", data, true);
		getFeatureEvaluationForIdea();
	});
}

//Add another contains method
jQuery.expr[':'].Contains = function(a,i,m){
    return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
};

function filterIdeas(element) {
	var filter = $(element).val();
    if (filter != '' && filter != null) { 
      $("#ideasList .idea .formHead").find(".ideatitle:not(:Contains('" + filter + "'))").parent().slideUp();
      $("#ideasList .idea .formHead").find(".ideatitle:Contains('" + filter + "')").parent().slideDown();
    } else {
      $("#ideasList .idea .formHead").find(".ideatitle").parent().slideDown();
    }
	
}

//var initFormId;

function initFormSelectTotals() {
	//alert("initFormSelectTotals");
	$("#compareList tr").each(function(index, element) {
		var initFormId = $(element).attr("id");
		if (initFormId != null && initFormId != ''){
			$(element).find("select").change(function () {
				var x = initFormId;
				updateFormSelectTotals(x);
			}); 
			updateFormSelectTotals(initFormId);
		}
	}); 
}

function updateFormSelectTotals(formId) {
	var total = 0;
	var count = 0;
	$("#" + formId + " select").each(function(index) {
		if (!isNaN(parseInt($(this).val()))){
			total = total + parseInt($(this).val());
			count++;
		}
	}); 
	if (count != 0)
		$("#" + formId + " span.itemTotal").html(Math.round(total/count));
}
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
	padding-left:0.5em;
	padding-right:0.5em;
	text-indent:0px;
}

a {
	font-size:0.9em;
}

#head {
	width:100%;
	height:2.0em;
	margin-top:0.3em;
	margin-bottom:0.3em;
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

/* CONTENT */
#content { 
	width:98%;
	padding:1%;
}

.formHead {
	background-color:#F2F2F2;
	padding-top: 0.1em;
	padding-bottom: 0.1em;
}

.formBody subform {
	padding:4px;
}

.responses {
	border:1px solid #000000;
	display:none;
	padding:5px;
	margin-bottom: 5px;
	height:1em;
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

select {
	width:5em;
}

.ideaDetails td.label {
	#width:50em;
}

.ideaDetails input[type=text] {
	#width:50em;
}

.ideaoptions {
	#display:none;
}

.ideatitle {
	font-size:1.2em;
}

.idea {
	margin-top: 10px;
	margin-bottom: 10px;
	border-left: 5px solid #CCC;
}

.selMenu {
	background-color:#444444;
	color:#FFFFFF;
	-moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px; 
}

.selMenu a {
	color:#FFFFFF;
	border-bottom:none;
	font-weight: bold;
}

div.loadingSpinner {
	
}

table
{text-align: center;
font-weight: normal;
color: #fff;
width: 100%;
background-color: #666;
border: 0px;
border-collapse: collapse;
border-spacing: 0px;}

table td
{
background-color: #CCC;
color: #000;
padding: 4px;
text-align: left;
border: 1px #fff solid;
}

table th
{
background-color: #AAA;
color: #fff;
padding: 4px;
text-align: left;
border-bottom: 2px #fff solid;
font-size: 12px;
font-weight: bold;
} 

table td input {
	width:100%;
}

</style>

</head>
<body class="tundra">

<div id="head">
	<div id="leftAlignMenu">
		<ul class="tabMenu">
			<li><img id="logo" style="height:25px; width:25px;" src="<?= $serverRoot?>ui/style/kubu.png"/>
			<img id="ajaxLoader" src="<?= $serverRoot?>ui/style/ajaxLoader.gif"/></li>
			<li><b>Innoworks</b></li>
			<li><a id="ideaslnk" class="menulnk" href="javascript:showIdeas(this)">Ideas</a></li>
			<li><a id="comparelnk" class="menulnk" href="javascript:showCompare(this)">Compare</a></li>
			<li><a id="groupslnk" class="menulnk" href="javascript:showGroups(this)">Groups</a></li>
			<li><a id="reportslnk" class="menulnk" href="javascript:showReports(this)">Reports</a></li>
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
			<input type="text" value="Search" onclick="$(this).val('')" onkeyup="filterIdeas(this)" onchange="filterIdeas(this)"/><a href="javascript:showDefaultIdeas()">My Ideas</a> 
			Groups <span id="ideaGroupsList">None</span>
		</div>
	</div>

	<div id="ideasList">
	</div>
</div>

<div id="reportTab" class="tabBody">
	<div id="reportDetails" class="two-column ui-corner-all" style="padding:10px">
		Select a group
	</div>
	<div id="reportList" class="two-column" style="padding:10px"></div>
</div>

<div id="groupTab" class="tabBody">
	<div id="groupSelect" class="two-column" >
		<div class="formHeadContain" style="width:100%">
			<form id="addGroupForm" class="addForm ui-corner-all" onsubmit="addGroup(); return false;">
			<span>New Group</span> <input name="title" type="text"></input> <input type="submit" value=" + "/>
			<input type="hidden" name="action" value="addGroup"/>
			</form>
		</div>
		<div id="groupsList" style="padding:10px; margin-top:1em">
		</div> 
	</div>
	<div id="groupDetails" class="two-column" style="padding:10px">
		Select a group
	</div>
</div>

<div id="compareTab" class="tabBody">
	<!-- <h2>R-W-W</h2>
	<p>The R-W-W method phrases key questions around the risks involved with each idea, allowing you to select and rank which ideas you feel are best.</p> -->
	<div class="addform ui-corner-all">Click here to add idea to comparison <input type='button' onclick='showAddRiskItem()' value=' + '/></div>
	<div id="compareList">
		<p>No comparisons yet</p>
	</div>
</div>

<div id="ideasPopup" dojoType="dijit.Dialog" title="More about idea">
    <div id="ideasPopupTabContainer" dojoType="dijit.layout.TabContainer" style="width: 35em; height: 300px;">
        <div id="ideaComments" dojoType="dijit.layout.ContentPane" title="Comments">
        	<div id="addComment">
        		<form id="addCommentForm" class="addForm ui-corner-all" onsubmit="addComment();return false;">
        			New Comment
        			<input type="text" name="text"/>
        			<input type="hidden" name="action" value="addComment" />
        			<input type="submit" value=" + "/>
        		</form>
        	</div>
            <div id="commentList">No comments yet</div>
        </div>
        
        <div id="ideaFeatureEvaluationList" dojoType="dijit.layout.ContentPane" title="Feature Evaluation">
            <div id="addFeatureEval"></div>
            <div id="fEvalContent"></div>
        </div>
    </div>
</div>

<div id="commonPopup" dojoType="dijit.Dialog" title="Action"  style="width: 15em; height: 300px;">
    <div id="actionDetails" dojoType="dijit.layout.ContentPane" >
          No actions yet
    </div>
</div>

</div>

</body>
</html>
