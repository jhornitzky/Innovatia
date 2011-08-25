<?if ($items && dbNumRows($items) > 0 ) {
	while ($item = dbFetchObject($items)) {?>
<div onclick="showIdeaDetails('<?= $item->ideaId?>');"
	class="itemHolder clickable" style="height: 2.5em; overflow: hidden">
	<div class="lefter" style="padding: 0.1em;">
		<img
			src="engine.ajax.php?action=ideaImg&actionId=<?= $item->ideaId ?>"
			style="width: 2.25em; height: 2.25em;" />
	</div>
	<div class="lefter">
		<?= $item->title ?><br/> 
		<img src="engine.ajax.php?action=userImg&actionId=<?= $item->userId ?>"
			style="width: 1em; height: 1em;"/> 
		<span><?= getDisplayUsername($item->userId); ?></span>
	</div>
</div>
	<?}
	if ($countItems > dbNumRows($items)) {
		renderTemplate('common.loadMore', array('action' => 'getDashCompare', 'limit' => ($limit + 20)));	
	}
} else {?>
	<span class="nohelp">Contrast and compare your existing ideas</span>
<?}?>