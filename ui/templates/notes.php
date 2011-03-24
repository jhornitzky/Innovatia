<?if ($notes && dbNumRows($notes) > 0 ) {
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
		
		if ($from) {
			$fromUser = $from->username;
		}?>
			<div class="<?= $class ?>" style="min-height: 2em">
				<table>
					<tr>
						<td><img src="retrieveImage.php?action=userImg&actionId=<?= $id ?>"
							style="width: 2em; height: 2em" />
						</td>
						<td><span><?= $note->noteText ?> </span><br /> <span class="noteData">
						<?= $note->createdTime ?>&nbsp; <?= $fromUser ?>&nbsp; &gt;&nbsp; <?= getUserInfo($note->toUserId)->username ?>&nbsp;
						</span>
						</td>
					</tr>
				</table>
			</div>
	<?}
	if ($countNotes > dbNumRows($notes)) {?>
		<a class="loadMore" style="clear: left; float: left"
			href="javascript:logAction()"
			onclick="loadResults(this, {action:'getNotes', limit:'<?= ($limit + 20) ?>'})">Load
			more</a>
	<?}
	markNotesAsRead($_SESSION['innoworks.ID']);
} else {?>
	<p>No notes yet</p>
<?}?>