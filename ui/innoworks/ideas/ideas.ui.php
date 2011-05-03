<?
require_once(dirname(__FILE__) . "/../pureConnector.php");
import("idea.service");

function renderIdeasDefault($user, $limit = 30) {
	$limitString = "LIMIT $limit";
	$countIdeas = countIdeas($user);
	$ideas = getIdeas($user, $limitString);
	if ($ideas && dbNumRows($ideas) > 0 ) {
		while ($idea = dbFetchObject($ideas)) {
			renderJustIdea($ideas,$idea, $_SESSION["innoworks.ID"]);
		}
		if ($countIdeas > dbNumRows($ideas)) {?>
			<a class="loadMore" href="javascript:logAction()" onclick="loadResults(this, {action:'getIdeas', limit:'<?= ($limit + 20) ?>'})">Load more</a>		
		<?}
	} else {
		echo "<p>No ideas yet</p>";
	}
}

function renderPublicIdeas($limit = 30) {
	$limitString = "LIMIT $limit";
	$countIdeas = countPublicIdeas();
	$ideas = getPublicIdeas($limit);
	if ($ideas && dbNumRows($ideas) > 0 ) {
		while ($idea = dbFetchObject($ideas)) {
			renderJustIdea($ideas,$idea, $_SESSION["innoworks.ID"]);
		}
		if ($countIdeas > dbNumRows($ideas)) {?>
			<a class="loadMore" href="javascript:logAction()" onclick="loadResults(this, {action:'getPublicIdeas', limit:'<?= ($limit + 20) ?>'})">Load more</a>		
		<?}
	} else {
		echo "<p>No ideas yet</p>";
	}
}

function renderIdeasForGroup($groupId) {
	if (!hasAccessToGroup($groupId, $_SESSION['innoworks.ID'])) 
		die("You have no access to this group and therefore cannot view these ideas.");
		
	import("group.service");
	$ideas = getIdeasForGroup($groupId);
	$countIdeas = countGetIdeasForGroup($groupId);
	if ($ideas && dbNumRows($ideas) > 0 ) {
		while ($idea = dbFetchObject($ideas)) {
			renderJustIdea($ideas,$idea, $_SESSION["innoworks.ID"]);
		}
		if ($countIdeas > dbNumRows($ideas)) {?>
			<a class="loadMore" href="javascript:logAction()" onclick="loadResults(this, {action:'getIdeasForGroup', limit:'<?= ($limit + 20) ?>'})">Load more</a>		
		<?}
	} else {
		echo "<p>No ideas yet</p>";
	}
}

function renderJustIdea($ideas, $idea, $user) {
	import("user.service");
	renderTemplate('idea', get_defined_vars());
}

function renderIdeaMission($ideaId) {	
	$canEdit = hasEditAccessToIdea($ideaId, $_SESSION['innoworks.ID']);
	$idea = dbFetchObject(getIdeaDetails($ideaId));
	renderTemplate('idea.mission', get_defined_vars());
}

function renderIdeaFeaturesForm($ideaId) {
	$rs = getIdeaDetails($ideaId);
	$idea = dbFetchObject($rs);
	$canEdit = hasEditAccessToIdea($ideaId, $_SESSION['innoworks.ID']);
	renderTemplate('idea.featuresForm', get_defined_vars());
}

function renderIdeaRolesForm($ideaId) {
	$rs = getIdeaDetails($ideaId);
	$idea = dbFetchObject($rs);
	$canEdit = hasEditAccessToIdea($ideaId, $_SESSION['innoworks.ID']);
	renderTemplate('idea.roles', get_defined_vars());
}

function renderIdeaFeatures($ideaId, $canEdit = null) {
	if (!isset($canEdit))
		$canEdit = hasEditAccessToIdea($ideaId, $_SESSION['innoworks.ID']);
	$features = getFeaturesForIdea($ideaId);
	if ($features && dbNumRows($features) > 0 ) {
		echo "<table id='featureTable_$ideaId' class='ideaFeatures'>";
		renderGenericHeaderWithRefData($features, array("featureId", "ideaId"), 'Features');
		while ($feature = dbFetchObject($features)) {
			renderFeature($features, $feature,$canEdit);
		}
		echo "</table>";
	} else {
		echo "<p>No features</p>";
	}
}

function renderFeature($features, $feature, $canEdit = false) {
	renderTemplate('idea.feature', get_defined_vars());
}

