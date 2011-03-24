<?if ($items && dbNumRows($items) > 0 ) {
	while ($item = dbFetchObject($items)) {?>
<div onclick="showIdeaDetails('<?= $item->ideaId?>');"
	class="itemHolder clickable" style="height: 2.5em; overflow: hidden">
	<div class="lefter" style="padding: 0.1em;">
		<img
			src="retrieveImage.php?action=ideaImg&actionId=<?= $item->ideaId ?>"
			style="width: 2.25em; height: 2.25em;" />
	</div>
	<div class="lefter">
	<?= $item->title ?>
		<br /> <img
			src="retrieveImage.php?action=userImg&actionId=<?= $item->userId ?>"
			style="width: 1em; height: 1em;" /> <span><?= getDisplayUsername($item->userId); ?>
		</span>
	</div>
</div>
	<?}
	if ($countItems > dbNumRows($items)) {?>
<a class="loadMore" href="javascript:logAction()"
	onclick="loadResults(this, {action:'getDashCompare', limit:'<?= ($limit + 20) ?>'})">Load
	more</a>
	<?}
} else {?>
<p>No compares yet</p>
<?}?>