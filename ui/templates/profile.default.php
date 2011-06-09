<div style="width: 100%;">
	<div class="fixed-left">
		<div class="treeMenu" style="margin-bottom:10px;">
			<div class="itemHolder headBorder">
				<h3>your statistics</h3>
			</div>
			<div class="itemHolder">
				<?= $noOfIdeas ?> <span>ideas</span>
				<div style="clear:both; width:<?= $noOfIdeas ?>%; height:3px; background-color:blue; max-width:100%"></div>
			</div>
			<div class="itemHolder">
				<?= $noOfSelectedIdeas ?> <span>selected ideas</span>
				<div style="clear:both; width:<?= $noOfSelectedIdeas ?>%; height:3px; background-color:green; max-width:100%"></div>
			</div>
			<div class="itemHolder">
				<?= $percentOfIdeas ?> <span>selection/idea %</span>
				<div style="clear:both; width:<?= $percentOfIdeas ?>%; height:3px; background-color:red; max-width:100%"></div>
			</div>
			<div class="itemHolder">
				<?= $noOfGroupsCreated?> <span>groups created</span>
				<div style="clear:both; width:<?= $noOfGroupsCreated ?>%; height:3px; background-color:orange; max-width:100%"></div>
			</div>
			<div class="itemHolder" style="margin-bottom:1.5em;">
				<?= $noOfGroupsIn?> <span>groups in</span>
				<div style="clear:both; width:<?= $noOfGroupsIn ?>%; height:3px; background-color:purple; max-width:100%"></div>
			</div>
			<div class="tiny">Similar profiles...</div>
			<div>
				<? renderOtherProfiles($user, $limit) ?>
			</div>
		</div>
	</div>
	<div class="fixed-right">
		<div class="overlay" style="color:#AAA; position:absolute; opacity:0.1; height:8em;">
			<img src="retrieveImage.php?action=userImg&actionId=<?= $_SESSION['innoworks.ID'] ?>" style="height:100%;"/>
		</div>
		<div style="height:4.5em; overflow:hidden;">
			<div class="lefter">
				<h1 style="font-size:3.5em;">
					<?= $userDetails->firstName . ' ' . $userDetails->lastName ?> <span style="color:#AAA"><?= $userDetails->username ?></span>
				</h1>
			</div>
			<div class="righter">
				<p style="font-size: 0.8em; margin: 0; padding: 0; color:#AAA">
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
