<? 
require_once(dirname(__FILE__)."/../ui.connector.php");
import("user.service");
if (!isset($_SESSION["isAuthen"]) && getUserInfo($_SESSION['innoworks.ID'])->isAdmin != 1) 
	die("Need to have admin rights.");
?>