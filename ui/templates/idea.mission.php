<div class="formBody">
		<div class="ideaDetails subform">
			<form id="ideadetails_form_<?= $idea->ideaId?>">
			<?if ($canEdit)
				renderGenericUpdateFormWithRefData(null ,$idea, array("ideaId", "title","userId", "createdTime", "username", "isPublic", "score", "lastUpdateTime", 'firstName', 'lastName'), "Ideas"); 
			else 
				renderGenericInfoForm(null ,$idea, array("ideaId", "title","userId", "createdTime", "username", "isPublic", "score", "lastUpdateTime", 'firstName', 'lastName'));?>
			<input type="hidden" name="ideaId" value="<?= $idea->ideaId?>" /> 
			<input type="hidden" name="action" value="updateIdeaDetails" /> 
			</form>
		</div>
	</div>
<?}