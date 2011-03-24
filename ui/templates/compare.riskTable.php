<table id='riskEvaluation' style='border-top:1px solid #EEE;border-left:1px solid #EEE;'>
<thead>
<? renderGenericHeaderWithRefData($riskItems, array("ideaId","riskEvaluationId","groupId", "userId", "score", "createdTime", "lastUpdateTime"),"RiskEvaluation","renderRiskItemHeadCallback");?>
</thead>
<tbody>
<? while ($riskItem = dbFetchObject($riskItems)) {
	renderRiskItem($riskItems, $riskItem);
}?>
</tbody>
</table>