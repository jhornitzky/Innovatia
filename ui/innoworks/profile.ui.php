<? 
require_once("thinConnector.php"); 
import("user.service");
 
function renderDefault() {
	$userDetails = getUserInfo($_SESSION['innoworks.ID']);
	$profiles = getSimilarUserProfiles($_SESSION['innoworks.ID']);
	?>
	<div style="width:100%;">
	
	<div style="width:58%; position:relative; float:left; padding: 1%;">
		<h2>Your Profile</h2>
		<form id="profileDetailsForm" onsubmit="updateProfile('profileDetailsForm'); return false;">
		<? renderGenericUpdateForm(null ,$userDetails, array("ideaId", "title","userId", "createdTime")); ?>
		<input type="hidden" name="action" value="updateProfile" /> 
		<input type="submit" value="Update" /></form>
	</div>
	
	<div style="width:37%; padding: 1%; position:relative; float:left; border:1px solid #000000" class="ui-corner-all">
		<h2>Profiles like yours</h2>
		<?
		if ($profiles && dbNumRows($profiles) > 0) { 
			echo "<ul>";
			while ($profile = dbFetchObject($profiles)) {
				renderOtherProfile($profile);
			}
			echo "</ul>";
		} else {
			echo "<p>No similar profiles</p>";
		}
		?>
	</div>
	
	</div>
<?
} 

function renderOtherProfile($profile) {
	echo  "<li>".$profile->username." <a href='mailto:$profile->email'>$profile->email</a></li>";	
}
?>
