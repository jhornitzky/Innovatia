<?
import("innoworks.connector");

function getTasksForIdea($id) {
	return dbQuery("SELECT Features.*, Tasks.effort, Tasks.complete, Tasks.startDate, Tasks.finishDate FROM Features LEFT JOIN Tasks ON Tasks.featureId = Features.featureId WHERE Features.ideaId='$id'");
}

function updateOrCreateTask($opts) {
	//Check of existing task
	$task = dbQuery('SELECT * FROM Tasks WHERE featureId = ' . $opts['featureId']);
	
	//create or update depending on if task exists
	if (isset($task) && dbNumRows($task) > 0) {
		return genericUpdate('Tasks', $opts, array('featureId'));
	} else {
		return genericCreate('Tasks', $opts);
	}
}
?>