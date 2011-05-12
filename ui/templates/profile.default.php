<div style="width: 100%;">
	<div class="fixed-left">
		<div class="treeMenu" style="margin-bottom:10px;">
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
			<div class="itemHolder" style="margin-bottom:1.5em;">
				<?= $noOfGroupsIn?> <span>groups in</span>
			</div>
			<div class="tiny">Similar profiles...</div>
			<div>
				<? renderOtherProfiles($user, $limit) ?>
			</div>
		</div>
	</div>
	<div class="fixed-right">
		<div style="height:5em; border-bottom:1px solid #DDD;">
			<div class="lefter lefterImage">
				<img src="retrieveImage.php?action=userImg&actionId=<?= $_SESSION['innoworks.ID'] ?>"
					style="width: 3.5em; height: 3.5em;" />
			</div>
			<div class="lefter">
				<h1><?= $userDetails->firstName . ' ' . $userDetails->lastName ?> <span style="color:#AAA"><?= $userDetails->username ?></span></h1>
				<?= $userDetails->organization ?>
			</div>
			<div class="righter">
				<p style="font-size: 0.8em; margin: 0; padding: 0;">
					Share your profile with a friend <a href="<?= $shareUrl ?>">here</a>
				</p>
				<div style="float:left;cursor:pointer" onclick="printUser('&profile=<?= $userDetails->userId ?>')"><img src="<?= $serverRoot?>ui/style/social/printIcon.jpg" style="width:32px; height:32px"/></div>
				<? renderTemplate('shareBtns', null); ?>
			</div>
		</div>

		<ul class="submenu">
			<li class="greybox"><a href="javascript:logAction()"
				onclick="showProfileNotes(this)">Notes</a>
			</li>
			<li class="greybox"><a href="javascript:logAction()"
				onclick="showProfileSubDetails(this)">Details</a>
			</li>
			<li class="bluebox"><a href="javascript:logAction()"
				onclick="showProfileIdeate(this)">Ideate</a>
			</li>
			<li class="bluebox"><a href="javascript:logAction()"
				onclick="showProfileCompare(this)">Compare</a>
			</li>
			<li class="bluebox"><a href="javascript:logAction()"
				onclick="showProfileSelect(this)">Select</a>
			</li>
		</ul>

		<div class="profileInfo" style="margin-top: 2em"></div>
	</div>
</div>
