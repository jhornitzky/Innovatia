<form id="newNoteForm" class="addForm" onsubmit="addNote(this); return false;">
	<input type="hidden" name="action" value="addNote" />
	<div class="tiny">Send note to...</div> 
	<div class="userChooser" onclick="showAddNoteUser(this);"><?= renderAddNoteUserHeader($latestUser); ?></div>
	<input class="toUserNote" name="toUserId" type="hidden" value="<?= $latestUser ?>"/>
	<input type="text" name="noteText" class="noteText dijitTextArea" style="width:100%:" dojoType="dijit.form.Textarea" />
	<input type="submit" value="send" title="Send" />
</form>
<div id='notePadder'>
	<? renderNotes($_SESSION['innoworks.ID'], $limit); ?>
</div>
<script type="text/javascript">
	dojo.parser.instantiate(dojo.query(".toUserNote"));
	dojo.parser.instantiate(dojo.query(".noteText"));
</script>