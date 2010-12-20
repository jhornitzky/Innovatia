<?
require_once("thinConnector.php");
import("note.service");
import("user.service");
$notes = getNewNotes($_SESSION['innoworks.ID']);
if ($notes && dbNumRows($notes) > 0) {?>
	<table>
	<? while ($note = dbFetchObject($notes)) {
		if ($note->fromUserId != $_SESSION['innoworks.ID']) { ?>
			<tr>
				<td><b><?= getUserInfo($note->fromUserId)->username  ?></b> : </td>
				<td><?= $note->noteText ?></td>
			</tr>
		<?}
	}?>
	</table>
	<? markNotesAsRead($_SESSION['innoworks.ID']);
}?>