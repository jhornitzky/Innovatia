<div id="selectideaform_<?= $idea->ideaId?>" class="selection idea hoverable" title="<?= $idea->title ?>">
	<div style="position:absolute; opacity:0.1; filter: alpha(opacity=10);">
		<img src="engine.ajax.php?action=ideaImg&actionId=<?= $idea->ideaId?>" style="width: 64px; height: 64px" />
	</div>
	<div style="position:absolute; width:98%; padding:1%;">
		<?php renderTemplate('ideator', array('userId' => $idea->userId))?>
			<span class="ideaoptions"> 
					<img onclick="showIdeaSummary(<?= $idea->ideaId?>)" src="<?= $uiRoot . 'style/summary.png'?>">
					<?if ($idea->userId == $user || $_SESSION['innoworks.isAdmin']) { ?>
						<img onclick="deleteSelectIdea(<?= $idea->selectionId?>)" src="<?= $uiRoot . 'style/trash.png'?>">
					<?}?> 
				</span><br/> 
				<span class="ideatitle">
					<a href="javascript:logAction()" onclick="showIdeaDetails('<?= $idea->ideaId?>');"> <?=trim($idea->title)?></a>
				</span><br/>
				<span style="font-size:0.85em; color:#AAA;">
				<b><?= dbNumRows($features); ?></b> features &nbsp; 
				<b><?= dbNumRows($roles); ?></b> roles &nbsp; 
				<b><?= dbNumRows($comments);?></b> comments &nbsp; 
				<b><?= dbNumRows($views);?></b> views
				</span>
	</div>
	<div style="margin-top:4em;">
	<? if (isset($tasks) && dbNumRows($tasks) > 0) { 
		while ($task = dbFetchObject($tasks)) {?>
		<div class="clearfix" style="font-size:0.75em;">
			<div style="float:left; width:64px; overflow:hidden; text-overflow:ellipsis; height:1.25em; text-align:right; margin-right:7px; "><?= $task->feature ?></div>
			<div style="float:left; width:85%; position:relative">
				<?
				$startDays = 1;
				$finishDays = 1;
				if (isset($task->startDate)) {
					$startDays = calculateDateDiff($task->startDate);
				}
				if (isset($task->finishDate)) {
					$finishDays = calculateDateDiff($task->finishDate);
				}
				
				$width = 0;
				if (isset($task->startDate) && isset($task->finishDate))
					$width = $startDays - $finishDays;
				?>
				<div style="background-color:blue; height:0.5em; margin-top:0.5em; width:<?= $width ?>%; left:<?= $startDays ?>%; position:absolute"></div>
			</div>
		</div>		
		<?}
	}?>
	</div>
</div>