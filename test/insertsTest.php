<?
require_once("thinConnector.php");
import("user.service");
import("idea.service");
import("group.service");

//$i = 0;
//while($i < 4000) {
//	createIdea(array("title" => "test idea", "userId" => $_SESSION["innoworks.ID"]));
//	$i++;
//}

$i = 0;
while($i < 1000) {
	createGroup(array("title" => "test group", "userId" => $_SESSION["innoworks.ID"]));
	$i++;
}

echo "<p> Completed test inserts </p>";
?>
