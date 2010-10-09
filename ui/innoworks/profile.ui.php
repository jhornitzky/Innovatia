<? 
require_once("thinConnector.php"); 
import("user.service");
 
function renderDefault() {
	$userDetails = getUserInfo($_SESSION['innoworks.ID']);
	?>
	<div style="width:100%; ">
	<div style="width:60%; position:relative; float:left">
		<h2>Your Profile</h2>
		<form id="profileDetailsForm">
		<? renderGenericUpdateForm(null ,$userDetails, array("ideaId", "title","userId", "createdTime")); ?>
		<input type="hidden" name="action" value="updateProfile" /> 
		<input type="button" onclick="updateProfile('profileDetailsForm')" value="Update" /></form>
	</div>
	
	<div style="width:auto; position:relative; float:left">
		<h2>Profiles like yours</h2>
	</div>
	</div>
<?
} 
?>
