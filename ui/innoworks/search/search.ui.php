<?
require_once(dirname(__FILE__) . "/../pureConnector.php");
import("search.service");

function renderSearchDefault($userid, $opts) {
	global $uiRoot;
	$limit = 20;

	$searchTerms = '';
	if (isset($_GET['searchTerms']))
		$searchTerms = htmlspecialchars($_GET['searchTerms']);
	
	if (!isset($_GET['searchTerms']) || empty($searchTerms))
		echo "<span>Find innovative ideas, people and groups</span>";
	else 
		echo "<span>Found result(s) for search terms</span>";
	?>
<form id="searchForm" onsubmit="showSearch(); return false;" style="font-size: 1.5em; clear: both;">
<div style="border: 1px solid #444444; position: relative; float: left; clear:right">
<table cellpadding="0" cellspacing="0">
<tr>
<td>
<input id="searchTerms" type="text"  name="searchTerms"
	value="<?= $searchTerms ?>"; placeholder=" . . . " style="font-size:1.2em; width:15.5em; border: none" /></td>
<td><img src="<?= $uiRoot."style/glass.png"?>" onclick="showSearch()" style="width:30px; height:24px; margin:2px;cursor:pointer"/>
</td>
</tr>
</table>
<input id="searchBtn" type="submit" value="Search" style="display:none;"/>
</div>
<div id="searchHider" style="clear:left;padding:0.25em;border:1px solid #DDD; background-color:#EEE; float:left;">
<a href="javascript:logAction()" onclick="toggleSearchOptions()" style="font-size:0.6em; margin:0;">More &gt;&gt;</a>
</div>
<div id="searchOptions" style="display:none; clear:left; font-size:0.7em; padding:0.25em; border:1px solid #DDD; background-color:#EEE; float:left">
<a href="javascript:logAction()" onclick="toggleSearchOptions()" style="margin:0; margin-bottom:0.5em;">&lt;&lt;Less</a><br/>
Date from <input type="text" name="dateFrom" dojoType="dijit.form.DateTextBox" value="<?= $dateFrom ?>"/>
Date to <input type="text" name="dateTo" dojoType="dijit.form.DateTextBox" value="<?= $dateTo ?>"/>
</div>
</form>

<div id="searchui" class="threeColumnContainer" style="clear:both">
<div class="threecol">
<div class="searchResults">
<?renderSearchIdeas($userId, $limit);?>
</div>
</div>

<div class="threecol">
<div class="searchResults">
<?renderSearchProfiles($userId, $limit);?>
</div>
</div>

<div class="threecol">
<div class="searchResults">
<?renderSearchGroups($userId, $limit);?>
</div>
</div>
</div>
<?}

function renderSearchIdeas($userId, $limit) {
	global $serverUrl, $serverRoot, $uiRoot;
	
	$searchTerms = '';
	if (isset($_GET['searchTerms']))
		$searchTerms = htmlspecialchars($_GET['searchTerms']);
	
	$userId = '';
	if (isset($_SESSION['innoworks.ID']))
		$userId = $_SESSION['innoworks.ID'];

	$filters = array();
	$dateFrom = '';
	$dateTo = '';
	if (isset($_GET['dateFrom']) && !empty($_GET['dateFrom'])){
		$filters['dateFrom'] = $_GET['dateFrom'];
		$dateFrom = htmlspecialchars($_GET['dateFrom']);
	}
	if (isset($_GET['dateTo']) && !empty($_GET['dateTo'])) {
		$filters['dateTo'] = $_GET['dateTo'];
		$dateTo = htmlspecialchars($_GET['dateFrom']);
	}
	
	$ideas = getSearchIdeas($searchTerms, $userId, $filters, "LIMIT $limit");
	$countIdeas = countGetSearchIdeas($searchTerms, $userId, $filters);?>
	<p><?=$countIdeas?> <b>idea(s)</b></p>
	<?if ($ideas && dbNumRows($ideas) > 0){
		while ($idea = dbFetchObject($ideas)) {?>
			<div class='itemHolder clickable' onclick="showIdeaSummary(<?= $idea->ideaId?>);"> 
			<img src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=ideaImg&actionId=<?= $idea->ideaId?>" style="width:1em; height:1em;"/>
			<?= $idea->title?>
			</div>
		<?}
		if ($countIdeas > dbNumRows($ideas)) {?>
			<a href="javascript:logAction()" onclick="loadResults(this, {action:'getSearchIdeas', limit:'<?= ($limit + 20) ?>'})">Load more</a>		
		<?}
	} else {?>
		<p>No ideas</p>";
	<?}	
}

