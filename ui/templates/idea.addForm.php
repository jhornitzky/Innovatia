<form id="addIdeaForm" class="addForm" onsubmit="addIdea(this);return false;">
	<span>Add new idea</span> <br/>
	<span>Title</span>
	<input id="addIdeaTitle" name="title" type="text" class="dijitTextBox"/> 
	<span>Description</span>
	<textarea id="addIdeaTitle" name="serviceDescription" class="dijitTextArea" style="width:95%;"></textarea><br/>
	<input type="submit" value="Add Idea " title="Add idea"/>  
	<input type="hidden" name="action" value="addIdea" />
</form>
