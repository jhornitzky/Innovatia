<?
$id = $_SESSION['innoworks.ID'];
if (!empty($note->toUserId) && $note->fromUserId == $id) {
	$class = "outgoing";
	$id = $note->toUserId;
} else if (!empty($note->fromUserId) && $note->toUserId == $id) {
	$class = "incoming";
	$id = $note->fromUserId;
} 
?>
<div class="note <?= $class ?> clearfix">
	<table>
		<tr>
			<td>
				<img src="retrieveImage.php?action=userImg&actionId=<?= $id ?>" style="width: 2em; height: 2em" />
			</td>
			<td>
				<span class="noteData">
					<a href="javascript:showUserSummary(<?= $id ?>)"><?= getDisplayUsername($id) ?></a>
					<? if (isset($note->ideaId)) {?>on <a href="javascript:showIdeaSummary(<?= $note->ideaId ?>)">idea</a><?}?> 
					<? if (isset($note->ideaId)) {?>on <a href="javascript:showGroupSummary(<?= $note->groupId ?>)">group</a><?}?>
					<? if (isset($note->announcementId)) {?>annouced<?}?>
					&nbsp;@&nbsp;<?= prettyDate($note->createdTime) ?> 
				</span>
				<br/>
				<span class="noteText"><?= $note->noteText ?> </span>
			</td>
		</tr>
	</table>
</div>
