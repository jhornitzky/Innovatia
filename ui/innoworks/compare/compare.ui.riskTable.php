<? require_once(dirname(__FILE__) . "/../thinConnector.php");  ?>
<table id='riskEvaluation'>
<thead>
<? renderGenericHeaderWithRefData($riskItems, array("ideaId","riskEvaluationId","groupId", "userId", "score", "createdTime", "lastUpdateTime"),"RiskEvaluation","renderRiskItemHeadCallback");?>
</thead>
<tbody>
<? while ($riskItem = dbFetchObject($riskItems)) {
	renderRiskItem($riskItems, $riskItem);
}?>
</tbody>
</table>