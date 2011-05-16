<form id='ideaNameDetails'>
	<input type='hidden' name='action' value='updateIdeaDetails'>
	<input type='hidden' name='ideaId' value='<?=$ideaId?>'>
	<input name='title' class='dijitTextBox' style='font-size: 1.2em' value='<?=$details->title?>'
		onblur='updateIdeaDetails("form#ideaNameDetails", "refreshVisibleTab();")' /><br/>
	<span class="smallInfo">	
	<b>by</b> <?=getDisplayUsername($details)?> <b>last updated</b> <?=$details->lastUpdateTime?>
	</span>
</form>