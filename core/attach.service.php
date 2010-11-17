<?
function retrieveImageForIdea($ideaId) { 
	$imgs = dbQuery("SELECT * FROM Attachments WHERE ideaId = $ideaId AND type LIKE 'image/%' LIMIT 1");
	return genericRetrieveImage($imgs,"defGear.png");
}

function retrieveImageForGroup($groupId) { 
	$imgs = dbQuery("SELECT * FROM Attachments WHERE groupId = $groupId AND type LIKE 'image/%' LIMIT 1");
	return genericRetrieveImage($imgs,"defGear.png");
}

function retrieveImageForUser($userId) { 
	$imgs = dbQuery("SELECT * FROM Attachments WHERE userId = $userId AND type LIKE 'image/%' LIMIT 1");
	return genericRetrieveImage($imgs,"defGear.png");
}

function genericRetrieveImage($imgs, $defImg) {
	global $uiRoot;
	if ($imgs && dbNumRows($imgs) > 0) {
		$img = dbFetchObject($imgs);
		readfile($_SERVER['DOCUMENT_ROOT'].$img->path);
	} else {
		readfile($_SERVER['DOCUMENT_ROOT'].$uiRoot."style/".$defImg); 
	}
}
?>