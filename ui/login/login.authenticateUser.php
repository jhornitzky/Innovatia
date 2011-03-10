<?
	/**
	 * Handles the authentication upon login
	 */
	require_once("thinConnector.php");
	import("user.service");
	global $serverRoot;
	
	//If user is found
	if (loginUser($_POST['username'],$_POST['password']))
	{
		echo "Login successful. Innovation starting...";
		?>
			<script type="text/javascript">
			{
            	window.location.reload(true);
			}
            </script>  			
		<? 
	}
	else
	{
		echo "Login unsuccessful. Try again."; 		
	}
?>