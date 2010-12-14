<?
require_once("thinConnector.php");
import("idea.service");

function renderIdeasDefault() {
	$ideas = getIdeas($_SESSION["innoworks.ID"]) or die("Error retrieving ideas. Report to IT Support.");
	if ($ideas && dbNumRows($ideas) > 0 ) {
		while ($idea = dbFetchObject($ideas)) {
			renderJustIdea($ideas,$idea, $_SESSION["innoworks.ID"]);
		}
	} else {
		echo "<p>No ideas yet</p>";
	}
}

function renderPublicIdeas() {
	$ideas = getPublicIdeas() or die("Error retrieving ideas. Report to IT Support.");
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
		while ($idea = dbFetchObject($ideas)) {
			renderJustIdea($ideas,$idea, $_SESSION["innoworks.ID"]);
		}
	} else {
		echo "<p>No ideas yet</p>";
	}
}

function renderJustIdea($ideas, $idea, $user) {
	global $serverRoot;
	?>
<div id="ideaform_<?= $idea->ideaId?>" class="idea hoverable" title="<?= $idea->title ?>">
<table>
<tr>
<td class="image">
<img src="retrieveImage.php?action=ideaImg&actionId=<?= $idea->ideaId?>" style="width:64px; height:64px"/><br/>
</td>
<td>
<span class="ideaoptions">
<?= $idea->username?>
<?if ($idea->userId == $user) { ?> 
<input type="button" value=" - " onclick="deleteIdea(<?= $idea->ideaId?>)" title="Delete this idea" /> 
<?}?>
</span><br/>
<span class="ideatitle"><a href="javascript:logAction()" onclick="showIdeaDetails('<?= $idea->ideaId?>');"><?=trim($idea->title)?></a></span><br/>
</td>
</tr>
</table>
</div>
<?}

function renderIdeaMission($ideaId) {	
	$canEdit = hasEditAccessToIdea($ideaId, $_SESSION['innoworks.ID']);
	$idea = dbFetchObject(getIdeaDetails($ideaId));?>
<div class="formBody">
<div class="ideaDetails subform">
<form id="ideadetails_form_<?= $idea->ideaId?>">
<? 
if ($canEdit)
	renderGenericUpdateForm(null ,$idea, array("ideaId", "title","userId", "createdTime", "username", "isPublic", "score", "lastUpdateTime")); 
else 
	renderGenericInfoForm(null ,$idea, array("ideaId", "title","userId", "createdTime", "username", "isPublic", "score", "lastUpdateTime"));
?>
<input type="hidden" name="ideaId" value="<?= $idea->ideaId?>" /> <input
	type="hidden" name="action" value="updateIdeaDetails" /> </form>
</div>
</div>
<?}

function renderIdeaFeaturesForm($ideaId) {
		$rs = getIdeaDetails($ideaId);
		$idea = dbFetchObject($rs);
		$canEdit = hasEditAccessToIdea($ideaId, $_SESSION['innoworks.ID']);
	?>
<div class="ideaFeatures subform">
<? if ($canEdit) { ?>
<form id="addfeature_form_<?= $idea->ideaId?>" class="addForm"><span>
New Feature </span> <input type="text" name="feature" /> <input
	type="hidden" name="ideaId" value="<?= $idea->ideaId?>" /> <input
	type="hidden" name="action" value="addFeature" /> <input type="button"
	onclick="addFeature('addfeature_form_<?= $idea->ideaId?>', 'ideafeatures_<?= $idea->ideaId?>','<?= $idea->ideaId ?>');"
	value=" + " /></form>
<?} ?>
<div id="ideafeatures_<?= $idea->ideaId?>">
<? renderIdeaFeatures($idea->ideaId); ?>
</div>
</div>
<?}

function renderIdeaRolesForm($ideaId) {
		$rs = getIdeaDetails($ideaId);
		$idea = dbFetchObject($rs);
		$canEdit = hasEditAccessToIdea($ideaId, $_SESSION['innoworks.ID']);?>
<div class="ideaRoles subform">
<? if ($canEdit) { ?>
<form id="addrole_form_<?= $idea->ideaId?>" class="addForm"><span> New
Role </span> <input type="text" name="role" /> <input type="hidden"
	name="ideaId" value="<?= $idea->ideaId?>" /> <input type="hidden"
	name="action" value="addRole" /> <input type="button"
	onclick="addRole('addrole_form_<?= $idea->ideaId?>', 'idearoles_<?= $idea->ideaId?>','<?= $idea->ideaId ?>');"
	value=" + " /></form>
<? } ?>
<div id="idearoles_<?= $idea->ideaId?>">
<? renderIdeaRoles($idea->ideaId); ?>
</div>
</div>
<?}

