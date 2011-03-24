<div style="width: 100%;">
	<div class="fixed-left">
		<div class="treeMenu">
			<div class='itemHolder headBorder' style='background-color: #DDD'>Similar
				profiles</div>
			<div>
			<? renderOtherProfiles($user, $limit) ?>
			</div>
		</div>
	</div>
	<div class="fixed-right">
		<div class='itemHolder headBorder treeMenu' style="height: 7em;">
			<div class="lefter lefterImage">
				<img
					src="retrieveImage.php?action=userImg&actionId=<?= $_SESSION['innoworks.ID'] ?>"
					style="width: 5em; height: 5em;" />
			</div>
			<div class="lefter">
				<h3>
				<?= $userDetails->firstName . ' ' . $userDetails->lastName . ' / ' . $userDetails->username ?>
				</h3>
				<?= $userDetails->organization ?>
			</div>
			<div class="righter" style="padding-left: 0.5em;">
				<span class="timestamp">Joined <?= $userDetails->createdTime ?>
				</span> | <a href="javascript:logAction()"
					onclick="printUser('&profile=<?= $userDetails->userId ?>')"> Print
				</a>
				</p>
				<p style="font-size: 0.8em; margin: 0; padding: 0;">
					Share your profile with a friend at:<br />
					<?= $shareUrl ?>
				</p>
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
