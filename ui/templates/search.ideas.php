<p>
	<b><?=$countIdeas?> </b> idea(s)
</p>
<?if ($ideas && dbNumRows($ideas) > 0){
	while ($idea = dbFetchObject($ideas)) {?>
<div class='itemHolder clickable'
	onclick="showIdeaSummary(<?= $idea->ideaId?>);" style="height: 2.5em;">
	<div class="lefter" style="padding: 0.1em;">
		<img
			src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=ideaImg&actionId=<?= $idea->ideaId?>"
			style="width: 2.25em; height: 2.25em;" />
	</div>
	<div class="lefter">
	<?= $idea->title ?>
		<br /> <img
			src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=userImg&actionId=<?= $idea->userId ?>"
			style="width: 1em; height: 1em;" /> <span style="color: #666"><?= getDisplayUsername($idea->userId)?>
		</span>
	</div>
</div>
	<?}
	if ($countIdeas > dbNumRows($ideas)) {?>
<a href="javascript:logAction()"
	onclick="loadResults(this, {action:'getSearchIdeas', limit:'<?= ($limit + 20) ?>'})">Load
	more</a>
	<?}
} else {?>
<p>No ideas</p>
<?}