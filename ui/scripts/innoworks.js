function pollServer() {
	$.get("poll.php", function(data){
		if (data != null && data != '') {
			showResponses("#ideaResponses", data, true);
			if (!($("#noteTab").is(":hidden"))) {
				showNotes();
			}
			//$(".responses").html(data);
		}
	});
}

function loadPopupShow() {
	$("span#ideaName").load("ideas.ajax.php?action=getIdeaName&actionId="+currentIdeaId);
	if (selectedChild == "mission") 
		getMission("ideaMission",currentIdeaId);
	else if (selectedChild == "features")
		getFeaturesForm("ideaFeatures",currentIdeaId);
	else if (selectedChild == "roles")
		getRolesForm("ideaRoles",currentIdeaId);
	else if (selectedChild == "featureEvaulation")
		getFeatureEvaluationsForIdea();
	else if (selectedChild == "comments")
		getCommentsForIdea();
	else 
		getMission("ideaMission",currentIdeaId);
}

function getMission(formId,actionId) { 
	$.get("ideas.ajax.php?action=getMission&actionId=" + actionId, function (data) {
		$("#"+formId).html(data);
		dojo.parser.instantiate(dojo.query('#' + formId + ' *'));
	});
}

function getFeaturesForm(formId,actionId) {
	$.get("ideas.ajax.php?action=getFeaturesForm&actionId=" + actionId, function (data) {
		$("#"+formId).html(data);
		dojo.parser.instantiate(dojo.query('#' + formId + ' *'));
	});
}

function getRolesForm(formId,actionId) {
	$.get("ideas.ajax.php?action=getRolesForm&actionId=" + actionId, function (data) {
		$("#"+formId).html(data);
		dojo.parser.instantiate(dojo.query('#' + formId + ' *'));
	});
}

function getFeatures(formId,actionId) {
	$.get("ideas.ajax.php?action=getFeatures&actionId=" + actionId, function (data) {
		$("#"+formId).html(data);
		dojo.parser.instantiate(dojo.query('#' + formId + ' *'));
	});
}

function getRoles(formId,actionId) {
	$.get("ideas.ajax.php?action=getRoles&actionId=" + actionId, function (data) {
		$("#"+formId).html(data);
		dojo.parser.instantiate(dojo.query('#' + formId + ' *'));
	});
}

//////////// MENU ///////////
function showIdeaReviews(ideaId) {
	showIdeaDetails(ideaId);
	/*
	currentIdeaId = ideaId;
	var tabs = dijit.byId("ideasPopup");
	tabs.selectChild(dojo.byId("ideaComments"));
	loadPopupShow();
	dijit.byId('ideasPopup').show();
	*/
}
 
function showIdeaSummary(id) {
	var idea = new dijit.Dialog({href:"compare.ajax.php?action=getIdeaSummary&actionId="+id});
	dojo.body().appendChild(idea.domNode);
	idea.startup();
	idea.show();
}

function showIdeaDetails(ideaId) { 
	currentIdeaId = ideaId;
	loadPopupShow();
	dijit.byId('ideasPopup').show();
}

///// GROUP SELECTION////////////
function showIdeaGroupsForUser() {
	$.get("ideas.ajax.php?action=getIdeaGroupsForUser", function (data) {
		$(".ideaGroupsList").html(data);
		//$(".ideaGroupsList a[groupId=" + currentGroupId + "]").addClass("selected");
		dojo.parser.instantiate(dojo.query("div.ideaGroupsSel *"));
		dojo.parser.instantiate(dojo.query("div.ideaGroupsSel"));
		$("button span.dijitButtonText").html(currentGroupName);
	});
}

function showDefaultIdeas() {
	currentGroupId = null;
	currentGroupName = "Private";
	$("#addIdeaTitle").removeAttr('disabled');
	//$(".ideaGroups .ideaGroupsList a").removeClass('selected');
	getIdeas();
	getCompare();
	getSelect();
	showIdeaGroupsForUser();
}

function showIdeasForGroup(gId, elem) {
	currentGroupId = gId;
	currentGroupName = elem;
	$("#addIdeaTitle").attr('disabled', 'disabled');
	//$("button span.dijitButtonText").html(elem);
	getIdeas();
	getCompare();
	getSelect();
	showIdeaGroupsForUser();
}

