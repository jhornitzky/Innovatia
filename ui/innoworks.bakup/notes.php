<? 
require_once("thinConnector.php");
import("note.service");
import("user.service");
?>
<form id="newNoteForm" class="ui-corner-all addForm" onsubmit="addNote(this); return false;">
	Send Note to
	<input type="hidden" name="action" value="addNote"/>
	<select class="toUserNote" dojoType="dijit.form.ComboBox" name="toUserId">
		<?
			$users = getAllUsers();
			while ($user = dbFetchObject($users)) {
				echo "<option value='$user->userId'>$user->username</option>"; 
			}
		?>
	</select>
	<input type="submit" value=" + " title = "Send"/>
	<input type="text" name="noteText" class="noteText" dojoType="dijit.form.Textarea" />
</form>

<script type="text/javascript">
	dojo.parser.instantiate(dojo.query(".toUserNote"));
	dojo.parser.instantiate(dojo.query(".noteText"));
</script>

<?
$notes = getAllNotes($_SESSION['innoworks.ID']);
if ($notes && dbNumRows($notes) > 0 ) {
	echo "<table cellpadding='3px'>";
	while ($note = dbFetchObject($notes)) {
		$class;
		if ($note->toUserId == $_SESSION['innoworks.ID']) 
			$class = "incoming";
		else 
			$class = "outgoing";	
		?>
		<tr class="<?= $class ?>">
			<td class="first"><i><?= $note->createdTime ?></i></td>
			<td><b><?= getUserInfo($note->fromUserId)->username ?></b></td>
			<td>></td>
		    <td><b><?= getUserInfo($note->toUserId)->username ?></b></td>
			<td class="last"><?= $note->noteText ?></td>
		</tr>
	<?}
	echo "</table>";
	markNotesAsRead($_SESSION['innoworks.ID']);
} else {
	echo "<p>No inbox notes yet</p>";
}
?>

