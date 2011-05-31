<?php
require_once('thinConnector.php');

if (isset($_REQUEST['action'])) {
	$success = false;
	switch($_REQUEST['action']) {
		case 'unpublicIdeas':
			$success = dbQuery('UPDATE Ideas SET isPublic = 0');
			break;
		case 'deleteGroups':
			$success = dbQuery('DELETE FROM Groups');
			break;
		case 'deleteAllNonAdminUsers':
			$success = dbQuery('DELETE FROM Users WHERE isAdmin = 0');
			break;
		case 'deleteAllIdeas':
			$success = dbQuery('DELETE FROM Ideas');
			break;
	}
}
?>
<html>
<head>
<? require_once("head.php"); ?>
<style>
html,body {
	margin-bottom:2em;
	font-family:arial;
}

div {
	width:100%;
	float:left;
	text-align:center;
}
</style>
</head>
<body>
<h1>Bulk Actions</h1>
<? if (isset($success) && $success) {?>
	<p>Action completed successfully</p>
<?} else if (isset($success) && !$success) {?>
	<p>Problem completing action... data unchanged</p>
<?}?>
<form onsubmit="return confirm('Are you absolutely sure you wish to do this? You should make a backup of all data before you do this.')">
<input type="submit" value="Unpublicize all ideas"/>
<input type="hidden" name="action" value="unpublicIdeas"/>
</form>
<form onsubmit="return confirm('Are you absolutely sure you wish to do this? You should make a backup of all data before you do this.')">
<input type="submit" value="Delete all groups"/>
<input type="hidden" name="action" value="deleteGroups"/>
</form>
<form onsubmit="return confirm('Are you absolutely sure you wish to do this? You should make a backup of all data before you do this.')">
<input type="submit" value="Delete all non-admin users"/>
<input type="hidden" name="action" value="deleteNonAdmin"/>
</form>
<form onsubmit="return confirm('Are you absolutely sure you wish to do this? You should make a backup of all data before you do this.')">
<input type="submit" value="Delete all ideas"/>
<input type="hidden" name="action" value="deleteAllIdeas"/>
</form>
</body>
</html>