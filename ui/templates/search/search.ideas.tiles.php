<?if ($ideas && dbNumRows($ideas) > 0){
	while ($idea = dbFetchObject($ideas)) {?>
	<div class="tile" onclick="showIdeaSummary(<?= $idea->ideaId?>);">
		<img src="<?= $serverUrl . $uiRoot ?>innoworks/engine.ajax.php?action=ideaImg&actionId=<?= $idea->ideaId?>"/>
		<div class="lefter">
			<?= $idea->title ?>
		</div>
	</div>
	<?}
}?>
<div class="tile" onclick="showBook('idea')">
	<div style="color:#AAA">see all</div>
	<h1>
		<?=$countIdeas?>
	</h1>
	ideas
	<div class="pointer" style="float:right; font-size:80px; color:#AAA; margin-top:-30px;">&raquo;</div>
</div>