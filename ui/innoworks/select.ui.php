<?
require_once("thinConnector.php");
import("idea.service");

function renderSelectDefault($userId) {
	//require_once("ideas.ui.php");
	$ideas = getSelectedIdeas($userId) or die("Error retrieving ideas. Report to IT Support.");
	if ($ideas && dbNumRows($ideas) > 0 ) {
		while ($idea = dbFetchObject($ideas)) {
			renderSelectIdea($ideas,$idea, $userId);
		}?>
		<!-- <script type="text/javascript">
		dojo.parser.instantiate(dojo.query('#ideasList *'));
		</script> -->
	<?} else {
		echo "<p>No selections yet</p>";
	}
}

function renderSelectIdea($ideas,$idea,$user) {?>
<div id="selectideaform_<?= $idea->ideaId?>" class="idea ui-corner-all" onmouseover="showIdeaOptions(this)" onmouseout="hideIdeaOptions(this)">

<div class="formHead" ><!--  <input name="title" type="text" onchange="updateValue()" value="<?=$idea->title?>">-->
<span class="ideatitle"><?=$idea->title?></span> 
<span class="ideaoptions">
<a href="javascript:showIdeaReviews('<?= $idea->ideaId?>');">Review</a>
<?if ($idea->userId == $user) { ?>
	<input type="button" value=" - " onclick="deleteSelectIdea(<?= $idea->selectionId?>)" title="Delete this idea" />
<?}?>
</span>
</div>
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
?>