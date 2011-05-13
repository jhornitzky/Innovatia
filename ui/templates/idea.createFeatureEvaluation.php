<form id="addFeatureEvaluationContainer_<?= $idea->ideaId?>"
	class="addForm">
	<span>New feature evaluation</span> 
	<input type="text" class="dijitTextBox" name="title" />
	<input type="hidden" name="ideaId" value="<?= $idea->ideaId?>" /> 
	<input type="hidden" name="action" value="createFeatureEvaluation" /> 
	<input type="button"
		onclick="addFeatureEvaluation('addFeatureEvaluationContainer_<?= $idea->ideaId?>');"
		value=" + "/>
</form>