<? 
require_once("thinConnector.php");
import("search.service");

$searchTerms = $_GET['q'];

function hasSearchTerms() {
	global $searchTerms;
	if (!isset($_GET['q']) || $searchTerms == null || $searchTerms == "" || $searchTerms == "undefined") 
		return false;
	return true;
}

?>
<form id="searchForm" onsubmit="showSearch();return false;" style="font-size:1.5em">
	<div style="border:1px solid #444444; position: relative; float:left;">
		<input id="searchInput" type="text" name="input" value="<?if (hasSearchTerms()) echo $_GET['searchTerms']; ?>" style="width:14em; font-size:0.9em; border:none"/>
		<input type="submit" value="Search" />
	</div>
</form>
<br/>
<?
if (!hasSearchTerms()) {
	echo "<p>Enter some search terms above to find ideas, people and groups</p>";
} else {
	$ideas = getSearchIdeas($searchTerms, $_SESSION['innoworks.ID']);
	$users = getSearchPeople($searchTerms, $_SESSION['innoworks.ID']);
	$groups = getSearchGroups($searchTerms, $_SESSION['innoworks.ID']);
	
	echo "<b>".(dbNumRows($ideas) + dbNumRows($users) + dbNumRows($groups) )."</b> result(s) for terms: " . $searchTerms;
	echo "<p>".dbNumRows($ideas)." <b>idea(s)</b></p>";
	if ($ideas && dbNumRows($ideas) > 0){
		while ($idea = dbFetchObject($ideas)) {
			echo "<p>".$idea->title."</a></p>";
		}
	} else {
		echo "<p>No ideas</p>";
	}
	
	echo "<p>".dbNumRows($users)." <b>profile(s)</b></p>";
	if ($users && dbNumRows($users) > 0){
		while ($user = dbFetchObject($users)) {
			echo "<p>".$user->username."</p>";
		}
	} else {
		echo "<p>No people</p>";
	}
	 
	echo "<p>".dbNumRows($groups)." <b>group(s)</b></p>";
	if ($groups && dbNumRows($groups) > 0){
		while ($group = dbFetchObject($groups)) {
			echo "<p>".$group->title."</p>";
		}
	} else {
		echo "<p>No groups</p>";
	}
}
?>