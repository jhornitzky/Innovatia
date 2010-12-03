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

function createIv() {
	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    return mcrypt_create_iv($iv_size, MCRYPT_RAND);
}

function encrypt($data, $iv) {
	global $salt;
    $key = $salt;
    $text = $data;
    $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv);
   	return htmlspecialchars(base64_encode($crypttext));
}

function decrypt($data, $iv) {
	global $salt;
	$data = htmlspecialchars_decode(base64_decode($data));
	return mcrypt_decrypt( MCRYPT_RIJNDAEL_256, $salt , $data , MCRYPT_MODE_ECB, $iv);
}
?>