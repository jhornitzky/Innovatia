<div style="width: 100%;">
	<div class="fixed-left">
		<div onclick="showProfile()" style="height: 150px; border:1px solid #AAA; position:relative">
			<div style="position:absolute;width:100%;height:150px; background-image:url('retrieveImage.php?action=userImg&actionId=<?= $_SESSION['innoworks.ID'] ?>');opacity:0.1; background-position:center center;"></div>
			<div style="position:absolute;width:100%;height:150px; background-image:url('retrieveImage.php?action=userImg&actionId=<?= $_SESSION['innoworks.ID'] ?>'); opacity:0.8; background-repeat:no-repeat; background-position:center center;">
				<h1>hello<br/><?= getDisplayFirstName($_SESSION['innoworks.ID'])?></h1>
			</div>
		</div>
		<div style="border-right:1px solid #CCC; margin-top:1em;">
			<?
			if (isset($ideas) && dbNumRows($ideas) > 0) { 
				while($idea = dbFetchObject($ideas)) { ?>
					<div class="infoBox clickable clearfix" title="<?= $idea->title ?>" onclick='showIdeaSummary(<?=$idea->ideaId?>)'>
						<img src="retrieveImage.php?action=ideaImg&actionId=<?=$idea->ideaId?>"/>
					</div>
				<?}
			}
			
			if (isset($groups) && dbNumRows($groups) > 0) { 
				while($group = dbFetchObject($groups)) { ?>
					<div class="infoBox clickable clearfix" title="<?= $group->title ?>" onclick='showGroupSummary(<?=$group->groupId?>)'>
						<img src="retrieveImage.php?action=groupImg&actionId=<?=$group->groupId?>"/>
					</div>
				<?}
			}?>
			<div style="clear:both"></div>
		</div>
	</div>
	<div class="fixed-right">
		<div class="tiles" style="border:none;">
			<div class="tile" onclick="showInnovate(); showIdeas()" style="background-image:url('../style/cube.png')">
				<div class="tileTitle">ideas</div>
				<div class="tileTotal"><?= $countIdeas ?></div>
				<div class="tileHit" onclick="showCreateIdea(this)">&oplus;</div>
			</div>	
			<div class="tile" onclick="showInnovate(); showCompare()">
				<div class="tileTitle">compare</div>
				<div class="tileTotal"><?= $countItems ?></div>
			</div>
			<div class="tile" onclick="showInnovate(); showSelect()">
				<div class="tileTitle">select</div>
				<div class="tileTotal"><?= $countSelections ?></div>
			</div>	
			<div class="tile" onclick="showGroups()" style="background-image:url('../style/group.png')">
				<div class="tileTitle">groups</div>
				<div class="tileTotal"><?= $countGroups ?></div>
				<div class="tileHit" onclick="showCreateNewGroup(this)">&oplus;</div>
			</div>	
		</div>
		<div>
			<div class="clearfix" style="width: 65%; margin-bottom:1em; position:relative; float:left; padding-top:5px;">
				<div class="itemHolder" style="border:none;">
					<h1 class="dashNoteControl" style="font-size:30px; margin-top:0;"><span class="selected" onclick="getDashPersonal(this)" style="font-size:1em;"><img src="../style/user.png"/></span> <span onclick="getDashPublic(this)" style="font-size:1em;"><img src="../style/public.png"/></span> activity</h1>
				</div>
				<div class="dashNote">
					<? renderTemplate('notes.dash', array('notes' => $notes));?> 
				</div>
			</div>
			<div style="position:relative; float:right; width:31%; padding-top:5px;">
				<h1 style="color:#AAA; font-size:30px; margin-left:-10px;" onclick="showInnovate();">jump in &rarr;</h1>
				<div class="tiny">or get started...</div>
				<ul class="dash" style="list-style: none; font-size:0.9em; padding-left:0; margin-top:0.5em;">
					<li onclick="goTo('http://www.youtube.com/user/thoughtthinkers#p/u/4/X14S99KFn2A')"><h3><a href="javascript:log(this)">Watch the tour</a></h3></li>
					<li onclick="showProfile()"><h3><a href="javascript:log(this)">Setup your profile</a></h3></li>
					<li onclick="showSearch()"><h3><a href="javascript:log(this)">Find ideas and people</a></h3></li>
					<li onclick="showHelp()"><h3><a href="javascript:log(this)">Read the docs</a></h3></li>
					<li onclick="showFeedback(this)"><h3><a href="javascript:log(this)">Give feedback</a></h3></li>
				</ul>
			</div>
		</div>
	</div>
</div>