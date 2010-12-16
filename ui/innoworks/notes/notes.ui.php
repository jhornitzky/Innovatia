<? 
require_once(dirname(__FILE__) . "/../thinConnector.php");
import("note.service");
import("user.service");
import("search.service");

function renderNotesDefault($user) {
	$limit = 20;
	$users = getSearchPeople("",$_SESSION['innoworks.ID'], array());
	
	$notes = getAllNotes($user);?>
	<div style="width:100%;">
		<div class="fixed-left">
			<h2 id="pgName">Notes</h2>
		</div>	
		<div class="fixed-right">
		<form id="newNoteForm" class="ui-corner-all addForm" onsubmit="addNote(this); return false;">
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
				</tr>
			</table>
		</form>
		<div id='notePadder'>
			<?renderNotes($_SESSION['innoworks.ID'], $limit);?>
		</div>
		</div>
	</div>
	<script type="text/javascript">
	dojo.parser.instantiate(dojo.query(".toUserNote"));
	dojo.parser.instantiate(dojo.query(".noteText"));
	</script>
<?}

function renderNotes($user, $limit) {
	$notes = getAllNotes($user, "LIMIT $limit");
	$countNotes = countGetAllNotes($user);
	if ($notes && dbNumRows($notes) > 0 ) {
		while ($note = dbFetchObject($notes)) {
			$class;
			if ($note->fromUserId == $user) 
				$class = "outgoing";
			else 
				$class = "incoming";	
			$fromUser = '';
			$from = getUserInfo($note->fromUserId);
			if ($from) 
				$fromUser = $from->username;
			?>
			<div class="<?= $class ?>">
				<span><?= $note->noteText ?></span><br/>
				<span class="noteData">
					<?= $note->createdTime ?>&nbsp;
					<?= $fromUser ?>&nbsp;
					&gt;&nbsp;
		    		<?= getUserInfo($note->toUserId)->username ?>&nbsp;
		    	</span>
			</div>
		<?}
		if ($countNotes > dbNumRows($notes)) {?>
			<a style="clear:left; float:left" href="javascript:logAction()" onclick="loadResults(this, {action:'getNotes', limit:'<?= ($limit + 20) ?>'})">Load more</a>
		<?}
		markNotesAsRead($_SESSION['innoworks.ID']);
	} else {?>	
		<p>No notes yet</p>
	<?}
}
?>