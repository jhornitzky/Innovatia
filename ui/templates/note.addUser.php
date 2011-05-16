<p>Select a user to send a note to</p>
<div style="width: 100%; clear: both; height: 2.5em;">
	<form id="popupAddSearch" onsubmit="findNoteUsers(); return false;">
		<div style="border: 1px solid #444444; position: relative; float: left; clear: right">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<input type="text" name="criteria" value="<?= htmlspecialchars($criteria); ?>" placeholder=" . . . " style="border: none" />
					</td>
					<td>
						<img src="<?= $uiRoot."style/glass.png" ?>" onclick="findNoteUsers()" style="width: 30px; height: 24px; margin: 2px; cursor: pointer" />
					</td>
				</tr>
			</table>
			<input id="searchBtn" type="submit" value="Search" style="display: none;" />
		</div>
	</form>
</div>