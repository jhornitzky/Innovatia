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

$ideas = getSearchIdeas($searchTerms, $_SESSION['innoworks.ID']);
$users = getSearchPeople($searchTerms, $_SESSION['innoworks.ID']);
$groups = getSearchGroups($searchTerms, $_SESSION['innoworks.ID']);

if (!hasSearchTerms()) {
	echo "<span>Find innovative ideas, people and groups</span>";
} else {
	echo "<span><b>".(dbNumRows($ideas) + dbNumRows($users) + dbNumRows($groups) )."</b> result(s) for search terms</span>";
}?>
<form id="searchForm" onsubmit="showIdeas();return false;" style="font-size: 1.5em; clear: both;">
<div style="border: 1px solid #444444; position: relative; float: left;">
<table cellpadding="0" cellspacing="0">
<tr><td><input id="searchInput" type="text"  name="input"
	value="<?= $_GET['searchTerms'] ?>"; 
	placeholder=" . . ."
	style="font-size:1.2em; width:15.5em; border: none" /> </td><td><img src="<?= $serverRoot."ui/style/glass.png"?>" onclick="showSearch()" style="width:30px; height:24px; margin:2px;cursor:pointer"/>
	</td></tr>
</table>
<input id="searchBtn" type="submit" value="" style="display:none;"/>
</div>
</form>
<br />

<div id="searchui" class="threeColumnContainer" style="clear:both">
<div class="threecol">
<? echo "<p>".dbNumRows($ideas)." <b>idea(s)</b></p>";
	if ($ideas && dbNumRows($ideas) > 0){
		while ($idea = dbFetchObject($ideas)) {?>
		<p><a href="javascript:logAction()" onclick="showIdeaDetails(<?= $idea->ideaId?>); showIdeas()"> 
		<?= $idea->title?>
		</a></p>
	<?}
} else {
	echo "<p>No ideas</p>";
}?>
</div>

<div class="threecol"><? echo "<p>".dbNumRows($users)." <b>profile(s)</b></p>";
if ($users && dbNumRows($users) > 0){
	while ($user = dbFetchObject($users)) { ?>
<p>
<a href="javascript:showProfileSummary('<?=$user->userId?>')"><?=$user->username?></a>
</p>
	<?}
} else {
	echo "<p>No people</p>";
} ?></div>

<div class="threecol"><? echo "<p>".dbNumRows($groups)." <b>group(s)</b></p>";
if ($groups && dbNumRows($groups) > 0){
	while ($group = dbFetchObject($groups)) {?>
<p><a href="javascript:logAction()" onclick="currentGroupId=<?= $group->groupId; ?>; showGroups()"><?= $group->title; ?></a></p>
	<?}
} else {
	echo "<p>No groups</p>";
}
?>
</div>

</div>