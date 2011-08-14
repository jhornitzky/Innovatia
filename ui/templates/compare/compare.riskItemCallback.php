<td class="headCol">
	<div style="position:absolute; opacity:0.1; filter: alpha(opacity=10);">
		<img src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=ideaImg&actionId=<?= $riskItem->ideaId?>" style="width: 2.5em; height: 2.5em;" />
	</div>
	<div class="hoverable">
		<span class="itemName"> 
		<a href="javascript:logAction()"
			onclick="showIdeaDetails('<?= $riskItem->ideaId?>')"><?= $value ?></a> 
		</span><br/> 
		<?php renderTemplate('ideator', array('userId' => $riskItem->userId))?>	
		<span class="ideaOptions" style="position:relative">
			<img onclick="showIdeaSummary(<?= $riskItem->ideaId?>)" src="<?= $uiRoot . 'style/summary.png'?>">
			<?if ($idea->userId == $user || $_SESSION['innoworks.isAdmin']) { ?>
				<img onclick="deleteRisk('<?= $riskItem->riskEvaluationId ?>');" src="<?= $uiRoot . 'style/trash.png'?>">
			<?}?> 
		</span>
	</div>
</td>
