<? 
require_once("thinConnector.php");
?>
<form id="searchForm" onsubmit="showSearch();return false;">
	<input id="searchInput" type="text" name="input" value=""/>
	<input type="submit" value="Search" />
</form>
<?
$searchTerms = $_GET['searchTerms'];
if (!isset($_GET['searchTerms']) || $searchTerms == null || $searchTerms == "" || $searchTerms == "undefined") { //FIXME
	echo "<p>Add some search terms from above</p>";
} else {
	echo "<p><b>Ideas</b></p>";
	echo "<p>No ideas</p>";
	
	echo "<p><b>People</b></p>";
	echo "<p>No people</p>";
	 
	echo "<p><b>Groups</b></p>";
	echo "<p>No groups</p>";
}
?>