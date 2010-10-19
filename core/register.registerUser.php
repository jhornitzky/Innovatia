<?
	require_once("user.service.php");
	
	function error()
	{
		echo "<script>{hideWait(); showResponse();}</script>";
		exit();
	}
	
	if (checkUsernameExists($_POST['username']))
	{
		echo "Username Exists. Pick a different one";
		error();
	}
	
	if (!strlen($_POST['username']) > 0)
	{
		echo "Username Required";
		error();
	}
	
	if (!strlen($_POST['password']) > 0)
	{
		echo "Password Required";
		error();
	}
	
	if(registerUser($_POST)) {		
		?> <b>User <?= $_POST['username'] ?> created.</b>
		<?
	} else {
		echo "Failure creating user... you should check your fields";
		error();
	}

?>
