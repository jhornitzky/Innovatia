<? 
require_once("thinConnector.php");
import("search.service");

$searchTerms = $_GET['searchTerms'];

function hasSearchTerms() {
	global $searchTerms;
	if (!isset($_GET['searchTerms']) || $searchTerms == null || $searchTerms == "" || $searchTerms == "undefined") 
		return false;
	return true;
}

?>
<form id="searchForm" onsubmit="showSearch();return false;" style="font-size:1.5em">
	<div style="border:1px solid #444444; position: relative; float:left;">
		<input id="searchInput" type="text" name="input" value="<?if (hasSearchTerms()) echo $_GET['searchTerms']; ?>" style="width:20em; border:none"/>
		<input type="submit" value="Search" />
	</div>
</form>
<hr/>
<?
if (!hasSearchTerms()) {
	echo "<p>Enter some search terms from above</p>";
} else {
	$ideas = getSearchIdeas($searchTerms, $_SESSION['innoworks.ID']);
	$users = getSearchPeople($searchTerms, $_SESSION['innoworks.ID']);
	$groups = getSearchGroups($searchTerms, $_SESSION['innoworks.ID']);
	
	echo "<b>".(dbNumRows($ideas) + dbNumRows($users) + dbNumRows($groups) )."</b> result(s) for terms: " . $searchTerms;
	echo "<p>".dbNumRows($ideas)." <b>idea(s)</b></p>";
	if ($ideas && dbNumRows($ideas) > 0){
		while ($idea = dbFetchObject($ideas)) {?>
			<p> <a href="javascript:logAction()" onclick="showIdeaDetails(<?= $idea->ideaId?>); showIdeas()"> <?= $idea->title?> </a> </p> 
		<?}
	} else {
		echo "<p>No ideas</p>";
	}
	
	echo "<p>".dbNumRows($users)." <b>profile(s)</b></p>";
	if ($users && dbNumRows($users) > 0){
		while ($user = dbFetchObject($users)) { ?>
			<p><a href="mailto:<?= $user->email?>"><?= $user->username?></a></p>
		<?}
	} else {
		echo "<p>No people</p>";
	}
	 
	echo "<p>".dbNumRows($groups)." <b>group(s)</b></p>";
	if ($groups && dbNumRows($groups) > 0){
		while ($group = dbFetchObject($groups)) {?>
			<p><a href="javascript:logAction()" onclick="currentGroupId=<?= $group->groupId; ?>; showGroups()"><?= $group->title; ?></a></p>
		<?}
	} else {
		echo "<p>No groups</p>";
	}
}
?>