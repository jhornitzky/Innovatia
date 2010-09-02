function authenUser() {
	$("#Wait").fadeIn();
	$.post("core/login.authenticateUser.php", $("#LoginForm").serialize(),
			function(data) {
				$("#Responses").html(data);
				$("#Wait").fadeOut();
			});
}

function enterOS() {
	window.location.reload();
}

function mainMenu() {
	hideResponse();
	$("#AjaxForm").fadeOut(250, function() {
		$("#Wait").fadeIn(250);
		$.get("core/login/login.html", function(data) {
			$("#content").html(data);
			hideWait();
		});
	});
}
function registerUser() {
	hideResponse();
	$("#AjaxForm").fadeOut(250, function() {
		$("#Wait").fadeIn(250);
		$.get("core/login/register.html", function(data) {
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

function openNxtnet() {
	window.open('xi/nxtnet');
}