<div class="ideaRoles subform">
<? if ($canEdit) { ?>
	<form id="addrole_form_<?= $idea->ideaId?>" class="addForm">
		<span>new role</span> <input class="dijitTextBox" type="text" name="role" /> <input
			type="hidden" name="ideaId" value="<?= $idea->ideaId?>" /> <input
			type="hidden" name="action" value="addRole" /> <input type="button"
			onclick="addRole('addrole_form_<?= $idea->ideaId?>', 'idearoles_<?= $idea->ideaId?>','<?= $idea->ideaId ?>');"
			value=" + " />
	</form>
	<? } ?>
	<div id="idearoles_<?= $idea->ideaId?>">
	<? renderIdeaRoles($idea->ideaId); ?>
	</div>
</div>