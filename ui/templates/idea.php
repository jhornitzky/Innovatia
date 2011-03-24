<div id="ideaform_<?= $idea->ideaId?>" class="idea hoverable"
	title="<?= $idea->title ?>">
	<table>
		<tr>
			<td class="image"><img
				src="retrieveImage.php?action=ideaImg&actionId=<?= $idea->ideaId?>"
				style="width: 64px; height: 64px" /><br /></td>
			<td><img
				src="retrieveImage.php?action=userImg&actionId=<?= $idea->userId?>"
				style="width: 1em; height: 1em"
				title="<?= getDisplayUsername($idea->userId); ?>" /> <span
				class="ideator"><?= getDisplayUsername($idea->userId); ?>
			</span> <span class="ideaoptions"> <?if ($idea->userId == $user || $_SESSION['innoworks.isAdmin']) { ?>
					<input type="button" value=" - "
					onclick="deleteIdea(<?= $idea->ideaId?>)" title="Delete this idea" />
					<?}?> </span><br /> <span class="ideatitle"> <a
					href="javascript:logAction()"
					onclick="showIdeaDetails('<?= $idea->ideaId?>');"><?=trim($idea->title)?>
				</a>
			</span><br /></td>
		</tr>
	</table>
</div>