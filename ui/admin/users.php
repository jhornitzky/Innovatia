<?require_once("thinConnector.php");
import("user.service");
$users = getAllUsers();?>
<html>
<head>
<?require_once('head.php')?>
<script>
function showProfileSummary(uId) {
	$("#userDetails").load("<?=$serverRoot?>ui/innoworks/profile.ajax.php?action=getProfileSummary&actionId=" + uId);
}
</script>
</head>
<body>
<div>
<div class="twoCol" style="width:226px;float:left">
<?if ($users && dbNumRows($users) > 0){
	while ($user = dbFetchObject($users)) { ?>
<div class='itemHolder clickable'
	onclick="showProfileSummary('<?=$user->userId?>')"><img
	src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=userImg&actionId=<?= $user->userId?>"
	style="width: 1em; height: 1em;" /> <?=$user->username?></div>
	<?}
}?>
</div>
<div id="userDetails" class="twoCol" style="left:226px; float:left">
</div>
</div>
</body>
</html>