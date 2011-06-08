<div class='itemHolder'>
	<span class="noteData"> <img
		src="retrieveImage.php?action=userImg&actionId=<?= $comment->userId ?>"
		style="width: 1em; height: 1em; vertical-align: middle;" /> <a
		href="javascript:showUserSummary(<?= $comment->userId ?>)"><?= getDisplayUsername($comment->userId) ?>
	</a>&nbsp;@&nbsp;<?= prettyDate($comment->timestamp) ?> </span>
	<?if ($comment->userId == $uId || $_SESSION['innoworks.isAdmin']) { ?>
	<img class="control" onclick='deleteComment("<?=$comment->commentId?>")'
		src="<?= $uiRoot.'style/trash.png'?>">
		<?}?>
	<br />
	<?=$comment->text;?>
</div>
