<?
/**
 * Functions for retrieving and adding users to the database or LDAP
 */
require_once("innoworks.connector.php");

/*
 * Top most method for logging in users
 */
function loginUser($username,$password){
	$userObj = authenticateUser($username,$password);
	if ($userObj) {
		$_SESSION['innoworks.ID'] = $userObj->userId;
		$_SESSION['innoworks.username'] = $userObj->username;
		$_SESSION['isAuthen'] = true;
		if ($userObj->isAdmin == 1)
		$_SESSION['innoworks.isAdmin'] = true;
		else
		$_SESSION['innoworks.isAdmin'] = false;
		return true;
	}
	return false;
}

/*
 * Generic user authentication flow. Check DB first (its faster), and then LDAP.
 * Register LDAP user if required.
 */
function authenticateUser($username,$password) {
	global $usesLdap;
	
	//Authenticate against DB
	$userObj = authenticateDb($username,$password);
	if ($userObj) {
		return $userObj;
	} else if ($usesLdap) {
		//Otherwise authenticate against LDAP
		if (authenticate_ldap($username,$password)) { 
			logDebug("AUTHENTICATED an LDAP user");
				
			//Check if the user already exists
			if (!checkUsernameExists($username)) { //username should be UTS ID number
				logDebug("Need to Register an LDAP user");
				//should register LDAP details into our DB
				$entries = getLdapUserDetails($username);
				if ($entries != 0) { //This is paranoia
					$entry = $entries[0];
					registerUser(
						array(
						"username" => $username, 
						"isExternal" => 1, 
						"firstName" => $entry['cn'][0], 
						"email" => $entry['utsmail'][0],
						"organization" => "UTS"
					));
				}
			}
			return getUserInfo(getUserIdForUsername($username));
		} else {
			logDebug("Failed LDAP user Authentication");
		}
	}
	return false;
}

/*
 * Authenticate a user against the database
 */
function authenticateDb($username,$password) {
	global $salt;
	$db = dbConnect();
	$pass = sha1($salt.$password);
	$query = sprintf("SELECT userId, username, isAdmin FROM Users WHERE (username = '%s' AND password = '%s') AND isExternal = 0", cleanseString($db, $username), $pass);
	$result = dbQuery($query);
	dbClose($db);
	if ($result && dbNumRows($result) == 1)
	return dbFetchObject($result);
	else
	return false;
}

/*
 * Get a users details from LDAP. ONLY USE ON AUTHENTICATED USERS!
 */
function getLdapUserDetails($user) {
	global $ldapUser, $ldapPass, $ldapPort, $ldapFullUrl;
	$entries = 0;
	$connection = ldap_connect($ldapFullUrl);
	//ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3); //OPTIONAL DEPENDING ON VERSION
	if (ldap_bind ($connection, $ldapUser, $ldapPass)) {
		$search_result = ldap_search ($connection, "o=uts", "(&(utsaccountstatus=ACTIVE)(utsidnumber=$user))"); 
		
		if ($search_result && (ldap_count_entries ($connection, $search_result) == 1))
			$entries = ldap_get_entries($connection,$search_result);

	}
	@ldap_unbind($connection);
	@ldap_close($connection);
	return $entries;
}

/*
 * Authenticate a user against the LDAP
 */
function authenticate_ldap($user, $pw) {
	global $ldapUser, $ldapPass, $ldapPort, $ldapFullUrl;

	if (empty($user) || empty($pw))
	return false;

	$connection = ldap_connect($ldapFullUrl);
	//ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3); //OPTIONAL DEPENDING ON VERSION
	logDebug("LDAP authentication trying BIND");
	if (ldap_bind ($connection, $ldapUser, $ldapPass)) {
		logDebug("LDAP Searching for user");
		/* Search for the user */
		$search_result = ldap_search ($connection, "o=uts", "(&(utsaccountstatus=ACTIVE)(utsidnumber=$user))"); //(utsIdNumber=$user)

		/* Check the result and the number of entries returned */
		if ($search_result && (ldap_count_entries ($connection, $search_result) == 1))
		{
			$userdn = ldap_get_dn($connection, ldap_first_entry($connection,$search_result));
			logDebug("LDAP the user dn is: " . $userdn);	
			
			// Rebind as the user with the supplied password and found dn
			if (ldap_bind ($connection, $userdn, $pw)) {
				logDebug("LDAP User authenticated");
				@ldap_unbind($connection);
				@ldap_close($connection);
				return true;
			}
		} else {
			logDebug("LDAP No such user found");
		}
	}
	@ldap_unbind($connection);
	@ldap_close($connection);
	return false;
}

/*
 * Register a user
 */
