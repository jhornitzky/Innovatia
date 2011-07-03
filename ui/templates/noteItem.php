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
			<td style="vertical-align:top;">
				<img onclick="showProfileSummary(<?= $id ?>)" src="retrieveImage.php?action=userImg&actionId=<?= $id ?>" style="width: 2em; height: 2em" />
			</td>
			<td>
				<span class="noteData">
					<? if ($note->fromUserId === $_SESSION['innoworks.ID']) {?>to<?}?>
					<a href="javascript:showProfileSummary(<?= $id ?>)"><?= getDisplayUsername($id) ?></a>
					<? if (isset($note->ideaId)) {?>on <a href="javascript:showIdeaSummary(<?= $note->ideaId ?>)">idea</a><?}?> 
					<? if (isset($note->groupId)) {?>on <a href="javascript:showGroupSummary(<?= $note->groupId ?>)">group</a><?}?>
					<? if (isset($note->isAnnouncement) && $note->isAnnouncement == 1) {?> <b>announced</b><?}?>
					<? if (isset($note->isFeedback) && $note->isFeedback == 1) {?> gave <b>feedback</b><?}?>
					&nbsp;@&nbsp;<?= prettyDate($note->createdTime) ?> 
				</span>
				<br/>
				<span class="noteText" style="font-size:0.85em;"><?= $note->noteText ?> </span>
			</td>
		</tr>
	</table>
</div>
