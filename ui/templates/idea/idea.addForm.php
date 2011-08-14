<form id="addIdeaForm" class="addForm" onsubmit="addIdea(this);return false;">
	<span>Add new idea</span> <br/>
	<span class="tiny">Title</span>
	<input id="addIdeaTitle" name="title" type="text" class="dijitTextBox"/> 
	<span class="tiny">Description</span>
	<textarea id="addIdeaTitle" name="proposedService" class="dijitTextArea" style="width:95%;"></textarea><br/>
	<input type="submit" value="add idea" title="Add idea"/>  
	<input type="hidden" name="action" value="addIdea" />
</form>
