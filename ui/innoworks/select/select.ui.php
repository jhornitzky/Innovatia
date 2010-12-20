<?
require_once(dirname(__FILE__) . "/../thinConnector.php");
import("idea.service");

function renderSelectDefault($userId) {
	$ideas = getSelectedIdeas($userId);
	if ($ideas && dbNumRows($ideas) > 0 ) {
		while ($idea = dbFetchObject($ideas)) {
			renderSelectIdea($ideas,$idea, $userId);
		}
	} else {
		echo "<p>No selections yet</p>";
	}
}

function renderSelectPublic() {
	$ideas = getPublicSelectedIdeas();
	if ($ideas && dbNumRows($ideas) > 0 ) {
		while ($idea = dbFetchObject($ideas)) {
			renderSelectIdea($ideas,$idea, $userId);
		}
	} else {
		echo "<p>No selections yet</p>";
	}
}

function renderSelectForGroup($groupId, $userId) {
	$ideas = getSelectedIdeasForGroup($groupId, $userId);
	if ($ideas && dbNumRows($ideas) > 0 ) {
		while ($idea = dbFetchObject($ideas)) {
			renderSelectIdea($ideas,$idea, $userId);
		}
	} else {
		echo "<p>No selections yet</p>";
	}
}

function renderSelectIdea($ideas,$idea,$user) {
import("task.service");
global $serverRoot;
$tasks = getTasksForIdea($idea->ideaId);
$features = getFeaturesForIdea($idea->ideaId);
$roles = getRolesForIdea($idea->ideaId);
$comments = getCommentsForIdea($idea->ideaId);
$views = getViewsForIdea($idea->ideaId);
?>
<div id="selectideaform_<?= $idea->ideaId?>" class="selection idea hoverable" title="<?= $idea->title ?>">
<table>
<tr>
<td class="image">
<img src="retrieveImage.php?action=ideaImg&actionId=<?= $idea->ideaId?>" style="width:64px; height:64px"/><br/>
</td>
<td style="width:10em">
<span class="ideaoptions">
<?= $idea->username?>
<?if ($idea->userId == $user) { ?> 
<input type="button" value=" - " onclick="deleteSelectIdea(<?= $idea->selectionId?>)" title="Deselect this idea" /> 
<?}?>
</span><br/>
<span class="ideatitle"><a href="javascript:logAction()" onclick="showIdeaDetails('<?= $idea->ideaId?>');">
<?=trim($idea->title)?></a></span>
<br/>
</td>
<td style="vertical-align:middle;">
<b><?= dbNumRows($features); ?></b> Feature(s) &nbsp; 
<b><?= dbNumRows($roles); ?></b> Role(s) &nbsp; 
<b><?= dbNumRows($comments);?></b> Comment(s) &nbsp; 
<b><?= dbNumRows($views);?></b> View(s) 
</td>
</tr>
</table>
</div>
<?}

function renderAddSelectIdea($actionId, $user, $criteria) {
	$limit=20;?>
	<form id="popupAddSearch" onsubmit="findAddSelectIdeas();return false;">
		<input id="addSelectIdeaSearchTerms" type="text" name="criteria"/> 
		<input type="submit" value="Find Ideas"/>
	</form>
	<p>Select an idea to add to group</p>
	<div>
	<?renderAddIdeaSelectItems($criteria, $limit);?>
	</div>
<?}

function renderAddIdeaSelectItems($criteria, $limit) {
	import("search.service");
	$ideas = getSearchIdeasByUser($criteria, $_SESSION['innoworks.ID'],array(), "LIMIT $limit");
	$countIdeas = countGetSearchIdeasByUser($criteria, $_SESSION['innoworks.ID'],array(), "LIMIT $limit");
	if ($ideas && dbNumRows($ideas) > 0) {
		while ($idea = dbFetchObject($ideas)) {?>
			<div>
			<a href='javascript:logAction()' onclick='addSelectItem("<?$idea->ideaId?>")'>
			<?= $idea->title ?></a></div>
		<?}
		if ($countIdeas > dbNumRows($ideas)) {?>
			<a href="javascript:logAction()" onclick="loadResults(this,{action:'getAddIdeaSelectItems', limit: '<?= $limit + 20; ?>'})">Load more</a>
		<?}
	} else {?>
		<p>No ideas found</p>
	<?}
}

function renderAddSelectIdeaForGroup($groupId, $userId) {
	$limit=20;?>
	<!-- <form id="popupAddSearch" onsubmit="findAddSelectIdeas();return false;">
		<input id="addSelectIdeaSearchTerms" type="text" name="criteria"/> 
		<input type="submit" value="Find Ideas"/>
	</form> -->
	<p>Select an idea for implementation from the group</p>
	<div>
	<?renderAddIdeaSelectItems($groupId, $userId, $limit);?>
	</div>
<?}

function renderAddIdeaSelectItemsForGroup($groupId, $userId, $limit) {
	import("group.service");
	//$ideas = getSearchIdeasByUser($criteria, $_SESSION['innoworks.ID'],array(), "LIMIT $limit");
	//$countIdeas = countGetSearchIdeasByUser($criteria, $_SESSION['innoworks.ID'],array(), "LIMIT $limit");
	$ideas = getIdeasForGroup($groupId, $userId, "LIMIT $limit");
	$countIdeas = countGetIdeasForGroup($groupId, $userId);
	if ($ideas && dbNumRows($ideas) > 0) {
		while ($idea = dbFetchObject($ideas)) {?>
			<div>
			<a href='javascript:logAction()' onclick='addSelectItem("<?$idea->ideaId?>")'>
			<?= $idea->title ?></a></div>
		<?}
		if ($countIdeas > dbNumRows($ideas)) {?>
			<a href="javascript:logAction()" onclick="loadResults(this,{action:'getAddIdeaSelectItemsForGroup', limit: '<?= $limit + 20; ?>'})">Load more</a>
		<?}
	} else {?>
		<p>No ideas found</p>
	<?}
}

function renderIdeaSelect($ideaId,$userId) {
	import("idea.service");
	$item = getIdeaSelect($ideaId,$userId);
	if ($item && dbNumRows($item) > 0) {
		$item = dbFetchObject($item);?>
		<form id="ideaSelectDetails">
		Selection reason
		<textarea name="reason" dojoType="dijit.form.Textarea"><?= $item->reason ?></textarea>
		<input type="hidden" name="selectionId" value="<?= $item->selectionId ?>"/>
		<input type="hidden" name="action" value="updateSelection" />
		</form>
		<!-- <h3>Tasks</h3>
		<form id="addTaskForm" onsubmit="return false;"><input type="submit" value=" + "/></form>
		 -->
		<p>Go to <a href='javascript:showSelect(); dijit.byId("ideasPopup").hide()'>Select</a></p>
	<?} else {?>
		<p>No selection data for this idea</p>
		<p>Select this idea <a onclick="addSelectItem('<?= $ideaId ?>');loadPopupShow()" href="javascript:logAction()">now</a> </p> 
		Go to <a href='javascript:showSelect(); dijit.byId(\"ideasPopup\").hide()'>Select</a>
	<?}
}
?>