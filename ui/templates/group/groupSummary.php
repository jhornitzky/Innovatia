<div class="summaryContainer">
<span class="summaryActions"><a href="javascript:logAction()" onclick="printUser('&profile=<?= $userDetails->userId?>');"><img src="<?= $uiRoot . 'style/social/printIcon.jpg'?>"/></a></span>
<div class="summaryHead curvetr">
	<table>
		<tr>
			<td style="background-color:#FFF; border-radius:2px; -webkit-border-radius:2px; -moz-border-radius:2px;">
				<? if (!isset($_REQUEST['doc'])) {?>
				<img src="<?= $serverUrl .  $uiRoot ?>innoworks/retrieveImage.php?action=groupImg&actionId=<?= $group->groupId ?>" style="width:3em; height:3em;"/> 
				<? } ?>
			</td>
			<td><h1><?= $group->title?></h1></td>
		</tr>
	</table>
	<span class="smallInfo">
		<b>Led by</b> <? renderTemplate('ideator', array('userId' => $group->userId)) ?> 
		<b>Created</b> @ <?= prettyDate($group->createdTime) ?> 
		<b>Updated</b> @ <?= prettyDate($group->lastUpdateTime) ?>
	</span>
	<p style="font-size:0.9em;"><?= $group->description ?></p>
	<div class="smallInfo"><i><?= $group->tags ?></i></div>
</div>
<?if (!$hasGroupAccess) {?>
	<p>You have no access to this group</p>
<?} else {
	if ($groups && (dbNumRows($groups) == 1)) {?>
		<h2>Ideas</h2>
		<?if ($groupIdeas && dbNumRows($groupIdeas) > 0) {
			while ($idea = dbFetchObject($groupIdeas)) {?>
				<div class="itemHolder">
					<img src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=ideaImg&actionId=<?= $idea->ideaId?>" style="width:1em; height:1em;"/>
					<a href="javascript:logAction()" onclick="showIdeaSummary('<?= $idea->ideaId?>');"><?=$idea->title?></a>
					<span><?= getDisplayUsername($idea->userId); ?></span>
				</div>
			<?}
			if (dbNumRows($groups) > 100) {?>
					<p>Only displaying 500 latest ideas</p>
			<?}
		} else {?>
			<p>None</p>
		<?}?>
	
		<h2>Users</h2>
		<?if ($groupUsers && dbNumRows($groupUsers) > 0) {
			while ($user = dbFetchObject($groupUsers)) {?>
				<div class="itemHolder">
					<img src='<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=userImg&actionId=$user->userId' style='width:1em; height:1em;'/>
					<a href="javascript:logAction()" onclick="showProfileSummary('<?= $user->userId ?>')"><?=$user->firstName . ' ' . $user->lastName . ' / ' . $user->username?></a>
				</div>
			<?}
			if (dbNumRows($groups) > 100) {?>
				<p>Only displaying 100 latest users</p>
			<?}
		} else {?>
			<p>None</p>
		<?}
	}
}?>
</div>