<div id="groupTab" class="tabBody">
	<div style="width: 100%;">
		<div class="fixed-left">
			<div id="groupsList">&nbsp;</div>
		</div>
		<div class="fixed-right">
			<form id="addGroupForm" class="addForm" style="margin-bottom:0.5em;" onsubmit="addGroup(); return false;">
				<div class="tiny">create new group...</div> 
				<input name="title" type="text" dojoType="dijit.form.TextBox" /> 
				<input type="submit" value=" + " title="Create a group" /> 
				<input type="hidden" name="action" value="addGroup" />
			</form>
			<div id="groupDetailsCont">
				<div id="groupDetails"></div>
			</div>
		</div>
	</div>
</div>