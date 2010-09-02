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
  error_log(" [DEBUG] ".$msg);
}

function logInfo($msg) {
  error_log(" [INFO] ".$msg);
}

function logWarning($msg) {
  error_log(" [WARN] ".$msg);
}

function logError($msg) {
  error_log(" [ERROR] ".$msg);
}

function logAudit($msg) {
  error_log(" [AUDIT] ".$msg);
}
?>
