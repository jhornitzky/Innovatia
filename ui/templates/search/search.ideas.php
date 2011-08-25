<h3 style="padding-top:1em;padding-bottom:0.5em;">
	<?=$countIdeas?> <span style="color:#AAA">ideas</span>
</h3>
<?if ($ideas && dbNumRows($ideas) > 0){
	while ($idea = dbFetchObject($ideas)) {
		renderTemplate('search.ideaItem', array('idea' => $idea));
	}
	if ($countIdeas > dbNumRows($ideas)) {
		renderTemplate('common.loadMore', array('action' => 'getSearchIdeas', 'limit' => ($limit + 20)));
	}
}?>