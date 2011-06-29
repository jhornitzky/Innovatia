<?if ($announces && dbNumRows($announces)) {
	while($announce = dbFetchObject($announces)) {?>
	<div class="note <?= $class ?> clearfix">
		<table>
			<tr>
				<td style="vertical-align:top;">
					<img src="retrieveImage.php?action=userImg&actionId=<?= $id ?>" style="width: 2em; height: 2em" />
				</td>
				<td>
					<span class="noteData">
						<a href="javascript:showUserSummary(<?= $id ?>)"><?= getDisplayUsername($announce->userId) ?></a>&nbsp;@&nbsp;<?= prettyDate($announce->date ) ?> 
					</span>
					<br/>
					<span class="noteText"><?= $announce->text ?> </span>
				</td>
			</tr>
		</table>
	</div>
	<?}
}?>