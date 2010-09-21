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
			$arrayString = $arrayString."'".$array[$i]."'";
		} else {
			$arrayString = $arrayString.",'".$array[$i]."'";
		}
	}
	return $arrayString;
}
?>