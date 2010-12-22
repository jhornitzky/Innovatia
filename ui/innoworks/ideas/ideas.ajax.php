<?
require_once(dirname(__FILE__) . "/../thinConnector.php");
require_once(dirname(__FILE__) . "/ideas.ui.php");
import("idea.service");

if (isset($_GET['action'])) {
	$limit = 20;
	if (isset($_GET['limit']))
		$limit = $_GET['limit'];
	switch ($_GET['action']) {
		case "getIdeas":
			renderIdeasDefault($_SESSION['innoworks.ID'], $limit);
			break;
		case "getPublicIdeas":
			renderPublicIdeas($limit);
			break;
		case "getIdeasForGroup":
			renderIdeasForGroup($_GET['groupId']);
			break;
		case "getCommentsForIdea":
			renderCommentsForIdea($_GET['actionId'], $_SESSION['innoworks.ID']);
			break;
		case "getIdeaFeatureEvaluationsForIdea":
			renderIdeaFeatureEvaluationsForIdea($_GET['actionId']);
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
			renderShare($_GET['actionId'], $_SESSION['innoworks.ID']);
			break;
		case "getAttachments": 
			renderAttachmentsIframe($_GET['actionId'], $_SESSION['innoworks.ID']);
			break;
		case "getIdeaName":
			registerIdeaView($_GET['actionId'], $_SESSION['innoworks.ID']);
			renderIdeaName($_GET['actionId'], $_SESSION['innoworks.ID']);
			break;
	}
}

if (isset($_POST['action'])) {
	switch ($_POST['action']) {
		case "addIdea":
			echo "Creating Idea.. ";
			$opts = array('title' => $_POST['title'], 'userId' => $_SESSION['innoworks.ID'], 'createdTime' => date_create()->format('Y-m-d H:i:sP'));
			unset($opts['action']);
			renderServiceResponse(createIdea($opts));
			break;
		case "deleteIdea":
			echo "Deleting Idea... ";
			$opts = array('ideaId' => $_POST['ideaId'], 'userId' => $_SESSION['innoworks.ID']);
			unset($opts['action']);
			renderServiceResponse(deleteIdea($opts));
			break;
		case "updateIdeaDetails":
			echo "Updating Idea... ";
			$opts = array_merge($_POST, array("userId" => $_SESSION['innoworks.ID']));
			unset($opts['action']);
			renderServiceResponse(updateIdeaDetails($opts));
			break;
		case "addFeature":
			echo "Adding Feature... ";
			$opts = $_POST;
			unset($opts['action']);
			renderServiceResponse(createFeature($opts));
			break;
		case "deleteFeature":
			echo "Deleting Feature... ";
			renderServiceResponse(deleteFeature($_POST['actionId']));
			break;
		case "addRole":
			echo "Adding Role... ";
			$opts = $_POST;
			unset($opts['action']);
			renderServiceResponse(createRole($opts));
			break;
		case "deleteRole":
			echo "Deleting Role... ";
			renderServiceResponse(deleteRole($_POST['actionId']));
			break;
		case "addComment":
			echo "Adding Comment... ";
			$opts = array_merge($_POST, array("userId" => $_SESSION['innoworks.ID']));
			unset($opts['action']);
			renderServiceResponse( createComment($opts));
			break;
		case "deleteComment":
			echo "Deleting Comment... ";
			renderServiceResponse(deleteComment($_POST['commentId']));
			break;
		case "createFeatureItem":
			echo "Adding Item... ";
			$opts = array_merge($_POST, array("userId" => $_SESSION['innoworks.ID']));
			unset($opts['action']);
			renderServiceResponse(createFeatureItem($opts));
			break;
		case "updateFeatureItem":
			echo "Updating Item... ";
			$opts = $_POST;
			unset($opts['action']);
			unset($opts['feature']);
			renderServiceResponse(updateFeatureItem($opts));
			break;
		case "deleteFeatureItem":
			echo "Deleting Item... ";
			renderServiceResponse(deleteFeatureItem($_POST['featureEvaluationId']));
			break;
		case "updateRole":
			echo "Updating Role... ";
			$opts = $_POST;
			unset($opts['action']);
			renderServiceResponse(updateRole($opts));
			break;
		case "updateFeature":
			echo "Updating Feature... ";
			$opts = $_POST;
			unset($opts['action']);
			renderServiceResponse(updateFeature($opts));
			break;
		case "createFeatureEvaluation":
			echo "Adding Item... ";
			logDebug("IDEA ID: ". $_POST['ideaId']);
			$opts = array_merge($_POST, array("userId" => $_SESSION['innoworks.ID']));
			unset($opts['action']);
			renderServiceResponse(createFeatureEvaluation($opts));
			break;
		case "updateFeatureEvaluation":
			echo "Updating Item... ";
			$opts = $_POST;
			unset($opts['action']);
			unset($opts['feature']);
			renderServiceResponse(updateFeatureEvaluation($opts));
			break;
		case "updateIdeaFeatureEvaluation";
			echo "Updating Evaluation... ";
			$opts = $_POST;
			unset($opts['action']);
			renderServiceResponse(updateIdeaFeatureEvaluation($opts));
			break;
		case "deleteFeatureEvaluation":
			echo "Deleting Item... ";
			renderServiceResponse(deleteFeatureEvaluation($_POST['actionId']));
			break;
		case "addAttachment":
			echo "Creating Attachment.. ";
			renderServiceResponse(createAttachment($_POST));
			break;
		case "deleteAttachment":
			echo "Deleting Item... ";
			renderServiceResponse(deleteAttachment($_POST['actionId']));
			break;
	}
}
?>