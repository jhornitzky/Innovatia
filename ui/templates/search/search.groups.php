<h3 style="padding-top:1em;padding-bottom:0.5em;">
	<?=$countGroups?> <span style="color:#AAA">groups</span>
</h3>
<?if ($groups && dbNumRows($groups) > 0){
	while ($group = dbFetchObject($groups)) {
		renderTemplate('search.groupItem', array('group' => $group));
	}
	if ($countGroups > dbNumRows($groups)) {
		renderTemplate('common.loadMore', array('action' => 'getSearchGroups', 'limit' => ($limit + 20)));
	}
}?>