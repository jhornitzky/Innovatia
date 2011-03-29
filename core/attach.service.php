<?
import("innoworks.connector");

function retrieveImageForIdea($ideaId) { 
	$imgs = dbQuery("SELECT * FROM Attachments WHERE ideaId = $ideaId AND type LIKE 'image/%' ORDER BY isDp DESC LIMIT 1");
	return genericRetrieveImage($imgs,"cube.png");
}

function retrieveImageForGroup($groupId) { 
	$imgs = dbQuery("SELECT * FROM Attachments WHERE groupId = $groupId AND type LIKE 'image/%' ORDER BY isDp DESC LIMIT 1");
	return genericRetrieveImage($imgs,"group.png");
}

function retrieveImageForUser($userId) { 
	$imgs = dbQuery("SELECT * FROM Attachments WHERE userId = '$userId' AND type LIKE 'image/%' AND ideaId IS NULL AND groupId IS NULL UNION SELECT * FROM Attachments WHERE userId = '$userId' AND (ideaId IS NOT NULL OR groupId IS NOT NULL) AND type LIKE 'image/%' ORDER BY isDp DESC LIMIT 1");
	return genericRetrieveImage($imgs,"user.png");
}

function genericRetrieveImage($imgs, $defImg) {
	global $uiRoot, $usersRoot, $serverUrl;
	if ($imgs && dbNumRows($imgs) > 0) {
		$img = dbFetchObject($imgs); 
		//header("Location: ".$serverUrl.$usersRoot.$img->path);
		readfile($_SERVER['DOCUMENT_ROOT'] . $usersRoot . $img->path);
	} else {
		//header("Location: ".$serverUrl.$uiRoot."style/".$defImg);
		readfile($_SERVER['DOCUMENT_ROOT'].$uiRoot."style/".$defImg); 
	}
}

function updateAttachment($opts) {
	return genericUpdate("Attachments", $opts, array("attachmentId"));
}

function createAttachmentFS($destFileName) {
	global $usersRoot;
	$destFilePath = $usersRoot.$destFileName; 
	logAudit("MOVING FILE TO: ".$_SERVER['DOCUMENT_ROOT'].$destFilePath);
	if(move_uploaded_file($_FILES['userfile']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$destFilePath)) {
		chmod($_SERVER['DOCUMENT_ROOT'].$destFilePath, 0444);
		return true;
	} else {
		logError("Error uploading file for user to path " . $_SERVER['DOCUMENT_ROOT'].$destFilePath);
		return false;
	}
}

function createAttachmentDb($destFileName) {
	$fileName = $_FILES['userfile']['name'];
	$tmpName  = $_FILES['userfile']['tmp_name'];
	$fileSize = $_FILES['userfile']['size'];
	$fileType = $_FILES['userfile']['type'];

	if(!get_magic_quotes_gpc())
		$fileName = addslashes($fileName);
	
	$query;
	if (isset($_POST['groupId']))
		$query = "INSERT INTO Attachments (groupId, title, path, type, size, userId) VALUES ('".$_POST['groupId']."','$fileName', '$destFileName', '$fileType', '$fileSize', '".$_SESSION['innoworks.ID']."')";
	else if (isset($_POST['ideaId']))
		$query = "INSERT INTO Attachments (ideaId, title, path, type, size, userId) VALUES ('".$_POST['ideaId']."','$fileName', '$destFileName', '$fileType', '$fileSize', '".$_SESSION['innoworks.ID']."')";
	else 
		$query = "INSERT INTO Attachments (title, path, type, size, userId) VALUES ('$fileName', '$destFileName', '$fileType', '$fileSize', '".$_SESSION['innoworks.ID']."')";
	return dbQuery($query);
}

function createAttachment($postArray) {
	if ($_FILES['userfile']['size'] > 0) {
		global $usersRoot;
		$info = pathinfo($_FILES['userfile']['name']);
		$newName = date(DATE_ATOM) . $_SESSION['innoworks.ID'] . $_FILES['userfile']['name'] . $info['extension'];
		$destFileName = sha1($newName);
		if (createAttachmentDb($destFileName))
			return createAttachmentFS($destFileName);
		else 
			return false;
	}
}

function deleteAttachment($id) {
	if(isset($id)) {
		global $usersRoot;
		$attach = getAttachmentById($id);
		unlink($_SERVER['DOCUMENT_ROOT'].$usersRoot.$attach->path);
		return dbQuery("DELETE FROM Attachments WHERE Attachments.attachmentId='$id'");
	}
}

function getAttachmentsForIdea($ideaId) {
	return dbQuery("SELECT * FROM Attachments WHERE ideaId = '$ideaId' ORDER BY isDp");
}

function getAttachmentsForGroup($groupId) {
	return dbQuery("SELECT * FROM Attachments WHERE groupId = '$groupId' ORDER BY isDp");
}

function getAttachmentsForUser($userId) {
	return dbQuery("SELECT * FROM Attachments WHERE userId = '$userId' AND ideaId IS NULL AND groupId IS NULL UNION SELECT * FROM Attachments WHERE userId = '$userId' AND (ideaId IS NOT NULL OR groupId IS NOT NULL)  ORDER BY isDp DESC");
}

function getAttachmentById($id) {
	return dbFetchObject(dbQuery("SELECT * FROM Attachments WHERE attachmentId = '$id'"));
}

function retrieveAttachment($id) {
	global $usersRoot;
	if(isset($id)) {
		$attach = getAttachmentById($id);
		header("Content-length: $attach->size");
		header("Content-type: $attach->type");
		header("Content-Disposition: attachment; filename=$attach->title");
		readfile($_SERVER['DOCUMENT_ROOT'].$usersRoot.$attach->path);
	}
}
?>