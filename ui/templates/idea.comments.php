<?if ($comments && dbNumRows($comments) > 0 ) {
	while ($comment = dbFetchObject($comments)) {
		renderTemplate('comment', array('comment' => $comment));
	}
} else {?>
	<p class="nohelp">Share a comment with others to get innovation flowing...</p>
<?}?>