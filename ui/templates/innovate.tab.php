<div style="width: 100%; clear:both;">
	<div class="fixed-left">
		<h1><span style="color:#7FBF4D;">add</span> idea</h1>
		<form class="addForm" onsubmit="addIdea(this);return false;">
			<span>Title</span>
			<input name="title" type="text" class="dijitTextBox" /> 
			<span>Description</span>
			<textarea name="serviceDescription" class="dijitTextArea" style="width:100%;"></textarea>
			<input type="button"
				value="+ add" title="Add idea" onclick="addIdea(this); refreshVisibleTab();" /> <input
				style="display: none" type="submit" /> 
			<input type="hidden" name="action" value="addIdea" />
		</form>
	</div>
	<div class="fixed-right">
		<div id="dashui" class="threeColumnContainer">
			<div class="threecol col1" style=" width: 32%; margin-right: 1.5%">
				<div class="widget ui-corner-all">
					<div class="dashResults">
					<? renderDashIdeas($userid, $limit)?>
					</div>
				</div>
			</div>
			<div class="threecol col2"
				style="width: 32%; margin-right: 1.5%">
				<div class="widget ui-corner-all">
					<div class="dashResults">
					<? renderDashCompare($userid, $limit);?>
					</div>
				</div>
			</div>
			<div class="threecol col3" style="width: 32%; margin-right: 0">
				<div class="widget ui-corner-all">
					<div class="dashResults">
					<? renderDashSelect($userid, $limit);?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>