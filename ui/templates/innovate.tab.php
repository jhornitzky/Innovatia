<div style="width: 100%; clear:both;">
	<div class="fixed-left">
		<form class="addForm" onsubmit="return false;">
			<input type="button" value="add new idea" title="Add idea" style="width:100%;" onclick="showCreateIdea(this)" /> 
		</form>
		<div style="padding-left:0.5em;">
			<div class="tiny">need help ideating?</div>
			<? renderTemplate('openInnovation'); ?>
		</div>
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
			<div class="threecol col2" style="width: 32%; margin-right: 1.5%">
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