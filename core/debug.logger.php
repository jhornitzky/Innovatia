<?
/**
 * Service for logging errors. Should be configurable to output to multiple log files. 
 * bool error_log  (  string $message  [,  int $message_type = 0  [,  string $destination  [,  string $extra_headers  ]]] ) 
 */

//set_error_handler("custom_warning_handler", E_WARNING);

function custom_warning_handler($errno, $errstr) {
	logDebug($errstr);
}

function logDebug($msg) { 
  error_log(" [DEBUG](".$_SERVER['SCRIPT_NAME'] . ") " . $msg);
}

function logInfo($msg) {
  error_log(" [INFO](".$_SERVER['SCRIPT_NAME'] . ") " .$msg);
}

function logWarning($msg) {
  error_log(" [WARN](".$_SERVER['SCRIPT_NAME'] . ") " .$msg);
}

function logError($msg) {
  error_log(" [ERROR](".$_SERVER['SCRIPT_NAME'] . ") " .$msg);
}

function logAudit($msg) {
  error_log(" [AUDIT](".$_SERVER['SCRIPT_NAME'] . ") " .$msg);
}
?>