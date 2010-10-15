<?php
require_once("thinConnector.php");
import("idea.service");

$ideas = getPublicIdeas();
if ($ideas && dbNumRows($ideas)) {
	$i = 0;
	while($idea = dbFetchObject($ideas) ) {
		$i++;
		echo "<p>".$idea->title." ".$idea->username."<br/>".$idea->serviceDescription."</p>";
		if ($i > 5)
			break;
	}
} else {
	echo "Sadly no public ideas";
}
?>