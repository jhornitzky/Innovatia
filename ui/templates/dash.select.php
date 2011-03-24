<?php
if ($selections && dbNumRows($selections) > 0 ) {
	while ($selection = dbFetchObject($selections)) {?>
<div onclick="showIdeaDetails('<?= $selection->ideaId?>');"
	class="itemHolder clickable" style="height: 2.5em; overflow: hidden">
	<div class="lefter" style="padding: 0.1em;">
		<img
			src="retrieveImage.php?action=ideaImg&actionId=<?= $selection->ideaId ?>"
			style="width: 2.25em; height: 2.25em;" />
	</div>
	<div class="lefter">
	<?= $selection->title ?>
		<br /> <img
			src="retrieveImage.php?action=userImg&actionId=<?= $selection->userId ?>"
			style="width: 1em; height: 1em;" /> <span><?= getDisplayUsername($selection->userId);  ?>
		</span>
	</div>
</div>
	<?}
	if ($countSelections > dbNumRows($selections)) {?>
<a class="loadMore" href="javascript:logAction()"
	onclick="loadResults(this, {action:'getDashSelect', limit:'<?= ($limit + 20) ?>'})">Load
	more</a>
	<?}
} else {?>
<p>No selections yet</p>
<?}?>