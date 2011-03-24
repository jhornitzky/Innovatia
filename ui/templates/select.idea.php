<div id="selectideaform_<?= $idea->ideaId?>"
	class="selection idea hoverable" title="<?= $idea->title ?>">
	<table>
		<tr>
			<td class="image"><img
				src="retrieveImage.php?action=ideaImg&actionId=<?= $idea->ideaId?>"
				style="width: 64px; height: 64px" /><br />
			</td>
			<td style="width: 10em"><img
				src="retrieveImage.php?action=userImg&actionId=<?= $idea->userId?>"
				style="width: 1em; height: 1em" /> <span class="ideator"><?= getDisplayUsername($idea->userId); ?>
			</span> <span class="ideaoptions"> <?if ($idea->userId == $user) { ?>
					<input type="button" value=" - "
					onclick="deleteSelectIdea(<?= $idea->selectionId?>)"
					title="Deselect this idea" /> <?}?> </span><br /> <span
				class="ideatitle"><a href="javascript:logAction()"
					onclick="showIdeaDetails('<?= $idea->ideaId?>');"> <?=trim($idea->title)?>
				</a> </span> <br />
			</td>
			<td style="vertical-align: middle;"><b><?= dbNumRows($features); ?> </b>
				Feature(s) &nbsp; <b><?= dbNumRows($roles); ?> </b> Role(s) &nbsp; <b><?= dbNumRows($comments);?>
			</b> Comment(s) &nbsp; <b><?= dbNumRows($views);?> </b> View(s)</td>
		</tr>
	</table>
</div>