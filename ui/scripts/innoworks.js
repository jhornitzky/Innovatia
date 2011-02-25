//QUEUE 
var queue = new Array();
var loadingString = "<div class='loadingAnim'></div>";
var smallLoadingString = "<div class='smallLoadingAnim'></div>";

function queueAction(action) {
	queue[queue.length+1] = action;
}

function processQueuedActions() {
	if (queue.length > 0) {
		for (x in queue) {
			eval(queue[x]);
		}
		queue = new Array();
	}
}

//GENERIC ACTIONS
function logAction() {} 

function doAction(jsonRequest, callback) {
	$.post("engine.ajax.php", jsonRequest, function(data) {
		showResponses( data, true);
		if (callback != undefined);
		eval(callback);
	});
}

function loadResults(element, jsonRequest) {
	var parent = $(element).parent();
	$(element).remove();
	parent.append(smallLoadingString);
	$.get("engine.ajax.php", jsonRequest, function(data) {
		parent.html(data); 
	});
}

function pollServer() {
	$.get("poll.php", function(data){
		if (data != null && data != '') {
			showResponses( data, true);
			if (!($("#noteTab").is(":hidden"))) {
				showNotes();
			}
		}
	});
}

function showLoading(selector) {
	$(selector).html(loadingString);
}

function subscribeForChild(child) {
	loadIdeaPopupData();
}

function loadIdeaPopupData() {
	if(!($("#ideaMission").is(":hidden")))
		getMission("ideaMission",currentIdeaId);
	else if (!($("#ideaFeatures").is(":hidden")))
		getFeaturesForm("ideaFeatures",currentIdeaId);
	else if (!($("#ideaRoles").is(":hidden")))
		getRolesForm("ideaRoles",currentIdeaId);
	else if (!($("#ideaFeatureEvaluationList").is(":hidden")))
		getFeatureEvaluationsForIdea();
	else if (!($("#ideaComments").is(":hidden")))
		getCommentsForIdea();
	else if (!($("#ideaAttachments").is(":hidden")))
		getAttachments();
	else if (!($("#ideaShare").is(":hidden")))
		getShareForIdea();
	else if (!($("#ideaSelect").is(":hidden")))
		getSelectForIdea();
	else if (!($("#ideaRiskEval").is(":hidden")))
		getRiskEvalForIdea("ideaRisks",currentIdeaId);
}

function loadPopupShow() {
	dijit.byId('ideasPopup').show();
	$("img#popupIdeaImgMain").attr("src", "retrieveImage.php?action=ideaImg&actionId=" + currentIdeaId);
	$("span#ideaName").load("engine.ajax.php?action=getIdeaName&actionId=" + currentIdeaId, function() { 
		loadIdeaPopupData();
	});
}

function refreshVisibleTab() {
	if(!($("#dashTab").is(":hidden")))
		getDash();
	else if (!($("#ideaTab").is(":hidden")))
		getIdeas();
	else if (!($("#compareTab").is(":hidden")))
		getCompare();
	else if (!($("#selectTab").is(":hidden")))
		getSelect();
	else if (!($("#publicTab").is(":hidden"))) {
		if (!($(".publicInfo .ideasList").is(":hidden")))
			getIdeas();
		else if (!($(".publicInfo .compareList").is(":hidden")))
			getCompare();
		else if (!($(".publicInfo .selectList").is(":hidden")))
			getSelect();
	}
	else if (!($("#profileTab").is(":hidden"))) {
		if (!($(".profileInfo .ideasList").is(":hidden")))
			getIdeas();
		else if (!($(".profileInfo .compareList").is(":hidden")))
			getCompare();
		else if (!($(".profileInfo .selectList").is(":hidden")))
			getSelect();
		else if (!($(".profileInfo #noteTab").is(":hidden")))
			getNotes();
		else
			getProfileSubDetails();
	}
	else if (!($("#groupTab").is(":hidden"))) {
		if (!($(".groupInfo .ideasList").is(":hidden")))
			getIdeas();
		else if (!($(".groupInfo .compareList").is(":hidden")))
			getCompare();
		else if (!($(".groupInfo .selectList").is(":hidden")))
			getSelect();
		else
			getGroupSubDetails();
	}
	else if (!($("#noteTab").is(":hidden")))
		getNotes();
	else if (!($("#searchTab").is(":hidden")))
		getSearch();
	else if (!($("#timelineTab").is(":hidden")))
		getTimeline();
	else if (!($("#reportTab").is(":hidden")))
		getReports();
}

//GET FUNCTIONS
function getMission(formId,actionId) { 
	showLoading("#"+formId);
	$.get("engine.ajax.php?action=getMission&actionId=" + actionId, function (data) {
		$("#"+formId).html(data);
		dojo.parser.instantiate(dojo.query('#' + formId + ' *'));
		$('#' + formId).find("textarea").blur(function() {
			updateIdeaDetails("#ideadetails_form_"+currentIdeaId);
		});
	});
}

function getFeaturesForm(formId,actionId) {
	showLoading("#"+formId);
	$.get("engine.ajax.php?action=getFeaturesForm&actionId=" + actionId, function (data) {
		$("#"+formId).html(data);
		dojo.parser.instantiate(dojo.query('#' + formId + ' *'));
		$("#featureTable_" + currentIdeaId + " tr").each(function() {
			var fId = $(this).attr("id");
			$(this).find(":input").blur(function() {
				updateFeature(fId);
			});
		});
	});
}

