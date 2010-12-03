function logAction() {}

function authenUser() {
	hideResponse();
	$("#Wait").fadeIn(250);
	$.post("ui/login/login.authenticateUser.php", $("#LoginForm").serialize(),
			function(data) {
				$("#Responses").html(data);
				hideWait();
				showResponse();
			});
}

function hideWait() {
	$("#Wait").fadeOut(250);
	$("#AjaxForm").fadeIn(250);
}

function showResponse() {
	$("#Responses").fadeIn(175);
}

function hideResponse() {
	$("#Responses").fadeOut(175);
}

function enterOS(goFS) {
	window.location.reload();
}

function mainMenu() {
	hideResponse();
	$("#AjaxForm").fadeOut(250, function() {
		$("#Wait").fadeIn(250);
		$.get("ui/login/login.html", function(data) {
			$("#AjaxForm").html(data);
			hideWait();
		});
	});
}

function updateForSelect(name) {
	$("#submenu li").removeClass("selected");
	$("#submenu li#" + name + "lnk").addClass("selected");
	$("#ajaxContent").removeClass();
	$("#ajaxContent").addClass(name);
}

function showAbout() {
	updateForSelect("about");
	hideResponse();
	$("#AjaxForm").fadeOut(250, function() {
		$("#Wait").fadeIn(250);
		$.get("ui/login/about.php", function(data) {
			$("#AjaxForm").html(data);
			hideWait();
		});
	});
}

function showSearch() {
	updateForSelect("ideaInno");
	var searchTerms = $("#searchForm").serialize();
	
	$("#AjaxForm").fadeOut(250, function() {
		$("#Wait").fadeIn(250);
		
		url = "ui/login/loginSearch.php";
		if (searchTerms != undefined)
			url += "?"+searchTerms; 
		
		$.get(url, function(data) {
			$("#AjaxForm").html(data);
			hideWait();
			//FIXME add dojo loading here
		});
	});
}

function registerUser() {
	updateForSelect("reg");
	hideResponse();
	$("#AjaxForm").fadeOut(250, function() {
		$("#Wait").fadeIn(250);
		$.get("ui/login/register.html", function(data) {
			$("#AjaxForm").html(data);
			hideWait();
		});
	});
}

function registerNewUser() {
	hideResponse();
	$("#AjaxForm").fadeOut(250,
			function() {
				$("#Wait").fadeIn(250);
				$.post("ui/login/register.registerUser.php", $("#registerform").serialize(), function(data) {
					$("#regResponse").html(data);
					hideWait();
					showResponse();
				});
			});
}

function showIdeaSummary(id) {}

function showProfileSummary(id) {}

function showGroupSummary(id) {}

function verifyusername() {}

function toggleSearchOptions() {
	$("#searchHider").toggle();
	$("#searchOptions").toggle();
}