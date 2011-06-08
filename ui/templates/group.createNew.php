<form class="addForm" onsubmit="addGroup(this); return false">
<span>create new group</span>
<div class="tiny">title</div>
<input type="text" name="title" class="dijitTextBox"/>
<div class="tiny">description</div>
<textarea name="description" class="dijitTextArea"></textarea>
<div class="tiny">tags</div>
<input type="text" name="tags" class="dijitTextBox"/>
<input type="submit" value="create"/>
<input type="hidden" name="action" value="addGroup"/>
</form>