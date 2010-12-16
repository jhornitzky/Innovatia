<?
function retrieveImageForIdea($ideaId) { 
	$imgs = dbQuery("SELECT * FROM Attachments WHERE ideaId = $ideaId AND type LIKE 'image/%' ORDER BY isDp DESC LIMIT 1");
	return genericRetrieveImage($imgs,"defGear.png");
}

function retrieveImageForGroup($groupId) { 
	$imgs = dbQuery("SELECT * FROM Attachments WHERE groupId = $groupId AND type LIKE 'image/%' ORDER BY isDp DESC LIMIT 1");
	return genericRetrieveImage($imgs,"defGear.png");
}

function retrieveImageForUser($userId) { 
	$imgs = dbQuery("SELECT * FROM Attachments WHERE userId = '$userId' AND type LIKE 'image/%' AND ideaId IS NULL AND groupId IS NULL UNION SELECT * FROM Attachments WHERE userId = '$userId' AND (ideaId IS NOT NULL OR groupId IS NOT NULL) AND type LIKE 'image/%' ORDER BY isDp DESC LIMIT 1");
	return genericRetrieveImage($imgs,"defGear.png");
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
?>