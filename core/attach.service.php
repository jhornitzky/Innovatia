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
		$imgPath = $_SERVER['DOCUMENT_ROOT'] . $usersRoot . $img->path;
		//FIXME possbily rewrite images on the fly...
		//readfile($imgPath);
		header("Location: ".$serverUrl.$usersRoot.$img->path);
	} else {
		//header("Location: ".$serverUrl.$uiRoot."style/".$defImg);
		readfile($_SERVER['DOCUMENT_ROOT'].$uiRoot."style/".$defImg);
	}
}

function generate_image_thumbnail( $source_image_path, $thumbnail_image_path )
{
	logInfo('shouldFixImageThumbnail');
	
	define( 'THUMBNAIL_IMAGE_MAX_WIDTH', 300 );
	define( 'THUMBNAIL_IMAGE_MAX_HEIGHT', 300 );

	//get image props
	list( $source_image_width, $source_image_height, $source_image_type ) = getimagesize( $source_image_path );

	if ($source_image_width != $source_image_height)  { //if dimensions are not the same then resize
		switch ( $source_image_type )
		{
			case IMAGETYPE_GIF:
				$source_gd_image = imagecreatefromgif( $source_image_path );
				break;

			case IMAGETYPE_JPEG:
				$source_gd_image = imagecreatefromjpeg( $source_image_path );
				break;

			case IMAGETYPE_PNG:
				$source_gd_image = imagecreatefrompng( $source_image_path );
				break;
		}

		if ( $source_gd_image === false )
		{
			return false;
		}

		//get the longer dimension
		if ($source_image_width > $source_image_height) {
			$longest = $source_image_width;
			$dst_y = $longest/2 - $source_image_height/2;
			$dst_x = 0;
		} else {
			$longest = $source_image_height;
			$dst_x = $longest/2 - $source_image_width/2;
			$dst_y = 0;
		}
		
		//then create new image at longest dimension
		$thumby = imagecreate($longest, $longest);
		imagefill($thumby,0,0,imagecolorallocate($thumby, 255, 255, 255));
		//input it into there
		imagecopy($thumby, $source_gd_image, $dst_x, $dst_y, 0, 0, $source_image_width, $source_image_height);
		
		//then save to the FS
		logInfo('savingboxedimagethumbnail: ' . $thumbnail_image_path);
		unlink($thumbnail_image_path); 
		imagejpeg( $thumby, $thumbnail_image_path, 90 ); //FIXME or alternatively render
		imagedestroy( $source_gd_image );
		imagedestroy( $thumby );

		return true;
	}
}

function updateAttachment($opts) {
	return genericUpdate("Attachments", $opts, array("attachmentId"));
}

function createAttachmentFS($destFileName) {
	global $usersRoot;
	$destFilePath = $usersRoot.$destFileName;
	logAudit("doMoveFile: ".$_SERVER['DOCUMENT_ROOT'].$destFilePath);
	if(move_uploaded_file($_FILES['userfile']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$destFilePath)) {
		//fix image on upload
		generate_image_thumbnail($_SERVER['DOCUMENT_ROOT'].$destFilePath, $_SERVER['DOCUMENT_ROOT'].$destFilePath);
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