<?
require_once("thinConnector.php");
import("note.service");
import("user.service");
$notes = getNewNotes($_SESSION['innoworks.ID']);
if ($notes && dbNumRows($notes) > 0) {
	echo "<table>";
	while ($note = dbFetchObject($notes)) {?>
		<tr>
			<td><b><?= getUserInfo($note->fromUserId)->username  ?></b></td>
			<td>></td>
			<td>you</td>
			<td><?= $note->noteText ?></td>
		</tr>
	<?}
	echo "</table>";
	markNotesAsRead($_SESSION['innoworks.ID']);
}
?>