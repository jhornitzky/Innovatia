<div class="borderHolder" style="border-right:1px solid #CCC; margin-bottom:1.5em;">
	<div class="tiny">change what you are viewing...</div>
	<div class='itemHolder clickable private clearfix' onclick="showDefaultIdeas()">
		<div class="lefter">
			private <span>your ideas</span>
		</div>
		<div class="righter righterImage">
			<img src="<?=$uiRoot?>style/user.png" style="width: 1em; height: 1em" />
		</div>
	</div>
	<div class='itemHolder clickable public clearfix' onclick="showPublicIdeas()">
		<div class="lefter">
			public <span>everyones ideas</span>
		</div>
		<div class="righter righterImage">
			<img src="<?=$uiRoot?>style/public.png" style="width: 1em; height: 1em" />
		</div>
	</div>
	<div class='groupsHolder' style="margin-bottom:1em;">
		<div class='groupsActualHolder'>
		<? renderIdeaGroupItemsForUser($uid, $limit); ?>
		</div>
	</div>
	<div class='tiny'>need more ideas?</div>
	<? renderTemplate('openInnovation'); ?>
</div>