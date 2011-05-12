<form id="newNoteForm" class="addForm" onsubmit="addNote(this); return false;">
	<input type="hidden" name="action" value="addNote" />
	<div class="tiny">Send note to...</div> 
	<div class="userChooser" onclick="showAddNoteUser();" style="color:#444; border: 1px dashed #AAA;">someone</div>
	<input class="toUserNote" name="toUserId" type="hidden"/>
	<!-- <select class="toUserNote" dojoType="dijit.form.FilteringSelect" name="toUserId">
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
	</select> -->
	<input type="text" name="noteText" class="noteText" dojoType="dijit.form.Textarea" />
	<input type="submit" value="send" title="Send" />
</form>
<div class="tiny">your latest notes...</div>
<div id='notePadder'>
<?renderNotes($_SESSION['innoworks.ID'], $limit);?>
</div>
<script type="text/javascript">
	dojo.parser.instantiate(dojo.query(".toUserNote"));
	dojo.parser.instantiate(dojo.query(".noteText"));
</script>