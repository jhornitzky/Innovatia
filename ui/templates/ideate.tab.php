<div id="ideaTab" class="tabBody">
	<div style="width: 100%; clear: left">
		<div class="fixed-left">
			<div class='itemHolder groupPreview'></div>
			<div class="ideaGroupsList"></div>
		</div>
		<div class="fixed-right">
			<div class='tiny'>add idea...</div>
			<form id="addIdeaForm" class="addForm" onsubmit="addIdea(this); return false;">
				<input id="addIdeaTitle" class="addIdeaForm" name="title" type="text" dojoType="dijit.form.TextBox" /> 
					<input type="button" value=" + " title="Add idea" onclick="addIdea(this)" placeHolder="new idea title here"/> 
					<input style="display: none" type="submit"/> <input type="hidden"
					name="action" value="addIdea" />
				<span></span>
			</form>
			<div class="ideasList"></div>
		</div>
	</div>
</div>