function registerUser($postArgs) {
	import("note.service");
	global $serverRoot, $salt;

	if (!isset($postArgs['isExternal']))
	$postArgs['isExternal'] = 0;

	$link = dbConnect();

	//ENCRYPT PASSWORD
	$pass = sha1($salt.$postArgs['password']);

	//Now prepare and run this query
	$sql = sprintf("INSERT INTO Users (username, password, firstName, lastName, email,isExternal,createdTime) VALUES ('%s','%s','%s','%s', '%s', '%s', '%s')",
		cleanseString($link,$postArgs['username']),
		$pass,
		cleanseString($link,$postArgs['firstName']),
		cleanseString($link,$postArgs['lastName']),
		cleanseString($link,$postArgs['email']),
		cleanseString($link,$postArgs['isExternal']),
		date_create()->format('Y-m-d H:i:sP')
	);

	$success = dbQuery($link,$sql);
	$successId = dbInsertedId($link);
	
	$opts = array();
	$opts['noteText'] = "Welcome to Innoworks! You can start innovating through the tabs above. If you get stuck you can click on the icon to the top right. Happy ideating!";
	$opts['toUserId'] = $successId;
	createNote($opts);
	
	//Tidy up
	dbClose($link);

	//Send welcome email FIXME TEMPLATIZE
	$message = '<html>
				<head>
				  <title>Innoworks Credentials</title>
				</head>
				<body>
				  <p>Innoworks - Login Credentials</p>
				  <table>
					<tr>
					  <th>Username:</th><td>'.$postArgs["username"].'</td>
					</tr>
					<tr>
					  <th>Password:</th><td>'.$postArgs["password"].'</td>
					</tr>
				  </table>
				</body>
				</html>';
	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: Innoworks' . "\r\n";
	mail($postArgs['email'], "Innoworks - Credentials", $message, $headers);

	return $success;
}

/*
 * Check if a username already exists. Can't have duplicate usernames.
 */
function checkUsernameExists($username) {
	logDebug("Checking for username: " . $username);

	//First open a connection
	$link = dbConnect();

	//Now prepare and run this query
	$sql = sprintf("SELECT * FROM Users WHERE username = '%s' ",
	cleanseString($link,$username));

	$result = dbQuery($link, $sql);

	$found = false;
	if ($result && dbNumRows($result) > 0)
	$found = true;

	//Tidy up
	dbRelease($result);
	dbClose($link);

	return $found;
}

function deleteUser($userId) {
	//First open a connection
	$link = dbConnect();

	//Now prepare and run this query
	$sql = sprintf("DELETE FROM Users WHERE userId = '%s'",
	cleanseString($link,$userId));

	$result = dbQuery($link,$sql);

	//FIXME Error checks here as well as file delete
	dbClose($link);
	
	return $result;
}

/*
 * Check is a user is logged in
 */
function isLoggedIn()
{
	if (isset($_SESSION['innoworks.ID']) && $_SESSION['isAuthen'] == true)
	return true;
	return false;
}

function getDisplayUsername($userId)
{
	if (is_object($userId)) {
		$row = $userId;
		if (!empty($row->firstName) || !empty($row->lastName))
			return $row->firstName . ' ' . $row->lastName;
		else 
			return $row->username;
	} else {
		$rs = dbQuery("SELECT * FROM Users WHERE (userId = '".$userId."')");
		if($rs && dbNumRows($rs) > 0) {
			$row = dbFetchObject($rs);
			if (!empty($row->firstName) || !empty($row->lastName))
				return $row->firstName . ' ' . $row->lastName;
			else 
				return $row->username;
		}
	} 
	return false;
}

function getUserInfo($userId)
{
	$rs = dbQuery("SELECT * FROM Users WHERE (userId = '".$userId."')");
	if($rs && dbNumRows($rs) > 0) {
		$row = dbFetchObject($rs);
		return $row;
	}
	return false;
}

function getSimilarUserProfiles($userId, $limit) {
	$info = getUserInfo($userId);
	$rs = dbQuery("SELECT * FROM Users WHERE userId != '$userId' AND (interests LIKE '%".$info->interests."%' OR organization = '".$info->organization."') AND isPublic='1' ORDER BY createdTime $limit");
	if ($rs && dbNumRows($rs) == 0) {
		$rs = dbQuery("SELECT * FROM Users WHERE userId != '$userId' AND isPublic='1' ORDER BY createdTime $limit");
	}
	return $rs;
}

function countGetSimilarUserProfiles($userId) {
	$info = getUserInfo($userId);
	$rs = dbQuery("SELECT COUNT(*) FROM Users WHERE userId != '$userId' AND (interests LIKE '%".$info->interests."%' OR organization = '".$info->organization."') AND isPublic='1'");
	if ($rs && dbNumRows($rs) == 0) {
		$rs = dbQuery("SELECT COUNT(*) FROM Users WHERE userId != '$userId' AND isPublic='1'");
	}
	return $rs;
}

function getPublicUsers() {
	return dbQuery("SELECT * FROM Users WHERE isPublic='1' ORDER BY createdTime");
}

function getUserGroups($user) {
	return dbQuery("SELECT Groups.* FROM GroupUsers, Groups WHERE (GroupUsers.userId = '$user' AND GroupUsers.groupId = Groups.groupId) UNION SELECT * FROM Groups WHERE  Groups.userId = '$user' ORDER BY createdTime DESC LIMIT 50");
}

function countGetUserGroups($user) {
	$count = dbFetchArray(dbQuery("SELECT COUNT(*) FROM (SELECT Groups.* FROM GroupUsers, Groups WHERE (GroupUsers.userId = '$user' AND GroupUsers.groupId = Groups.groupId) UNION SELECT * FROM Groups WHERE  Groups.userId = '$user') AS joinedTable"));
	return $count[0];
}

function getAllUsers($limit = 'LIMIT 200') {
	return dbQuery("SELECT * FROM Users ORDER BY username $limit");
}

function countGetAllUsers() {
	$array = dbFetchArray(dbQuery("SELECT COUNT(*) FROM Users"));
	return $array[0];
}

function updateUser($opts) {
	return genericUpdate("Users", $opts, array("userId") );
}

function getUserIdForUsername($username) {
	$rs = dbQuery("SELECT userId FROM Users WHERE (username = '".$username."')");
	$row = dbFetchObject($rs);
	return $row->userId;
}
?>