///////////// GET FUNCTIONS ///////////////

function getNotes() {
	$("#noteTab").load("notes.php");
}

function getDash() {
	$("#dashTab").load("dash.php");
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
	//showIdeaGroupsForUser();
} 

function getCompare() {
	if (currentGroupId == null) {
		$.get("compare.ajax.php?action=getComparison", function (data) {
			$("#compareList").html(data);
		});
	} else { 
		$.get("compare.ajax.php?action=getComparisonForGroup&groupId="+currentGroupId, function (data) {
			$("#compareList").html(data);
		});
	}
	//showIdeaGroupsForUser();
}

function getSelect() {
	$.get("select.ajax.php?action=getSelection", function (data) {
		$("#selectList").html(data);
		//showIdeaGroupsForUser();
	});
}

function getProfile() {
	$.get("profile.ajax.php?action=getProfile", function (data) {
		$("#profileTab").html(data);
		dojo.parser.instantiate(dojo.query('#profileTab *'));
	});
}

function getGroups() {
	$.get("groups.ajax.php?action=getGroups", function (data) {
		$("#groupsList").html(data);
		showIdeaGroupsForUser();
	});
}

function getReports() {
	$.get("reports.ajax.php?action=getReportDetails", function (data) {
		$("#reportDetails").html(data);
		$.get("reports.ajax.php?action=getReportGraphs", function (data) {
			$("#reportList").html(data);
		});
	});
}

function getSearch() {
	var searchTerms = $("#searchInput").val();
	$("#searchTab").load("search.php?searchTerms="+searchTerms);
}
function showSearch() {
	$(".menulnk").parent().removeClass("selMenu");
	$("#searchlnk").parent().addClass("selMenu");
	getSearch();
	$(".tabBody").hide();
	$("#searchTab").show();	
}

function getAdmin(){}

