<?php
require_once("thinConnector.php");
import("user.service");

$users = getPublicUsers();
if ($users && dbNumRows($users) > 0) {
	while($user = dbFetchObject($users)) {
		echo "<p>".$user->username."</p>";
	}
} else {
	echo "Sadly no public people yet";
}
?>