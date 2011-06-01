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
		$from = getUserInfo($note->fromUserId);?>
			<div class="<?= $class ?> clearfix">
				<table>
					<tr>
						<td><img src="retrieveImage.php?action=userImg&actionId=<?= $id ?>" style="width: 2em; height: 2em"/></td>
						<td><span><?= $note->noteText ?></span><br /> 
						<span class="noteData">
						<?= getDisplayUsername($from) ?>&nbsp; &gt;&nbsp; <?= getDisplayUsername(getUserInfo($note->toUserId)) ?>&nbsp;
						&nbsp; <?= $note->createdTime ?>
						</span>
						</td>
					</tr>
				</table>
			</div>
	<?}
	if ($countNotes > dbNumRows($notes)) {?>
		<a class="loadMore" style="clear: both; float:left"
			href="javascript:logAction()"
			onclick="loadResults(this, {action:'getNotes', limit:'<?= ($limit + 20) ?>'})">Load more</a>
	<?}
} else {?>
	<p class="nohelp">
		Notes are messages that you can send to other people using the innoWorks tool. Notes will automatically be sent to releant people when you do stuff (like adding an idea to a group or posting a comment), but you can also send other messages manually too.
	</p>
<?}?>