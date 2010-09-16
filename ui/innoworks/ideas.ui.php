<?
require_once("thinConnector.php");
import("idea.service");

function renderDefault() {
	$ideas = getIdeas($_SESSION["innoworks.ID"]) or die("Error retrieving ideas. Report to IT Support.");
	if ($ideas && dbNumRows($ideas) > 0 ) {
		while ($idea = dbFetchObject($ideas)) {
			renderJustIdea($ideas,$idea, $_SESSION["innoworks.ID"]);
		}
	} else {
		echo "<p>No ideas yet</p>";
	}
}

function renderIdeasForGroup($groupId) {
	import("group.service");
	$ideas = getIdeasForGroup($groupId) or die("Error retrieving ideas. Report to IT Support.");
	if ($ideas && dbNumRows($ideas) > 0 ) {
		//echo "<h3>Ideas for group</h3>";
		while ($idea = dbFetchObject($ideas)) {
			renderJustIdea($ideas,$idea, $_SESSION["innoworks.ID"]);
		}
	} else {
		echo "<p>No ideas yet</p>";
	}
}

function renderJustIdea($ideas, $idea, $user) {?>
<div id="ideaform_<?= $idea->ideaId?>" class="idea ui-corner-all" onmouseover="showIdeaOptions(this)" onmouseout="hideIdeaOptions(this)">
<div class="formHead" ><!--  <input name="title" type="text" onchange="updateValue()" value="<?=$idea->title?>">-->
<span class="ideatitle"><?=$idea->title?></span> 

<span class="ideaoptions">
<a href="javascript:showIdeaDetails('<?= $idea->ideaId?>');">Details</a>
<?if ($idea->userId == $user) { ?>
	<input type="button" value=" - " onclick="deleteIdea(<?= $idea->ideaId?>)" title="Delete this idea" />
<?}?>
</span>
</div>
</div>
<?}

function renderIdea($ideas, $idea, $user) { ?>
<div id="ideaform_<?= $idea->ideaId?>" class="idea ui-corner-all" onmouseover="showIdeaOptions(this)" onmouseout="hideIdeaOptions(this)">

<div class="formHead" ><!--  <input name="title" type="text" onchange="updateValue()" value="<?=$idea->title?>">-->
<span class="ideatitle"><?=$idea->title?></span> 

<span class="ideaoptions">
<a href="javascript:showDetails('ideadetails_form_<?= $idea->ideaId?>')">Mission</a>
<a href="javascript:showDetails('ideafeatures_form_<?= $idea->ideaId?>')">Features</a>
<a href="javascript:showDetails('idearoles_form_<?= $idea->ideaId?>')">Roles</a>
<a href="javascript:showIdeaReviews('<?= $idea->ideaId?>');">Review</a>
<?if ($idea->userId == $user) { ?>
	<input type="button" value=" - " onclick="deleteIdea(<?= $idea->ideaId?>)" title="Delete this idea" />
<?}?>
</span>
</div>

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
<form id="addfeature_form_<?= $idea->ideaId?>" class="addForm"><span> New
Feature | </span> <input type="text" name="feature"/>
<input type="hidden" name="ideaId" value="<?= $idea->ideaId?>" /> <input
	type="hidden" name="action" value="addFeature" /> <input type="button"
	onclick="genericAdd('addfeature_form_<?= $idea->ideaId?>');getFeatures('ideafeatures_<?= $idea->ideaId?>','<?= $idea->ideaId ?>');" value=" + " /></form>
<div id="ideafeatures_<?= $idea->ideaId?>">
<? renderIdeaFeatures($idea->ideaId); ?></div>
</div>
</div>

<div class="ideaRoles subform">
<div id="idearoles_form_<?= $idea->ideaId?>" style="display: none;">
<form id="addrole_form_<?= $idea->ideaId?>" class="addForm"><span> New Role |
</span> <input type="text" name="role"/>
<input type="hidden" name="ideaId" value="<?= $idea->ideaId?>" /> <input
	type="hidden" name="action" value="addRole" /> <input type="button"
	onclick="genericAdd('addrole_form_<?= $idea->ideaId?>');getRoles('idearoles_<?= $idea->ideaId?>','<?= $idea->ideaId ?>');" value=" + " /></form>
	<div id="idearoles_<?= $idea->ideaId?>">
		<? renderIdeaRoles($idea->ideaId); ?>
	</div>
</div>
</div>
</div>

</div>
<?
}

function renderIdeaMission($ideaId) { 
$rs = getIdeaDetails($ideaId);
$idea = dbFetchObject($rs);
?>
<div class="formBody">
<div class="ideaDetails subform">
<form id="ideadetails_form_<?= $idea->ideaId?>"">
<? renderGenericUpdateForm(null ,$idea, array("ideaId", "title","userId", "createdTime")); ?>
<input type="hidden" name="ideaId" value="<?= $idea->ideaId?>" /> <input
	type="hidden" name="action" value="updateIdeaDetails" /> <input
	type="button"
	onclick="updateIdeaDetails('#ideadetails_form_<?= $idea->ideaId?>')"
	value="Update" /></form>
</div>
</div>
<?}

