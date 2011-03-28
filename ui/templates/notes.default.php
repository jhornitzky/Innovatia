<form id="newNoteForm" class="ui-corner-all addForm" onsubmit="addNote(this); return false;">
	<input type="hidden" name="action" value="addNote" /> Send note to 
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
	<table>
		<tr>
			<td style="width: 90%;"><input type="text" name="noteText"
				class="noteText" dojoType="dijit.form.Textarea" />
			</td>
			<td style="width: 9%;"><input type="submit" value=" + " title="Send" />
			</td>
		</tr>
	</table>
</form>
<div id='notePadder'>
<?renderNotes($_SESSION['innoworks.ID'], $limit);?>
</div>
<script type="text/javascript">
	dojo.parser.instantiate(dojo.query(".toUserNote"));
	dojo.parser.instantiate(dojo.query(".noteText"));
</script>