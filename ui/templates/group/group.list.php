<div class="addForm" style="margin-bottom:0.5em;">
	<input type="button" value="create new group" style="width:100%;" onclick="showCreateNewGroup(this);"/>
</div>
<div class="treeMenu" style="padding: 0; margin-bottom:1em;">
	<div class='tiny'>groups that you created...</div>
	<div style="margin-bottom: 1.5em;">
	<? renderGroupsForCreatorUser($user, $limit); ?>
	</div>
	<div class='tiny'>groups that you are in...</div>
	<div style="margin-bottom: 1.5em;">
	<? renderPartOfGroupsForUser($user, $limit); ?>
	</div>
	<div class='tiny'>more groups to join...</div>
	<div style="margin-bottom: 1.5em;">
	<? renderOtherGroupsForUser($user, $limit); ?>
	</div>
	<div class='tiny'>want to join more groups?</div>
	<? renderTemplate('openInnovation'); ?>
</div>
