<?if ($announces && dbNumRows($announces)) {
	while($announce = dbFetchObject($announces)) {?>
		<div class="itemHolder" style="font-size: 0.85em">
		<?= $announce->text ?>
			<br /> <span><?= getDisplayUsername($announce->userId) . ' ' . $announce->date ?>
			</span>
		</div>
	<?}
}?>