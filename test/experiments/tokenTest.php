<?php
require_once '../thinConnector.php';

function ppPrintR($data) {
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}

//first setup zend
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__)."/../zend/library/");
require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace(dirname(__FILE__)."/../zend/library/");

//find file(s)
$startDir = $_SERVER['DOCUMENT_ROOT'] . $serverRoot . 'core/';
$omitList = array('auto.object.php');
$fileList = scandir($startDir);
foreach ($fileList as $key => &$fileItem) {
	//FIXME add omission
	$fileItem = $startDir . $fileItem;
}

//$fileList = array($_SERVER['DOCUMENT_ROOT'] . $serverRoot . 'core/compare.service.php');

//build parse tree for file, ripping out functions
foreach ($fileList as $key => &$fileItem) {
	if (is_file($fileItem)) {
		echo $fileItem . "<br/>";
		require_once($fileItem);
		$reflectedFile = new Zend_Reflection_File($fileItem);
		$functions = $reflectedFile->getFunctions();
		//now foreach function, read params and consider execution
		foreach($functions as &$functionItem) {
			echo $functionItem->name . "<br/>";
			$functionParams = $functionItem->getParameters();
			ppPrintR($functionParams);
		}
	}
}

/*
 foreach ($fileList as $key => &$fileItem) {
 $contents = file_get_contents($fileItem);
 $parsed = token_get_all($contents);
 }
 print_r($parsed);
 */

//FIXME also look at classes and reflection

//tokenize

//go through functions

//execute each function and watch for errors

//report clean or not


?>