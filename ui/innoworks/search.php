<? 
require_once("thinConnector.php");
import("search.service");
?>
<form id="searchForm" onsubmit="showSearch();return false;" style="font-size:1.5em">
	<div style="border:1px solid #444444; position: relative; float:left;">
		<input id="searchInput" type="text" name="input" value="" style="width:20em; border:none"/>
		<input type="submit" value="Search" />
	</div>
</form>
<hr/>
<?
$searchTerms = $_GET['searchTerms'];
if (!isset($_GET['searchTerms']) || $searchTerms == null || $searchTerms == "" || $searchTerms == "undefined") { //FIXME
	echo "<p>Add some search terms from above</p>";
} else {
	$ideas = getSearchIdeas($searchTerms, $_SESSION['innoworks.ID']);
	$users = getSearchPeople($searchTerms, $_SESSION['innoworks.ID']);
	$groups = getSearchGroups($searchTerms, $_SESSION['innoworks.ID']);
	
	echo "<b>".(dbNumRows($ideas) + dbNumRows($users) + dbNumRows($groups) )."</b> result(s) for terms: " . $searchTerms;
	echo "<p><b>Ideas</b></p>";
	if ($ideas && dbNumRows($ideas) > 0){
		while ($idea = dbFetchObject($ideas)) {
			echo "<p>".$idea->title."</p>";
		}
	} else {
		echo "<p>No ideas</p>";
	}
	
	echo "<p><b>People</b></p>";
	if ($users && dbNumRows($users) > 0){
		while ($user = dbFetchObject($users)) {
			echo "<p>".$user->username."</p>";
		}
	} else {
		echo "<p>No people</p>";
	}
	 
	echo "<p><b>Groups</b></p>";
	if ($groups && dbNumRows($groups) > 0){
		while ($group = dbFetchObject($groups)) {
			echo "<p>".$group->title."</p>";
		}
	} else {
		echo "<p>No groups</p>";
	}
}
?>