<?
/**
 * Functions for retrieving and adding users to the database or LDAP
 */
require_once("innoworks.connector.php");

/*
 * Top most method for logging in users
 */
function loginUser($username,$password)
{
	$rs = authenticateUser($username,$password);
	if (dbNumRows($rs) == 1)
	{
		$userObj = dbFetchObject($rs);
		$_SESSION['innoworks.ID'] = $userObj->userId;
		$_SESSION['innoworks.username'] = $userObj->username;
		$_SESSION['isAuthen'] = true;
		return true;
	}
	return false;
}

/*
 * Generic user authentication flow. Check DB first (its faster), and then LDAP. 
 * Register LDAP user if required.
 */
function authenticateUser($username,$password)
{
	$userObj = authenticateDb($username,$password);
	if ($userObj != false) {
		return $userObj;
	} else {
		$ldapdn = authenticate_ldap($username,$password);
		if ($ldapdn != 0) {
			if (checkUsernameExists($ldapdn)) 
				registerUser(array("username" => $ldapdn));	
			return getUserIdForUsername($ldapdn);
		}
	}
	return false;
}

/*
 * Authenticate a user against the database
 */
function authenticateDb($username,$password)
{
	global $salt;

	$db = dbConnect();
	//FIXME remove these unencrypted bits
	$query = sprintf("SELECT userId, username FROM Users WHERE (username = '%s' AND password = '%s')", cleanseString($db, $username), cleanseString($db, $password));
	$result = dbQuery($query);
	if (dbNumRows($result) == 0) {
		$pass = sha1($salt.$password);
		$query = sprintf("SELECT userId, username FROM Users WHERE (username = '%s' AND password = '%s')", cleanseString($db, $username), $pass);
		$result = dbQuery($query);
	}
	dbClose($db);

	return $result;
}

/*
 * Authenticate a user against the LDAP
 */
function authenticate_ldap($user, $pw) {
	if (empty($user) || empty($pw))
	return 0;

	$server = $ldapHost;
	$searchuser = $ldapUser;
	$searchpass = $ldapPass;

	$ldapConn = ldap_connect($server) or die();
	$connection = ldapConnection($ldapConn);

	if (@ldap_bind ($connection, $searchuser, $searchpass)) {
		/* Search for the user */
		$search_result = @ldap_search ($connection, "o=uts", "(utsIdNumber=$user)");

		/* Check the result and the number of entries returned */
		if ($search_result && (ldap_count_entries ($connection, $search_result) == 1))
		{
			$userdn = @ldap_get_dn($connection, @ldap_first_entry ($connection,$search_result));

			// Rebind as the user with the supplied password and found dn
			if (@ldap_bind ($connection, $userdn, $pw)) {
				@ldap_unbind($connection);
				@ldap_close($connection);
				return $userdn;
			}
		}
	}

	@ldap_unbind($connection);
	@ldap_close($connection);

	return 0;
}

/*
 * Register a user
 */
function registerUser($postArgs) {
	global $serverRoot, $salt;

	$link = dbConnect();

	//ENCRYPT PASSWORD
	$pass = sha1($salt.$postArgs['password']);

	//Now prepare and run this query
	$sql = sprintf("INSERT INTO Users (`username`, `password`, `firstName`, `lastName`, `email`) VALUES ('%s','%s','%s','%s', '%s')",
	cleanseString($link,$postArgs['username']),
	$pass,
	cleanseString($link,$postArgs['firstName']),
	cleanseString($link,$postArgs['lastName']),
	cleanseString($link,$postArgs['email'])
	);

	logDebug("Register user: " . $sql);

	$success = dbQuery($link,$sql);

	$successId = dbInsertedId($link);

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
	$headers .= 'From: Innoworks Registration' . "\r\n";

	mail($postArgs['email'], "Innoworks - Credentials", $message, $headers);

	//Tidy up
	dbClose($link);

	return $success;
}

/*
 * Check is a username already exists. Can't have duplicate usernames.
 */
function checkUsernameExists($username) {
	//First open a connection
	$link = dbConnect();

	//Now prepare and run this query
	$sql = sprintf("SELECT * FROM Users WHERE username = '%s' ",
	cleanseString($link,$username));

	$result = dbQuery($link, $sql);

	$found=false;
	if (dbNumRows($result) > 0) {
		$found=true;
	}

	//Tidy up
	dbRelease($result);
	dbClose($link);

	return $found;
}

function deleteUser($username) {
	//First open a connection
	$link = dbConnect();

	//Now prepare and run this query
	$sql = sprintf("DELETE FROM Users WHERE userId = '%s'",
	cleanseString($link,$username));

	$result = dbQuery($link,$sql);

	//FIXME Error checks here
	dbClose($link);
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

function getUserInfo($userId)
{
	$rs = dbQuery("SELECT * FROM Users WHERE (userId = '".$userId."')");
	$row = dbFetchObject($rs);
	return $row;
}

function getSimilarUserProfiles($userId) {
	$info = getUserInfo($userId);
	$rs = dbQuery("SELECT * FROM Users WHERE userId != '$userId' AND (interests LIKE '%".$info->interests."%' OR organization = '".$info->organization."') AND isPublic='1' ORDER BY createdTime");
	if ($rs && dbNumRows($rs) == 0) {
		$rs = dbQuery("SELECT * FROM Users WHERE userId != '$userId' AND isPublic='1' ORDER BY createdTime LIMIT 5");
	}
	return $rs;
}

function getPublicUsers() {
	return dbQuery("SELECT * FROM Users WHERE isPublic='1' ORDER BY createdTime");
}

function getUserGroups($user) {
	return dbQuery("SELECT Groups.* FROM GroupUsers, Groups WHERE GroupUsers.userId = '$user' AND GroupUsers.groupId = Groups.groupId");
}

function getAllUsers() {
	return dbQuery("SELECT * FROM Users");
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