function renderIdeaFeatures($ideaId) {
	$canEdit = hasEditAccessToIdea($ideaId, $_SESSION['innoworks.ID']);
	$features = getFeaturesForIdea($ideaId);
	if ($features && dbNumRows($features) > 0 ) {
		echo "<table id='featureTable_$ideaId'>";
		renderGenericHeader($features, array("featureId", "ideaId"));
		while ($feature = dbFetchObject($features)) {
			renderFeature($features, $feature,$canEdit);
		}
		echo "</table>";
	} else {
		echo "<p>No features</p>";
	}
}

function renderFeature($features, $feature, $canEdit) {?>
<tr id="featureform_<?= $feature->featureId ?>">
	<?
	if ($canEdit) {
		renderGenericUpdateRow($features, $feature, array("featureId", "ideaId"));?>
	<td>
	<input type="hidden"
		name="featureId"
		value="<?= $feature->featureId ?>" />
	<input type="button"
		onclick="deleteFeature('deleteFeature','<?= $feature->featureId ?>', 'ideafeatures_<?= $feature->ideaId?>','<?= $feature->ideaId ?>');"
		value=" - " /></td>
	<?} else { 
		renderGenericInfoRow($features, $feature, array("featureId", "ideaId"));
	}?>
</tr>
<?}

function renderIdeaRoles($ideaId) {
	$canEdit = hasEditAccessToIdea($ideaId, $_SESSION['innoworks.ID']);
	$roles = getRolesForIdea($ideaId);
	if ($roles && dbNumRows($roles) > 0 ) {
		echo "<table>";
		renderGenericHeader($roles, array("roleId", "ideaId"));
		while ($role = dbFetchObject($roles)) {
			renderRole($roles, $role, $canEdit);
		}
		echo "</table>";
	} else {
		echo "<p>No roles</p>";
	}
}

function renderRole($roles, $role, $canEdit) {?>
<tr id="roleform_<?= $role->roleId ?>">
	<?
	if ($canEdit) {
	renderGenericUpdateRow($roles, $role, array("roleId", "ideaId"));?>
	<td><input type="hidden" name="roleId" value="<?= $role->roleId ?>" />
	<input type="button"
		onclick="deleteRole('deleteRole','<?= $role->roleId ?>', 'idearoles_<?= $role->ideaId?>','<?= $role->ideaId ?>');"
		value=" - " /></td>
	<?} else { 
		renderGenericInfoRow($features, $feature, array("featureId", "ideaId"));
	}?>

</tr>
<?}

function renderIdeaGroupsForUser($uid) {
	import("group.service");
	$groups = getAllGroupsForUser($uid);?>
		<div class="ideaGroupsSel" dojoType="dijit.form.DropDownButton">
		    <span> Private </span>
			<div dojoType="dijit.Menu">
			<div dojoType="dijit.MenuItem" onClick="showDefaultIdeas()">Private</div>
			<div dojoType="dijit.MenuItem" onClick="showPublicIdeas()">Public</div>
			<div dojoType="dijit.MenuSeparator"></div>
			<?if ($groups && dbNumRows($groups) > 0 ) {
				while ($group = dbFetchObject($groups)) {?>
					<div dojoType="dijit.MenuItem" onClick="showIdeasForGroup(<?=$group->groupId?>, '<?=$group->title?>')"><?=$group->title?></div>
				<?}
			}?>	
			<div dojoType="dijit.MenuSeparator"></div>
			<div iconClass="dijitEditorIcon dijitEditorIconCopy" dojoType="dijit.MenuItem" onClick="showGroups()">Manage Groups</div>
			</div>
		</div>
<?}

