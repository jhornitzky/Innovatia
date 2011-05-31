<div id="selectideaform_<?= $idea->ideaId?>" class="selection idea hoverable" title="<?= $idea->title ?>">
	<table>
		<tr>
			<td class="image">
				<img src="retrieveImage.php?action=ideaImg&actionId=<?= $idea->ideaId?>" style="width: 64px; height: 64px" />
			</td>
			<td style="text-align:left;">
				<img src="retrieveImage.php?action=userImg&actionId=<?= $idea->userId?>" style="width: 1em; height: 1em" /> 
				<span class="ideator"><?= getDisplayUsername($idea->userId); ?></span> 
				<span class="ideaoptions"> 
				<?if ($idea->userId == $user) { ?>
					<input type="button" value=" - " onclick="deleteSelectIdea(<?= $idea->selectionId?>)" title="Deselect this idea" /> <?}?>
				</span><br/> 
				<span class="ideatitle">
					<a href="javascript:logAction()" onclick="showIdeaDetails('<?= $idea->ideaId?>');"> <?=trim($idea->title)?></a>
				</span><br/>
				<span style="font-size:0.85em">
				<b><?= dbNumRows($features); ?></b> feature(s) &nbsp; 
				<b><?= dbNumRows($roles); ?></b> role(s) &nbsp; 
				<b><?= dbNumRows($comments);?></b> comment(s) &nbsp; 
				<b><?= dbNumRows($views);?></b> view(s)
				</span>
			</td>
		</tr>
	</table>
	<? if (isset($tasks) && dbNumRows($tasks) > 0) { 
		while ($task = dbFetchObject($tasks)) {?>
		<div class="clearfix" style="font-size:0.75em">
			<div style="float:left; width:64px; text-overflow:ellipsis; height:1.25em; text-align:right; margin-right:7px; overflow:hidden;"><?= $task->feature ?></div>
			<div style="float:left; width:90%;">
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
				<div style="background-color:blue; height:0.5em; margin-top:0.5em; width:<?= $width ?>%; left:<?= $startDays ?>%;"></div>
			</div>
		</div>		
		<?}
	}?>
</div>