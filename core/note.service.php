<?
import("innoworks.connector");

function getAnnouncements($limit) {
	return dbQuery("SELECT * FROM Announcements ORDER BY date DESC $limit");
}

function createFeedbackNotes($opts) {
	import("user.service");
	$admins = getAdmins();

	if ($admins != null && dbNumRows($admins) > 0) {
		while ($admin = dbFetchObject($admins)) {
			$opts['noteText'] = "Feedback: " . $opts['noteText'];
			$opts['toUserId'] = $admin->userId;
			createNote($opts);
		}
	}

	return true;
}

function createAnnouncement($senderid, $msg) {
	$array = array();
	//$array['fromUserId'] = $senderid; //FIXME
	$array['noteText'] = "Announcement: " . $msg;

	import("user.service");
	$users = getAllUsers();
	if ($users && dbNumRows($users) > 0) {
		while ($user = dbFetchObject($users)) {
			$array['toUserId'] = $user->userId;
			createNote($array);
		}
	}

	$announce = array();
	$announce['userId'] = $senderid;
	$announce['text'] = $msg;
	return genericCreate("Announcements", $announce);
}

/**
 * 
 * Send a note to a user, or just create one.
 * 
 * 
 * @param array $opts {toUserId, fromUserId, noteText, isPublic, isAnnouncement, ideaId, groupId}
 */
function createNote($opts) {
	try {	
		import("user.service");
		$success = genericCreate("Notes", $opts);
		$userDetails = getUserInfo($opts["toUserId"]);
		$username = '';
		$userInfo = getUserInfo($opts["fromUserId"]);
		if ($userInfo != false)
		$username = $userInfo->username;
		if ($success && $userDetails->sendEmail == 1) {
			$message = $opts["noteText"];
	
			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'Innoworks' . "\r\n";
			
			if (isset($opts['mail']) && $opts['mail'] === false) {} 
			else {
				sendMail(array(
					'to' => $userDetails->email, 
					"subject" => "Innoworks update", 
					"msg" => $message, 
					"headers" => $headers));
			}
		}
		if ($success)
		return true;
	} catch (Exception $e) {}
	return false;
}

function createNoteForGroup($senderid,$groupid, $msg) {
	$array = array();
	$array['fromUserId'] = $senderid;
	$array['noteText'] = $msg;

	import("group.service");

	//Send to leader first
	$group = getGroupDetails($groupid);
	$array['toUserId'] = $group->userId;
	if ($senderid != $array['toUserId'])
	createNote($array);

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
	return dbQuery("SELECT Notes.* FROM Notes WHERE toUserId='$user' OR fromUserId='$user' ORDER BY createdTime DESC $limit");
}

function countGetAllNotes($user) {
	$count = dbFetchArray(dbQuery("SELECT COUNT(*) FROM Notes WHERE toUserId='$user' OR fromUserId='$user' ORDER BY createdTime DESC"));
	return $count[0];
}

function getAllIncomingNotes($user, $limit) {
	return dbQuery("SELECT * FROM Notes WHERE toUserId='$user' ORDER BY createdTime DESC $limit");
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
		//FIXME cahgne config based on what is available
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