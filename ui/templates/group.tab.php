<div id="groupTab" class="tabBody">
	<div style="width: 100%;">
		<div class="fixed-left">
			<div id="groupsList">&nbsp;</div>
		</div>
		<div class="fixed-right">
			<form id="addGroupForm" class="addForm"
				onsubmit="addGroup(); return false;">
				<span>Create new group</span> <input name="title" type="text"
					dojoType="dijit.form.TextBox" /> <input type="submit" value=" + "
					title="Create a group" /> <input type="hidden" name="action"
					value="addGroup" />
			</form>
			<div id="groupDetailsCont">
				<div id="groupDetails"></div>
			</div>
		</div>
	</div>
</div>
