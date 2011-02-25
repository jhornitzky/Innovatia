<?
/**
 * Service for logging errors. Should be configurable to output to multiple log files. 
 * bool error_log  (  string $message  [,  int $message_type = 0  [,  string $destination  [,  string $extra_headers  ]]] ) 
 */

//set_error_handler("custom_warning_handler", E_WARNING);

function custom_warning_handler($errno, $errstr) {
	logDebug($errstr);
}

function getBacktrace() {
	$backtrace = debug_backtrace();
	return $backtrace[1]['file'];
}

function logAudit($msg) {
	global $loglevel;
	if ($loglevel < 1)
  		error_log(" [AUDIT](".$_SERVER['SCRIPT_NAME'] . ") " .$msg);
}

function logDebug($msg) { 
	global $loglevel;
	if ($loglevel < 2)
 		error_log(" [DEBUG](".$_SERVER['SCRIPT_NAME'] . ") " . $msg);
}

function logInfo($msg) {
	global $loglevel;
	if ($loglevel < 3)
  		error_log(" [INFO](".$_SERVER['SCRIPT_NAME'].  ") " .$msg);
}

function logWarning($msg) {
	global $loglevel;
	if ($loglevel < 4)
  		error_log(" [WARN](".$_SERVER['SCRIPT_NAME'] .  ") " .$msg);
}

function logError($msg) {
	global $loglevel;
	if ($loglevel < 5)
  		error_log(" [ERROR](".$_SERVER['SCRIPT_NAME'] . ") " .$msg);
}
?>