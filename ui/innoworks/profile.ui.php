<? 
require_once("thinConnector.php"); 
import("user.service");
 
function renderDefault() {
	$userDetails = getUserInfo($_SESSION['innoworks.ID']);
	?>
	<h2>Your Profile</h2>
	<form id="profileDetailsForm">
	<? renderGenericUpdateForm(null ,$userDetails, array("ideaId", "title","userId", "createdTime")); ?>
	<input type="hidden" name="action" value="updateProfile" /> 
	<input type="button" onclick="updateProfile('profileDetailsForm')" value="Update" /></form>
<?
} 
?>
