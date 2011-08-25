<? if ($users && dbNumRows($users) > 0){ 
	while ($user = dbFetchObject($users)) { ?>
		<div class="tile" onclick="showProfileSummary('<?=$user->userId?>')">
			<img src="<?= $serverUrl . $uiRoot ?>innoworks/engine.ajax.php?action=userImg&actionId=<?= $user->userId?>"/>
			<div class="lefter">
				<?= getDisplayUsername($user->userId) ?>
			</div>
		</div>
	<?}
}?>
<div class="tile" onclick="showBook('profile')">
	<div style="color:#AAA">see all</div>
	<h1>
		<?=$countUsers?>
	</h1>
	users
	<div class="pointer" style="float:right; font-size:80px; color:#AAA; margin-top:-30px;">&raquo;</div>
</div>