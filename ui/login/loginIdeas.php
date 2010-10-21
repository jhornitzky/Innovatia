<?php
require_once("thinConnector.php");
import("idea.service");

$ideas = getPublicIdeas();
if ($ideas && dbNumRows($ideas)) {
	$i = 0;
	while($idea = dbFetchObject($ideas) ) {
		$i++;
		echo "<p>".$idea->title." by ".$idea->username."<br/><span style='font-size:0.8em;'>".$idea->serviceDescription."</span></p>";
		if ($i > 5)
			break;
	}
} else {
	echo "Sadly no public ideas";
}
?>