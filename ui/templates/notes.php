<?
if ($notes && dbNumRows($notes) > 0 ) {
	while ($note = dbFetchObject($notes)) {
		renderTemplate('noteItem', array('note' => $note));
	}
	if ($countNotes > dbNumRows($notes)) {
		renderTemplate('loadMore', array('action' => 'getNotes', 'limit' => ($limit + 20)));
	}
} else {?>
	<p class="nohelp">
		Notes are messages that you can send to other people using the innoWorks tool. Notes will automatically be sent to releant people when you do stuff (like adding an idea to a group or posting a comment), but you can also send other messages manually too.
	</p>
<?}?>