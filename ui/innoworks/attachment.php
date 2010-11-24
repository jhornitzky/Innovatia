<?
require_once("thinConnector.php");
import("idea.service");

$ideaId;
$groupId;
if (isset($_POST['ideaId'])) {
	$ideaId = $_POST['ideaId'];
} else if (isset($_GET['ideaId'])){
	$ideaId =  $_GET['ideaId'];
} else if (isset($_POST['groupId'])){
	$groupId =  $_POST['groupId'];
} else if (isset($_GET['groupId'])){
	$groupId =  $_GET['groupId'];
}

if (isset($_POST['action'])) {
	switch ($_POST['action']) {
		case "addAttachment":
			echo "Creating Attachment.. ";
			logDebug('Create attachment');
			renderServiceResponse(createAttachment($_POST));
			break;
		case "deleteAttachment":
			echo "Deleting Item... ";
			renderServiceResponse(deleteAttachment($_POST['actionId']));
			break;
	}
}
?>
<html>
<head>
<link href="<?= $serverRoot?>ui/style/style.css" rel="stylesheet"
	type="text/css" />
<style>
html, body {
	text-align:left;
}
</style>
</head>

<body>
	<? if (isset($groupId) || (isset($ideaId) && hasEditAccessToIdea($ideaId,$_SESSION['innoworks.ID']))) { ?>
	<form method="post" enctype="multipart/form-data"
	action="./attachment.php"><input type="hidden" name="MAX_FILE_SIZE"
	value="2000000"> <input name="userfile" type="file" id="userfile"> <input
	type="hidden" name="action" value="addAttachment" /> 
	<?
	if (isset($ideaId)) 
		echo '<input type="hidden" name="ideaId" value="'.$ideaId.'"/>';
	else if (isset($groupId))
		echo '<input type="hidden" name="groupId" value="'.$groupId.'"/>';?> 
	<input type="submit" value=" + " title="Add attachment" /></form>
	<?
	}
	
	global $usersRoot;
	$attachs;
	if (isset($ideaId))
		$attachs = getAttachmentsForIdea($ideaId);
	else if (isset($groupId))
		$attachs = getAttachmentsForGroup($groupId);
		
	if ($attachs && dbNumRows($attachs)) {
		while ($attach = dbFetchObject($attachs)) {
			if (preg_match("/^[image]/",$attach->type)) 
				echo "<img src='$usersRoot$attach->path' style='width:100px;height:75px'/>";?>
			<form method="post" action="./attachment.php">
			<a href="retrieveAttachment.php?action=retrieveAttachment&actionId=<?= $attach->attachmentId;?>">
			<?= $attach->title;?></a>
			<?if (isset($ideaId)) 
				echo '<input type="hidden" name="ideaId" value="'.$ideaId.'"/>';
			else if (isset($groupId))
				echo '<input type="hidden" name="groupId" value="'.$groupId.'"/>';
			
			if (isset($groupId) || (isset($ideaId) && hasEditAccessToIdea($ideaId,$_SESSION['innoworks.ID']))) { ?> 
			<input type="hidden" name="actionId" value="<?= $attach->attachmentId;?>" /> 
			<input type="hidden" name="action" value="deleteAttachment" /> 
			<input type="submit" value=" - " title="Delete attachment" />
			<?}?>
			</form>
		<?}
	} else {
		echo "<p>No attachments</p>";
	}?>
</body>

</html>
