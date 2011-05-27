<div style="width: 100%;">
	<div class="fixed-left" onclick="showProfile()">
		<h1 style="font-size:30px"><span style="color:#AAA;">hello</span> <?= getDisplayFirstName($_SESSION['innoworks.ID'])?></h1>
		<div style="width:99%; height: 120px; border:1px solid #AAA; position:relative">
			<div style="position:absolute;width:100%;height:120px; background-image:url('retrieveImage.php?action=userImg&actionId=<?= $_SESSION['innoworks.ID'] ?>');opacity:0.25; background-position:center center;"></div>
			<div style="position:absolute;width:100%;height:120px; background-image:url('retrieveImage.php?action=userImg&actionId=<?= $_SESSION['innoworks.ID'] ?>'); background-repeat:no-repeat; background-position:center center;"></div>
		</div>
	</div>
	<div class="fixed-right">
		<div style="width: 100%; margin-bottom: 1em; position:relative;">
			<div style="position:absolute; top:0; right:0; color:#777; text-align:right; font-size:0.75em">need help? click the i up here</div>
			<div class="itemHolder" style="border-top:none;">
				<h2 style="color:#AAA;padding-top:1px;">while you were gone here's what happened...</h2>
			</div>
				<? if ($notes && dbNumRows($notes) > 0) {
					while ($note = dbFetchObject($notes)) { ?>
					<div class="itemHolder">
						<div><?= $note->noteText ?><br/>
							<span>
								<img src="retrieveImage.php?action=userImg&actionId=<?= $note->fromUserId ?>" style="width:1em; height:1em;" /> 
								<a href="javascript:showProfileSummary('<?= $note->fromUserId ?>')">
									<?= getDisplayUsername($note->fromUserId)?>
								</a>
								sent <span><?= $note->createdTime ?></span>
							</span>
						</div>
					</div>
					<?}
				}
				$limit = 6;?>
		</div>
	</div>
</div>
<div style="width: 100%; clear:both; padding-top:1.5em;">
	<div class="fixed-left" style="border-width:0; border-top-width:1px; border-style:solid; border-color:#7FBF4D;">
		<h1><span style="color:#7FBF4D;">add</span> idea</h1>
		<form class="addForm" onsubmit="addIdea(this);return false;">
			<span>Title</span>
			<input name="title" type="text" class="dijitTextBox" /> 
			<span>Description</span>
			<textarea name="serviceDescription" class="dijitTextArea" style="width:100%;"></textarea>
			<input type="button"
				value="+ add" title="Add idea" onclick="addIdea(this); refreshVisibleTab();" /> <input
				style="display: none" type="submit" /> 
			<input type="hidden" name="action" value="addIdea" />
		</form>
	</div>
	<div class="fixed-right">
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
