<?
require_once("thinConnector.php");
import("idea.service");
import("attach.service");
import("group.service");

$ideaId;
$groupId;
if (isset($_POST['ideaId'])) {
	$ideaId = $_POST['ideaId'];
} else if (isset($_GET['ideaId'])){
	$ideaId =  $_GET['ideaId'];
} else if (isset($_POST['groupId'])){
	$groupId =  $_POST['groupId'];
} else if (isset($_GET['groupId'])){
	$groupId =  $_GET['groupId'];
}
$success;
$msg;
if (isset($_POST['action'])) {
	switch ($_POST['action']) {
		case "addAttachment":
			$msg = "Creating Attachment.. ";
			$success = createAttachment($_POST);
			break;
		case "deleteAttachment":
			$msg = "Deleting Item... ";
			$success = deleteAttachment($_POST['actionId']);
			break;
	}
}
?>
<html>
<head>
<link href="<?= $serverRoot?>ui/style/style.css" rel="stylesheet" type="text/css" />

<style>
html, body {
	text-align:left;
}
</style>

<script type="text/javascript" src="<?= $serverRoot?>ui/scripts/jQuery-Min.js"></script>
<script type="text/javascript">
var ctime;

function dpAttach(id) {
	var post = "action=dpChange&attachmentId="+id+"&isDp=1";
	$.post("engine.ajax.php", post, function (data) {
		showResponses( data, true);
	});
}

function unDpAttach(id) {
	var post = "action=dpChange&attachmentId="+id+"&isDp=0";
	$.post("engine.ajax.php", post, function (data) {
		showResponses( data, true);
	});
}

function toggleDp(elem, id) {
	if($(elem).is(':checked')) {
		dpAttach(id);
	} else {
		unDpAttach(id);
	}
}

function hideResponses() {
	$(".responses").slideUp(function () {
		$(".responses").empty();
	});
}

function showResponses(data, timeout) {
	var selector = ".responses";
	$(selector).html(data);
	$(selector).slideDown();
	if (timeout) {
		if (ctime != null)
			window.clearTimeout(ctime);
		ctime = window.setTimeout('hideResponses("'+selector+'")', 10000);
	} 
}
</script>
</head>

<body>
	<div class="responses"><?= $msg . $success ?></div>
	<? if ((isset($ideaId) && hasEditAccessToIdea($ideaId,$_SESSION['innoworks.ID'])) 
	|| isset($groupId) || (!isset($groupId) && !isset($userId))) { ?>
	<form method="post" enctype="multipart/form-data"
	action="./attachment.php"><input type="hidden" name="MAX_FILE_SIZE"
	value="2000000"> <input name="userfile" type="file" id="userfile"> <input
	type="hidden" name="action" value="addAttachment" /> 
	<?
	if (isset($ideaId)) 
		echo '<input type="hidden" name="ideaId" value="'.$ideaId.'"/>';
	else if (isset($groupId))
		echo '<input type="hidden" name="groupId" value="'.$groupId.'"/>';?> 
	<input type="submit" value=" + " title="Add attachment" /></form>
	<?
	}
	
	global $usersRoot;
	$attachs;
	if (isset($ideaId))
		$attachs = getAttachmentsForIdea($ideaId);
	else if (isset($groupId))
		$attachs = getAttachmentsForGroup($groupId);
	else 
		$attachs = getAttachmentsForUser($_SESSION['innoworks.ID']);
		
	if ($attachs && dbNumRows($attachs)) {
		while ($attach = dbFetchObject($attachs)) {?>
			<table style="border-top:1px solid #DDD">
			<tr>
			<td>
			<?if (preg_match("/^[image]/",$attach->type)) {?>
				<img src='<?=$usersRoot . $attach->path?>' style='width:100px;height:75px'/>
			<?}?>
			</td>
			<td>
			<form method="post" action="./attachment.php" style="padding:0; margin:0">
			<a href="retrieveAttachment.php?action=retrieveAttachment&actionId=<?= $attach->attachmentId;?>" style="padding:0; margin:0">
			<?= $attach->title;?></a>
			<?if (isset($ideaId)) 
				echo '<input type="hidden" name="ideaId" value="'.$ideaId.'"/>';
			else if (isset($groupId))
				echo '<input type="hidden" name="groupId" value="'.$groupId.'"/>';
			
			if (isset($groupId) || (isset($attach->ideaId) && hasEditAccessToIdea($attach->ideaId,$_SESSION['innoworks.ID'])) || ($attach->userId == $_SESSION['innoworks.ID']) || $_SESSION['innoworks.isAdmin']) { ?> 
			<input type="hidden" name="actionId" value="<?= $attach->attachmentId;?>" /> 
			<input type="hidden" name="action" value="deleteAttachment" /> 
			<input type="submit" value=" - " title="Delete attachment" />
			<?}?>
			</form>
			<ul style="padding-top:0; margin-top:0;padding-bottom:0;margin-bottom:0;">
			<? if (isset($attach->ideaId) && !empty($attach->ideaId)) { echo '<li style="font-size:0.8em">'. getIdeaDetails($attach->ideaId)->title . '</li>';}?>
			<? if (isset($attach->groupId) && !empty($attach->groupId)) { echo '<li style="font-size:0.8em">'.getGroupDetails($attach->groupId)->title . '</li>';}?>
			<? if (isset($attach->userId) && !empty($attach->userId)) { echo '<li style="font-size:0.8em">' . getUserInfo($attach->userId)->username . '</li>';}?>
			</ul>
			<?if (preg_match("/^[image]/",$attach->type)) {?>
				<span style="font-size:0.8em">Use as DP</span> <input type='checkbox' onclick="toggleDp(this, '<?= $attach->attachmentId?>')"
				<? if ($attach->isDp == 1) { echo 'checked';}?> />
			<?}?>
			</td>
			</tr>
			</table>
		<?}
	} else {?>
		<p>No attachments</p>
	<?}?>
</body>

</html>