function getRolesForm(formId,actionId) {
	showLoading("#"+formId);
	$.get("engine.ajax.php?action=getRolesForm&actionId=" + actionId, function (data) {
		$("#"+formId).html(data);
		dojo.parser.instantiate(dojo.query('#' + formId + ' *'));
		$("#idearoles_" + currentIdeaId + " table tr").each(function() {
			var fId = $(this).attr("id");
			$(this).find(":input").blur(function() {
				updateRole(fId);
			});
		});
	});
}

function getFeatures(formId,actionId) {
	showLoading("#"+formId);
	$.get("engine.ajax.php?action=getFeatures&actionId=" + actionId, function (data) {
		$("#"+formId).html(data);
		dojo.parser.instantiate(dojo.query('#' + formId + ' *'));
		$("#featureTable_" + currentIdeaId + " tr").each(function() {
			var fId = $(this).attr("id");
			$(this).find(":input").blur(function() {
				updateFeature(fId);
			});
		});
	});
}

function getRoles(formId,actionId) {
	showLoading("#"+formId);
	$.get("engine.ajax.php?action=getRoles&actionId=" + actionId, function (data) {
		$("#"+formId).html(data);
		dojo.parser.instantiate(dojo.query('#' + formId + ' *'));
		$("#idearoles_" + currentIdeaId + " table tr").each(function() {
			var fId = $(this).attr("id");
			$(this).find(":input").blur(function() {
				updateRole(fId);
			});
		});
	});
}

//////////// MENU ///////////
function showIdeaReviews(ideaId) {
	currentIdeaId = ideaId;
	dijit.byId("ideasPopupReview").selectChild(dijit.byId("ideaComments"));
	dijit.byId("ideasPopupTabContainer").selectChild(dijit.byId("ideasPopupReview"));
	loadPopupShow();
}

var summaryStyleString = "width:20em; height:30em;";

function showIdeaSummary(id) {
	var idea = new inno.Dialog({title:"Idea", href:"engine.ajax.php?action=getIdeaSummary&actionId="+id, style: summaryStyleString});
	dojo.body().appendChild(idea.domNode);
	idea.startup();
	idea.show();
}

function showProfileSummary(id) {
	var profile = new inno.Dialog({title:"Profile", href:"engine.ajax.php?action=getProfileSummary&actionId="+id, style: summaryStyleString});
	dojo.body().appendChild(profile.domNode);
	profile.startup();
	profile.show();
}

function showGroupSummary(id) {
	var group = new inno.Dialog({title:"Group", href:"engine.ajax.php?action=getGroupSummary&actionId="+id, style: summaryStyleString});
	dojo.body().appendChild(group.domNode);
	group.startup();
	group.show();
}

function showIdeaDetails(ideaId) { 
	currentIdeaId = ideaId;
	loadPopupShow();
}

///// GROUP SELECTION////////////
function showIdeaGroupsForUser() {
	$.get("engine.ajax.php?action=getIdeaGroupsForUser", function (data) {
		$(".ideaGroupsList").html(data);
		highlightSelectedSpace();
		getGroupPreview();
	});
}

function getGroupPreview() {
	if (currentGroupName == "Public") {
		$("div.groupPreview").load("engine.ajax.php?action=getPublicGroupPreview");
	} else if (currentGroupName == "Private") {
		$("div.groupPreview").load("engine.ajax.php?action=getPrivateGroupPreview");
	} else {
		$("div.groupPreview").load("engine.ajax.php?action=getGroupPreview&actionId=" + currentGroupId);
	}
}

function highlightSelectedSpace() {
	$(".ideaGroupsList div").removeClass("selected");
	if (currentGroupName == "Public") {
		$(".ideaGroupsList div.public").addClass("selected");
	} else if (currentGroupName == "Private") {
		$(".ideaGroupsList div.private").addClass("selected");
	} else {
		$(".ideaGroupsList div.groupsHolder").addClass("selected");
	}
}

function showDefaultIdeas() {
	currentGroupId = null;
	currentGroupName = "Private";
	refreshVisibleTab();
	showIdeaGroupsForUser();
	scroll(0,0);
}

function showPublicIdeas() {
	currentGroupId = null;
	currentGroupName = "Public";
	refreshVisibleTab();
	showIdeaGroupsForUser();
	scroll(0,0);
}

function showIdeasForGroup(gId, elem) {
	currentGroupId = gId;
	currentGroupName = elem;
	refreshVisibleTab();
	getGroupPreview();
	highlightSelectedSpace();
	scroll(0,0);
}

///////////// MORE GET FUNCTIONS ///////////////
function getPublic() {
	showLoading("#publicTab");
	$("#publicTab").load("engine.ajax.php?action=getPublicDefault", function() {
		showPublicIdeate();
		$("#publicTab").find("ul.submenu li:first").addClass("selected");
	});
}

function getNotes() {
	showLoading("#noteTab");
	$("#noteTab").load("engine.ajax.php?action=getNotesDefault");
}

function getDash() {
	showLoading("#dashTab");
	$("#dashTab").load("engine.ajax.php?action=getDashDefault");
}

function getIdeas() {
	showLoading(".ideasList");
	if (currentGroupId == null && currentGroupName == "Private") {
		$(".ideasList").load("engine.ajax.php?action=getIdeas");
		$("#addIdeaForm span").html("Add new idea");
		$("#addIdeaTitle").fadeIn();
	} else if (currentGroupId == null && currentGroupName == "Public") {
		$(".ideasList").load("engine.ajax.php?action=getPublicIdeas");
		$("#addIdeaForm span").html("Make a <b>private</b> idea public");
		$("#addIdeaTitle").hide();
	} else {
		$(".ideasList").load("engine.ajax.php?action=getIdeasForGroup&groupId="+currentGroupId); 
		$("#addIdeaForm span").html("Add a <b>private</b> idea to the group");
		$("#addIdeaTitle").hide();
	}
} 

