<? 
require_once("thinConnector.php");
import("note.service");
import("user.service");
import("search.service");
$users = getSearchPeople("",$_SESSION['innoworks.ID']);
$notes = getAllNotes($_SESSION['innoworks.ID']);
?>
<div style="width:100%;">
		<div class="fixed-left">
			<h2 id="pgName">Notes</h2>
		</div>	
		<div class="fixed-right">
<form id="newNoteForm" class="ui-corner-all addForm" onsubmit="addNote(this); return false;" style="max-width:25em; ">
	<input type="hidden" name="action" value="addNote"/>
	Send note to
	<select class="toUserNote" dojoType="dijit.form.ComboBox" name="toUserId">
		<?
			if ($notes && dbNumRows($notes) > 0 ) {
				$firstNote = dbFetchObject($notes);
				if ($firstNote->fromUserId == $_SESSION['innoworks.ID']) 
					echo "<option value='$firstNote->toUserId'>".getUserInfo($firstNote->toUserId)->username."</option>";
				else
					echo "<option value='$firstNote->fromUserId'>".getUserInfo($firstNote->fromUserId)->username."</option>"; 
			}
			while ($user = dbFetchObject($users)) {
				echo "<option value='$user->userId'>$user->username</option>"; 
			}
		?>
	</select>
	<table style="100%">
	<tr>
	<td style="width:90%;">
	<input type="text" name="noteText" class="noteText" dojoType="dijit.form.Textarea" />
	</td>
	<td style="width:9%;">
		<input type="submit" value=" + " title = "Send"/>
	</td>
	</tr></table>
</form>

<script type="text/javascript">
	dojo.parser.instantiate(dojo.query(".toUserNote"));
	dojo.parser.instantiate(dojo.query(".noteText"));
</script>

<?
if ($notes && dbNumRows($notes) > 0 ) {
	dbRowSeek($notes, 0);
	echo "<div id='notePadder'>";
	while ($note = dbFetchObject($notes)) {
		$class;
		if ($note->toUserId == $_SESSION['innoworks.ID']) 
			$class = "incoming";
		else 
			$class = "outgoing";	
		?>
		<div class="<?= $class ?>">
			<span><?= $note->noteText ?></span><br/>
			<span class="noteData">
				<?= $note->createdTime ?>&nbsp;
				<?= getUserInfo($note->fromUserId)->username ?>&nbsp;
				&gt;&nbsp;
		    	<?= getUserInfo($note->toUserId)->username ?>&nbsp;
		    </span>
		</div>
	<?}
	echo "</div>";
	markNotesAsRead($_SESSION['innoworks.ID']);
} else {
	echo "<p>No inbox notes yet</p>";
}
?>
</div>
</div>