function renderIdeaRoles($ideaId, $canEdit = null) {
	if (!isset($canEdit))
		$canEdit = hasEditAccessToIdea($ideaId, $_SESSION['innoworks.ID']);
		
	$roles = getRolesForIdea($ideaId);
	if ($roles && dbNumRows($roles) > 0 ) {
		echo "<table class='ideaRoles'>";
		renderGenericHeaderWithRefData($roles, array("roleId", "ideaId"), 'Roles');	
		while ($role = dbFetchObject($roles)) {
			renderRole($roles, $role, $canEdit);
		}
		echo "</table>";
	} else {
		echo "<p>No roles</p>";
	}
}

function renderRole($roles, $role, $canEdit = false) {
	renderTemplate('idea.role', get_defined_vars());
}

function renderIdeaFeatureEvaluationsForIdea($id, $shouldEdit) {
	import("user.service");
	$rs = getIdeaDetails($id);
	$idea = dbFetchObject($rs);
	$featureEvaluationStack = getIdeaFeatureEvaluationsForIdea($id);
	
	if (!(isset($shouldEdit) && !$shouldEdit)) {?>
		<form id="addFeatureEvaluationContainer_<?= $idea->ideaId?>" class="addForm">
		<span> New feature evaluation </span> 
		<input type="text" name="title" /> 
		<input type="hidden" name="ideaId" value="<?= $idea->ideaId?>" /> 
		<input type="hidden" name="action" value="createFeatureEvaluation" /> 
		<input type="button" onclick="addFeatureEvaluation('addFeatureEvaluationContainer_<?= $idea->ideaId?>');" value=" + " />
		</form>
	<?}
	
	if ($featureEvaluationStack && dbNumRows($featureEvaluationStack) > 0 ) {
		while ($featureEvaluation = dbFetchObject($featureEvaluationStack)) {
			//calculate totals //FIXME should be done elsewhere
			$featureItemsTemp = getFeatureEvaluationForIdea($featureEvaluation->ideaFeatureEvaluationId);
			$total = 0;
			if ($featureItemsTemp && dbNumRows($featureItemsTemp) > 0 ) {
				$count = 0;
				$runningTotal = 0;
				while ($featureItemTemp = dbFetchArray($featureItemsTemp)) {
					foreach($featureItemTemp as $key => $attr) {
						if (!in_array($key, array('score','userId','ideaFeatureEvaluationId','featureId','featureEvaluationId')) && is_numeric($attr) && is_string($key)) {
							logInfo($key); 
							$count++;
							$runningTotal = $runningTotal + $attr;
						}
					}
				}	
				$total = round($runningTotal/$count);
			}
			
			//continue with display
			$canEdit = false;
			if (isset($shouldEdit) && !$shouldEdit)
				$canEdit = false;
			else if ($featureEvaluation->userId == $_SESSION['innoworks.ID'] || $_SESSION['innoworks.isAdmin'])
				$canEdit = true;?>
				<div id="featureEvaluationContainer_<?= $featureEvaluation->ideaFeatureEvaluationId ?>" class="featureEvaluation itemHolder">
				<table class="titleTT">
				<tr>
				<td style="width:2.5em;"><span class="evalTotal" style="font-size:3em; font-weight:bold"><?=$total?></span></td>
				<td style="width:13em;"><span class="title"><?=$featureEvaluation->title?></span><span class="timestamp">by <?= getDisplayUsername($featureEvaluation->userId) ?></span> 
				<br/>
				<? if ($canEdit) { ?>
				<input type="button" 
					onclick="doAction('action=deleteFeatureEvaluation&actionId=<?= $featureEvaluation->ideaFeatureEvaluationId ?>');getFeatureEvaluationsForIdea();"
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

function renderFeatureItem($featureItems, $featureItem, $canEdit) {
	renderTemplate('idea.featureItem', get_defined_vars());
}

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
	renderTemplate('idea.comments', get_defined_vars());
}

function renderAttachments($ideaId, $userId) {
	$attachs = getAttachmentsForIdea($ideaId);
	renderTemplate('idea.attachments', get_defined_vars());
}

function renderAttachmentsIframe($ideaId, $userId) {?>
	<iframe src="attachment.php?ideaId=<?= $ideaId?>" style="width:100%;height:100%"></iframe>
<?}

function renderIdeaName($ideaId) {
	$details = dbFetchObject(getIdeaDetails($ideaId));
	renderTemplate('idea.name', get_defined_vars());
}
?>