<div style="width: 100%; vertical-align: bottom;">
	<div class="fixed-left">
		<div class="treeMenu">
			<div class="itemHolder headBorder" style="background-color: #EEE">Your
				statistics</div>
			<div class="itemHolder">
			<?= $noOfIdeas ?>
				<br /> <span>ideas</span>
			</div>
			<div class="itemHolder">
			<?= $noOfSelectedIdeas ?>
				<br /> <span>selected ideas</span>
			</div>
			<div class="itemHolder">
			<? if($noOfIdeas > 0) echo $noOfSelectedIdeas/$noOfIdeas; else echo 0; ?>
				<br /> <span>selected idea ratio</span>
			</div>
			<div class="itemHolder">
			<?= $noOfGroupsCreated?>
				<br /> <span>groups created</span>
			</div>
			<div class="itemHolder">
			<?= $noOfGroupsIn?>
				<br /> <span>groups in</span>
			</div>
		</div>
		<!-- <div class="itemHolder headBorder" style="background-color:#EEE">More options</div>
	<div class="itemHolder clickable" onclick="showGroups();">Groups<br/><span>Share your ideas</span></div>
	<div class="itemHolder clickable" onclick="showProfile();">Profiles<br/><span>Find expert innovators</span></div>
	<div class="itemHolder clickable" onclick="showNotes();">Notes<br/><span>Communicate in real-time</span></div>
	<div class="itemHolder clickable" onclick="showSearch();">Search<br/><span>Find ideas, people or groups</span></div>
	<div class="itemHolder clickable" onclick="showTimelines();">Timelines<br/><span>Review your schedule</span></div>
	<div class="itemHolder clickable" onclick="showReports();">Reports<br/><span>View statistics for innoworks</span></div> -->

	</div>
	<div class="fixed-right">
		<div
			style="width: 100%; border: 1px solid #AAA; border-left: none; margin-bottom: 1em">
			<div class="itemHolder" style="background-color: #EEE">Latest notes
				to you</div>
				<? if ($notes && dbNumRows($notes) > 0) {
					while ($note = dbFetchObject($notes)) { ?>
			<div class="itemHolder">
				<table>
					<tr>
						<td><img
							src="retrieveImage.php?action=userImg&actionId=<?= $note->fromUserId ?>"
							style="width: 2em; height: 2em;" /></td>
						<td><?= $note->noteText ?><br /> <span><?= getDisplayUsername($note->fromUserId)?>
						</span></td>
					</tr>
				</table>
			</div>
			<?}
				}
				$limit = 8;
				?>
		</div>
		<div id="dashui" class="threeColumnContainer">
			<div class="threecol col1 bluebox"
				style="border-left: none; width: 32%; margin-right: 1.5%">
				<div class="widget ui-corner-all">
					<div class="itemHolder" style="background-color: #EEE">
						Ideate<br /> <span>Record, manage and explore ideas to help them
							take shape</span>
					</div>
					<div class="dashResults">
					<? renderDashIdeas($userid, $limit)?>
					</div>
				</div>
			</div>
			<div class="threecol col2 greenbox"
				style="border-left: none; width: 32%; margin-right: 1.5%">
				<div class="widget ui-corner-all">
					<div class="itemHolder" style="background-color: #EEE">
						Compare<br /> <span>Contrast and compare your existing ideas</span>
					</div>
					<div class="dashResults">
					<? renderDashCompare($userid, $limit);?>
					</div>
				</div>
			</div>
			<div class="threecol col3 orangebox"
				style="border-left: none; width: 32%; margin-right: 0">
				<div class="widget ui-corner-all">
					<div class="itemHolder" style="background-color: #EEE">
						Select<br /> <span>Choose ideas to work on and manage their tasks.</span>
					</div>
					<div class="dashResults">
					<? renderDashSelect($userid, $limit);?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
