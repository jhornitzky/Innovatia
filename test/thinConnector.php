<? 
require_once($_SERVER['DOCUMENT_ROOT']."/innovation/core/innoworks.connector.php");
if (!isset($_SESSION["isAuthen"]) && getUserInfo($_SESSION['innoworks.ID'])->isAdmin != 1) 
	die("Need to have admin rights.");
?>