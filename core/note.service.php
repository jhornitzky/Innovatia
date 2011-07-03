<?
import("innoworks.connector");

function getAnnouncements($limit) {
	return dbQuery("SELECT * FROM Announcements ORDER BY date DESC $limit");
}

function createFeedbackNotes($opts) {
	import("user.service");
	$admins = getAdmins();
	$opts['isFeedback'] = 1;
	
	if ($admins != null && dbNumRows($admins) > 0) {
		while ($admin = dbFetchObject($admins)) {
			$opts['toUserId'] = $admin->userId;
			createNote($opts);
		}
	}

	return true;
}

/**
 * 
 * Create an announcement that is sent to everybody
 * @param unknown_type $senderid
 * @param unknown_type $msg
 */
function createAnnouncement($senderid, $msg) {
	$note = array();
	$note['fromUserId'] = $senderid; 
	$note['noteText'] = $msg;
	$note['isAnnouncement'] = 1;
	$success = createNote($note);
	
	sendAllUsersMail($note);

	//$announce = array();
	//$announce['userId'] = $senderid;
	//$announce['text'] = $msg;
	//return genericCreate("Announcements", $announce);
	return $success;
}

function sendAllUsersMail($note) {
	import("user.service");
	$users = getAllUsers();
	if ($users && dbNumRows($users) > 0) {
		while ($user = dbFetchObject($users)) {
			$note['toUserId'] = $user->userId;
			sendNoteToMail($array);
		}
	}
}

/**
 * 
 * Send a note to a user, or just create one.
 * 
 * @param array $opts {toUserId, fromUserId, noteText, isPublic, isAnnouncement, ideaId, groupId}
 */
function createNote($opts) {
	try {	
		import("user.service");
		
		//create the note first
		$note = $opts;
		unset($note['mail']);
		$success = genericCreate("Notes", $note);
		
		//now send folow up messages
		$userDetails = getUserInfo($opts["toUserId"]);
		$username = '';
		$userInfo = getUserInfo($opts["fromUserId"]);
		if ($userInfo != false) $username = $userInfo->username;
		
		//only send if allowed
		if ($success && $userDetails->sendEmail == 1) {
			$message = $opts["noteText"];
			if (!isset($opts['mail']) || (isset($opts['mail']) && $opts['mail'] !== false)) {
				sendMail(array(
					'to' => $userDetails->email, 
					"subject" => "Innoworks update", 
					"msg" => $message, 
					"headers" => $headers));
			}
		} 
		if ($success) return true;
	} catch (Exception $e) {} 
	return false;		
}

/**
 * Create a note/comment for groups and email notify everyone
 * 
 */
function createNoteForGroup($senderid, $groupid, $msg) {
	import("group.service");
	
	//Create group note first
	$array = array();
	$array['fromUserId'] = $senderid;
	$array['noteText'] = $msg;
	$array['groupId'] = $groupid;
	createNote($array);

	//Then send to leader
	$group = getGroupDetails($groupid);
	$array['toUserId'] = $group->userId;
	if ($senderid != $array['toUserId']) createNote($array);

	//Now send to group members
	$groupUsers = getUsersForGroup($groupid);
	if ($groupUsers && dbNumRows($groupUsers) > 0) {
		while ($groupUser = dbFetchObject($groupUsers)) {
			$array['toUserId'] = $groupUser->userId;
			if ($senderid != $array['toUserId'])
			createNote($array);
		}
	}

	return true;
}

function deleteNote($opts) {
	return genericDelete("Notes", $opts);
}

function getAllNotes($user, $limit) {
	return dbQuery("SELECT * FROM Notes WHERE toUserId='$user' OR fromUserId='$user' ORDER BY createdTime DESC $limit");
}

function getPublicNotes($limit) {
	return dbQuery("SELECT * FROM Notes WHERE (toUserId IS NULL AND isAnnouncement=1) OR isPublic=1 ORDER BY createdTime DESC $limit");
}

function getGroupNotes($groupId, $limit) {
	return dbQuery("SELECT * FROM Notes WHERE groupId IS NULL AND groupId = '$groupId' ORDER BY createdTime DESC $limit");
}

function getIdeaNotes($ideaId, $limit) {
	return dbQuery("SELECT * FROM Notes WHERE ideaId IS NULL AND ideaId = '$ideaId' ORDER BY createdTime DESC $limit");
}

function countGetAllNotes($user) {
	$count = dbFetchArray(dbQuery("SELECT COUNT(*) FROM Notes WHERE toUserId='$user' OR fromUserId='$user' ORDER BY createdTime DESC"));
	return $count[0];
}

function getAllIncomingNotes($user, $limit) {
	return dbQuery("SELECT * FROM Notes WHERE toUserId='$user' OR isPublic=1 OR isAnnouncement=1 ORDER BY createdTime DESC $limit");
}

function getAllSentNotes($user, $limit) {
	return dbQuery("SELECT * FROM Notes WHERE fromUserId='$user' ORDER BY createdTime DESC  $limit");
}

function getNewNotes($user) {
	return dbQuery("SELECT * FROM Notes WHERE toUserId='$user' AND isRead='0' ORDER BY createdTime DESC");
}

function markNotesAsRead($user) {
	return dbQuery("UPDATE Notes SET isRead='1' WHERE toUserId='$user' AND isRead='0'");
}

/**
 * Send a note directly to email
 */
function sendNoteToMail($opts) {
	$userDetails = getUserInfo($opts["toUserId"]);
	$username = '';
	$userInfo = getUserInfo($opts["fromUserId"]);
	if ($userInfo != false)
		$username = $userInfo->username;
		
	//only send if allowed
	if ($userDetails->sendEmail == 1) {
		$message = $opts["noteText"];
		if (!isset($opts['mail']) || (isset($opts['mail']) && $opts['mail'] !== false)) {
			sendMail(array(
				'to' => $userDetails->email, 
				"subject" => "Innoworks update", 
				"msg" => $message, 
				"headers" => $headers));
		}
	}
}

function sendMail($inputs) {
	global $mailMethod, $mailServer, $mailPort, $mailUser, $mailPass, $serverBase;
	
	logDebug('doSendEmail');
	session_write_close(); //speed up
	
	//pretty up the msg
	$inputs['msg'] = renderTemplateAsString('email', $inputs);
	if (!isset($inputs['subject'])) {
		$inputs['subject'] = 'innoWorks update';
	}
	
	if ($mailMethod == 'smtp') {
		//FIXME change config based on what is available
		$config = array('auth' => 'login',
                	'username' => $mailUser,
                	'password' => $mailPass, 
					'ssl' => 'ssl',
                	'port' => $mailPort);
		
		$transport = new Zend_Mail_Transport_Smtp($mailServer, $config);

		//Send mail
		$mail = new Zend_Mail();
		$mail->setFrom('notifications@'.$serverBase);
		//$mail->setReplyTo($email); //FIXME include user email
		$mail->addTo($inputs['to']);
		$mail->setSubject($inputs['subject']);
		$mail->setBodyHtml($inputs['msg']);
		return $mail->send($transport);
	} else {
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: notifications@'. $serverBase . "\r\n";
		return mail($inputs['to'], $inputs['subject'], $inputs['msg'], $headers);
	}
}
?>