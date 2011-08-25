<div class='itemHolder note'>
	<table>
		<tr>
			<td style="vertical-align:top;">
				<img src="engine.ajax.php?action=userImg&actionId=<?= $comment->userId ?>" style="width: 3em; height: 3em; vertical-align: middle;" /> 
			</td>
			<td>
				<span class="noteData" style="font-size:0.85em"> 
					<a href="javascript:showUserSummary(<?= $comment->userId ?>)"><?= getDisplayUsername($comment->userId) ?></a>&nbsp;@&nbsp;<?= prettyDate($comment->timestamp) ?> </span>
				<?if ($comment->userId == $uId || $_SESSION['innoworks.isAdmin']) { ?>
					<img class="control" onclick='deleteComment("<?=$comment->commentId?>")' src="<?= $uiRoot.'style/trash.png'?>">
				<?}?>
				<br />
				<span class="noteText">
					<?=$comment->text;?>
				</span>
			</td>
		</tr>
	</table>
</div>