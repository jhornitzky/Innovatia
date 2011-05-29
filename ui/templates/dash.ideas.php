<?if ($ideas && dbNumRows($ideas) > 0 ) {
	while ($idea = dbFetchObject($ideas)) {?>
<div onclick="showIdeaDetails('<?= $idea->ideaId?>');"
	class="itemHolder clickable" style="height: 2.5em; overflow: hidden">
	<div class="lefter" style="padding: 0.1em;">
		<img
			src="retrieveImage.php?action=ideaImg&actionId=<?= $idea->ideaId ?>"
			style="width: 2.25em; height: 2.25em;" />
	</div>
	<div class="lefter">
	<?= $idea->title ?>
		<br /> <img
			src="retrieveImage.php?action=userImg&actionId=<?= $idea->userId ?>"
			style="width: 1em; height: 1em;" /> <span><?= getDisplayUsername($idea->userId);  ?>
		</span>
	</div>
</div>
	<?}
	if ($countIdeas > dbNumRows($ideas)) {?>
	<a class="loadMore" href="javascript:logAction()"
		onclick="loadResults(this, {action:'getDashIdeas', limit:'<?= ($limit + 20) ?>'})">Load
		more</a>
	<?}
} else {?>
	<p class="nohelp">Record, manage and explore ideas to help them take shape</p>
<?}?>