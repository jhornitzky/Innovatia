<? 
require_once("thinConnector.php");
import("note.service");
import("user.service");
?>

<form id="newNoteForm" class="ui-corner-all addForm" onsubmit="addNote(this); return false;">
	Send Note
	<input type="hidden" name="action" value="addNote"/>
	<select name="toUserId">
		<?
			$users = getAllUsers();
			while ($user = dbFetchObject($users)) {
				echo "<option value='$user->userId'>$user->username</option>"; 
			}
		?>
	</select>
	<input type="text" name="noteText" id="noteText"/>
	<input type="submit" value=" + " title = "Add Note"/>
</form>

<?
echo "<h3>Inbox Notes</h3>";
$notes = getAllNotes($_SESSION['innoworks.ID']);
if ($notes && dbNumRows($notes) > 0 ) {
	echo "<table>";
	while ($note = dbFetchObject($notes)) {?>
		<tr>
			<td><b><?= $note->fromUserId ?></b></td>
			<td><?= $note->noteText ?></td>
			<td><i><?= $note->createdTime ?></i></td>
		</tr>
	<?}
	echo "</table>";
	markNotesAsRead($_SESSION['innoworks.ID']);
} else {
	echo "<p>No inbox notes yet</p>";
}

echo "<h3>Sent Notes</h3>";
$sentNotes = getAllSentNotes($_SESSION['innoworks.ID']);
if ($sentNotes && dbNumRows($sentNotes) > 0 ) {
	echo "<table>";
	while ($note = dbFetchObject($sentNotes)) {?> 
		<tr>
			<td><b><?= $note->toUserId ?></b></td>
			<td><?= $note->noteText ?></td>
			<td><i><?= $note->createdTime ?></i></td>
		</tr>
	<?}
	echo "</table>";
} else {
	echo "<p>No sent notes yet</p>";
}
?>