function renderIdeaGroupsListForUser($uid) {
	global $serverRoot;
	import("group.service");
	$groups = getAllGroupsForUser($uid);?>
	<div class='itemHolder clickable private' onclick="showDefaultIdeas()">Private</div>
	<div class='itemHolder clickable public' onclick="showPublicIdeas()">Public</div>
	<?if ($groups && dbNumRows($groups) > 0 ) {
		while ($group = dbFetchObject($groups)) {
			echo "<div class='itemHolder clickable groupSel_$group->groupId' onclick=\"showIdeasForGroup($group->groupId)\">";
			echo '<img src="retrieveImage.php?action=groupImg&actionId='.$group->groupId.'" style="width:3em; height:3em"/><br/>';
			echo $group->title . "</div>";
		}
	} 
}

function renderIdeaFeatureEvaluationsForIdea($id) {
	import("user.service");
	$rs = getIdeaDetails($id);
	$idea = dbFetchObject($rs);
	?>
<form id="addFeatureEvaluationContainer_<?= $idea->ideaId?>" class="addForm">
	<span> New feature evaluation </span> 
	<input type="text" name="title" /> 
	<input type="hidden" name="ideaId" value="<?= $idea->ideaId?>" /> 
	<input type="hidden" name="action" value="createFeatureEvaluation" /> 
	<input type="button" onclick="addFeatureEvaluation('addFeatureEvaluationContainer_<?= $idea->ideaId?>');" value=" + " />
	</form>
	<?
	$featureEvaluationStack = getIdeaFeatureEvaluationsForIdea($id);
	if ($featureEvaluationStack && dbNumRows($featureEvaluationStack) > 0 ) {
		while ($featureEvaluation = dbFetchObject($featureEvaluationStack)) {
		$canEdit = false;
		if ($featureEvaluation->userId == $_SESSION['innoworks.ID'] || $_SESSION['innoworks.isAdmin'])
			$canEdit = true;?>
<div id="featureEvaluationContainer_<?= $featureEvaluation->ideaFeatureEvaluationId ?>" class="featureEvaluation itemHolder">
<table class="titleTT">
<tr>
<td style="width:2.5em;"><span class="evalTotal" style="font-size:3em; font-weight:bold">0</span></td>
<td style="width:13em;"><span class="title"><?=$featureEvaluation->title?></span><span class="timestamp">by <?= getUserInfo($featureEvaluation->userId)->username ?></span> 
<br/>
<? if ($canEdit) { ?>
<input type="button" 
	onclick="genericDelete('deleteFeatureEvaluation','<?= $featureEvaluation->ideaFeatureEvaluationId ?>');getFeatureEvaluationsForIdea();"
	title="Delete feature evaluation" value=" - " />
<?}
$featureList = getFeaturesForIdea($id);
	if ($featureList && dbNumRows($featureList) > 0 ) {
		if ($canEdit) { ?>
		<div dojoType="dijit.form.DropDownButton">
			<span> Add feature </span>
			<div dojoType="dijit.Menu">
			<?while ($feature = dbFetchObject($featureList)) {?>
				<div dojoType="dijit.MenuItem" 
				onClick="addFeatureItem(<?= $feature->featureId ?>,<?= $featureEvaluation->ideaFeatureEvaluationId ?>)">
					<?= $feature->feature ?>
				</div>
			<?}?>
			</div>
		</div>
		<?}
	}?>
</td>
<td>
<?if ($canEdit) {?>
<textarea dojoType="dijit.form.Textarea" onblur="updateFeatureEvalSummary(this, '<?= $featureEvaluation->ideaFeatureEvaluationId?>')"><?= $featureEvaluation->summary ?></textarea>
<? } else { ?>
<div><?= $featureEvaluation->summary ?></div>
<? } ?>
</td>
</tr>
</table>
<?renderFeatureEvaluationForIdea($featureEvaluation->ideaId, $featureEvaluation->ideaFeatureEvaluationId, $canEdit, $featureList);?>
</div>
<?}
	} else {
		echo "<p>No feature evaluations</p>";
	}
}

function renderFeatureEvaluationForIdea($id, $evalId, $canEdit, $featureList) {
	if ($featureList && dbNumRows($featureList) > 0 ) {
		renderFeatureEvaluationTable($evalId, $canEdit);
	} else {
		echo "<p>No features to rate</p>";
	}
}

