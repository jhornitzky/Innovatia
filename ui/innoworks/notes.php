<? 
require_once("thinConnector.php");
//import("note.service");
$notes = dbQuery("SELECT * FROM Notes WHERE toUserId='".$_SESSION['innoworks.ID']."' ORDER BY createdTime");
if ($notes && dbNumRows($notes) > 0 ) {
	while ($note = dbFetchObject($notes)) {?>
	<p><?= $note->noteText ?></p>
	<?}
} else {
	echo "<p>No notes yet</p>";
}
?>

