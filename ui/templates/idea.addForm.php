<form id="addIdeaForm" class="addForm" onsubmit="addIdea(this);return false;">
	<span>Add new idea</span> 
	<p>Title</p>
	<input id="addIdeaTitle" name="title"
		type="text" dojoType="dijit.form.TextBox" /> 
	<p>Description</p>
	<textarea id="addIdeaTitle" name="serviceDescription" style="width:280px;"/><br/>
	<input type="button"
		value=" + " title="Add idea" onclick="addIdea(this)" /> <input
		style="display: none" type="submit" /> 
	<input type="hidden"
		name="action" value="addIdea" />
</form>
