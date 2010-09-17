<?
/**
 * Functions for retrieving and adding users to the database
 */
require_once("innoworks.connector.php");

//Get the user details for a user
function authenticateUser($username,$password)
{
	$db = dbConnect();
	$query = sprintf("SELECT userId, username FROM Users WHERE (username = '%s' AND password = '%s')", cleanseString($db, $username), cleanseString($db, $password));
	dbClose($db);
	return dbQuery($query);
}

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

function registerUser($postArgs) {
	global $serverRoot;

	$link = dbConnect();

	//Now prepare and run this query
	$sql = sprintf("INSERT INTO Users (`username`, `password`, `firstName`, `lastName`, `email`) VALUES ('%s','%s','%s','%s', '%s')",
	cleanseString($link,$postArgs['username']),
	cleanseString($link,$postArgs['password']),
	cleanseString($link,$postArgs['firstName']),
	cleanseString($link,$postArgs['lastName']),
	cleanseString($link,$postArgs['email'])
	);

	$success = execQuery($link,$sql);
	
	$successId = dbInsertedId($link);

	//Add default apps FIXME
	$message = '<html>
				<head>
				  <title>Innoworks Credentials</title>
				</head>
				<body>
				  <p>Innoworks - Login Credentials</p>
				  <table>
					<tr>
					  <th>username:</th><td>'.$postArgs["username"].'</td>
					</tr>
					<tr>
					  <th>password:</th><td>'.$postArgs["password"].'</td>
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

function checkusernameExists($username) {
	//First open a connection
	$link = dbConnect();

	//Now prepare and run this query
	$sql = sprintf("SELECT * FROM Users WHERE username = '%s' ",
	cleanseString($link,$username));

	$result = execQuery($link,$sql);

	$found=false;
	if (dbNumRows($result) > 0) {
		$found=true;
	}

	//Tidy up
	dbRelease($result);
	dbClose($link);

	return $found;
}

//Delete a user from the DB
function deleteUser($username) {
	//First open a connection
	$link = dbConnect();

	//Now prepare and run this query
	$sql = sprintf("DELETE FROM Users WHERE userId = '%s'",
	cleanseString($link,$username));

	$result = execQuery($link,$sql);

	//FIXME Error checks here
	dbClose($link);
}

function isLoggedIn()
{
	if (isset($_SESSION['innoworks.ID']) && $_SESSION['isAuthen'] == true)
	{ return true; }
	return false;
}

function getUserInfo($userId)
{
	$rs = dbQuery("SELECT * FROM Users WHERE (userId = '".$userId."')");
	$row = dbFetchObject($rs);
	return $row;
}

function getUserGroups($user) {
	return dbQuery("SELECT * FROM GroupUsers, Groups WHERE GroupUsers.userId = '$user' AND GroupUsers.groupId = Groups.groupId");
}

function getAllUsers() {
	return dbQuery("SELECT * FROM Users");
}

function updateUser($opts) {
	return genericUpdate("Users", $opts, array("userId") );
}
?>
