<?
/**
 * AJAX HANDLER/CONTROL layer that routes requests
 */
require_once("thinConnector.php");

//Log actions
if (isset($_REQUEST['action']))
	logDebug('[User ' . $_SESSION['innoworks.ID'] . '] '. $_REQUEST['action']);
	
//ORDER FOR SPEED BASED ON MOST likely action
require_once("dash/dash.ajax.php");
require_once("ideas/ideas.ajax.php");
require_once("compare/compare.ajax.php");
require_once("groups/groups.ajax.php");
require_once("select/select.ajax.php");
require_once("profile/profile.ajax.php");
require_once("search/search.ajax.php");
require_once("notes/notes.ajax.php");
require_once("attachment/attach.ajax.php");
?>