function getAttachments() {
	$.get("ideas.ajax.php?action=getAttachments&actionId="+currentIdeaId, function (data) {
		$("#ideaAttachments").html(data);
	});
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

//COMMON methods for doing counts across forms and so on
function initFormSelectTotals(selector) {
	$(selector + " tr").each(function(index, element) {
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

function genericFormUpdate(target, element) {}

function genericFieldUpdate(target, element) {}

function addNote(element) {
	$.post("notes.ajax.php", $(element).serialize(), function(data) {
		showResponses("#ideaResponses", data, true);
		showNotes();
	});
}

/////// IDEA FUNCTIONS /////////
function addIdea() {
	if(currentGroupId == null) {
		$.post("ideas.ajax.php", $("#addIdeaForm").serialize(), function(data) {
			showResponses("#ideaResponses", data, true);
			getIdeas();
		});
	} else {
		showAddGroupIdea();
	}
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

function showIdeaOptions(element) {}

function hideIdeaOptions(element) {}

function updateFeature(id,form, ideaId) {
	formData = getInputDataFromId(form);
	formData['action'] = 'updateFeature';
	$.post("ideas.ajax.php", getSerializedArray(formData), function(data, form, ideaId) {
		showResponses("#ideaResponses", data, true);
	});
}

function updateRole(id,form, ideaId) {
	formData = getInputDataFromId(form);
	formData['action'] = 'updateRole';
	$.post("ideas.ajax.php", getSerializedArray(formData), function(data, form, ideaId) {
		showResponses("#ideaResponses", data, true);
	});
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
		getGroups();
	});
}

function showGroupDetails() {
	$.get("groups.ajax.php?action=getGroupDetails&actionId="+currentGroupId, function(data) {
		$("#groupDetails").html(data);
	});
}

function updateForGroup(id,name) {
	currentGroupId = id;
	currentGroupName = name;
	showGroupDetails();
	showIdeaGroupsForUser();
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
		showResponses("#ideaResponses", data, true);
		showCompare();
	});
}

function addRiskItemForGroup(id, groupId) {
	dijit.byId('commonPopup').hide();
	$.post("compare.ajax.php", {action: "createRiskItemForGroup", ideaId:id, groupId:groupId}, function(data) {
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

function getFeatureEvaluationsForIdea() {
	$.get("ideas.ajax.php?action=getIdeaFeatureEvaluationsForIdea&actionId="+currentIdeaId, function(data) {
		//alert("CURR IDEA: " + currentIdeaId);
		$("#ideaFeatureEvaluationList").html(data);
		dojo.parser.instantiate(dojo.query('#ideaFeatureEvaluationList *'));
	});
}

function getFeatureEvaluationForIdea() {
	$.get("ideas.ajax.php?action=getFeatureEvaluationForIdea&actionId="+currentIdeaId, function(data) {
		$("#ideaFeatureEvaluationList").html(data);
		dojo.parser.instantiate(dojo.query('#ideaFeatureEvaluationList *'));
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

function addFeatureItem(fId, evalId) {
	$.post("ideas.ajax.php", {action: "createFeatureItem", featureId: fId, ideaFeatureEvaluationId:evalId}, function(data) {
		showResponses("#ideaResponses", data, true);
		getFeatureEvaluationsForIdea();
	});
}
 
function updateFeatureItem(featureItemId,featureForm){
	formData = getInputDataFromId(featureForm);
	formData['action'] = 'updateFeatureItem';
	$.post("ideas.ajax.php", getSerializedArray(formData), function(data) {
		showResponses("#ideaResponses", data, true);
		//getFeatureEvaluationForIdea();
	});
}

function deleteFeatureItem(fid){
	$.post("ideas.ajax.php", {action: "deleteFeatureItem", featureEvaluationId:fid}, function(data) {
		showResponses("#ideaResponses", data, true);
		getFeatureEvaluationsForIdea();
	});
}

function addFeatureEvaluation(selector) {
	//alert("FId: " + id);
	$.post("ideas.ajax.php", $("#"+selector).serialize(), function(data) {
		showResponses("#ideaResponses", data, true);
		getFeatureEvaluationsForIdea();
	});
}
 
function updateFeatureEvaluation(FeatureEvaluationId,featureForm){
	formData = getInputDataFromId(featureForm);
	formData['action'] = 'updateFeatureEvaluation';
	$.post("ideas.ajax.php", getSerializedArray(formData), function(data) {
		showResponses("#ideaResponses", data, true);
		//getFeatureEvaluationForIdea();
	});
}

function deleteFeatureEvaluation(fid){
	$.post("ideas.ajax.php", {action: "deleteFeatureEvaluation", featureEvaluationId:fid}, function(data) {
		showResponses("#ideaResponses", data, true);
		getFeatureEvaluationsForIdea();
	});
}

function updateProfile(form){
	formData = getInputDataFromId(form);
	formData['action'] = 'updateProfile';
	$.post("profile.ajax.php", getSerializedArray(formData), function(data) {
		showResponses("#ideaResponses", data, true);
	});
}

//////////////// SELECTIONS ////////////////
function showAddSelectIdea() {
	$('#commonPopup #actionDetails').empty();
	dijit.byId('commonPopup').show();
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
		showResponses("#ideaResponses", data, true);
		showSelect();
	});
}

function deleteSelectIdea(id){
	$.post("select.ajax.php", {action: "deleteSelection", selectionId:id}, function(data) {
		showResponses("#ideaResponses", data, true);
		showSelect();
	});
}

//////////////// ATTACHMENTS /////////////
/*
function addIdeaAttachment(element) {
	alert("adding attach");
	$("#loading").ajaxStart(function(){
		$(this).show();
	}).ajaxComplete(function(){
		$(this).hide();
	});

	alert("File uploading attach");
	$.ajaxFileUpload
	(
		{
			url:'ideas.ajax.php',
			secureuri:false,
			fileElementId:'userfile',
			datstyle="position:relative; float:left"aType: 'json',
			complete: function() {
				alert("File uploading attach done");
				//getAttachments();
			}
		}
	)
	
	return false;
}

function deleteIdeaAttachment(attachmentId) {
	$.post("ideas.ajax.php", {actionId: attachmentId, action:"deleteAttachment"} , function(data) {
		showResponses("#ideaResponses", data, true);
		getAttachments();
	});
}
*/