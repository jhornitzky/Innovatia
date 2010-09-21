<?
/*
	require_once("thinConnector.php");
	import("idea.service");
	$mysqli=mysqli_connect('localhost','root','return','innovation_works');
	if (!$mysqli)
		die("Can't connect to MySQL: ".mysqli_connect_error());

	$id=6;  
	$stmt = $mysqli->prepare("SELECT data FROM Attachments WHERE attachmentId=?"); 
	$stmt->bind_param("i", $id);

	$stmt->execute();
	$stmt->store_result();

	$stmt->bind_result($data);
	//$stmt->bind_result($type);
	$stmt->fetch();

	header("Content-Type: image/jpeg");
	//header("Content-Type: ". $type);
	echo $data; 
	exit;
	*/
 
require_once("thinConnector.php");
import("idea.service");

if (isset($_GET) && $_GET != '') {
	switch ($_GET['action']) {
		case "retrieveAttachment":
			retrieveAttachment($_GET['actionId']); 
			break;
	} 
}


?>