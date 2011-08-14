<div class="tile" style="background-image:url('<?= $serverUrl . $uiRoot?>style/user.png');">
	<h1>
		<?=$countUsers?>
	</h1>
</div>
<? if ($users && dbNumRows($users) > 0){ 
	while ($user = dbFetchObject($users)) { ?>
		<div class="tile" onclick="showProfileSummary('<?=$user->userId?>')">
			<img src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=userImg&actionId=<?= $user->userId?>"/>
			<div class="lefter">
				<?= getDisplayUsername($user->userId) ?>
			</div>
		</div>
	<?}
}?>