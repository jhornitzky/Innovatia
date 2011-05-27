<div class="summaryContainer">
<span class="summaryActions"><a href="javascript:logAction()" onclick="printUser('&profile=<?= $userDetails->userId?>');">Print</a></span>
<table>
	<tr>
		<td>
			<img src="<?= $serverUrl .  $uiRoot ?>innoworks/retrieveImage.php?action=groupImg&actionId=<?= $group->groupId ?>" style="width:3em; height:3em;"/> 
		</td>
		<td><h1><?= $group->title?></h1>
			<!-- <div style="margin-bottom:1.0em">
				 <span><?= getDisplayUsername($group->userId)?></span>  |
				<span class="summaryActions"><a href="javascript:logAction()" onclick="printGroupSummary('<?= "&group=" . $group->groupId ?>')">Print</a>
				<a href="javascript:logAction()" onclick="currentGroupId=<?= $group->groupId ?>;showGroups();">Edit</a></span> 
			</div> -->
		</td>
	</tr>
</table>
<span class="smallInfo"><b>Led by</b> <?= getDisplayUsername($group->userId) ?> 
<?php renderGenericInfoLine($groups, $group ,array('groupId', 'userId', 'title', 'description', 'tags'))?>
</span>
<div class="smallInfo"><?= $group->tags ?></div>
<div class="smallInfo"><?= $group->description ?></div>

<?if (!$hasGroupAccess) {?>
	<p>You have no access to this group</p>
<?} else {
	if ($groups && (dbNumRows($groups) == 1)) {?>
		<h2>Idea(s)</h2>
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
	
		<h2>User(s)</h2>
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