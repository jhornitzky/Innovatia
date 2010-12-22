<? 
require_once(dirname(__FILE__) . "/../thinConnector.php");
import("note.service");
import("user.service");
import("search.service");

function renderNotesDefault($user) {
	$limit = 20;
	$users = getSearchPeople("",$_SESSION['innoworks.ID'], array());
	$notes = getAllNotes($user, "LIMIT $limit");?>
	<div style="width:100%;">
		<div class="fixed-left">
			<h2 class="pgName"></h2>
		</div>	
		<div class="fixed-right">
		<form id="newNoteForm" class="ui-corner-all addForm" onsubmit="addNote(this); return false;">
			<input type="hidden" name="action" value="addNote"/>
			Send note to
			<select class="toUserNote" dojoType="dijit.form.FilteringSelect" name="toUserId">
			<?
				$firstUser;
				if ($notes && dbNumRows($notes) > 0 ) {
					$firstNote = dbFetchObject($notes);
					if ($firstNote->fromUserId == $_SESSION['innoworks.ID'])
						$firstUser = getUserInfo($firstNote->toUserId);
					else 
						$firstUser = getUserInfo($firstNote->fromUserId);
					echo "<option value='$firstUser->userId'>" . $firstUser->firstName . " " . $firstUser->lastName . " / " . $firstUser->username  . "</option>"; 
				}
				
				while ($user = dbFetchObject($users)) {
					if ($user->userId != $firstUser->userId) 
						echo "<option value='$user->userId'>" . $user->firstName . " " . $user->lastName . " / " . $user->username ."</option>"; 
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
			$id;
			if ($note->fromUserId == $user) {
				$class = "outgoing";
				$id = $note->toUserId;
			} else {
				$class = "incoming";
				$id = $note->fromUserId;	
			}
			$fromUser = '';
			$from = getUserInfo($note->fromUserId);
			if ($from) 
				$fromUser = $from->username;
			?>
			<div class="<?= $class ?>" style="min-height:2em">
				<table>
				<tr>
					<td>
					<img src="retrieveImage.php?action=userImg&actionId=<?= $id ?>" style="width:2em; height:2em"/>
					</td>
					<td>
					<span><?= $note->noteText ?></span><br/>
					<span class="noteData">
						<?= $note->createdTime ?>&nbsp;
						<?= $fromUser ?>&nbsp;
						&gt;&nbsp;
		    			<?= getUserInfo($note->toUserId)->username ?>&nbsp;
		    		</span>
		    		</td>
				</tr>
				</table>
				
			</div>
		<?}
		if ($countNotes > dbNumRows($notes)) {?>
			<a class="loadMore" style="clear:left; float:left" href="javascript:logAction()" onclick="loadResults(this, {action:'getNotes', limit:'<?= ($limit + 20) ?>'})">Load more</a>
		<?}
		markNotesAsRead($_SESSION['innoworks.ID']);
	} else {?>	
		<p>No notes yet</p>
	<?}
}
?>