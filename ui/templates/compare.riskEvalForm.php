<?if ($item && dbNumRows($item) > 0) {
	$item = dbFetchObject($item);
	$canEdit = false;
	if (hasEditAccessToIdea($ideaId, $userId) || $_SESSION['innoworks.isAdmin'])
	$canEdit = true;?>
<form id="ideaRiskEvalDetails">
<?if ($canEdit)
renderGenericUpdateFormWithRefData(array(), $item, array("riskEvaluationId","groupId","userId","ideaId", "score", "createdTime", "lastUpdateTime"), "RiskEvaluation");
else
renderGenericInfoForm(array(), $item, array("riskEvaluationId","groupId","userId","ideaId", "score", "createdTime", "lastUpdateTime"));?>
	<input type="hidden" name="riskEvaluationId"
		value="<?= $item->riskEvaluationId ?>" /> <input type="hidden"
		name="action" value="updateRiskEval" />
</form>
<a href='javascript:showCompare(); dijit.byId("ideasPopup").hide()'>Compare</a>
<?} else {?>
<p>No compare data for idea</p>
<p>
	Add comparison data for idea <a
		onclick="addRiskItem('<?= $ideaId ?>');loadPopupShow()"
		href="javascript:logAction()">now</a>
</p>
Go to
<a href='javascript:showCompare(); dijit.byId("ideasPopup").hide()'>Compare</a>
<?} ?>