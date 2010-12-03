<?
function import($file) 
{
	require_once($file.".php");
}

function requireUserGroup($userid, $groupname) {
	import("user.service");
	$rs = getUserGroups($userid);
	if ($rs && dbNumRows($rs) > 0) {
		while ($obj = dbFetchObject($rs)) {
			if ($obj->title == $groupname)
			return true;
		}
	}
	die("Not authenticated in correct group");
}

function requireLogin() {
	if (!isset($_SESSION['isAuthen']) || !$_SESSION['isAuthen'])
	die("Not authenticated in correct group");
}

function dbValImplode($array) {
	$arrayString;
	for ($i = 0; $i < count($array); $i++) {
		if ($i == 0) {
			$arrayString = "'".$array[$i]."'";
		} else {
			$arrayString = $arrayString.",'".$array[$i]."'";
		}
	}
	return $arrayString;
}

////////////SAFE URL ENCODE FUNCTIONS /////////////////

function base64_url_encode($input)
{
    return strtr(base64_encode($input), '+/=', '-_,');
}

function base64_url_decode($input)
{
    return base64_decode(strtr($input, '-_,', '+/='));
}

function createIv() {
	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
    return trim(mcrypt_create_iv($iv_size, MCRYPT_RAND));
}

function encrypt($data, $iv) { 
	global $salt;
    return trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $data, MCRYPT_MODE_CBC, $iv));
}

function decrypt($data, $iv) {
	global $salt;
	return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt , $data , MCRYPT_MODE_CBC, $iv));
}
?>