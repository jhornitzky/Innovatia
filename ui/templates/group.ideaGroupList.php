<div class="tiny">change what you are viewing...</div>
<div class="borderHolder" style="border-right:1px solid #AAA">
	<div class='itemHolder clickable private'
		onclick="showDefaultIdeas()">
		private <span>your ideas</span>
	</div>
	<div class='itemHolder clickable public'
		onclick="showPublicIdeas()">
		public <span>everyones ideas</span>
	</div>
	<div class='groupsHolder'>
		<div class='groupsActualHolder'>
		<? renderIdeaGroupItemsForUser($uid, $limit); ?>
		</div>
	</div>
</div>