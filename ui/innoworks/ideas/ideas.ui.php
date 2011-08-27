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
		if ($countIdeas > dbNumRows($ideas)) {
			renderTemplate('common.loadMore', array('action' => 'getIdeas', 'limit' => $limit + 20));
		}
	} else {
		renderTemplate('no.ideas');
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
		if ($countIdeas > dbNumRows($ideas)) {
			renderTemplate('common.loadMore', array('action' => 'getPublicIdeas', 'limit' => $limit + 20));
		}
	} else {
		renderTemplate('no.ideas');
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
		if ($countIdeas > dbNumRows($ideas)) {
			renderTemplate('common.loadMore', array('action' => 'getIdeasForGroup', 'limit' => $limit + 20));
		}
	} else {
		renderTemplate('no.ideas');
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
			renderTemplate('idea.feature', get_defined_vars());
		}
		echo "</table>";
	} else {
		renderTemplate('no.features');
	}
}

function renderIdeaRoles($ideaId, $canEdit = null) {
	if (!isset($canEdit))
		$canEdit = hasEditAccessToIdea($ideaId, $_SESSION['innoworks.ID']);
		
	$roles = getRolesForIdea($ideaId);
	if ($roles && dbNumRows($roles) > 0 ) {
		echo "<table class='ideaRoles'>";
		renderGenericHeaderWithRefData($roles, array("roleId", "ideaId"), 'Roles');	
		while ($role = dbFetchObject($roles)) {
			renderTemplate('idea.role', get_defined_vars());
		}
		echo "</table>";
	} else {
		renderTemplate('no.roles');
	}
}

function renderIdeaFeatureEvaluationsForIdea($id, $shouldEdit) {
	import("user.service");
	$rs = getIdeaDetails($id);
	$idea = dbFetchObject($rs);
	$featureEvaluationStack = getIdeaFeatureEvaluationsForIdea($id);
	
	if (!(isset($shouldEdit) && !$shouldEdit)) {
		renderTemplate('idea.createFeatureEvaluation', get_defined_vars());
	}
	
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
				$canEdit = true;
				
			//render
			renderTemplate('idea.featureEvaluationContainer', get_defined_vars());
		}
	} else {
		renderTemplate('no.featureEval');
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

function renderAttachmentsIframe($ideaId, $userId) {
	renderTemplate('attach.iframe', array('ideaId' => $ideaId));
}

function renderIdeaName($ideaId) {
	$details = dbFetchObject(getIdeaDetails($ideaId));
	renderTemplate('idea.name', get_defined_vars());
}

function renderIdeaBook() {
	import('user.service');
	import('search.service');
	logVerbose('renderIdeaBook');
	$ideas = getSearchIdeas('', $_SESSION['innoworks.ID'], '');
	while($idea = dbFetchObject($ideas)) {
		renderTemplate('search.ideaItem', array('idea' => $idea));
	}
}

function renderWiki($userId) {
	echo '<h1 style="text-align:center;">coming soon</h1>';
}
?>