<tr id="featureform_<?= $feature->featureId ?>">
<?
if ($canEdit) {
	renderGenericUpdateRow($features, $feature, array("featureId", "ideaId"));?>
	<td><input type="hidden" name="featureId"
		value="<?= $feature->featureId ?>" /> <input type="button"
		onclick="deleteFeature('deleteFeature','<?= $feature->featureId ?>', 'ideafeatures_<?= $feature->ideaId?>','<?= $feature->ideaId ?>');"
		value=" - " />
	</td>
	<?} else {
		renderGenericInfoRow($features, $feature, array("featureId", "ideaId"));
	}?>
</tr>