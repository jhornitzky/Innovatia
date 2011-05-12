<div style="width: 100%; vertical-align: bottom;">
	<div class="fixed-left">
		<div class="treeMenu">
			<div class="itemHolder headBorder"><h3>your statistics</h3></div>
			<div class="itemHolder">
			<?= $noOfIdeas ?> <span>ideas</span>
			</div>
			<div class="itemHolder">
			<?= $noOfSelectedIdeas ?> <span>selected ideas</span>
			</div>
			<div class="itemHolder">
			<? if($noOfIdeas > 0) echo round($noOfSelectedIdeas/$noOfIdeas, 2); else echo 0; ?> <span>selection/idea ratio</span>
			</div>
			<div class="itemHolder">
			<?= $noOfGroupsCreated?> <span>groups created</span>
			</div>
			<div class="itemHolder">
			<?= $noOfGroupsIn?> <span>groups in</span>
			</div>
		</div>
	</div>
	<div class="fixed-right">
		<div style="width: 100%; margin-bottom: 1em; position:relative;">
			<div style="position:absolute; top:0; right:0; color:#777;text-align:right; font-size:0.75em">need help? click the i up here</div>
			<div class="itemHolder" style="border-top:none;">
				<h1><span style="color:#AAA;">hi</span> <?= getDisplayFirstName($_SESSION['innoworks.ID'])?></h1>
				<span style="color:#777">while you were gone here's what happened...</span>
			</div>
				<? if ($notes && dbNumRows($notes) > 0) {
					while ($note = dbFetchObject($notes)) { ?>
					<div class="itemHolder">
						<div><?= $note->noteText ?><br/>
							<span>from
								<img src="retrieveImage.php?action=userImg&actionId=<?= $note->fromUserId ?>" style="width:1em; height:1em;" /> 
								<a href="javascript:showProfileSummary('<?= $note->fromUserId ?>')">
									<?= getDisplayUsername($note->fromUserId)?>
								</a>
							</span>
						</div>
						<div style="position:absolute; right:0; top:0;">
							<span><?= $note->createdTime ?></span>
						</div>
					</div>
					<?}?>
					<a class="loadMore" href="javascript:showProfile();">see all notes</a>
					
				<?}
				$limit = 8;
				?>
		</div>
		<div id="dashui" class="threeColumnContainer">
			<div class="threecol col1 bluebox"
				style="border-width:0; border-top-width:1px; width: 32%; margin-right: 1.5%">
				<div class="widget ui-corner-all">
					<div class="itemHolder" onclick="showIdeas()">
						<h2>ideate</h2>
						<span style="color:#777">record, manage and explore ideas to help them take shape</span>
					</div>
					<div class="dashResults">
					<? renderDashIdeas($userid, $limit)?>
					</div>
				</div>
			</div>
			<div class="threecol col2 greenbox"
				style="border-width:0; border-top-width:1px; width: 32%; margin-right: 1.5%">
				<div class="widget ui-corner-all">
					<div class="itemHolder" onclick="showCompare()">
						<h2>compare</h2>
						<span style="color:#777">contrast and compare your existing ideas</span>
					</div>
					<div class="dashResults">
					<? renderDashCompare($userid, $limit);?>
					</div>
				</div>
			</div>
			<div class="threecol col3 orangebox" style="border-width:0; border-top-width:1px; width: 32%; margin-right: 0">
				<div class="widget ui-corner-all">
					<div class="itemHolder" onclick="showSelect()">
						<h2>select</h2>
						<span style="color:#777">choose ideas to work on and manage their tasks.</span>
					</div>
					<div class="dashResults">
					<? renderDashSelect($userid, $limit);?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
