<? if ($comments && dbNumRows($comments) > 0) {
	while ($comment = dbFetchObject($comments)) {?>
		<div class='itemHolder'>
			<img
				src="retrieveImage.php?action=userImg&actionId=<?= $comment->userId ?>"
				style="width: 1em; height: 1em;" /> <span class='title'><?=$userService->getDisplayUsername($comment->userId)?>
			</span> <span class='timestamp'><?=$comment->timestamp?> </span>
			<?if ($comment->userId == $uId || $_SESSION['innoworks.isAdmin']) { ?>
			<input type='button'
				onclick='deleteComment("<?=$comment->commentId?>")' value=' - '>
				<?}?>
			<br />
			<?=$comment->text;?>
		</div>
	<?}
} else {?>
	<p class="nohelp">Share a comment with others to get innovation flowing...</p>
<?}?>