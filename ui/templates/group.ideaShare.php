<div>
	<div style="width: 49%; float: left">
		<table style="border: 1px solid #DDD">
			<tr>
				<th>Group</th>
				<th>Viewable</th>
				<th>Editable</th>
			</tr>
			<tr>
				<td style="background-color: #EEE;">Public<br /> <span
					style="font-size: 0.85em">Share your idea with everyone</span></td>
				<td style="background-color: #EEE;">
					<input id="ideaIsPublic" type="checkbox" onclick="togglePublicIdea(this)"
						<? if ($idea->isPublic == 1) echo "checked"; ?> />
				</td>
				<td style="background-color: #EEE;"></td>
			</tr>
			<?
			if ($groups && dbNumRows($groups) > 0 ) {
				while ($group = dbFetchObject($groups)) {
					renderIdeaGroupItem($idea, $group, $items);
				}
			}
			?>
		</table>
	</div>
	<div style="width: 49%; float: left; clear: right;">
	<? if ($countGroups > dbNumRows($groups)) {?>
		<p>
			Displaying only 200 of your <?= $countGroups?> groups. Go to groups or search to manage.
		</p>
		<? } ?>
		<p>
			Show <a href='javascript:showGroups(); dijit.byId("ideasPopup").hide()'>Groups</a>
		</p>
		<p>Share this idea with a friend <a href="<?= $shareUrl ?>">here</a></p>
		<?renderTemplate('shareBtns', null); ?>
	</div>
</div>
