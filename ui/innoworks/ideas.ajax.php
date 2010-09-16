<?
require_once("thinConnector.php");
require_once("ideas.ui.php");
import("idea.service");

if (isset($_GET) && $_GET != '') {
	switch ($_GET['action']) {
		case "getIdeas":
			renderDefault();
			break;
		case "getIdeaGroupsForUser":
			renderIdeaGroupsForUser($_SESSION['innoworks.ID']);
			break;
		case "getIdeasForGroup":
			renderIdeasForGroup($_GET['groupId']);
			break;
		case "getCommentsForIdea":
			renderCommentsForIdea($_GET['actionId']);
			break;
		case "getFeatureEvaluationForIdea":
			renderFeatureEvaluationForIdea($_GET['actionId']);
			break;
		case "getMission":
			renderIdeaMission($_GET['actionId']);
			break;
		case "getFeatures":
			renderIdeaFeatures($_GET['actionId']);
			break;
		case "getRoles":
			renderIdeaRoles($_GET['actionId']);
			break;
		case "getFeaturesForm":
			renderIdeaFeaturesForm($_GET['actionId']);
			break;
		case "getRolesForm":
			renderIdeaRolesForm($_GET['actionId']);
			break;
		case "getShare":
			renderShare($_GET['actionId'], $_SESSION['username']);
			break;
	}
}

if (isset($_POST) && $_POST != '') {
	switch ($_POST['action']) {
		case "addIdea":
			echo "Creating Idea.. ";
			$opts = array('title' => $_POST['title'], 'userId' => $_SESSION['innoworks.ID']);
			unset($opts['action']);
			$idea = createIdea($opts);
			echo "Response Code: ".$idea;
			break;
		case "deleteIdea":
			echo "Deleting Idea... ";
			$opts = array('ideaId' => $_POST['ideaId'], 'userId' => $_SESSION['innoworks.ID']);
			unset($opts['action']);
			$idea = deleteIdea($opts);
			echo "Response Code: ".$idea;
			break;
		case "updateIdeaDetails":
			echo "Updating Idea... ";
			$opts = array_merge($_POST, array("userId" => $_SESSION['innoworks.ID']));
			unset($opts['action']);
			$idea = updateIdeaDetails($opts);
			echo "Response Code: ".$idea;
			break;
		case "addFeature":
			echo "Adding Feature... ";
			$opts = $_POST;
			unset($opts['action']);
			$feature = createFeature($opts);
			echo "Response Code: ".$feature;
			break;
		case "deleteFeature":
			echo "Deleting Feature... ";
			$feature = deleteFeature($_POST['actionId']);
			echo "Response Code: ".$feature;
			break;
		case "addRole":
			echo "Adding Role... ";
			$opts = $_POST;
			unset($opts['action']);
			$feature = createRole($opts);
			echo "Response Code: ".$feature;
			break;
		case "deleteRole":
			echo "Deleting Role... ";
			//$opts = array_merge($_POST['actionId'], array("userId" => $_SESSION['innoworks.ID']));
			$feature = deleteRole($_POST['actionId']);
			echo "Response Code: ".$feature;
			break;
		case "addComment":
			echo "Adding Comment... ";
			$opts = array_merge($_POST, array("userId" => $_SESSION['innoworks.ID']));
			unset($opts['action']);
			$comment = createComment($opts);
			echo "Response Code: ".$comment;
			break;
		case "deleteComment":
			echo "Deleting Comment... ";
			//$opts = array_merge($_POST['actionId'], (array) $_SESSION['innoworks.ID']);
			$feature = deleteComment($_POST['commentId']);
			echo "Response Code: " . $feature;
			break;
		case "createFeatureItem":
			echo "Adding Item... ";
			$opts = array_merge($_POST, array("userId" => $_SESSION['innoworks.ID']));
			unset($opts['action']);
			$resp = createFeatureItem($opts);
			echo "Response Code: ".$resp;
			break;
		case "updateFeatureItem":
			echo "Updating Item... ";
			$opts = $_POST;
			unset($opts['action']);
			unset($opts['feature']);
			$resp = updateFeatureItem($opts);
			echo "Response Code: ".$resp;
			break;
		case "deleteFeatureItem":
			echo "Deleting Item... ";
			$feature = deleteFeatureItem($_POST['featureEvaluationId']);
			echo "Response Code: " . $feature;
			break;
		case "updateRole":
			echo "Updating Role... ";
			$opts = $_POST;
			unset($opts['action']);
			$feature = createRole($opts);
			echo "Response Code: ".$feature;
			break;
		case "updateFeature":
			echo "Updating Feature... ";
			$opts = $_POST;
			unset($opts['action']);
			$feature = updateFeature($opts);
			echo "Response Code: ".$feature;
			break;
	}
}
?>