function renderFeatureEvaluationTable($id, $canEdit) {
	$featureItems = getFeatureEvaluationForIdea($id);
	if ($featureItems && dbNumRows($featureItems) > 0){
		echo "<table id='featureEvaluation_$id' class='featureEvaluationBit'>";
		renderGenericHeaderWithRefData($featureItems, array("featureId","featureEvaluationId","groupId", "userId","ideaFeatureEvaluationId","score"), "FeatureEvaluation", "renderFeatureEvaluationTableCallback");
		while ($featureItem = dbFetchObject($featureItems)) {
			renderFeatureItem($featureItems, $featureItem, $canEdit);
		}
		echo "</table><br/>";
	}
}

function renderFeatureEvaluationTableCallback($key) {
	if($key == "feature") {?>
		<th class="headCol">Feature</th>
		<?return true;
	}
	return false;
}

function renderFeatureItem($featureItems, $featureItem, $canEdit) {?>
<tr id="featureitemform_<?= $featureItem->featureEvaluationId ?>">
<? if ($canEdit) {
	renderGenericUpdateRowWithRefData($featureItems, $featureItem, array("featureId","featureEvaluationId","groupId", "userId","ideaFeatureEvaluationId", "score"), "FeatureEvaluation", "renderFeatureEvaluationItemCallback");?>
<?} else { 
	renderGenericInfoRow($featureItems, $featureItem, array("featureId","featureEvaluationId","groupId", "userId","ideaFeatureEvaluationId", "score"), "FeatureEvaluation", null);?>
<?}?>
<td class="totalCol">
<span class="itemTotal">0</span> 
<? if ($canEdit) {?><input type="button" onclick="deleteFeatureItem('<?= $featureItem->featureEvaluationId ?>')" value=" - " /> <? } ?>
</td>
</tr>
<?}

function renderFeatureEvaluationItemCallback($key, $value, $row) {
	if ($key == "feature") {?>
		<td class="headCol">
			<?= $value ?>
			<input type="hidden" name="featureEvaluationId" value="<?= $row->featureEvaluationId ?>" />
		</td>
		<?return true;
	}
	return false;
}

function renderCommentsForIdea($id, $uId) {
	$userService = new AutoObject("user.service");
	$comments = getCommentsForIdea($id);
	if ($comments && dbNumRows($comments) > 0 ) {
		while ($comment = dbFetchObject($comments)) {
			echo "<div class='itemHolder'>";
			echo "<span class='title'>".$userService->getUserInfo($comment->userId)->username."</span><span class='timestamp'>$comment->timestamp</span>";
			if ($comment->userId == $uId || $_SESSION['innoworks.isAdmin'])
				echo "<input type='button' onclick='deleteComment(". $comment->commentId .")' value=' - '>";
			echo "<br/>";
			echo $comment->text;
			echo "</div>";
		}
	} else {
		echo "<p>None</p>";
	}
}

function renderAttachments($ideaId, $userId) {?>
	<form method="post" enctype="multipart/form-data" onsubmit="addIdeaAttachment(this);return false;">
	<input type="hidden" name="MAX_FILE_SIZE" value="2000000"> 
	<input name="userfile" type="file" id="userfile"> 
	<input type="hidden" name="action" value="addAttachment"/>
	<input type="submit" value=" + " title="Add attachment" />
	</form>
	<?
	$attachs = getAttachmentsForIdea($ideaId);
	if ($attachs && dbNumRows($attachs)) {
		while ($attach = dbFetchObject) {
			echo $attach->title;
		}
	} else {
		echo "<p>No attachments</p>";
	}
}

function renderAttachmentsIframe($ideaId, $userId) {?>
	<iframe src="attachment.php?ideaId=<?= $ideaId?>" style="width:100%;height:100%"></iframe>
<?}

function renderIdeaName($ideaId) {
	$details = dbFetchObject(getIdeaDetails($ideaId));
	echo "<form id='ideaNameDetails'><input type='hidden' name='action' value='updateIdeaDetails'><input type='hidden' name='ideaId' value='$ideaId'><input name='title' style='font-size:1.2em' value='".$details->title."' onblur='updateIdeaDetails(\"form#ideaNameDetails\")'/> by ".$details->username." | last updated ".$details->lastUpdateTime."</form>";
}
?>