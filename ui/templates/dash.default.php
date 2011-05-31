<div style="width: 100%;">
	<div class="fixed-left">
		<h1 style="font-size:30px" onclick="showProfile()"><span style="color:#AAA;">hello</span> <?= getDisplayFirstName($_SESSION['innoworks.ID'])?></h1>
		<div onclick="showProfile()" style="width:99%; height: 120px; border:1px solid #AAA; position:relative">
			<div style="position:absolute;width:100%;height:120px; background-image:url('retrieveImage.php?action=userImg&actionId=<?= $_SESSION['innoworks.ID'] ?>');opacity:0.25; background-position:center center;"></div>
			<div style="position:absolute;width:100%;height:120px; background-image:url('retrieveImage.php?action=userImg&actionId=<?= $_SESSION['innoworks.ID'] ?>'); background-repeat:no-repeat; background-position:center center;"></div>
		</div>
		<div style="border-right: 1px solid #AAAAAA;margin-top:1em;">
			<div class="tiny">latest ideas...</div>
			<?if (isset($ideas) && dbNumRows($ideas) > 0) { 
				while($idea = dbFetchObject($ideas)) { ?>
					<div class='itemHolder clickable clearfix' onclick='showIdeaSummary(<?=$idea->ideaId?>)'>
						<div class="lefter">
							<?= $idea->title ?>
						</div>
						<div class="righter righterImage">
							<img src="retrieveImage.php?action=ideaImg&actionId=<?=$idea->ideaId?>" style="width: 1em; height: 1em" />
						</div>
					</div>
				<?}
			}?>
			<div class="itemHolder" style="margin-bottom:1.5em;">
				<a href="javascript:showInnovate()">+ new idea</a>
			</div>
			<div class="tiny">...and groups</div>
			<?if (isset($groups) && dbNumRows($groups) > 0) { 
				while($group = dbFetchObject($groups)) { ?>
					<div class='itemHolder clickable clearfix' onclick='showGroupSummary(<?=$group->groupId?>)'>
						<div class="lefter">
							<?= $group->title ?>
						</div>
						<div class="righter righterImage">
							<img src="retrieveImage.php?action=groupImg&actionId=<?=$group->groupId?>" style="width: 1em; height: 1em" />
						</div>
					</div>
				<?}
			}?>
			<div class="itemHolder" style="margin-bottom:1.5em;">
				<a href="javascript:showGroups()">+ new group</a>
			</div>
		</div>
	</div>
	<div class="fixed-right">
		<div class="clearfix" style="width: 65%; margin-bottom: 1em; position:relative; float:left;">
			<div class="itemHolder" style="border-top:none;">
				<h2 style="color:#AAA;padding-top:1px;">latest activity...</h2>
			</div>
				<? $count = 0;
				if ($notes && dbNumRows($notes) > 0) {
					while ($note = dbFetchObject($notes)) { 
						$count++;?>
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
					<?}?>
					<a class="loadMore" href="javascript:log(this)" onclick="showProfile();">show and send more notes</a>
				<?}
				
				if ($count < 1) { ?>
					<p style="margin-top:0;">Welcome to innoWorks, the innovation management tool.</p>
					<p>
						innoWorks helps you innovate by giving you a simple yet structured way to manage your ideas, compare them and select them. 
						A good place to start is by hitting the green add button up the top left, or you can move throughout the system at your own pace.
					</p>
					<p>
						If you get stuck you can click on the 'i' icon to the top right for help. 
						Happy ideating!
					</p>
				<?}?>
		</div>
		<div style="position:relative; float:right; width:30%">
			<div style="position:absolute; top:0; right:0; color:#777; text-align:right; font-size:0.75em">need help? click the i up here</div>
			<div style="margin-top:2.25em; padding:0.25em; background-color:#EEE">Getting started</div>
			<ul style="list-style: square; font-size:0.9em; padding-left:1.75em; margin-top:0.5em;">
				<li onclick="goTo('http://www.youtube.com/user/thoughtthinkers#p/u/4/X14S99KFn2A')"><a href="javascript:log(this)">Watch the tour</a></li>
				<li onclick="showHelp()"><a href="javascript:log(this)">Read the docs</a></li>
				<li onclick="showSearch()"><a href="javascript:log(this)">Find ideas and people</a></li>
				<li onclick="showFeedback(this)"><a href="javascript:log(this)">Give feedback</a></li>
			</ul>
			<div style="margin-top:2.25em; padding:0.25em; background-color:#EEE">Jump in</div>
			<ul style="list-style: square; font-size:0.9em; padding-left:1.75em; margin-top:0.5em;">
				<li onclick="showInnovate()"><a href="javascript:log(this)">Add an idea</a></li>
				<li onclick="showGroups()"><a href="javascript:log(this)">Create a group</a></li>
				<li onclick="showInnovate();showIdeas()"><a href="javascript:log(this)">Ideate</a></li>
				<li onclick="showInnovate();showCompare()"><a href="javascript:log(this)">Compare</a></li>
				<li onclick="showInnovate();showSelect()"><a href="javascript:log(this)">Select</a></li>
			</ul>
			<div style="margin-top:2.25em; padding:0.25em; background-color:#EEE">Openly innovate</div>
			<div style="padding-left:0.5em; font-size:0.9em;padding-top:0.5em"><? renderTemplate('openInnovation'); ?></div>
		</div>
	</div>
</div>