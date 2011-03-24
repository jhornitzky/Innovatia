<div id="compareTab" class="tabBody">
	<div style="width: 100%; clear: left">
		<div class="fixed-left">
			<div class='itemHolder groupPreview'></div>
			<div class="ideaGroupsList"></div>
		</div>
		<div class="fixed-right">
			<form class="addForm">
				Click here to add idea to comparison <input type='button'
					onclick='showAddRiskItem(this)' value=' + '
					title="Add an idea to comparison" />
			</form>
			<div class="compareList"></div>
			<div id="compareComments" style="margin-top: 1em;">
				<form id="addCompareCommentForm" class="addForm"
					onsubmit="addCompareComment(this);return false;">
					<input type="hidden" name="action" value="addComment" /> Comments <input
						type="submit" value=" + " />
					<textarea name="text" dojoType="dijit.form.Textarea"
						style="width: 100%"></textarea>
				</form>
				<div class="compareCommentList"></div>
			</div>
		</div>
	</div>
</div>
