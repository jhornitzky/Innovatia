<?
/**
 * Service for logging errors. Should be configurable to output to multiple log files.
 * bool error_log  (  string $message  [,  int $message_type = 0  [,  string $destination  [,  string $extra_headers  ]]] )
 */

//set_error_handler("custom_warning_handler", E_WARNING);

//FIXME quickfix
if (!isset($shortTermMemory)) 
	$shortTermMemory = array();

function custom_warning_handler($errno, $errstr) {
	logDebug($errstr);
}

function getBacktrace() {
	$backtrace = debug_backtrace();
	$bt = '';
	for ($i = 0; $i < count($backtrace); $i++) {
		$bt .= basename($backtrace[$i]['file']) . ':' .  $backtrace[$i]['line'] . '-> ';
		if ($i > 5) {
			$bt .= 'and ' . (count($backtrace) - 6) . ' more...';
			$i = count($backtrace);
		}
	}
	return $bt;
}

function logVerbose($msg) {
	global $loglevel, $shortTermMemory;
	if ($loglevel < 1) {
		$logMsg = " [VERB]" .$msg . "\n(" .  getBacktrace() . ')';
		error_log($logMsg);
		array_push($shortTermMemory, $msg);
		return $logMsg;
	}
}

function logAudit($msg) {
	global $loglevel, $shortTermMemory;
	if ($loglevel < 2){
		$logMsg = " [AUDIT]" .$msg . "\n(" .  getBacktrace() . ')';
		error_log($logMsg);
		array_push($shortTermMemory, $msg);
		return $logMsg;
	}
}

function logDebug($msg) {
	global $loglevel, $shortTermMemory;
	if ($loglevel < 3){
		$logMsg = " [DEBUG]". $msg . "(".$_SERVER['SCRIPT_NAME'] . ") " ;
		error_log($logMsg);
		array_push($shortTermMemory, $msg);
		return $logMsg;
	}
}

function logInfo($msg) {
	global $loglevel, $shortTermMemory;
	if ($loglevel < 4){
		$logMsg = " [INFO]". $msg . "(".$_SERVER['SCRIPT_NAME'].  ") ";
		error_log($logMsg);
		array_push($shortTermMemory, $msg);
		return $logMsg;
	}
}

function logWarning($msg) {
	global $loglevel;
	if ($loglevel < 5)
  		error_log(" [WARN]". $msg . "(".$_SERVER['SCRIPT_NAME'] .  ") ");
}

function logError($msg) {
	global $loglevel;
	if ($loglevel < 6)
  		error_log(" [ERROR]". $msg . "(".$_SERVER['SCRIPT_NAME'] . ") ");
}

function logErrorMemory($msg) {
	global $loglevel, $shortTermMemory;
	error_log(" [ERROR](".$_SERVER['SCRIPT_NAME'] . ") " .$msg);
  	array_push($shortTermMemory, array('type' => 'errorMemory','msg' => $msg));
}
?>