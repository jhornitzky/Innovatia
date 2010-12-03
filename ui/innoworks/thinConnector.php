<? 
require_once("pureConnector.php"); 
import("user.service");
if (!isLoggedIn()){
	die("You need to login to access this resource");
}
?>