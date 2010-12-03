<?
require_once("thinConnector.php"); 

$val = 12345;
$iv = createIv();
$encString = encrypt($val, $iv);
$decryptString = decrypt($encString, $iv);
echo $val . ' : ' . $decryptString;
//logDebug("Decrypt string: " . $decryptString);
if ($val == $decryptString) 
	echo "PASS";
else 
	echo "FAIL";
?>