function renderSearchProfiles($userId, $limit) {
	global $serverUrl, $serverRoot, $uiRoot;
	
	$searchTerms = '';
	if (isset($_GET['searchTerms']))
		$searchTerms = htmlspecialchars($_GET['searchTerms']);
	
	$userId = '';
	if (isset($_SESSION['innoworks.ID']))
		$userId = $_SESSION['innoworks.ID'];

	$filters = array();
	$dateFrom = '';
	$dateTo = '';
	if (isset($_GET['dateFrom']) && !empty($_GET['dateFrom'])){
		$filters['dateFrom'] = $_GET['dateFrom'];
		$dateFrom = htmlspecialchars($_GET['dateFrom']);
	}
	if (isset($_GET['dateTo']) && !empty($_GET['dateTo'])) {
		$filters['dateTo'] = $_GET['dateTo'];
		$dateTo = htmlspecialchars($_GET['dateFrom']);
	}
	
	$users = getSearchPeople($searchTerms, $userId, $filters, "LIMIT $limit");
	$countUsers = countGetSearchPeople($searchTerms, $userId, $filters);
	echo "<p>".$countUsers." <b>profile(s)</b></p>";
	if ($users && dbNumRows($users) > 0){
		while ($user = dbFetchObject($users)) { ?>
			<div class='itemHolder clickable' onclick="showProfileSummary('<?=$user->userId?>')">
				<img src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=userImg&actionId=<?= $user->userId?>" style="width:1em; height:1em;"/>
				<?=$user->username?>
			</div>
		<?}
		if ($countUsers > dbNumRows($users)) {?>
			<a href="javascript:logAction()" onclick="loadResults(this, {action:'getSearchProfiles', limit:'<?= ($limit + 20) ?>'})">Load more</a>		
		<?}
	} else {
		echo "<p>No people</p>";
	} 
}

function renderSearchGroups($userId, $limit) {
	global $serverUrl, $serverRoot, $uiRoot;
	
	$searchTerms = '';
	if (isset($_GET['searchTerms']))
		$searchTerms = htmlspecialchars($_GET['searchTerms']);
	
	$userId = '';
	if (isset($_SESSION['innoworks.ID']))
		$userId = $_SESSION['innoworks.ID'];

	$filters = array();
	$dateFrom = '';
	$dateTo = '';
	if (isset($_GET['dateFrom']) && !empty($_GET['dateFrom'])){
		$filters['dateFrom'] = $_GET['dateFrom'];
		$dateFrom = htmlspecialchars($_GET['dateFrom']);
	}
	if (isset($_GET['dateTo']) && !empty($_GET['dateTo'])) {
		$filters['dateTo'] = $_GET['dateTo'];
		$dateTo = htmlspecialchars($_GET['dateFrom']);
	}
	
	$groups = getSearchGroups($searchTerms, $userId, $filters, "LIMIT $limit");
	$countGroups = countGetSearchGroups($searchTerms, $userId, $filters);
	echo "<p>".$countGroups." <b>group(s)</b></p>";
	if ($groups && dbNumRows($groups) > 0){
		while ($group = dbFetchObject($groups)) {?>
		<div class='itemHolder clickable' onclick="showGroupSummary(<?= $group->groupId; ?>);">
		<img src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=groupImg&actionId=<?= $group->groupId?>" style="width:1em; height:1em;"/>
			<?= $group->title; ?>
		</div>
		<?}
		if ($countGroups > dbNumRows($groups)) {?>
			<a href="javascript:logAction()" onclick="loadResults(this, {action:'getSearchGroups', limit:'<?= ($limit + 20) ?>'})">Load more</a>		
		<?}
	} else {
		echo "<p>No groups</p>";
	}
}
?>