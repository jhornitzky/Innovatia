<?
require_once("innoworks.connector.php");

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

function createNote($opts) {
	import("user.service");
	$success = genericCreate("Notes", $opts);
	$userDetails = getUserInfo($opts["toUserId"]);
	$username = '';
	$userInfo = getUserInfo($opts["fromUserId"]);
	if ($userInfo != false) 
		$username = $userInfo->username;
	if ($success && $userDetails->sendEmail == 1) {
		$message = '<html>
				<head>
				</head>
				<body>
				  <table>
					<tr>
					  <th>Message from:</th><td>' . $username . '</td>
					</tr>
					<tr>
					  <td>' . $opts["noteText"] . '</td>
					</tr>
				  </table>
				</body>
				</html>';
		
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'Innoworks' . "\r\n";
		mail($userDetails->email, "Innoworks update", $message, $headers);
	}
	if ($success)
		return true;
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
?>