<?if ($item && dbNumRows($item) > 0) {
	$item = dbFetchObject($item);?>
		<form class="addForm" id="ideaSelectDetails">
			Selection reason
			<textarea name="reason" dojoType="dijit.form.Textarea">
			<?= $item->reason ?>
			</textarea>
			<input type="hidden" name="selectionId"
				value="<?= $item->selectionId ?>" /> <input type="hidden"
				name="action" value="updateSelection" />
		</form>
		<!-- <h3>Tasks</h3>
				<form id="addTaskForm" onsubmit="return false;"><input type="submit" value=" + "/></form>
				 -->
		<p>
			Go to <a
				href='javascript:showSelect(); dijit.byId("ideasPopup").hide()'>Select</a>
		</p>
	<?} else {?>
		<? renderTemplate('no.select'); ?>
		<p>
			Select this idea <a
				onclick="addSelectItem('<?= $ideaId ?>');loadPopupShow()"
				href="javascript:logAction()">now</a>
		</p>
		Go to
		<a href='javascript:showSelect(); dijit.byId(\"ideasPopup\").hide()'>Select</a>
	<?}?>