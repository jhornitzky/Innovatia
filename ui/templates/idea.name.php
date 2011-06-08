<form id='ideaNameDetails'>
	<input type='hidden' name='action' value='updateIdeaDetails'>
	<input type='hidden' name='ideaId' value='<?=$ideaId?>'>
	<input name='title' class='dijitTextBox' style='font-size: 1.4em' value='<?=$details->title?>'
		onblur='updateIdeaDetails("form#ideaNameDetails", "refreshVisibleTab();")' /><br/>
	<span class="smallInfo">	
	<span><b>by</b><? renderTemplate('ideator', array('userId' => $details->userId)) ?></span> <span><b>last updated</b> @ <?= prettyDate($details->lastUpdateTime)?></span>
	</span>
</form>