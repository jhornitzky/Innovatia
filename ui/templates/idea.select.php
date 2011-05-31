<?if ($item && dbNumRows($item) > 0) {
	$item = dbFetchObject($item);?>
		<form class="addForm" id="ideaSelectDetails">
			Selection reason
			<textarea name="reason" dojoType="dijit.form.Textarea"><?= $item->reason ?></textarea>
			<input type="hidden" name="selectionId" value="<?= $item->selectionId ?>" /> 
			<input type="hidden" name="action" value="updateSelection" />
		</form>
	<?if (isset($tasks) && dbNumRows($tasks) > 0) { ?>
			<table id="taskDetails">
				<tr>
					<th style="padding-right:20px; font-weight:normal;">
						<span style="font-size:1.5em; color:#AAA;">
							tasks
						</span>
					</th>
					<th>effort (days)</th> 
					<th>status (%)</th> 
					<th>start date</th> 
					<th>end date</th> 
				</tr>
				<?while ($task = dbFetchObject($tasks)) {?>
					<tr>
						<td><?= $task->feature ?></td> 
						<td><input class="dijitTextBox" type="text" name="effort" value="<?= $task->effort ?>" onchange="updateTask(this, '<?= $task->featureId ?>')"/></td> 
						<td><input class="dijitTextBox" type="text" name="complete" value="<?= $task->complete ?>" onchange="updateTask(this, '<?= $task->featureId ?>')"/></td> 
						<td><input type="text" name="startDate" dojoType="dijit.form.DateTextBox" value="<?= $task->startDate ?>" onchange="updateDateTask(this, 'startDate', '<?= $task->featureId ?>')"/></td> 
						<td><input type="text" name="finishDate" dojoType="dijit.form.DateTextBox" value="<?= $task->finishDate ?>" onchange="updateDateTask(this, 'finishDate', '<?= $task->featureId ?>')"/></td> 
					</tr>
				<?}?>
			</table>
	<?}?>
	<!-- <p>Go to <a href='javascript:showSelect(); dijit.byId("ideasPopup").hide()'>Select</a></p> -->
<?} else {?>
	<? renderTemplate('no.select'); ?>
	<p>
		Select this idea 
		<a onclick="addSelectItem('<?= $ideaId ?>');loadPopupShow()" href="javascript:logAction()">now</a>
	</p>
	<!-- Go to <a href='javascript:showSelect(); dijit.byId(\"ideasPopup\").hide()'>select</a>-->
<?}?>