function prepCompareTable() {
	initFormSelectTotals('table#riskEvaluation');
	$("table#riskEvaluation tr").each(function() { 
		var fId = $(this).attr("id");
		$(this).find(":input").blur(function() {
			updateRisk(fId);
		});
	});
}

function getCompare() {
	showLoading(".compareList");
	if (currentGroupId == null && currentGroupName == "Private") {
		$.get("engine.ajax.php?action=getComparison", function (data) {
			$(".compareList").html(data);
			prepCompareTable();
		});
	} else if (currentGroupId == null && currentGroupName == "Public") {
		$.get("engine.ajax.php?action=getPublicComparison", function (data) {
			$(".compareList").html(data);
			prepCompareTable();
		});
	} else { 
		$.get("engine.ajax.php?action=getComparisonForGroup&groupId="+currentGroupId, function (data) {
			$(".compareList").html(data);
			prepCompareTable();
		});
	}
	getCompareComments();
}

function getSelect() {
	showLoading(".selectList");
	if (currentGroupId == null && currentGroupName == "Private") {
		$(".selectList").load("engine.ajax.php?action=getSelection");
	} else if (currentGroupId == null && currentGroupName == "Public") {
		$(".selectList").load("engine.ajax.php?action=getPublicSelection");
	} else {
		$(".selectList").load("engine.ajax.php?action=getSelectionForGroup&actionId="+currentGroupId);
	}
}

function getProfile() {
	showLoading("#profileTab");
	$.get("engine.ajax.php?action=getProfile", function (data) {
		$("#profileTab").html(data);
		showProfileNotes();
		$("#profileTab").find("ul.submenu li:first").addClass("selected");
	});
}

function getGroups() {
	showLoading("#groupsList");
	$.get("engine.ajax.php?action=getGroups", function (data) {
		$("#groupsList").html(data);
		showIdeaGroupsForUser();
	});
	
	if (currentGroupId != null)
		showGroupDetails();
}

function getReports() {
	showLoading("#reportList");
	$.get("engine.ajax.php?action=getReportDetails", function (data) {
		$("#reportDetails").html(data);
		$("#reportList").load("engine.ajax.php?action=getReportGraphs");
	});
}

function getSearch() {
	var data = $("#searchForm").serialize();
	showLoading("#searchTab #searchResults");
	var url = "engine.ajax.php?action=getSearchDefault";
	if (data != undefined) 
		url += "&" + data;
	$("#searchTab #searchResults").load(url, function() {
		dojo.parser.instantiate(dojo.query("#searchOptions *")); 
	});
}

function getAttachments() {
	showLoading("#ideaAttachments");
	$("#ideaAttachments").load("engine.ajax.php?action=getAttachments&actionId="+currentIdeaId);
}

function getRiskEvalForIdea() {
	showLoading("#ideaRiskEval");
	$("#ideaRiskEval").load("engine.ajax.php?action=getRiskEvalForIdea&actionId="+currentIdeaId, function() {
		$("#ideaRiskEvalDetails").find(":input").each(function() {
			$(this).blur(function() {
				updateRisk("ideaRiskEvalDetails");
			});
		});
	});
}

function getShareForIdea() {
	showLoading("#ideaShare");
	$("#ideaShare").load("engine.ajax.php?action=getShareForIdea&actionId="+currentIdeaId);
}

function getSelectForIdea() {
	showLoading("#ideaSelect");
	$("#ideaSelect").load("engine.ajax.php?action=getSelectForIdea&actionId="+currentIdeaId, function () {
		dojo.parser.instantiate(dojo.query('#ideaSelectDetails *')); 
		$("#ideaSelectDetails").find(":input").each(function() {
			$(this).blur(function() {
				updateSelection("ideaSelectDetails");
			});
		});
	});
}

function showSearch() {
	$(".menulnk").parent().removeClass("selMenu");
	$("#searchlnk").parent().addClass("selMenu");
	$(".menulnk").removeClass("selLnk");
	$("#searchlnk").addClass("selLnk");
	getSearch();
	$(".tabBody").hide();
	$("#searchTab").fadeIn();	
}

function showIdeas(elem) {
	$(".menulnk").parent().removeClass("selMenu");
	$("#ideaslnk").parent().addClass("selMenu");
	$(".menulnk").removeClass("selLnk");
	$("#ideaslnk").addClass("selLnk");
	getIdeas();
	showIdeaGroupsForUser();
	$(".tabBody").hide();
	$("#ideaTab").fadeIn();
}

function showReports(elem) {
	$(".menulnk").parent().removeClass("selMenu");
	$("#reportslnk").parent().addClass("selMenu");
	$(".menulnk").removeClass("selLnk");
	$("#reportslnk").addClass("selLnk");
	getReports();
	$(".tabBody").hide();
	$("#reportTab").fadeIn();
}	

function showProfile(elem) {
	currentGroupId = null;
	currentGroupName = "Private";
	$(".menulnk").parent().removeClass("selMenu");
	$("#profilelnk").parent().addClass("selMenu");
	$(".menulnk").removeClass("selLnk");
	$("#profilelnk").addClass("selLnk");
	getProfile();
	$(".tabBody").hide();
	$("#profileTab").fadeIn();
}

