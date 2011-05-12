<div id="compareTab" class="tabBody">
	<div style="width: 100%; clear: left">
		<div class="fixed-left">
			<div class='itemHolder groupPreview'></div>
			<div class="ideaGroupsList"></div>
		</div>
		<div class="fixed-right">
			<form class="addForm">
				<div class="tiny">compare idea...</div>
				<input type='button'
					onclick='showAddRiskItem(this)' value=' + '
					title="Add an idea to comparison" /> compare an idea 
			</form>
			<div class="compareList"></div>
			<div id="compareComments" style="margin-top: 1em;">
				<form id="addCompareCommentForm" class="addForm" onsubmit="addCompareComment(this);return false;">
					<div class="tiny">post comments...</div>
					<input type="hidden" name="action" value="addComment" />
					<textarea name="text" dojoType="dijit.form.Textarea" placeholder="insert comment here" style="width: 100%"></textarea>
					<input class="cupid-blue" type="submit" value="post" />
				</form>
				<div class="compareCommentList"></div>
			</div>
		</div>
	</div>
</div>
