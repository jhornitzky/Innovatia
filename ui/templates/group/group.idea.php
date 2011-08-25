<tr>
	<td><img
		src="engine.ajax.php?action=groupImg&actionId=<?= $group->groupId ?>"
		style="width: 1em; height: 1em;" />
		<?= $group->title ?>
	</td>
	<td><input type="checkbox"
		onclick="toggleGroupShareIdea(this, <?= $group->groupId ?>)"
		<? if ($shared) echo "checked"; ?> />
	</td>
	<td><input type='checkbox'
		onclick='toggleGroupEditIdea(this, <?= $idea->ideaId ?>, <?= $group->groupId ?>)'
		alt='Assign edit access to group' <? if ($canEdit) echo "checked"; ?> />
	</td>
</tr>