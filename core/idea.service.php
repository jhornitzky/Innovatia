<?
/**
 * Functions for retrieving and adding users to the database
 */
import("innoworks.connector");

function getAllIdeas() {
	return dbQuery("SELECT *FROM Ideas ORDER BY createdTime DESC LIMIT 10000");
}

function getPublicIdeas() {
	return dbQuery("SELECT Ideas.*, Users.username FROM Ideas, Users WHERE Ideas.isPublic = '1' AND Ideas.userId = Users.userId ORDER BY createdTime");
}

function countPublicIdeas() {
	$count = dbFetchArray(dbQuery("SELECT COUNT(*) FROM Ideas WHERE Ideas.isPublic = '1'"));
	return $count[0];
}

function getIdeas($userid, $limit) {
	return dbQuery("SELECT Ideas.*, Users.username FROM Ideas, Users WHERE Ideas.userId = '".$userid."' AND Users.userId = Ideas.userId ORDER BY createdTime DESC $limit");
}

function countIdeas($userid) {
	$array = dbFetchArray(dbQuery("SELECT COUNT(Ideas.title) FROM Ideas, Users WHERE Ideas.userId = '".$userid."' AND Users.userId = Ideas.userId"));
	return $array[0];
}

function getProfileIdeas($userid, $limit) {
	if (isset($_SESSION['innoworks.ID']) && !empty($_SESSION['innoworks.ID']))
		$sql = "SELECT Ideas.* FROM Ideas WHERE Ideas.userId='$userid' AND Ideas.isPublic='1' UNION SELECT Ideas.* FROM Ideas, GroupIdeas, Groups, GroupUsers WHERE GroupUsers.userId = '" . $_SESSION['innoworks.ID'] . "' AND GroupUsers.groupId = Groups.groupId AND Groups.groupId = GroupIdeas.groupId AND GroupIdeas.ideaId = Ideas.ideaId AND Ideas.userId = '$userid' $limit";
	else 
		$sql = "SELECT Ideas.* FROM Ideas WHERE Ideas.userId='$userid' AND Ideas.isPublic='1' $limit";
	return dbQuery($sql);
}

function countGetProfileIdeas($userid) {
	$sql = "SELECT COUNT(*) FROM (SELECT Ideas.* FROM Ideas WHERE Ideas.userId='$userid' AND Ideas.isPublic='1' UNION SELECT Ideas.* FROM Ideas, GroupIdeas, Groups, GroupUsers WHERE GroupUsers.userId = '$userid' AND GroupUsers.groupId = Groups.groupId AND Groups.groupId = GroupIdeas.groupId AND GroupIdeas.ideaId = Ideas.ideaId) AS joinedResult";
	$array = dbFetchArray(dbQuery($sql));
	return $array[0];
}

function getAddIdeas($userid) {
	return dbQuery("SELECT * FROM Ideas WHERE userId = '".$userid."'");
}

function getIdeaDetails($ideaId) {
	return dbQuery("SELECT Ideas.*, Users.username, Users.firstName, Users.lastName FROM Ideas, Users WHERE ideaId = '".$ideaId."'  AND Users.userId = Ideas.userId");
}

function getSelectedIdeas($userid) {
	return dbQuery("SELECT Ideas.*, Selections.*, Users.username FROM Ideas, Selections, Users WHERE Ideas.userId = '".$userid."' AND Selections.ideaId =  Ideas.ideaId AND Users.userId = Ideas.userId");
}

function getPublicSelectedIdeas() {
	return dbQuery("SELECT Ideas.*, Selections.*, Users.username FROM Ideas, Selections, Users WHERE Ideas.isPublic = '1' AND Selections.ideaId =  Ideas.ideaId AND Users.userId = Ideas.userId");
}

function getSelectedIdeasForGroup($groupid,$userid) {
	return dbQuery("SELECT Ideas.*, Selections.*, Users.username FROM Ideas, Selections, GroupIdeas, Users WHERE Ideas.ideaId = GroupIdeas.ideaId AND GroupIdeas.groupId = '$groupid' AND Selections.ideaId =  Ideas.ideaId AND Users.userId = Ideas.userId");
}

function getIdeaSelect($ideaId, $userid) {
	return dbQuery("SELECT Selections.* FROM Ideas, Selections WHERE Selections.ideaId =  Ideas.ideaId AND Ideas.ideaId = '$ideaId'");
}

function createIdea($opts) {
	import('note.service');
	$id = genericCreate("Ideas", $opts);
	
	if ($id) {
		$note = array();
		$note['mail'] = false;
		$note['fromUserId'] = $_SESSION['innoworks.ID'];
		$note['toUserId'] = $_SESSION['innoworks.ID'];
		$note['noteText'] = 'Created new idea ' . $opts['title'];
		$note['ideaId'] = $id;
		createNote($note);
	}
	
	return $id;
}

function updateIdeaDetails($opts) {
	if (hasEditAccessToIdea($opts['ideaId'], $_SESSION['innoworks.ID'])){
		$where = array("ideaId", "userId");
		$success = genericUpdate("Ideas", $opts, $where);
		return $success;
	}
	return false;
}

