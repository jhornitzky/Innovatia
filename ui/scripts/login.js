function authenUser() {
	hideResponse();
	$("#Wait").fadeIn(250);
	$.post("core/login.authenticateUser.php", $("#LoginForm").serialize(),
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


function showIdeas() {
	updateForSelect("ideaInno");
	hideResponse();
	$("#AjaxForm").fadeOut(250, function() {
		$("#Wait").fadeIn(250);
		$.get("ui/login/loginIdeas.php", function(data) {
			$("#AjaxForm").html(data);
			hideWait();
		});
	});
}

function showInnovators() {
	updateForSelect("innovators");
	hideResponse();
	$("#AjaxForm").fadeOut(250, function() {
		$("#Wait").fadeIn(250);
		$.get("ui/login/loginInnovators.php", function(data) {
			$("#AjaxForm").html(data);
			hideWait();
		});
	});
}

function showSearch() {
	updateForSelect("search");
	query = $("#searchInput").val();
	hideResponse();
	$("#AjaxForm").fadeOut(250, function() {
		$("#Wait").fadeIn(250);
		$.get("ui/login/loginSearch.php?q=" + query, function(data) {
			$("#AjaxForm").html(data);
			hideWait();
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
				$.post("core/register.registerUser.php", $("#registerform").serialize(), function(data) {
					$("#Responses").html(data);
					hideWait();
					showResponse();
				});
			});
}