<?require_once("thinConnector.php");
import("user.service");
$users = getAllUsers();?>
<html>
<head>
<?require_once('head.php')?>
<script type="text/javascript" src="<?= $uiRoot?>scripts/base/dojo/dojo.js"></script>
<script type="text/javascript" src="<?= $serverRoot?>ui/scripts/dojoLayer.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $uiRoot?>scripts/base/dijit/themes/tundra/tundra.css" />

<script>
function logAction() {}

function showProfileSummary(uId) {
	$("#userDetails").load("<?= $uiRoot . "innoworks/" ?>profile/profile.ajax.php?action=getProfileSummary&actionId=" + uId);
}

function showIdeaSummary(id) {
	var idea = new inno.Dialog({href:"<?= $uiRoot . "innoworks/" ?>compare/compare.ajax.php?action=getIdeaSummary&actionId="+id, style: "width: 250px;height:" + (document.documentElement.clientHeight * 0.75) + "px;"});
	dojo.body().appendChild(idea.domNode);
	idea.startup();
	idea.show();
}

function showGroupSummary(id) {
	var group = new inno.Dialog({href:"<?= $uiRoot . "innoworks/" ?>groups/groups.ajax.php?action=getGroupSummary&actionId="+id, style: "width: 250px;height:" + (document.documentElement.clientHeight * 0.75) + "px;"});
	dojo.body().appendChild(group.domNode);
	group.startup();
	group.show();
}
</script>

<style>
.summaryActions {
	display:none;
}
</style>
</head>
<body class="tundra">
<div>
<div class="twoCol" style="width:226px;float:left">
<?if ($users && dbNumRows($users) > 0){
	while ($user = dbFetchObject($users)) { ?>
<div class='itemHolder clickable'
	onclick="showProfileSummary('<?=$user->userId?>')"><img
	src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=userImg&actionId=<?= $user->userId?>"
	style="width: 1em; height: 1em;" /> <?=$user->username?></div>
	<?}
}?>
</div>
<div id="userDetails" class="twoCol" style="left:226px; float:left">
</div>
</div>
</body>
</html>