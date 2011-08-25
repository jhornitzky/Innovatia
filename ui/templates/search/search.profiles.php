<h3 style="padding-top:1em;padding-bottom:0.5em;">
	<?=$countUsers?> <span style="color:#AAA">profiles</span>
</h3>
<?if ($users && dbNumRows($users) > 0){ 
	while ($user = dbFetchObject($users)) {
		renderTemplate('search.profileItem', array('user' => $user));	
	}
	if ($countUsers > dbNumRows($users)) {
		renderTemplate('common.loadMore', array('action' => 'getSearchProfiles', 'limit' => ($limit + 20)));
	}
}?>