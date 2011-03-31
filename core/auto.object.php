<?
/**
 * Override of the Zend framework to handle namespaces too
 */
class Zend_Reflection_File_WithNamespace extends Zend_Reflection_File {
	public function getFunctionsWithNamespace($namespace = '', $reflectionClass = 'Zend_Reflection_Function')
	{
		$functions = array();
		foreach ($this->_functions as $function) {
			$newName = $namespace . "\\" . $function;
			$instance = new $reflectionClass($newName);
			if (!$instance instanceof Zend_Reflection_Function) {
				require_once 'Zend/Reflection/Exception.php';
				throw new Zend_Reflection_Exception('Invalid reflection class provided; must extend Zend_Reflection_Function');
			}
			$functions[] = $instance;
		}
		return $functions;
	}
}

/**
 * Dynamically define any object based on existing procedural style file
 * Creates a temporary copy, and then removes it
 * FIXME include the ability to wrap or extend classes?
 */
class AutoObject {
	public $filename;
	public $namespace;
	public $functions;
	public $clashAvoid;

	function __construct($filename, $clashAvoid = true){
		$this->filename = $filename;
		$this->clashAvoid = $clashAvoid;
		
		if ($clashAvoid && is_file($filename)) {
			global $tempRoot;
			
			//Take file and convert it
			$findDir = $filename;
			
			//Get the contents and generate a namespace
			$inContents = file_get_contents($findDir);
			$randIden = 'm' . preg_replace('/\.|\s/', '', microtime());
			$this->namespace = $randIden;

			//Replace the <?[php] at the start of the file with <? namespace xyz;
			$inContents = trim($inContents);
			$addString = 'namespace ' . $randIden . '; ';
			$longTagPos = strpos($inContents,'<?php');
			$shortTagPos = strpos($inContents,'<?');
			if ($longTagPos !== false && $longTagPos < 10) {
				$inContents = str_replace('<?php', '', $inContents);
				$addString = '<?php ' . $addString;
			} else if ($shortTagPage !== false && $longTagPos < 10) {
				$inContents = str_replace('<?', '', $inContents);
				$addString = '<? ' . $addString;
			}
			$outContents = $addString . $inContents;

			//Now write and require new temp file
			//$tempItem = $_SERVER['DOCUMENT_ROOT'] . $tempRoot . basename($filename);
			$tempItem = tempnam(sys_get_temp_dir(), 'ino'); //FIXME watch for write permissions
			file_put_contents($tempItem, $outContents);
			require($tempItem);
			
			//build my own function tree so I know what I have available
			$reflectedFile = new Zend_Reflection_File_WithNamespace($tempItem);
			$this->functions = $reflectedFile->getFunctionsWithNamespace($randIden);
			
			//cleanup
			@unlink($tempItem);
		}
	}

	function __call($name, $arguments) {
		logDebug("autoObjectExec: " . $name);
		if ($this->clashAvoid)
			return call_user_func_array($namespace . '\\' . $name, $arguments);
		else 
			return call_user_func_array($name, $arguments);
	}
}
?>