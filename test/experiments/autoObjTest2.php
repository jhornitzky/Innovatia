<?php
$time_start = microtime(true);
require_once '../thinConnector.php';

//find file(s)
$startDir = 'hello/';
//$tempDir = 'php://temp/resource=';
//$startDir = '../../core/';
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
		$testObject = new AutoObject($startDir . $fileItem);
		$functions = $testObject->functions;
		
		//Now foreach function, read params and consider execution
		foreach($functions as &$functionItem) {
			echo $functionItem->name . "<br/>";
			$functionParams = $functionItem->getParameters();
			ppPrintR($functionParams);
		}
	}
}

$time_end = microtime(true);
$time = $time_end - $time_start;
echo "Completed in $time seconds";
?>