function renderIdeaFeaturesForm($ideaId) {
$rs = getIdeaDetails($ideaId);
$idea = dbFetchObject($rs);
?>
<div class="ideaFeatures subform">
<form id="addfeature_form_<?= $idea->ideaId?>" class="addForm"><span> New
Feature | </span> <input type="text" name="feature"/>
<input type="hidden" name="ideaId" value="<?= $idea->ideaId?>" /> <input
	type="hidden" name="action" value="addFeature" /> <input type="button"
	onclick="genericAdd('addfeature_form_<?= $idea->ideaId?>');getFeatures('ideafeatures_<?= $idea->ideaId?>','<?= $idea->ideaId ?>');" value=" + " /></form>
<div id="ideafeatures_<?= $idea->ideaId?>">
<? renderIdeaFeatures($idea->ideaId); ?></div>
</div>
<?
}

function renderIdeaRolesForm($ideaId) {
$rs = getIdeaDetails($ideaId);
$idea = dbFetchObject($rs);
?>
<div class="ideaRoles subform">
<form id="addrole_form_<?= $idea->ideaId?>" class="addForm"><span> New Role |
</span> <input type="text" name="role"/>
<input type="hidden" name="ideaId" value="<?= $idea->ideaId?>" /> <input
	type="hidden" name="action" value="addRole" /> <input type="button"
	onclick="genericAdd('addrole_form_<?= $idea->ideaId?>');getRoles('idearoles_<?= $idea->ideaId?>','<?= $idea->ideaId ?>');" value=" + " /></form>
	<div id="idearoles_<?= $idea->ideaId?>">
		<? renderIdeaRoles($idea->ideaId); ?>
	</div>
</div>
</div>
<?}

function renderIdeaFeatures($ideaId) { 
	$features = getFeaturesForIdea($ideaId);
	if ($features && dbNumRows($features) > 0 ) {
		echo "<table id='featureTable_$ideaId'>";
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
<tr id="featureform_<?= $feature->featureId ?>">
	<?renderGenericUpdateRow($features, $feature, array("featureId", "ideaId"));?>
	<td>
	<input type="hidden" name="featureId" value="<?= $feature->featureId?>" /> 
	<input type="button" onclick="updateFeature('<?= $feature->featureId ?>','featureform_<?= $feature->featureId ?>','<?= $feature->ideaId ?>')"  value=" U "/>
	<input type="button"
		onclick="genericDelete('deleteFeature','<?= $feature->featureId ?>');getFeatures('ideafeatures_<?= $feature->ideaId?>','<?= $feature->ideaId ?>');"
		value=" - " /></td>
</tr>
<?}

function renderIdeaRoles($ideaId) { 
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
<tr id="roleform_<?= $role->roleId ?>'">
	<?renderGenericUpdateRow($roles, $role, array("roleId", "ideaId"));?>
	<td>
		<input type="hidden" name="roleId" value="<?= $role->roleId ?>" /> 
		<input type="button" onclick="updateRole('<?= $role->roleId ?>','roleform_<?= $role->roleId ?>','<?= $role->roleId ?>')"  value=" U "/>
		<input type="button" onclick="genericDelete('deleteRole','<?= $role->roleId ?>');getRoles('idearoles_<?= $role->ideaId?>','<?= $role->ideaId ?>');"
		value=" - " />
	</td>
</tr>
<?}

function renderIdeaGroupsForUser($uid) {
	import("group.service");
	$groups = getAllGroupsForUser($uid);
	if ($groups && dbNumRows($groups) > 0 ) {
		while ($group = dbFetchObject($groups)) {
			echo "<a groupId='$group->groupId' href=\"javascript:showIdeasForGroup($group->groupId)\">" . $group->title . "</a>";
		}
	} else {
		echo "None";
	}
}

function renderFeatureEvaluationForIdea($id) {
	$featureList = getFeaturesForIdea($id);
	
	if ($featureList && dbNumRows($featureList) > 0 ) {?>
		<div dojoType="dijit.form.DropDownButton">
			<span>
        		Add feature
    		</span>
			<div dojoType="dijit.Menu">
            	<?while ($feature = dbFetchObject($featureList)) {?>
            	<div dojoType="dijit.MenuItem" onClick="addFeatureItem(<?=$feature->featureId?>)"><?=$feature->feature?></div>
				<?}?>
        	</div>
		</div>
		<? 
		renderFeatureEvaluationTable($id);
	} else {
		echo "<p>No features to rate</p>";
	}
}

function renderFeatureEvaluationTable($id) {
	$featureItems = getFeatureEvaluationForIdea($id);
	if ($featureItems && dbNumRows($featureItems) > 0){
		echo "<table id='featureEvaluation_$id'>";
		renderGenericHeader($featureItems, array("featureId","featureEvaluationId","groupId", "userId"));
		while ($featureItem = dbFetchObject($featureItems)) {
			renderFeatureItem($featureItems, $featureItem);
		}
		echo "</table>";?>
		<script type="text/javascript">
			initFormSelectTotals('table#featureEvaluation_<?= $id?>');
		</script>
		<?
	}
} 

function renderFeatureItem($featureItems, $featureItem) {?> 
	<tr id="featureitemform_<?= $featureItem->featureEvaluationId ?>">
		<?renderGenericUpdateRowWithRefData($featureItems, $featureItem, array("featureId","featureEvaluationId","groupId", "userId"), "FeatureEvaluation");?>
		<td>
			Score: <span class="itemTotal">0 </span>
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
		echo "<p>None</p>";
	}
}

function renderShare($ideaId) {
	echo "<p>Sharing to come</p>";
}
?>

