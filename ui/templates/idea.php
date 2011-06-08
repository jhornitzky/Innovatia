<div id="ideaform_<?= $idea->ideaId?>" class="idea hoverable" title="<?= $idea->title ?>">
	<table>
		<tr>
			<td class="image">
				<img src="retrieveImage.php?action=ideaImg&actionId=<?= $idea->ideaId?>" style="width: 64px; height: 64px" />
			</td>
			<td>
				<? renderTemplate('ideator', array('userId' => $idea->userId)); ?>
				<span class="ideaoptions"> 
					<img onclick="showIdeaSummary(<?= $idea->ideaId?>)" src="<?= $uiRoot . 'style/summary.png'?>">
					<?if ($idea->userId == $user || $_SESSION['innoworks.isAdmin']) { ?>
						<img onclick="deleteIdea(<?= $idea->ideaId?>)" src="<?= $uiRoot . 'style/trash.png'?>">
					<?}?> 
				</span><br/> 
				<span class="ideatitle"> 
					<a href="javascript:logAction()" onclick="showIdeaDetails('<?= $idea->ideaId?>');"><?=trim($idea->title)?></a>
				</span><br/>
				<span class="ideadescription" style="color:#777; font-size:0.85em; overflow:hidden; text-overflow:ellipsis">
					<?= htmlspecialchars($idea->proposedService) ?>		
				</span>
			</td>
		</tr>
	</table>
</div>