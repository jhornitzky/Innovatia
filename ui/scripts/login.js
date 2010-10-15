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

function showAbout() {
	hideResponse();
	$("#AjaxForm").fadeOut(250, function() {
		$("#Wait").fadeIn(250);
		$.get("ui/login/about.html", function(data) {
			$("#AjaxForm").html(data);
			hideWait();
		});
	});
}


function showIdeas() {
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
	hideResponse();
	$("#AjaxForm").fadeOut(250, function() {
		$("#Wait").fadeIn(250);
		$.get("ui/login/loginInnovators.php", function(data) {
			$("#AjaxForm").html(data);
			hideWait();
		});
	});
}


function registerUser() {
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
	$("#AjaxForm").fadeOut(
			250,
			function() {
				$("#Wait").fadeIn(250);
				$.post("core/register.registerUser.php", $("#registerform")
						.serialize(), function(data) {
					$("#Responses").html(data);
				});
			});
}