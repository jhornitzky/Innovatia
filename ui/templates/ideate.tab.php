<div id="ideaTab" class="tabBody">
	<div style="width: 100%; clear: left">
		<div class="fixed-left">
			<div class='itemHolder groupPreview'></div>
			<div class="ideaGroupsList"></div>
		</div>
		<div class="fixed-right">
			<form id="addIdeaForm" class="addForm"
				onsubmit="addIdea(this);return false;">
				<span>Add new idea</span> <input id="addIdeaTitle" name="title"
					type="text" dojoType="dijit.form.TextBox" /> <input type="button"
					value=" + " title="Add idea" onclick="addIdea(this)" /> <input
					style="display: none" type="submit" /> <input type="hidden"
					name="action" value="addIdea" />
			</form>
			<div class="ideasList"></div>
		</div>
	</div>
</div>