function showGroups(elem) {
	$(".menulnk").parent().removeClass("selMenu");
	$("#groupslnk").parent().addClass("selMenu");
	$(".menulnk").removeClass("selLnk");
	$("#groupslnk").addClass("selLnk");
	getGroups(); 
	showGroupDetails();
	$(".tabBody").hide();
	$("#groupTab").fadeIn();
}

function showCompare(elem) {
	$(".menulnk").parent().removeClass("selMenu");
	$("#comparelnk").parent().addClass("selMenu");
	$(".menulnk").removeClass("selLnk");
	$("#comparelnk").addClass("selLnk");
	getCompare();
	showIdeaGroupsForUser();
	$(".tabBody").hide();
	$("#compareTab").fadeIn();
}

function showSelect(elem) {
	$(".menulnk").parent().removeClass("selMenu");
	$("#selectlnk").parent().addClass("selMenu");
	$(".menulnk").removeClass("selLnk");
	$("#selectlnk").addClass("selLnk");
	getSelect();
	showIdeaGroupsForUser();
	$(".tabBody").hide();
	$("#selectTab").fadeIn();	
}

function showDash(elem) {
	$(".menulnk").parent().removeClass("selMenu");
	$("#dashlnk").parent().addClass("selMenu");
	$(".menulnk").removeClass("selLnk");
	$("#dashlnk").addClass("selLnk");
	getDash();
	$(".tabBody").hide();
	$("#dashTab").fadeIn();
}

function showAdmin(elem) {	
	$(".menulnk").parent().removeClass("selMenu");
	$("#adminlnk").parent().addClass("selMenu");
	$(".menulnk").removeClass("selLnk");
	$("#adminlnk").addClass("selLnk");
	getAdmin();
	$(".tabBody").hide();
	$("#adminTab").fadeIn();	
}

function showTimelines(elem) {
	$(".menulnk").parent().removeClass("selMenu");
	$("#timelinelnk").parent().addClass("selMenu");
	$(".menulnk").removeClass("selLnk");
	$("#timelinelnk").addClass("selLnk");
	$("#timelineTab").load("timeline/timeline.php");
	$(".tabBody").hide();
	$("#timelineTab").fadeIn();	
}

function showNotes() {
	$(".menulnk").parent().removeClass("selMenu");
	$("#noteslnk").parent().addClass("selMenu");
	$(".menulnk").removeClass("selLnk");
	$("#noteslnk").addClass("selLnk");
	getNotes();
	$(".tabBody").hide();
	$("#noteTab").fadeIn();
}

function showPublic() {
	currentGroupId = null;
	currentGroupName = "Public";
	$(".menulnk").parent().removeClass("selMenu");
	$("#publiclnk").parent().addClass("selMenu");
	$(".menulnk").removeClass("selLnk");
	$("#publiclnk").addClass("selLnk");
	getPublic();
	$(".tabBody").hide();
	$("#publicTab").fadeIn();
}

function showFeedback(elem) {
	window.open("mailto:james.hornitzky@uts.edu.au");
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
	return a.join("&") 
}

function showDetails(id) {
	$("#" + id).toggle();
}

function logout() {
	window.location.href = serverRoot + "?logOut=1";
}

function showResponses(data, timeout) {
	var selector;
	if (dijit.byId("ideasPopup").open)  
		selector = "#ideaPopupResponses"; 
	else 
		selector = "#ideaResponses"; 
	$(selector).html(data);
	$(selector).slideDown();
	if (timeout) {
		if (ctime != null)
			window.clearTimeout(ctime);
		ctime = window.setTimeout('hideResponses("'+selector+'")', 10000);
	} 
}

function hideResponses() {
	$(".responses").slideUp(function () {
		$(".responses").empty();
	});
}

//Add another contains method
jQuery.expr[':'].Contains = function(a,i,m){
  return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
};

function filterIdeas(element) {
	var filter = $(element).val();
	if (filter != '' && filter != null) { 
		$(".ideasList .idea .formHead").find(".ideatitle:not(:Contains('" + filter + "'))").parent().parent().slideUp();
    	$(".ideasList .idea .formHead").find(".ideatitle:Contains('" + filter + "')").parent().parent().slideDown();
	} else {
		$(".ideasList .idea .formHead").find(".ideatitle").parent().parent().slideDown();
	}
}

