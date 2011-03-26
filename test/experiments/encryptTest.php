<?
require_once("thinConnector.php"); 

$val = 75;

echo "<br/>BASIC encryption test<br/>";
$iv = createIv();
$encString = encrypt($val, $iv);
$decryptString = decrypt($encString, $iv);
echo $val . ' : ' . $decryptString;
if ($val == $decryptString) 
	echo "<br/>PASS";
else 
	echo "<br/>FAIL";

echo "<br/>COMPLEX encryption test<br/>";
$encHtmlString = urlencode(base64_encode($encString));
$encHtmlIv = urlencode(base64_encode($iv));
$decHtmlString = base64_decode(urldecode($encHtmlString));
$decHtmlIv = base64_decode(urldecode($encHtmlIv));
$decHtmlStringDec = decrypt($decHtmlString, $decHtmlIv);
echo $val . ' : ' . $decHtmlStringDec;
if ($val == $decHtmlStringDec) 
	echo "<br/>PASS";
else 
	echo "<br/>FAIL";
?>