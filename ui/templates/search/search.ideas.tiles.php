<div class="tile" style="background-image:url('<?= $serverUrl . $uiRoot?>style/cube.png');">
	<h1>
		<?=$countIdeas?>
	</h1>
</div>
<?if ($ideas && dbNumRows($ideas) > 0){
	while ($idea = dbFetchObject($ideas)) {?>
	<div class="tile" onclick="showIdeaSummary(<?= $idea->ideaId?>);">
		<img src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=ideaImg&actionId=<?= $idea->ideaId?>"/>
		<div class="lefter">
			<?= $idea->title ?>
		</div>
	</div>
	<?}
}?>