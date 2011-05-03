<form id='ideaNameDetails'>
	<input type='hidden' name='action' value='updateIdeaDetails'>
	<input type='hidden' name='ideaId' value='<?=$ideaId?>'>
	<input name='title' style='font-size: 1.2em' value='<?=$details->title?>'
		onblur='updateIdeaDetails("form#ideaNameDetails", "refreshVisibleTab();")' />
	by <?=getDisplayUsername($details)?> | last updated <?=$details->lastUpdateTime?>
</form>