function deleteIdea($opts) {
	if (hasEditAccessToIdea($opts['ideaId'], $_SESSION['innoworks.ID'])){
		return genericDelete("Ideas", array('ideaId' => $opts['ideaId']));
	}
	return false;
}

function createIdeaSelect($opts) {
	return genericCreate("Selections", $opts);
}

function updateIdeaSelect($opts) {
	$where = array("ideaId", "userId", "selectionId");
	return genericUpdate("Selections", $opts, $where);
}

function deleteIdeaSelect($opts) {
	return genericDelete("Selections", $opts);
}

function getFeaturesForIdea($id) {
	return dbQuery("SELECT * FROM Features WHERE ideaId = ".$id);
}

function createFeature($opts) {
	return genericCreate("Features", $opts);
}

function deleteFeature($id) {
	return genericDelete("Features", array("featureId"=>$id));
}

function getRolesForIdea($id) {
	return dbQuery("SELECT * FROM Roles WHERE ideaId = ".$id);
}

function createRole($opts) {
	return genericCreate("Roles", $opts);
}

function deleteRole($id) {
	return genericDelete("Roles", array("roleId"=>$id));
}

function getIdeaFeatureEvaluationsForIdea($id) {
	return dbQuery("SELECT IdeaFeatureEvaluations.* FROM IdeaFeatureEvaluations, Ideas WHERE IdeaFeatureEvaluations.ideaId = Ideas.ideaId AND Ideas.ideaId = '$id'");
}

function getFeatureEvaluationForIdea($id) {
	return dbQuery("SELECT Features.feature, FeatureEvaluation.* FROM FeatureEvaluation, Ideas, Features,IdeaFeatureEvaluations WHERE FeatureEvaluation.featureId = Features.featureId AND Features.ideaId = IdeaFeatureEvaluations.ideaId AND FeatureEvaluation.ideaFeatureEvaluationId = IdeaFeatureEvaluations.ideaFeatureEvaluationId AND Ideas.ideaId = Features.ideaId AND IdeaFeatureEvaluations.ideaFeatureEvaluationId='$id' ORDER BY FeatureEvaluation.score DESC");
}

function getCommentsForIdea($id) {
	return dbQuery("SELECT * FROM Comments WHERE ideaId = '$id' ORDER BY timestamp DESC");
}

function getViewsForIdea($id) {
	return dbQuery("SELECT * FROM Views WHERE ideaId = '$id'");
}

function createComment($opts) {
	return genericCreate("Comments", $opts);
}

function deleteComment($id) {
	if (hasEditAccessToComment($id, $_SESSION['innoworks.ID'])) {
		return genericDelete("Comments", array("commentId" => $id));
	}
	return false;
}

function createFeatureEvaluation($opts) {
	return genericCreate("IdeaFeatureEvaluations", $opts);
}

function updateIdeaFeatureEvaluation($opts) {
	$where = array("ideaFeatureEvaluationId");
	return genericUpdate("IdeaFeatureEvaluations", $opts, $where);
}

function updateFeatureEvaluation($opts) {
	$where = array("featureEvaluationId");
	return genericUpdate("FeatureEvaluation", $opts, $where);
}

function updateFeatureEvaluationTotal($opts) {
	$where = array("featureEvaluationId");
	return genericUpdate("FeatureEvaluation", $opts, $where);
}

function deleteFeatureEvaluation($id) {
	return genericDelete("IdeaFeatureEvaluations", array("ideaFeatureEvaluationId"=>$id));
}

function createFeatureItem($opts) {
	return genericCreate("FeatureEvaluation", $opts);
}

function updateFeatureItem($opts) {
	$where = array("featureEvaluationId");
	return genericUpdate("FeatureEvaluation", $opts, $where);
}

function deleteFeatureItem($id) {
	return genericDelete("FeatureEvaluation", array("featureEvaluationId"=>$id));
}

function updateFeature($opts) {
	$where = array("featureId");
	return genericUpdate("Features", $opts, $where);
}

function updateRole($opts) {
	$where = array("roleId");
	return genericUpdate("Roles", $opts, $where);
}

function getFeatureEvaluationTotalForIdea($ideaId, $userId) {
	$score = dbFetchObject(dbQuery("SELECT AVG(FeatureEvaluation.score) AS score FROM IdeaFeatureEvaluations, FeatureEvaluation WHERE IdeaFeatureEvaluations.ideaId = '$ideaId' AND FeatureEvaluation.ideaFeatureEvaluationId = IdeaFeatureEvaluations.ideaFeatureEvaluationId"));
	logAudit("FeatureEvaluation score is: " . $score->score);
	if ($score->score == null)
		return 0;
	else
		return round($score->score);
}

function getRiskEvaluationTotalForIdea($ideaId, $userId) {
	$score = dbFetchObject(dbQuery("SELECT AVG(score) AS score FROM RiskEvaluation WHERE ideaId = '$ideaId'"));
	if ($score->score == null)
		return 0;
	else
		return round($score->score); 
}

function registerIdeaView($ideaId, $userId) {
	$opts = array();
	$opts['ideaId'] = $ideaId;
	$opts['userId'] = $userId;
	$opts['ip'] = $_SERVER['REMOTE_ADDR'];
	genericCreate("Views",$opts);
}
?>