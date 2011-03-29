<?php
$time_start = microtime(true);
require_once '../thinConnector.php';

//first setup zend
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__)."/../zend/library/");
require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace(dirname(__FILE__)."/../zend/library/");

//include my extender class
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

//find file(s)
$startDir = 'hello/';
//$tempDir = 'php://temp/resource=';
$startDir = '../../core/';
$tempDir = 'temp/';
$fileList = scandir($startDir);

function ppPrintR($data) {
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}

//Now loop through each file, first writing to temp, including and then testing
foreach ($fileList as $key => &$fileItem) {
	if (is_file($startDir . $fileItem)) {
		//Take file and convert it
		$findDir = $startDir . $fileItem;
		echo $startDir . $fileItem ."<br/>";
		
		$inContents = file_get_contents($findDir); 
		$randIden = 'm' . preg_replace('/\.|\s/', '', microtime());
		
		//Replace the <?[php] at the start of the file with <? namespace xyz;
		$inContents = trim($inContents);
		$addString = 'namespace ' . $randIden . '; ';
		
		$longTagPos = strpos($inContents,'<?php');
		$shortTagPos = strpos($inContents,'<?');
		
		if ($longTagPos !== false && $longTagPos < 10) {
			$inContents = str_replace('<?php', '', $inContents);
			$addString = '<?php ' . $addString;
		}
		else if ($shortTagPage !== false && $longTagPos < 10) {
			$inContents = str_replace('<?', '', $inContents);
			$addString = '<? ' . $addString;
		}
		$outContents = $addString . $inContents;
		
		//Now write and require new temp file
		$tempItem = $tempDir . $fileItem;
		file_put_contents($tempItem, $outContents);
		require($tempItem);
		
		//Now do normal things
		$reflectedFile = new Zend_Reflection_File_WithNamespace($tempItem);
		$functions = $reflectedFile->getFunctionsWithNamespace($randIden);
		
		//Now foreach function, read params and consider execution
		foreach($functions as &$functionItem) {
			echo $functionItem->name . "<br/>";
			$functionParams = $functionItem->getParameters();
			ppPrintR($functionParams);
		}
		
		//FIXME should clean here ??
	}
}

$time_end = microtime(true);
$time = $time_end - $time_start;
echo "Completed in $time seconds";
?>