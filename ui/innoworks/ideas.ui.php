<?
require_once("thinConnector.php");
import("idea.service");

function renderDefault() {
	$ideas = getIdeas($_SESSION["innoworks.ID"]) or die("Error retrieving ideas. Report to IT Support.");
	if ($ideas && dbNumRows($ideas) > 0 ) {
		while ($idea = dbFetchObject($ideas)) {
			renderIdea($ideas,$idea);
		}
	} else {
		echo "<p>No ideas yet</p>";
	}
}

function renderIdeasForGroup($groupId) {
	import("group.service");
	$ideas = getIdeasForGroup($groupId) or die("Error retrieving ideas. Report to IT Support.");
	if ($ideas && dbNumRows($ideas) > 0 ) {
		while ($idea = dbFetchObject($ideas)) {
			renderIdea($ideas,$idea);
		}
	} else {
		echo "<p>No ideas yet</p>";
	}
}

function renderIdea($ideas, $idea) { ?>
<div id="ideaform_<?= $idea->ideaId?>" class="idea ui-corner-all">

<div class="formHead"><!--  <input name="title" type="text" onchange="updateValue()" value="<?=$idea->title?>">-->
<?=$idea->title?> <a
	href="javascript:showDetails('ideadetails_form_<?= $idea->ideaId?>')">Mission</a>
<a
	href="javascript:showDetails('ideafeatures_form_<?= $idea->ideaId?>')">Features</a>
<a href="javascript:showDetails('idearoles_form_<?= $idea->ideaId?>')">Roles</a>
<a href="javascript:showIdeaReviews('<?= $idea->ideaId?>');">Review</a>
<input type="button" value=" - "
	onclick="deleteIdea(<?= $idea->ideaId?>)" /></div>
<div class="formBody">
<div class="ideaDetails subform">
<form id="ideadetails_form_<?= $idea->ideaId?>" style="display: none;">
<? renderGenericUpdateForm($ideas ,$idea, array("ideaId", "title","userId", "createdTime")); ?>
<input type="hidden" name="ideaId" value="<?= $idea->ideaId?>" /> <input
	type="hidden" name="action" value="updateIdeaDetails" /> <input
	type="button"
	onclick="updateIdeaDetails('#ideadetails_form_<?= $idea->ideaId?>')"
	value="Update" /></form>
</div>

<div class="ideaFeatures subform">
<div id="ideafeatures_form_<?= $idea->ideaId?>" style="display: none;">
<? renderIdeaFeatures($idea->ideaId); ?></div>
</div>

<div class="ideaRoles subform">
<div id="idearoles_form_<?= $idea->ideaId?>" style="display: none;"><? renderIdeaRoles($idea->ideaId); ?>
</div>
</div>
</div>

</div>
<?
}

function renderIdeaFeatures($ideaId) { ?>
<form id="addfeature_form_<?= $ideaId?>" class="addForm"><span> New
Feature | </span> <? renderGenericAddForm("Features", array("featureId", "ideaId")); ?>
<input type="hidden" name="ideaId" value="<?= $ideaId?>" /> <input
	type="hidden" name="action" value="addFeature" /> <input type="button"
	onclick="genericAdd('addfeature_form_<?= $ideaId?>')" value=" + " /></form>
<?
$features = getFeaturesForIdea($ideaId);
if ($features && dbNumRows($features) > 0 ) {
	echo "<table>";
	renderGenericHeader($features, array("featureId", "ideaId"));
	while ($feature = dbFetchObject($features)) {
		renderFeature($features, $feature);
	}
	echo "</table>";
} else {
	echo "<p>No features</p>";
}
}

function renderFeature($features, $feature) {?>
<tr>
<?renderGenericUpdateRow($features, $feature, array("featureId", "ideaId"));?>
	<td><input type="button"
		onclick="genericDelete('deleteFeature','<?= $feature->featureId ?>')"
		value=" - " /></td>
</tr>
<?}

