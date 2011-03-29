<?
//DB CREDENTIALS
$dbUser = 'root'; 
$dbPass = 'return';
$dbURL = 'localhost';
$dbSchema = 'innovation_works';

//LDAP 
$ldapUser = 'cn=admin,dc=example,dc=com';
$ldapPass = 'secret';
$ldapHost = 'ldap://localhost';
$ldapPort = '389';
$ldapFullUrl = 'ldap://localhost';
$usesLdap = false;

//PATHS
$serverUrl = 'localhost';
$serverRoot = '/innovation/'; 
$usersRoot = $serverRoot.'users/'; 
$uiRoot = $serverRoot.'ui/'; 
$tempRoot = $serverRoot.'temp/'; 
$serverAdminEmail = 'james.hornitzky@uts.edu.au';

//OTHER PROPERTIES
$loglevel = 1; //0-5, with 0 being lowest
$salt = '123456789987654321';
?>