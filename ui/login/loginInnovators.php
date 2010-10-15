<?php
require_once("thinConnector.php");
import("user.service");

$users = getPublicUsers();
if ($users && dbNumRows($users) > 0) {
	while($user = dbFetchObject($users)) {
		echo $user->username;
	}
} else {
	echo "Sadly no public people yet";
}
?>