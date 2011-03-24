<div class="ideaFeatures subform">
<? if ($canEdit) { ?>
	<form id="addfeature_form_<?= $idea->ideaId?>" class="addForm">
		<span> New Feature </span> <input type="text" name="feature" /> <input
			type="hidden" name="ideaId" value="<?= $idea->ideaId?>" /> <input
			type="hidden" name="action" value="addFeature" /> <input
			type="button"
			onclick="addFeature('addfeature_form_<?= $idea->ideaId?>', 'ideafeatures_<?= $idea->ideaId?>','<?= $idea->ideaId ?>');"
			value=" + " />
	</form>
	<?}?>
	<div id="ideafeatures_<?= $idea->ideaId?>">
	<? renderIdeaFeatures($idea->ideaId); ?>
	</div>
</div>
