<div class="borderHolder" style="border-right:1px solid #CCC; margin-bottom:1.5em;">
	<div class="tiny">change what you are viewing...</div>
	<div class='itemHolder clickable private'
		onclick="showDefaultIdeas()">
		private <span>your ideas</span>
	</div>
	<div class='itemHolder clickable public'
		onclick="showPublicIdeas()">
		public <span>everyones ideas</span>
	</div>
	<div class='groupsHolder' style="margin-bottom:1em;">
		<div class='groupsActualHolder'>
		<? renderIdeaGroupItemsForUser($uid, $limit); ?>
		</div>
	</div>
	<div class='tiny'>need more ideas?</div>
	<? renderTemplate('openInnovation'); ?>
</div>