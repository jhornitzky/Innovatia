<td class="headCol">
	<div class="hoverable">
		<div style="float: left; position: relative">
			<img
				src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=ideaImg&actionId=<?= $riskItem->ideaId?>"
				style="width: 2.5em; height: 2.5em;" />
		</div>
		<span class="itemName"> <a href="javascript:logAction()"
			onclick="showIdeaSummary('<?= $riskItem->ideaId?>')"><?= $value ?>
		</a> </span><br /> <img
			src="<?= $serverUrl . $uiRoot ?>innoworks/retrieveImage.php?action=userImg&actionId=<?= $riskItem->userId?>"
			style="width: 1em; height: 1em;" />
			<?= $name ?>
		<input type="button"
			onclick="deleteRisk('<?= $riskItem->riskEvaluationId ?>');"
			title="Delete this risk item" value=" - " /> <input type="hidden"
			name="riskEvaluationId" value="<?= $riskItem->riskEvaluationId ?>" />
	</div></td>
