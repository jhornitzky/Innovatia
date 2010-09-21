<?
require_once("thinConnector.php");
import("idea.service");

$ideaId;
if (!isset($_GET['ideaId'])) {
	$ideaId = $_POST['ideaId'];
} else {
	$ideaId =  $_GET['ideaId']; 
}

if (isset($_POST) && $_POST != '') {
	switch ($_POST['action']) {
		case "addAttachment":
			echo "Creating Attachment.. ";
			logDebug('Create Attach!!!!!');
			//$opts = array('title' => $_POST['title'], 'userId' => $_SESSION['innoworks.ID']);
			//unset($opts['action']);
			$idea = createAttachment($_POST);
			echo $idea;
			break;
		case "deleteAttachment":
			echo "Deleting Item... ";
			$feature = deleteAttachment($_POST['actionId']);
			echo "Response Code: " . $feature;
			break;
	}
}
?>

<html>

<head>
</head>

<body>
	<form method="post" enctype="multipart/form-data" action="./attachment.php">
	<input type="hidden" name="MAX_FILE_SIZE" value="2000000"> 
	<input name="userfile" type="file" id="userfile"> 
	<input type="hidden" name="action" value="addAttachment"/>
	<input type="hidden" name="ideaId" value="<?= $ideaId;?>"/>
	<input type="submit" value=" + " title="Add attachment" />
	</form>
	<?
	$attachs = getAttachmentsForIdea($ideaId);
	
	if ($attachs && dbNumRows($attachs)) {
		while ($attach = dbFetchObject($attachs)) {?>
			<form method="post" action="./attachment.php" >
				<a href="retrieveAttachment.php?action=retrieveAttachment&actionId=<?= $attach->attachmentId;?>"><?= $attach->title;?></a>
				<input type="hidden" name="actionId" value="<?= $attach->attachmentId;?>"/>
				<input type="hidden" name="action" value="deleteAttachment"/>
				<input type="hidden" name="ideaId" value="<?= $ideaId;?>"/>
				<input type="submit" value=" - " title="Delete attachment" />
			</form>
		<?}
	} else {
		echo "<p>No attachments</p>";
	}?>
</body>

</html>