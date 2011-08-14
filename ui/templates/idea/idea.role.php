<tr id="roleform_<?= $role->roleId ?>">
<?
if ($canEdit) {
	renderGenericUpdateRow($roles, $role, array("roleId", "ideaId"));?>
	<td><input type="hidden" name="roleId" value="<?= $role->roleId ?>" />
		<input type="button"
		onclick="deleteRole('deleteRole','<?= $role->roleId ?>', 'idearoles_<?= $role->ideaId?>','<?= $role->ideaId ?>');"
		value=" - " />
	</td>
	<?} else {
		renderGenericInfoRow($roles, $role, array("roleId", "ideaId"));
	}?>
</tr>
