<?
//DB CREDENTIALS
$dbUser = 'root'; 
$dbPass = 'return';
$dbURL = 'localhost';
$dbSchema = 'innoworks';

//LDAP 
$ldapUser = 'cn=admin,dc=example,dc=com';
$ldapPass = 'secret';
$ldapHost = 'ldap://localhost';
$ldapPort = '389';
$ldapFullUrl = 'ldap://localhost';
$usesLdap = false;

//PATHS
$serverTestMode = true;
$serverBase = 'localhost';
$serverUrl = $serverBase;
$serverRoot = '/innovation/'; 
$usersRoot = $serverRoot.'users/'; 
$uiRoot = $serverRoot.'ui/'; 
$tempRoot = $serverRoot.'temp/'; 
$serverAdminEmail = 'james.hornitzky@uts.edu.au';

//MAIL
$mailMethod = 'smtp'; 
$mailMethod = '';
$mailServer = 'smtp.gmail.com';
$mailPort = 465;
$mailUser = '';
$mailPass = '';
$mailFrom = 'notifications@innoworks.feit.uts.edu.au';

$mailConfig = array('auth' => 'login',
                	'username' => $mailUser,
                	'password' => $mailPass, 
					'ssl' => 'ssl',
                	'port' => $mailPort);

//OTHER PROPERTIES
$versionName = 'localhost';
$loglevel = 1; //0-5, with 0 being lowest
$salt = '123456789987654321';
?>