function renderIdeaRoles($ideaId) { ?>
<form id="addrole_form_<?= $ideaId?>" class="addForm"><span> New Role |
</span> <? renderGenericAddForm("Roles", array("roleId", "ideaId"));	?>
<input type="hidden" name="ideaId" value="<?= $ideaId?>" /> <input
	type="hidden" name="action" value="addRole" /> <input type="button"
	onclick="genericAdd('addrole_form_<?= $ideaId?>')" value=" + " /></form>

<?
$roles = getRolesForIdea($ideaId);
if ($roles && dbNumRows($roles) > 0 ) {
	echo "<table>";
	renderGenericHeader($roles, array("roleId", "ideaId"));
	while ($role = dbFetchObject($roles)) {
		renderRole($roles, $role);
	}
	echo "</table>";
} else {
	echo "<p>No roles</p>";
}
}

function renderRole($roles, $role) {?>
<tr>
<?renderGenericUpdateRow($roles, $role, array("roleId", "ideaId"));?>
	<td><input type="button"
		onclick="genericDelete('deleteRole','<?= $role->roleId ?>')"
		value=" - " /></td>
</tr>
<?}

function renderIdeaGroupsForUser($uid) {
	import("group.service");
	$groups = getAllGroupsForUser($uid);
	if ($groups && dbNumRows($groups) > 0 ) {
		while ($group = dbFetchObject($groups)) {
			echo "<a href='javascript:showIdeasForGroup($group->groupId)'>" . $group->title . "</a>";
		}
	} else {
		echo "None";
	}
}

function renderFeatureEvaluationForIdea($id) {
	$featureList = getFeaturesForIdea($id);
	
	if ($featureList && dbNumRows($featureList) > 0 ) {?>
		<script type="text/javascript">
		var menu = new dijit.Menu({
			style: "display: none;"
		});
		<?while ($feature = dbFetchObject($featureList)) {?>
			menu.addChild(new dijit.MenuItem({
				label: "<?=  $feature->feature ?>",
				onClick: function() {
					addFeatureItem(<?=  $feature->featureId ?>);
				}
				})
			);
		<?}?>
		var button = new dijit.form.DropDownButton({
			label: "Add",
			name: "programmatic2",
			dropDown: menu,
			id: "progButton"
		});
		
		dojo.byId("addFeatureEval").appendChild(button.domNode);
		</script>
	<? } else {
		echo "No features to rate";
	}
	
	renderFeatureEvaluationTable($id);
}

function renderFeatureEvaluationTable($id) {
	$featureItems = getFeatureEvaluationForIdea($id);
	echo "<table>";
	renderGenericHeader($featureItems, array("featureId","featureEvaluationId","groupId", "userId"));
	while ($featureItem = dbFetchObject($featureItems)) {
		renderFeatureItem($featureItems, $featureItem);
	}
	echo "</table>";
} 

function renderFeatureItem($featureItems, $featureItem) {?> 
	<tr id="featureitemform_<?= $featureItem->featureEvaluationId ?>">
		<?renderGenericUpdateRow($featureItems, $featureItem, array("featureId","featureEvaluationId","groupId", "userId"));?>
		<td>
			<input type="hidden" name="featureEvaluationId" value="<?= $featureItem->featureEvaluationId ?>"/>
			<input type="button" onclick="updateFeatureItem('<?= $featureItem->featureEvaluationId ?>','featureitemform_<?= $featureItem->featureEvaluationId ?>')"  value=" U "/>
			<input type="button" onclick="deleteFeatureItem('<?= $featureItem->featureEvaluationId ?>')"  value=" - "/>
		</td>
	</tr>
<?}

function renderCommentsForIdea($id) {
	$comments = getCommentsForIdea($id);
	if ($comments && dbNumRows($comments) > 0 ) {
		while ($comment = dbFetchObject($comments)) {
			echo $comment->text. "<input type='button' onclick='deleteComment(". $comment->commentId .")' value=' - '><br/>";
		}
	} else {
		echo "None";
	}
}
?>