function initFormSelectTotals(selector, parentSelector) {
	$(selector + " tr").each(function(index, element) {
		var initFormId = $(element).attr("id");
		if (initFormId != null && initFormId != ''){
			$(element).find("select").change(function () {
				var x = initFormId;
				updateFormSelectTotals(x);
				updateFormTotal(parentSelector);
			}); 
			updateFormSelectTotals(initFormId);
			updateFormTotal(parentSelector);
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
		$("#" + formId).find("span.itemTotal").html(Math.round(total/count));
}

function updateFormTotal(formId) {
	var total = 0;
	var count = 0;
	$(formId).find("select").each(function(index) {
		if (!isNaN(parseInt($(this).val()))){
			total = total + parseInt($(this).val());
			count++;
		}
	}); 
	if (count != 0)
		$(formId).find("span.evalTotal").html(Math.round(total/count));
}

function addNote(element) {
	doAction($(element).serialize(),"getNotes()");
}

/////// IDEA FUNCTIONS /////////
function addPrivateIdea(elem) {
	if(currentGroupId == null && currentGroupName == "Private") {
		doAction($("#addPrivateIdeaForm").serialize(),"getIdeas()");
	} else if(currentGroupId == null && currentGroupName == "Public") {
		showAddPublicIdea(elem);
	} else {
		showAddGroupIdea(elem);
	}
}

function addIdea(elem) {
	if(currentGroupId == null && currentGroupName == "Private") {
		doAction($("#addIdeaForm").serialize(),"getIdeas()");
	} else if(currentGroupId == null && currentGroupName == "Public") {
		showAddPublicIdea(elem);
	} else {
		showAddGroupIdea(elem);
	}
}

function deleteIdea(iId) {
	if (confirm("Are you sure you wish to delete this idea?")) {
		doAction({action:"deleteIdea", ideaId:iId},"getIdeas()");
	} 
	return false;
}

function updateIdeaDetails(formId) {
	doAction($(formId).serialize());
}

function updateFeature(form) {
	formData = getInputDataFromId(form);
	formData['action'] = 'updateFeature';
	doAction(getSerializedArray(formData));
}

function updateRole(form) {
	formData = getInputDataFromId(form);
	formData['action'] = 'updateRole';
	doAction(getSerializedArray(formData));
}

function addFeature(selector, callbackForm, callbackIdea) {
	$.post("engine.ajax.php", $("#"+selector).serialize(), function(data) {
		showResponses( data, true);
		getFeatures(callbackForm, callbackIdea);
	});	
}

function addRole(selector, callbackForm, callbackIdea) {
	$.post("engine.ajax.php", $("#"+selector).serialize(), function(data) {
		showResponses( data, true);
		getRoles(callbackForm, callbackIdea);
	});	
}

function deleteFeature(target, id, callbackForm, callbackIdea) {
	if (confirm(removeString)) {
		$.post("engine.ajax.php", {actionId:id, action:target}, function(data) {
			showResponses( data, true);
			getFeatures(callbackForm, callbackIdea);
		});		
	} 
	return false;
}

function deleteRole(target, id, callbackForm, callbackIdea) {
	if (confirm(removeString)) {
		$.post("engine.ajax.php", {actionId:id, action:target}, function(data) {
			showResponses( data, true);
			getRoles(callbackForm, callbackIdea);
		});		
	} 
	return false;
}

///////////////// GROUP ///////////////

function addGroup() {
	doAction($("#addGroupForm").serialize(), "getGroups()");
}

function deleteGroup(gId) {
	if (confirm(removeString)) {
		$.post("engine.ajax.php", {action: "deleteGroup", groupId:gId}, function(data) {
			showResponses( data, true);
			if (gId == currentGroupId) {
				currentGroupId = null;
				$("#groupDetails").empty();
			}
			getGroups();
		});
	} 
	return false;
}

function updateGroupDetails(formId) {
	doAction($("#groupDetailsForm").serialize());
}

function showGroupDetails() {
	$.get("engine.ajax.php?action=getGroupDetails&actionId="+currentGroupId, function(data) {
		$("#groupDetails").html(data);
		//showGroupSubDetails();
		showGroupComments();
		$("#groupDetails ul.submenu li:first").addClass("selected");
	});
}

function acceptGroup() {
	doAction({action: "acceptGroup", actionId:currentGroupId}, "showGroupDetails()");
}

function updateForGroup(id,name) {
	currentGroupId = id;
	currentGroupName = name;
	showGroupDetails();
	showIdeaGroupsForUser();
	scroll(0,0);
}

function showAddGroupIdea(elem) {
	showCommonPopup(elem);
	$("#actionDetails").load("engine.ajax.php?action=getAddIdea");
}

function showAddPublicIdea(elem) {
	showCommonPopup(elem);
	$("#actionDetails").load("engine.ajax.php?action=getPublicAddIdea");
}

function showAddGroupUser(elem) {
	showCommonPopup(elem);
	$("#actionDetails").load("engine.ajax.php?action=getAddUser");
} 

function addUserToCurGroup(id) {
	dijit.byId('commonPopup').hide();
	doAction({action: "linkUserToGroup", userId:id, groupId:currentGroupId}, "refreshVisibleTab()");
}

function addIdeaToGroup(id, gId) {
	dijit.byId('commonPopup').hide();
	doAction({action: "linkIdeaToGroup", ideaId:id, groupId:gId}, "refreshVisibleTab()");
}

function addIdeaToCurGroup(id) {
	addIdeaToGroup(id, currentGroupId);
}

function addIdeaToPublic(id) {
	dijit.byId('commonPopup').hide();
	doAction({action: "addIdeaToPublic", actionId:id}, "getIdeas()");
}

function refuseGroup() {
	$.post("engine.ajax.php", {action: "refuseGroup", actionId:currentGroupId}, function(data) {
		showResponses( data, true);
		currentGroupId = null;
		showGroupDetails();
	});
}

function requestGroup() {
	doAction({action: "requestGroup", actionId:currentGroupId}, "showGroupDetails()");
}

function approveGroupUser(uId) {
	doAction({action: "approveGroupUser", actionId:currentGroupId, userId:uId}, "showGroupDetails()");
}

function delUserFromCurGroup(id) {
	if (confirm("Are you sure you wish to remove the user from this group?")) {
		$.post("engine.ajax.php", {action: "unlinkUserToGroup", userId:id, groupId:currentGroupId}, function(data) {
			showResponses( data, true);
			showGroupDetails();
			getGroups();
		});
	} else {
		return false;
	}
}

function delIdeaFromGroup(id, gId) {
	if (confirm("Are you sure you wish to remove this idea from this group?")) {
		sendDelIdeaFromGroup(id, gId);
	} else {
		return false;
	}
}

function sendDelIdeaFromGroup(id, gId) {
	doAction({action: "unlinkIdeaToGroup", ideaId:id, groupId:gId}, "showGroupDetails()");
}

function delIdeaFromCurGroup(id) {
	delIdeaFromGroup(id, currentGroupId);
}

///////////// RISK EVALUATION /////////////

function showCommonPopup(elem) {
	var popup = dijit.byId('commonPopup');
	$('#commonPopup #actionDetails').empty();
	popup.show();	
	if (elem != undefined) {
		var elemInfo = dojo.position(elem, true);
		$("#commonPopup").animate({left: Math.floor(elemInfo.x) + "px",
        top: Math.floor(elemInfo.y + elemInfo.h) + "px"});
	}
}

function showAddRiskItem(elem) {
	showCommonPopup(elem);
	if (currentGroupId == null ) {
		$("#commonPopup #actionDetails").load("engine.ajax.php?action=getAddRisk");
	} else {
		$("#commonPopup #actionDetails").load("engine.ajax.php?action=getAddRiskForGroup&groupId="+currentGroupId);
	}
} 

function addRiskItem(id) {
	dijit.byId('commonPopup').hide();
	doAction({action: "createRiskItem", ideaId:id}, "getCompare()");
}

function addRiskItemForGroup(id, groupId) {
	dijit.byId('commonPopup').hide();
	doAction({action: "createRiskItemForGroup", ideaId:id, groupId:currentGroupId}, "getCompare()");
}
 
function updateRisk(riskform){
	formData = getInputDataFromId(riskform);
	formData['action'] = 'updateRiskItem';
	formData['score'] = $("#" + riskform + " span.itemTotal").html();
	doAction(getSerializedArray(formData));
}

function deleteRisk(riskid){
	if (confirm("Are you sure you wish to remove this risk item?")) {
		doAction({action: "deleteRiskItem", riskEvaluationId:riskid}, "getCompare()");
	} 
	return false;
}

///////////// REVIEWS /////////////////////
function getCommentsForIdea() {
	$("#commentList").load("engine.ajax.php?action=getCommentsForIdea&actionId="+currentIdeaId);
}

function getCompareComments() {
	if (currentGroupId == null && currentGroupName == "Private") {
		$.get("engine.ajax.php?action=getCompareComments", function(data) {
			$(".compareCommentList").html(data);
			$("#addCompareCommentForm").fadeIn();
		});
	} else if (currentGroupId == null && currentGroupName == "Public") {
		/*$.get("engine.ajax.php?action=getPublicCompareComments", function(data) {
			$("#compareCommentList").html(data);
			$("#addCompareCommentForm").hide();
		});*/
		$(".compareCommentList").empty();
		$("#addCompareCommentForm").hide();
	} else { 
		$.get("engine.ajax.php?action=getCompareCommentsForGroup&actionId="+currentGroupId, function(data) {
			$(".compareCommentList").html(data);
			$("#addCompareCommentForm").fadeIn();
		});
	}
}

function getFeatureEvaluationsForIdea() {
	$.get("engine.ajax.php?action=getIdeaFeatureEvaluationsForIdea&actionId="+currentIdeaId, function(data) {
		$("#ideaFeatureEvaluationList").html(data);
		dojo.parser.instantiate(dojo.query('#ideaFeatureEvaluationList *'));
		dojo.parser.instantiate(dojo.query('#ideaFeatureEvaluationList * textarea'));
		
		$(".featureEvaluationBit").each(function() {
			initFormSelectTotals("#" + $(this).attr("id"), "#" + $(this).parent().attr("id"));
		})
		
		$(".featureEvaluation").each(function() { 
			$(this).find("table tr").each(function() {
				var fId = $(this).attr("id");
				$(this).find(":input").blur(function() {
					updateFeatureEvaluation(fId);
				});
			});
		});
	});
}

function addComment() {
	doAction($("#addCommentForm").serialize()+"&ideaId="+currentIdeaId, 'getCommentsForIdea()');
}

function addCompareComment(elem) {
	var urlAddition = "";
	if (currentGroupId)
		urlAddition = "&groupId="+currentGroupId;
	doAction($(elem).serialize()+urlAddition, 'getCompareComments()');
}

function deleteComment(cid) {
	if (confirm(removeString)) {
		$.post("engine.ajax.php", {action: "deleteComment", commentId:cid}, function(data) {
			showResponses( data, true);
			if (dijit.byId("ideasPopup").open)
				getCommentsForIdea();
			else	
				getCompareComments();
		}); 
	}
	return false;
}

function addFeatureItem(fId, evalId) {
	doAction({action: "createFeatureItem", featureId: fId, ideaFeatureEvaluationId:evalId}, 'getFeatureEvaluationsForIdea()');
}
 
function updateFeatureItem(featureItemId,featureForm){
	formData = getInputDataFromId(featureForm);
	formData['action'] = 'updateFeatureItem';
	doAction(getSerializedArray(formData));
}

function deleteFeatureItem(fid){
	if (confirm(removeString)) {
		doAction({action: "deleteFeatureItem", featureEvaluationId:fid}, 'getFeatureEvaluationsForIdea()');
	} else {
		return false;
	}
}

function addFeatureEvaluation(selector) {
	doAction($("#"+selector).serialize(), 'getFeatureEvaluationsForIdea()');
}
 
function updateFeatureEvaluation(featureForm){
	formData = getInputDataFromId(featureForm);
	formData['action'] = 'updateFeatureEvaluation';
	formData['score'] = $("#" + featureForm + " span.itemTotal").html();
	doAction( getSerializedArray(formData) );
}

function deleteFeatureEvaluation(fid){
	if (confirm(removeString)) {
		doAction( {action: "deleteFeatureEvaluation", featureEvaluationId:fid}, 'getFeatureEvaluationsForIdea()');
	} else {
		return false;
	}
}

function updateProfile(form) {
	formData = getInputDataFromId(form);
	formData['action'] = 'updateProfile';
	doAction(getSerializedArray(formData));
}

//////////////// SELECTIONS ////////////////
function showAddSelectIdea(elem) {
	showCommonPopup(elem);
	if (currentGroupId == null ) {
		$("#commonPopup #actionDetails").load("engine.ajax.php?action=getAddSelect");
	} else {
		$("#commonPopup #actionDetails").load("engine.ajax.php?action=getAddSelectForGroup&groupId="+currentGroupId);
	}
} 

function addSelectItem(id) {
	dijit.byId('commonPopup').hide();
	doAction({action: "createSelection", ideaId:id}, 'getSelect()');
}

function deleteSelectIdea(id){
	if (confirm(removeString)) {
		doAction({action: "deleteSelection", selectionId:id}, 'getSelect()');
	} else {
		return false;
	}
}

function updateSelection(selectform){
	formData = getInputDataFromId(selectform);
	formData['action'] = 'updateSelection';
	doAction(getSerializedArray(formData), 'getSelect()');
}

function printPopupIdea() {
	printIdea("&idea=" + currentIdeaId);
}

function printIdea(urlE) {
	genericPrintViewer("viewer.php?print=true" + urlE);
}

function printUser(urlE) {
	genericPrintViewer("viewer.php?print=true" + urlE);
}

function printGroup(urlE) {
	genericPrintViewer("viewer.php?print=true&group=" + currentGroupId);
}

function printGroupSummary(urlE) {
	genericPrintViewer("viewer.php?print=true" + urlE);
}

function genericPrintViewer(url) {
	window.open(url);
}

function genericPrint(url, id) {
	var sendId;
	if (id != null && id != undefined) {
		sendId = id;
	} else {
		sendId = currentIdeaId;
	}
	newWin = window.open(url + sendId);
	newWin.print();
}

// IDEA SHARING TOGGLING
function togglePublicIdea(elem) {
	if($(elem).is(':checked')) {
		doAction("action=updateIdeaDetails&ideaId="+currentIdeaId+"&isPublic=1");
	} else {
		doAction("action=updateIdeaDetails&ideaId="+currentIdeaId+"&isPublic=0");
	}
}

function toggleGroupShareIdea(elem, id) {
	if ($(elem).is(':checked')) {
		addIdeaToGroup(currentIdeaId, id);
	} else {
		sendDelIdeaFromGroup(currentIdeaId, id);
	}
}

function toggleGroupEditIdea(elem, iId, gId) {
	if ($(elem).is(':checked')) 
		doAction({action: "editIdeaToGroup", ideaId:iId, groupId:gId});
	else
		doAction({action: "rmEditIdeaToGroup", ideaId:iId, groupId:gId});
}

function updateFeatureEvalSummary(elem, id) { 
	doAction({action: "updateIdeaFeatureEvaluation", ideaFeatureEvaluationId:id, summary:$(elem).val()});
}

//SEARCH OPTIONS VISIBILITY
function toggleSearchOptions() {
	$("#searchHider").toggle();
	$("#searchOptions").toggle();
}

//TOGGLE Profiles
function togglePublicProfile(elem) {
	if ($(elem).is(':checked')) {
		doAction({action:'updateProfile', isPublic:1});
	} else {
		doAction({action:'updateProfile', isPublic:0});
	}
}

function toggleSendEmail(elem) {
	if ($(elem).is(':checked')) {
		doAction({action:'updateProfile', sendEmail:1});
	} else {
		doAction({action:'updateProfile', sendEmail:0});
	}
}

function findUsers() {
	var data = $("#popupAddSearch").serialize();
	showLoading("#commonPopup #actionDetails");
	var url = "engine.ajax.php?action=getAddIdea";
	if (data != undefined) 
		url += "&" + data;
	$("#commonPopup #actionDetails").load(url);
}


function findUsers() {
	var data = $("#popupAddSearch").serialize();
	showLoading("#commonPopup #actionDetails");
	var url = "engine.ajax.php?action=getAddUser"; 
	if (data != undefined) 
		url += "&" + data;
	$("#commonPopup #actionDetails").load(url);
}

function findIdeas() {
	commonAddFind("getAddIdea");
}

function findPublicIdeas() {
	commonAddFind("getPublicAddIdea");
}

function findAddSelectIdeas() {
	commonAddFind("getAddSelect");
}

function findAddRiskIdeas() {
	commonAddFind("getAddRisk");
}

function commonAddFind(action) {
	var data = $("#popupAddSearch").serialize();
	showLoading("#commonPopup #actionDetails");
	var url = "engine.ajax.php?action=" + action;
	if (data != undefined) 
		url += "&" + data;
	$("#commonPopup #actionDetails").load(url);
}

var ideateFormString = "<form class='addForm'>Click here to select an idea <input type='button' onclick='showAddSelectIdea(this)' value=' + ' title='Select an idea to work on' /></form>";
var compareFormString = "<form class='addForm'>Click here to add idea to comparison <input type='button' onclick='showAddRiskItem(this)' value=' + ' title='Add an idea to comparison' /></form>";
var selectFormString = "<form class='addForm'>Click here to select an idea <input type='button' onclick='showAddSelectIdea(this)' value=' + ' title='Select an idea to work on' /></form>";

function showGroupComments(elem) {
	$("ul.submenu li").removeClass("selected");
	$(elem).parent().addClass("selected");
	$(".groupInfo").html('<form class="addForm" onsubmit="addCompareComment(this);return false;">' +
			'<input type="hidden" name="action" value="addComment" />' +
			'Comments <input type="submit" value=" + " />' + 
			'<textarea name="text" dojoType="dijit.form.Textarea" style="width: 100%"></textarea> '+
			'</form>' +
			'<div class="compareCommentList"></div>');
	getCompareComments();
}
function showGroupSubDetails(elem) {
	$("ul.submenu li").removeClass("selected");
	$(elem).parent().addClass("selected");
	$(".groupInfo").load("engine.ajax.php?action=getGroupDetailsTab&actionId=" + currentGroupId, function() {
		dojo.parser.instantiate(dojo.query('#groupDetailsForm *'));
		$('#groupDetailsForm').find("textarea").blur(function() {
			updateGroupDetails("#groupDetailsForm");
		});
	});
}
function showGroupIdeate(elem) {
	$("ul.submenu li").removeClass("selected");
	$(elem).parent().addClass("selected");
	$(".groupInfo").html('<form class="addForm" onsubmit="addPrivateIdea(this); return false;">' +
			'<span>Add a <b>private</b> idea to group</span>' +
			'<input type="button" value=" + " title="Add idea" onclick="addPrivateIdea(this)"/>' +
			'</form>'+
			'<div class="ideasList"></div>');
	getIdeas();
}
function showGroupCompare(elem) {
	$("ul.submenu li").removeClass("selected");
	$(elem).parent().addClass("selected");
	$(".groupInfo").html(compareFormString + '<div class="compareList"></div>');
	getCompare();
}
function showGroupSelect(elem) {
	$("ul.submenu li").removeClass("selected");
	$(elem).parent().addClass("selected");
	$(".groupInfo").html(selectFormString + '<div class="selectList"></div>');
	getSelect();
}

function showProfileSubDetails(elem) {
	$("ul.submenu li").removeClass("selected");
	$(elem).parent().addClass("selected");
	$(".profileInfo").load("engine.ajax.php?action=getProfileDetailsTab", function() {
		dojo.parser.instantiate(dojo.query('#profileTab *'));
		$("#profileDetailsForm").find(":input").each(function() {
			$(this).blur(function() {
				updateProfile("profileDetailsForm");
			});
		});
	});
}
function showProfileIdeate(elem) {
	$("ul.submenu li").removeClass("selected");
	$(elem).parent().addClass("selected");
	$(".profileInfo").html(
			'<form id="addPrivateIdeaForm" class="addForm" onsubmit="addPrivateIdea(this); return false;">' +
				'<span>Add new idea</span>' + 
				'<input name="title" type="text"/>' +
				'<input type="button" value=" + " title="Add idea" onclick="addPrivateIdea(this)"/>' +
				'<input style="display:none" type="submit" />' +
				'<input type="hidden" name="action" value="addIdea" />' +
			'</form>' +
			'<div class="ideasList"></div>');
	getIdeas();
}

function showProfileCompare(elem) {
	$("ul.submenu li").removeClass("selected");
	$(elem).parent().addClass("selected");
	$(".profileInfo").html(compareFormString + '<div class="compareList"></div>');
	getCompare();
}
function showProfileSelect(elem) {
	$("ul.submenu li").removeClass("selected");
	$(elem).parent().addClass("selected");
	$(".profileInfo").html(selectFormString + '<div class="selectList"></div>');
	getSelect();
}
function showProfileNotes(elem) {
	$("ul.submenu li").removeClass("selected");
	$(elem).parent().addClass("selected");
	$(".profileInfo").html('<div id="noteTab"></div>');
	getNotes();
}

function showPublicIdeate(elem) {
	$("ul.submenu li").removeClass("selected");
	$(elem).parent().addClass("selected");
	$(".publicInfo").html('<form class="addForm" onsubmit="addPrivateIdea(this); return false;">' +
			'<span>Make a <b>private</b> idea public</span>' +
			'<input type="button" value=" + " title="Add idea" onclick="addPrivateIdea(this)"/>' +
			'</form>'+
			'<div class="ideasList"></div>');
	getIdeas();
}

function showPublicCompare(elem) {
	$("ul.submenu li").removeClass("selected");
	$(elem).parent().addClass("selected");
	$(".publicInfo").html(compareFormString + '<div class="compareList"></div>');
	getCompare();
}

function showPublicSelect(elem) {
	$("ul.submenu li").removeClass("selected");
	$(elem).parent().addClass("selected");
	$(".publicInfo").html(selectFormString + '<div class="selectList"></div>');
	getSelect();
}

function showHelp() {
	var help = new inno.Dialog({title:"Help", href:"help/help.html", style: "width:1000px; height:530px;"});
	dojo.body().appendChild(help.domNode);
	help.startup();
	help.show();
}

function showHelpSize(elem, sizeProps, selfVisibility) {
	$(elem).css('visibility', selfVisibility);
	$(elem).parent().stop().animate(sizeProps);
}