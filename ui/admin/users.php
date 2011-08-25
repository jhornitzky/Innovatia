<?php 
require_once('thinConnector.php');
import('user.service');
import('search.service');
import('attach.service');
?>
<html>
<head>
<?php require_once 'head.php';?>
<script type="text/javascript" src="<?= $uiRoot?>scripts/base/dojo/dojo.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $uiRoot?>scripts/base/dijit/themes/tundra/tundra.css" />
<style>
a {
	font-size:1.0em;
}

td, th {
	border:1px solid #AAA;
	padding: 0.5em;
}

th {
	background-color:#AAA;
}

table img {
	width:1.5em; height:1.5em;
}
</style>
<script type="text/javascript">
dojo.addOnLoad(function(){	
	dojo.require("dijit.form.DateTextBox"); 
	
	//Parse controls
	dojo.parser.parse();	
});

function checkAll(elem) {
	if ($(elem).is(':checked')) {
		$('.controls input[type=checkbox]').attr('checked', 'checked');
	} else {
		$('.controls input[type=checkbox]').removeAttr('checked');
	}
}

function handleSubmit(force) {
	if (!force && confirm('Are you sure you wish to delete selected users? You should back up your data in tables before deleting any data.'))
		$('#userForm').submit(true);
}

function openUser(id) {
	window.open('<?= $uiRoot?>innoworks/viewer.php?profile=' + id);
}
</script>
</head>

<body class="tundra">
<?
//Perform delete action
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'Delete') {
	foreach($_REQUEST['id'] as $key => $id){
		logAudit('doDeleteUser' . $id);
		
		//First remove all attachments for user
		$attaches = getAttachmentsForUser($id);
		if ($attaches != null)  {
			while($attach = dbFetchObject($attaches)) {
				@unlink($userRoot.$attach->path);
			}
		}
		
		//Then remove user DB data
		$success = deleteUser($id);
	}
	echo '<p><b>Deleted selected users</b></p>';
} 

//User search/default
$filters = array();
$dateFrom = '';
$dateTo = '';
$searchTerms = '';
if (isset($_REQUEST['searchTerms']) && !empty($_REQUEST['searchTerms'])){
	$searchTerms = $_REQUEST['searchTerms'];
}
if (isset($_REQUEST['dateFrom']) && !empty($_REQUEST['dateFrom'])){
	$filters['dateFrom'] = $_REQUEST['dateFrom'];
	$dateFrom = htmlspecialchars($_REQUEST['dateFrom']);
}
if (isset($_REQUEST['dateTo']) && !empty($_REQUEST['dateTo'])) {
	$filters['dateTo'] = $_REQUEST['dateTo'];
	$dateTo = htmlspecialchars($_REQUEST['dateFrom']);
}	
$users = getSearchPeople($searchTerms, $_SESSION['innoworks.ID'], $filters, 'LIMIT 300');
?>
<h2>Users</h2>
<div class="searchOptions" style="margin-bottom:0.5em;">
	<form id="filterForm" method="post" action="users.php">
		Filters :
		Terms <input type="text" name="searchTerms" value="<?= htmlspecialchars($searchTerms)?>"/>
		Date from <input type="text" name="dateFrom" dojoType="dijit.form.DateTextBox" value="<?= $dateFrom ?>"/>
		Date to <input type="text" name="dateTo" dojoType="dijit.form.DateTextBox" value="<?= $dateTo ?>"/>
		<input type="submit" name="action" value="Filter"/><br/>
	</form>
</div>

<div class="searchResults">
	<form id="userForm" method="post" action="users.php" onsubmit='handleSubmit(); return false;'>
		<table style="border:1px solid #AAA;">
			<tr>
				<td colspan="5">
					<input type="submit" name="action" value="Delete"/>
					<span style="float:right; font-size:0.85em">Displaying <?= dbNumRows($users) ?> of up to 300 results. Total users : <?= countGetAllUsers(); ?></span>
				</td>
			</tr> 
			<tr>
				<th>
					<input type="checkbox" onchange="checkAll(this)"/>
				</th>
				<th>
					User
				</th>
				<th>
					Login ID
				</th>
				<th>
					Creation time
				</th>
				<th>
					Flags
				</th>
			</tr> 
			<? while ($user = dbFetchObject($users)) { ?>
			<tr>
				<td class="controls">
					<? if (!$user->isAdmin) {?>
						<input type="checkbox" name="id[]" value="<?= $user->userId ?>"/>
					<?}?>
				</td>
				<td>
					<img src="<?= $uiRoot?>/innoworks/engine.ajax.php?action=userImg&actionId=<?= $user->userId?>"/>
					<a href="javascript:void(0)" onclick="openUser('<?= $user->userId ?>')"><?= getDisplayUsername($user->userId) ?></a>
				</td>
				<td>
					<?= $user->username ?>
				</td>
				<td>
					<?= $user->createdTime; ?>
				</td>
				<td>
					<? if ($user->isAdmin) {?>
						ADMIN
					<?}?>
					<? if ($user->isExternal) {?>
						EXTERNAL
					<?}?>
				</td>
			</tr> 
			<?}?>
		</table>
	</form>
</div>

</body>
</html>