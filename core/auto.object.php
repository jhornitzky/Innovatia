<? 
/**
 * Dynamically define any object based on existing procedural style file
 * 
 * TODO Have a look at http://www.garfieldtech.com/blog/magical-php-call
 * for more object like behaviour and static calls
 */
class AutoObject {
  	private $filename;
	
	function __construct($filename){
		$this->filename = $filename ;
	}
	
	function __call($name, $arguments) {
		logDebug("auto import " . $this->filename);
		logDebug($name . "(" . implode(', ', $arguments). ")");
		import($this->filename);
		return call_user_func_array($name, $arguments);
	}
}
?>