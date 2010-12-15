var queue = new Array();

function logAction() {}

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
	$(selector).html("<div class='loadingAnim'></div>");
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
	$("span#ideaName").load("ideas.ajax.php?action=getIdeaName&actionId=" + currentIdeaId, function() { 
		loadIdeaPopupData();
	});
}

function getMission(formId,actionId) { 
	showLoading("#"+formId);
	$.get("ideas.ajax.php?action=getMission&actionId=" + actionId, function (data) {
		$("#"+formId).html(data);
		dojo.parser.instantiate(dojo.query('#' + formId + ' *'));
		$('#' + formId).find("textarea").blur(function() {
			updateIdeaDetails("#ideadetails_form_"+currentIdeaId);
		});
	});
}

function getFeaturesForm(formId,actionId) {
	showLoading("#"+formId);
	$.get("ideas.ajax.php?action=getFeaturesForm&actionId=" + actionId, function (data) {
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
	$.get("ideas.ajax.php?action=getRolesForm&actionId=" + actionId, function (data) {
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
	$.get("ideas.ajax.php?action=getFeatures&actionId=" + actionId, function (data) {
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
	$.get("ideas.ajax.php?action=getRoles&actionId=" + actionId, function (data) {
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

function showIdeaSummary(id) {
	var idea = new inno.Dialog({href:"compare.ajax.php?action=getIdeaSummary&actionId="+id, style: "width: 250px;height:" + (document.documentElement.clientHeight * 0.75) + "px;"});
	dojo.body().appendChild(idea.domNode);
	idea.startup();
	idea.show();
}

function showProfileSummary(id) {
	var profile = new inno.Dialog({href:"profile.ajax.php?action=getProfileSummary&actionId="+id, style: "width: 250px;height:" + (document.documentElement.clientHeight * 0.75) + "px;"});
	dojo.body().appendChild(profile.domNode);
	profile.startup();
	profile.show();
}

function showGroupSummary(id) {
	var group = new inno.Dialog({href:"groups.ajax.php?action=getGroupSummary&actionId="+id, style: "width: 250px;height:" + (document.documentElement.clientHeight * 0.75) + "px;"});
	dojo.body().appendChild(group.domNode);
	group.startup();
	group.show();
}

function showIdeaDetails(ideaId) { 
	currentIdeaId = ideaId;
	loadPopupShow();
}

function moveDialogsToSide() {}

///// GROUP SELECTION////////////
function showIdeaGroupsForUser() {
	$.get("ideas.ajax.php?action=getIdeaGroupsForUser", function (data) {
		$(".ideaGroupsList").html(data);
		//dojo.parser.instantiate(dojo.query("div.ideaGroupsSel *"));
		//dojo.parser.instantiate(dojo.query("div.ideaGroupsSel"));
		//$("button span.dijitButtonText").html(currentGroupName);
		$(".ideaGroupsList div").removeClass("selected");
		if (currentGroupName == "Public") {
			$(".ideaGroupsList div.public").addClass("selected");
		} else if (currentGroupName == "Private") {
			$(".ideaGroupsList div.private").addClass("selected");
		} else {
			$(".ideaGroupsList div.groupSel_"+currentGroupId).addClass("selected");
		}
	});
}

function showStuffForTab() {
	if (!($("#ideaTab").is(":hidden"))) {
		getIdeas();
	} else if (!($("#compareTab").is(":hidden"))){
		getCompare();
	} else if (!($("#selectTab").is(":hidden"))) {
		getSelect();
	}
}

function showDefaultIdeas() {
	currentGroupId = null;
	currentGroupName = "Private";
	showStuffForTab();
	showIdeaGroupsForUser();
}

function showPublicIdeas() {
	currentGroupId = null;
	currentGroupName = "Public";
	showStuffForTab();
	showIdeaGroupsForUser();
}

function showIdeasForGroup(gId, elem) {
	currentGroupId = gId;
	currentGroupName = elem;
	showStuffForTab();
	showIdeaGroupsForUser();
}

///////////// GET FUNCTIONS ///////////////

function getNotes() {
	showLoading("#noteTab");
	$("#noteTab").load("notes.php");
}

function getDash() {
	showLoading("#dashTab");
	$("#dashTab").load("dash.php");
}

function getIdeas() {
	showLoading("#ideasList");
	if (currentGroupId == null && currentGroupName == "Private") {
		$.get("ideas.ajax.php?action=getIdeas", function (data) {
			$("#ideasList").html(data);
		});
		$("#addIdeaForm span").html("Add new idea");
		$("#addIdeaTitle").show();
	} else if (currentGroupId == null && currentGroupName == "Public") {
		$.get("ideas.ajax.php?action=getPublicIdeas", function (data) {
			$("#ideasList").html(data);
		});
		$("#addIdeaForm span").html("Make a private idea public");
		$("#addIdeaTitle").hide();
	} else { 
		$.get("ideas.ajax.php?action=getIdeasForGroup&groupId="+currentGroupId, function (data) {
			$("#ideasList").html(data);
		});
		$("#addIdeaForm span").html("Add a private idea to the group");
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
	showLoading("#compareList");
	if (currentGroupId == null && currentGroupName == "Private") {
		$.get("compare.ajax.php?action=getComparison", function (data) {
			$("#compareList").html(data);
			prepCompareTable();
		});
	} else if (currentGroupId == null && currentGroupName == "Public") {
		$.get("compare.ajax.php?action=getPublicComparison", function (data) {
			$("#compareList").html(data);
			prepCompareTable();
		});
	} else { 
		$.get("compare.ajax.php?action=getComparisonForGroup&groupId="+currentGroupId, function (data) {
			$("#compareList").html(data);
			prepCompareTable();
		});
	}
	getCompareComments();
}

function getSelect() {
	showLoading("#selectList");
	if (currentGroupId == null && currentGroupName == "Private") {
		$("#selectList").load("select.ajax.php?action=getSelection");
	} else if (currentGroupId == null && currentGroupName == "Public") {
		$("#selectList").load("select.ajax.php?action=getPublicSelection");
	} else {
		$("#selectList").load("select.ajax.php?action=getSelectionForGroup&actionId="+currentGroupId);
	}
}

function getProfile() {
	showLoading("#profileTab");
	$.get("profile.ajax.php?action=getProfile", function (data) {
		$("#profileTab").html(data);
		dojo.parser.instantiate(dojo.query('#profileTab *'));
		$("#profileDetailsForm").find(":input").each(function() {
			$(this).blur(function() {
				updateProfile("profileDetailsForm");
			});
		});
	});
}

function getGroups() {
	showLoading("#groupsList");
	$.get("groups.ajax.php?action=getGroups", function (data) {
		$("#groupsList").html(data);
		showIdeaGroupsForUser();
	});
	
	if (currentGroupId != null)
		showGroupDetails();
}

function getReports() {
	showLoading("#reportList");
	$.get("reports.ajax.php?action=getReportDetails", function (data) {
		$("#reportDetails").html(data);
		$.get("reports.ajax.php?action=getReportGraphs", function (data) {
			$("#reportList").html(data);
		});
	});
}

function getSearch() {
	var data = $("#searchForm").serialize();
	showLoading("#searchTab #searchResults");
	url = "search.php?" + data;
	$("#searchTab #searchResults").load(url, function() {
		dojo.parser.instantiate(dojo.query("#searchOptions *")); 
	});
}

function getAdmin(){}

function getAttachments() {
	showLoading("#ideaAttachments");
	$("#ideaAttachments").load("ideas.ajax.php?action=getAttachments&actionId="+currentIdeaId);
}

function getRiskEvalForIdea() {
	showLoading("#ideaRiskEval");
	$("#ideaRiskEval").load("compare.ajax.php?action=getRiskEvalForIdea&actionId="+currentIdeaId, function() {
		$("#ideaRiskEvalDetails").find(":input").each(function() {
			$(this).blur(function() {
				updateRisk("ideaRiskEvalDetails");
			});
		});
	});
}

function getShareForIdea() {
	showLoading("#ideaShare");
	$("#ideaShare").load("groups.ajax.php?action=getShareForIdea&actionId="+currentIdeaId);
}

function getSelectForIdea() {
	showLoading("#ideaSelect");
	$("#ideaSelect").load("select.ajax.php?action=getSelectForIdea&actionId="+currentIdeaId, function () {
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
	$("#searchTab").show();	
}

function showIdeas(elem) {
	$(".menulnk").parent().removeClass("selMenu");
	$("#ideaslnk").parent().addClass("selMenu");
	$(".menulnk").removeClass("selLnk");
	$("#ideaslnk").addClass("selLnk");
	getIdeas();
	showIdeaGroupsForUser();
	$(".tabBody").hide();
	$("#ideaTab").show();
}

function showReports(elem) {
	$(".menulnk").parent().removeClass("selMenu");
	$("#reportslnk").parent().addClass("selMenu");
	$(".menulnk").removeClass("selLnk");
	$("#reportslnk").addClass("selLnk");
	getReports();
	$(".tabBody").hide();
	$("#reportTab").show();
}	

function showProfile(elem) {
	$(".menulnk").parent().removeClass("selMenu");
	$("#profilelnk").parent().addClass("selMenu");
	$(".menulnk").removeClass("selLnk");
	$("#profilelnk").addClass("selLnk");
	getProfile();
	$(".tabBody").hide();
	$("#profileTab").show();
}

function showGroups(elem) {
	$(".menulnk").parent().removeClass("selMenu");
	$("#groupslnk").parent().addClass("selMenu");
	$(".menulnk").removeClass("selLnk");
	$("#groupslnk").addClass("selLnk");
	getGroups(); 
	$(".tabBody").hide();
	$("#groupTab").show();
}

function showCompare(elem) {
	$(".menulnk").parent().removeClass("selMenu");
	$("#comparelnk").parent().addClass("selMenu");
	$(".menulnk").removeClass("selLnk");
	$("#comparelnk").addClass("selLnk");
	getCompare();
	showIdeaGroupsForUser();
	$(".tabBody").hide();
	$("#compareTab").show();
}

function showSelect(elem) {
	$(".menulnk").parent().removeClass("selMenu");
	$("#selectlnk").parent().addClass("selMenu");
	$(".menulnk").removeClass("selLnk");
	$("#selectlnk").addClass("selLnk");
	getSelect();
	showIdeaGroupsForUser();
	$(".tabBody").hide();
	$("#selectTab").show();	
}

function showDash(elem) {
	$(".menulnk").parent().removeClass("selMenu");
	$("#dashlnk").parent().addClass("selMenu");
	$(".menulnk").removeClass("selLnk");
	$("#dashlnk").addClass("selLnk");
	getDash();
	$(".tabBody").hide();
	$("#dashTab").show();
}

function showAdmin(elem) {	
	$(".menulnk").parent().removeClass("selMenu");
	$("#adminlnk").parent().addClass("selMenu");
	$(".menulnk").removeClass("selLnk");
	$("#adminlnk").addClass("selLnk");
	getAdmin();
	$(".tabBody").hide();
	$("#adminTab").show();	
}

function showTimelines(elem) {
	$(".menulnk").parent().removeClass("selMenu");
	$("#timelinelnk").parent().addClass("selMenu");
	$(".menulnk").removeClass("selLnk");
	$("#timelinelnk").addClass("selLnk");
	$("#timelineTab").load("timeline.php");
	$(".tabBody").hide();
	$("#timelineTab").show();	
}

function showNotes() {
	$(".menulnk").parent().removeClass("selMenu");
	$("#noteslnk").parent().addClass("selMenu");
	$(".menulnk").removeClass("selLnk");
	$("#noteslnk").addClass("selLnk");
	getNotes();
	$(".tabBody").hide();
	$("#noteTab").show();
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

function showHelp(text) {
	$('#commonPopup #actionDetails').empty();
	$('#commonPopup #actionDetails').html(text);
	dijit.byId('commonPopup').show();
}

//Add another contains method
jQuery.expr[':'].Contains = function(a,i,m){
  return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
};

function filterIdeas(element) {
	var filter = $(element).val();
  if (filter != '' && filter != null) { 
    $("#ideasList .idea .formHead").find(".ideatitle:not(:Contains('" + filter + "'))").parent().parent().slideUp();
    $("#ideasList .idea .formHead").find(".ideatitle:Contains('" + filter + "')").parent().parent().slideDown();
  } else {
    $("#ideasList .idea .formHead").find(".ideatitle").parent().parent().slideDown();
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

function genericFormUpdate(target, element) {}

function genericFieldUpdate(target, element) {}

function addNote(element) {
	$.post("notes.ajax.php", $(element).serialize(), function(data) {
		showResponses( data, true);
		showNotes();
	});
}

/////// IDEA FUNCTIONS /////////
function addIdea(elem) {
	if(currentGroupId == null && currentGroupName == "Private") {
		$.post("ideas.ajax.php", $("#addIdeaForm").serialize(), function(data) {
			showResponses( data, true);
			getIdeas();
		});
	} else if(currentGroupId == null && currentGroupName == "Public") {
		showAddPublicIdea(elem);
	} else {
		showAddGroupIdea(elem);
	}
}

function deleteIdea(iId) {
	if (confirm("Are you sure you wish to delete this idea?")) {
		$.post("ideas.ajax.php", {action:"deleteIdea", ideaId:iId}, function (data) {
			showResponses( data, true);
			getIdeas();
		});
	} 
	return false;
}

function updateIdeaDetails(formId) {
	$.post("ideas.ajax.php", $(formId).serialize(), function (data) {
		showResponses( data, true);
	});
}

function showIdeaOptions(element) {}

function hideIdeaOptions(element) {}

function updateFeature(form) {
	formData = getInputDataFromId(form);
	formData['action'] = 'updateFeature';
	$.post("ideas.ajax.php", getSerializedArray(formData), function(data) {
		showResponses( data, true);
	});
}

function updateRole(form) {
	formData = getInputDataFromId(form);
	formData['action'] = 'updateRole';
	$.post("ideas.ajax.php", getSerializedArray(formData), function(data) {
		showResponses( data, true);
	});
}

function addFeature(selector, callbackForm, callbackIdea) {
	$.post("ideas.ajax.php", $("#"+selector).serialize(), function(data) {
		showResponses( data, true);
		getFeatures(callbackForm, callbackIdea);
	});	
}

function addRole(selector, callbackForm, callbackIdea) {
	$.post("ideas.ajax.php", $("#"+selector).serialize(), function(data) {
		showResponses( data, true);
		getRoles(callbackForm, callbackIdea);
	});	
}

function deleteFeature(target, id, callbackForm, callbackIdea) {
	if (confirm(removeString)) {
		$.post("ideas.ajax.php", {actionId:id, action:target}, function(data) {
			showResponses( data, true);
			getFeatures(callbackForm, callbackIdea);
		});		
	} 
	return false;
}

function deleteRole(target, id, callbackForm, callbackIdea) {
	if (confirm(removeString)) {
		$.post("ideas.ajax.php", {actionId:id, action:target}, function(data) {
			showResponses( data, true);
			getRoles(callbackForm, callbackIdea);
		});		
	} 
	return false;
}

///////////////// GROUP ///////////////

function addGroup() {
	$.post("groups.ajax.php", $("#addGroupForm").serialize(), function(data) {
		showResponses( data, true);
		getGroups();
	});
}

function deleteGroup(gId) {
	if (confirm(removeString)) {
		$.post("groups.ajax.php", {action: "deleteGroup", groupId:gId}, function(data) {
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
	$.post("groups.ajax.php", $("#groupDetailsForm").serialize(), function(data) {
		showResponses(data, true);
	});
}

function showGroupDetails() {
	$.get("groups.ajax.php?action=getGroupDetails&actionId="+currentGroupId, function(data) {
		$("#groupDetails").html(data);
		dojo.parser.instantiate(dojo.query('#groupDetailsForm *'));
		$('#groupDetailsForm').find("textarea").blur(function() {
			updateGroupDetails("#groupDetailsForm");
		});
	});
}

function acceptGroup() {
	$.post("groups.ajax.php", {action: "acceptGroup", actionId:currentGroupId}, function(data) {
		showResponses( data, true);
		showGroupDetails();
	});
}

function updateForGroup(id,name) {
	currentGroupId = id;
	currentGroupName = name;
	showGroupDetails();
	showIdeaGroupsForUser();
}

function showAddGroupIdea(elem) {
	showCommonPopup(elem);
	$.get("groups.ajax.php?action=getAddIdea", function(data) {
		$("#actionDetails").html(data);
	});
}

function showAddPublicIdea(elem) {
	showCommonPopup(elem);
	$.get("groups.ajax.php?action=getPublicAddIdea", function(data) {
		$("#actionDetails").html(data);
	});
}

function showAddGroupUser(elem) {
	showCommonPopup(elem);
	$.get("groups.ajax.php?action=getAddUser", function(data) {
		$("#actionDetails").html(data);
	});
} 

function addUserToCurGroup(id) {
	dijit.byId('commonPopup').hide();
	$.post("groups.ajax.php", {action: "linkUserToGroup", userId:id, groupId:currentGroupId}, function(data) {
		showResponses( data, true);
		showGroupDetails();
	});
}

function addIdeaToGroup(id, gId) {
	dijit.byId('commonPopup').hide();
	$.post("groups.ajax.php", {action: "linkIdeaToGroup", ideaId:id, groupId:gId}, function(data) {
		showResponses( data, true);
		showGroupDetails();
	});
}

function addIdeaToCurGroup(id) {
	addIdeaToGroup(id, currentGroupId);
}

function addIdeaToPublic(id) {
	dijit.byId('commonPopup').hide();
	$.post("groups.ajax.php", {action: "addIdeaToPublic", actionId:id}, function(data) {
		showResponses(data, true);
		getIdeas();
	});
}

function refuseGroup() {
	$.post("groups.ajax.php", {action: "refuseGroup", actionId:currentGroupId}, function(data) {
		showResponses( data, true);
		currentGroupId = null;
		showGroupDetails();
	});
}

function requestGroup() {
	$.post("groups.ajax.php", {action: "requestGroup", actionId:currentGroupId}, function(data) {
		showResponses( data, true);
		showGroupDetails();
	});
}

function approveGroupUser(uId) {
	$.post("groups.ajax.php", {action: "approveGroupUser", actionId:currentGroupId, userId:uId}, function(data) {
		showResponses( data, true);
		showGroupDetails();
	});
}

function delUserFromCurGroup(id) {
	if (confirm("Are you sure you wish to remove the user from this group?")) {
		$.post("groups.ajax.php", {action: "unlinkUserToGroup", userId:id, groupId:currentGroupId}, function(data) {
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
	$.post("groups.ajax.php", {action: "unlinkIdeaToGroup", ideaId:id, groupId:gId}, function(data) {
		showResponses( data, true);
		showGroupDetails();
	});
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
		$.get("compare.ajax.php?action=getAddRisk", function(data) {
			$("#commonPopup #actionDetails").html(data);
		});
	} else {
		$.get("compare.ajax.php?action=getAddRiskForGroup&groupId="+currentGroupId, function(data) {
			$("#commonPopup #actionDetails").html(data);
		});
	}
} 

function addRiskItem(id) {
	dijit.byId('commonPopup').hide();
	$.post("compare.ajax.php", {action: "createRiskItem", ideaId:id}, function(data) {
		showResponses( data, true);
		showCompare();
	});
}

function addRiskItemForGroup(id, groupId) {
	dijit.byId('commonPopup').hide();
	$.post("compare.ajax.php", {action: "createRiskItemForGroup", ideaId:id, groupId:groupId}, function(data) {
		showResponses( data, true);
		showCompare();
	});
}
 
function updateRisk(riskform){
	formData = getInputDataFromId(riskform);
	formData['action'] = 'updateRiskItem';
	formData['score'] = $("#" + riskform + " span.itemTotal").html();
	$.post("compare.ajax.php", getSerializedArray(formData), function(data) {
		showResponses(data, true);
	});
}

function deleteRisk(riskid){
	if (confirm("Are you sure you wish to remove this risk item?")) {
		$.post("compare.ajax.php", {action: "deleteRiskItem", riskEvaluationId:riskid}, function(data) {
			showResponses( data, true);
			showCompare();
		});
	} 
	return false;
}

///////////// REVIEWS /////////////////////
function getCommentsForIdea() {
	$.get("ideas.ajax.php?action=getCommentsForIdea&actionId="+currentIdeaId, function(data) {
		$("#commentList").html(data);
	});
}

function getCompareComments() {
	if (currentGroupId == null && currentGroupName == "Private") {
		$.get("compare.ajax.php?action=getCompareComments", function(data) {
			$("#compareCommentList").html(data);
			$("#addCompareCommentForm").show();
		});
	} else if (currentGroupId == null && currentGroupName == "Public") {
		/*$.get("compare.ajax.php?action=getPublicCompareComments", function(data) {
			$("#compareCommentList").html(data);
			$("#addCompareCommentForm").hide();
		});*/
		$("#compareCommentList").empty();
		$("#addCompareCommentForm").hide();
	} else { 
		$.get("compare.ajax.php?action=getCompareCommentsForGroup&actionId="+currentGroupId, function(data) {
			$("#compareCommentList").html(data);
			$("#addCompareCommentForm").show();
		});
	}
}

function getFeatureEvaluationsForIdea() {
	$.get("ideas.ajax.php?action=getIdeaFeatureEvaluationsForIdea&actionId="+currentIdeaId, function(data) {
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
	$.post("ideas.ajax.php", $("#addCommentForm").serialize()+"&ideaId="+currentIdeaId, function(data) {
		showResponses( data, true);
		getCommentsForIdea();
	});
}

function addCompareComment() {
	var urlAddition = "";
	if (currentGroupId)
		urlAddition = "&groupId="+currentGroupId;
	$.post("ideas.ajax.php", $("#addCompareCommentForm").serialize()+urlAddition, function(data) {
		showResponses( data, true);
		getCompareComments();
	});
}

function deleteComment(cid) {
	if (confirm(removeString)) {
		$.post("ideas.ajax.php", {action: "deleteComment", commentId:cid}, function(data) {
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
	$.post("ideas.ajax.php", {action: "createFeatureItem", featureId: fId, ideaFeatureEvaluationId:evalId}, function(data) {
		showResponses( data, true);
		getFeatureEvaluationsForIdea();
	});
}
 
function updateFeatureItem(featureItemId,featureForm){
	formData = getInputDataFromId(featureForm);
	formData['action'] = 'updateFeatureItem';
	$.post("ideas.ajax.php", getSerializedArray(formData), function(data) {
		showResponses( data, true);
	});
}

function deleteFeatureItem(fid){
	if (confirm(removeString)) {
	$.post("ideas.ajax.php", {action: "deleteFeatureItem", featureEvaluationId:fid}, function(data) {
		showResponses( data, true);
		getFeatureEvaluationsForIdea();
	});
	} else {
		return false;
	}
}

function addFeatureEvaluation(selector) {
	$.post("ideas.ajax.php", $("#"+selector).serialize(), function(data) {
		showResponses( data, true);
		getFeatureEvaluationsForIdea();
	});
}
 
function updateFeatureEvaluation(featureForm){
	formData = getInputDataFromId(featureForm);
	formData['action'] = 'updateFeatureEvaluation';
	formData['score'] = $("#" + featureForm + " span.itemTotal").html();
	$.post("ideas.ajax.php", getSerializedArray(formData), function(data) {
		showResponses( data, true);
	});
}

function deleteFeatureEvaluation(fid){
	if (confirm(removeString)) {
	$.post("ideas.ajax.php", {action: "deleteFeatureEvaluation", featureEvaluationId:fid}, function(data) {
		showResponses( data, true);
		getFeatureEvaluationsForIdea();
	});
	} else {
		return false;
	}
}

function updateProfile(form) {
	formData = getInputDataFromId(form);
	formData['action'] = 'updateProfile';
	$.post("profile.ajax.php", getSerializedArray(formData), function(data) {
		showResponses( data, true);
	});
}

//////////////// SELECTIONS ////////////////
function showAddSelectIdea(elem) {
	showCommonPopup(elem);
	if (currentGroupId == null ) {
		$.get("select.ajax.php?action=getAddSelect", function(data) {
			$("#commonPopup #actionDetails").html(data);
		});
	} else {
		$.get("select.ajax.php?action=getAddSelectForGroup&groupId="+currentGroupId, function(data) {
			$("#commonPopup #actionDetails").html(data);
		});
	}
} 

function addSelectItem(id) {
	dijit.byId('commonPopup').hide();
	$.post("select.ajax.php", {action: "createSelection", ideaId:id}, function(data) {
		showResponses( data, true);
		showSelect();
	});
}

function deleteSelectIdea(id){
	if (confirm(removeString)) {
	$.post("select.ajax.php", {action: "deleteSelection", selectionId:id}, function(data) {
		showResponses( data, true);
		showSelect();
	});
	} else {
		return false;
	}
}

function updateSelection(selectform){
	formData = getInputDataFromId(selectform);
	formData['action'] = 'updateSelection';
	$.post("select.ajax.php", getSerializedArray(formData), function(data) {
		showResponses( data, true);
		showCompare();
	});
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
function publicIdea() {
	var publicIdeaPost = "action=updateIdeaDetails&ideaId="+currentIdeaId+"&isPublic=1";
	$.post("ideas.ajax.php", publicIdeaPost, function (data) {
		showResponses( data, true);
	});
}

function unpublicIdea() {
	var publicIdeaPost = "action=updateIdeaDetails&ideaId="+currentIdeaId+"&isPublic=0";
	$.post("ideas.ajax.php", publicIdeaPost, function (data) {
		showResponses( data, true);
	});
}

function togglePublicIdea(elem) {
	if($(elem).is(':checked')) {
		publicIdea();
	} else {
		unpublicIdea();
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
	if ($(elem).is(':checked')) {
		addEditPermToGroup(elem, iId, gId);
	} else {
		removeEditPermToGroup(elem, iId, gId);
	}
}

function addEditPermToGroup(elem, iId, gId) {
	$.post("groups.ajax.php", {action: "editIdeaToGroup", ideaId:iId, groupId:gId}, function(data) {
		showResponses( data, true);
	});
}

function removeEditPermToGroup(elem, iId, gId) {
	$.post("groups.ajax.php", {action: "rmEditIdeaToGroup", ideaId:iId, groupId:gId}, function(data) {
		showResponses( data, true);
	});
}

function updateFeatureEvalSummary(elem, id) { 
	$.post("ideas.ajax.php", {action: "updateIdeaFeatureEvaluation", ideaFeatureEvaluationId:id, summary:$(elem).val()}, function(data) {
		showResponses(data, true);
	});
}


//SEARCH OPTIONS VISIBILITY
function toggleSearchOptions() {
	$("#searchHider").toggle();
	$("#searchOptions").toggle();
}

//TOGGLE Profiles
function togglePublicProfile(elem) {
	if ($(elem).is(':checked')) {
		$.post("profile.ajax.php", {action:'updateProfile', isPublic:1}, function(data) {
			showResponses( data, true);
		});
	} else {
		$.post("profile.ajax.php", {action:'updateProfile', isPublic:0}, function(data) {
			showResponses( data, true);
		});
	}
}

function toggleSendEmail(elem) {
	if ($(elem).is(':checked')) {
		$.post("profile.ajax.php", {action:'updateProfile', sendEmail:1}, function(data) {
			showResponses( data, true);
		});
	} else {
		$.post("profile.ajax.php", {action:'updateProfile', sendEmail:0}, function(data) {
			showResponses( data, true);
		});
	}
}