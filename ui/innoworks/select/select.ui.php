<?
require_once(dirname(__FILE__) . "/../thinConnector.php");
import("idea.service");

function renderSelectDefault($userId) {
	$ideas = getSelectedIdeas($userId) or die("Error retrieving ideas. Report to IT Support.");
	if ($ideas && dbNumRows($ideas) > 0 ) {
		while ($idea = dbFetchObject($ideas)) {
			renderSelectIdea($ideas,$idea, $userId);
		}
	} else {
		echo "<p>No selections yet</p>";
	}
}

function renderSelectPublic() {
	$ideas = getPublicSelectedIdeas() or die("Error retrieving ideas. Report to IT Support.");
	if ($ideas && dbNumRows($ideas) > 0 ) {
		while ($idea = dbFetchObject($ideas)) {
			renderSelectIdea($ideas,$idea, $userId);
		}
	} else {
		echo "<p>No selections yet</p>";
	}
}

function renderSelectForGroup($groupId, $userId) {
	$ideas = getSelectedIdeasForGroup($groupId, $userId) or die("Error retrieving ideas. Report to IT Support.");
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

function renderAddSelectIdea() {
	echo "Select an idea to add to risk evaluation";
	$ideas = getIdeas($_SESSION['innoworks.ID']); 
	if ($ideas && dbNumRows($ideas) > 0) { 
		echo "<ul>";
		while ($idea = dbFetchObject($ideas)) {
			echo  "<li><a href='javascript:addSelectItem(\"$idea->ideaId\")'>".$idea->title. "</a></li>";
		}
		echo "</ul>";
	}
}

function renderAddSelectIdeaForGroup($groupId, $userId) {
	import("group.service");
	echo "Select an idea to add to risk evaluation";
	$ideas = getIdeasForGroup($groupId, $userId); 
	if ($ideas && dbNumRows($ideas) > 0) { 
		echo "<ul>";
		while ($idea = dbFetchObject($ideas)) {
			echo  "<li><a href='javascript:addSelectItem(\"$idea->ideaId\")'>".$idea->title. "</a></li>";
		}
		echo "</ul>";
	}
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