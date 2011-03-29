<? namespace m0227139001301402067;  
/**
 * Dynamically define any object based on existing procedural style file

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