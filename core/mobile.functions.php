<?
require_once("mobile.detection.php");

//	mobile_device_detect('http://iphone','http://ipad','http://android','http://opera','http://blackberry','http://palm','http://winMobile','http://MobileBrowser','http://DesktopBrowser');

function is_iPhone()
{
	$result = mobile_device_detect(true, false, false, false, false, false, false);
	//echo "iPhone: $result<BR />";
	return  $result;$result;
}

function is_iPad()
{
	$result = mobile_device_detect(false, true, false, false, false, false, false);
	//echo "iPad: $result<BR />";
	return  $result;
}

function is_Android()
{
	$result = mobile_device_detect(false, false, true, false, false, false, false);
	//echo "Android: $result<BR />";
	return  $result;
}

function is_OperaMini()
{
	$result = mobile_device_detect(false, false, false, true, false, false, false);
	//echo "Opera Mini: $result<BR />";
	return  $result;
}

function is_BlackBerry()
{
	$result = mobile_device_detect(false, false, false, false, true, false, false);
	//echo "BlackBerry: $result<BR />";
	return  $result;
}

function is_PalmOS()
{
	$result = mobile_device_detect(false, false, false, false, false, true, false);
	//echo "PalmOS: $result<BR />";
	return  $result;
}

function is_WinMobile()
{
	$result = mobile_device_detect(false, false, false, false, false, false, true);
	//echo "Windows Mobile: $result<BR />";
	return  $result;
}

function isMobile() {
	$deviceTypes = array( array(is_iPhone(), "iPhone"), array(is_Android(), "Android"), array(is_OperaMini(), "Opera Mini"), array(is_BlackBerry(), "BlackBerry"), array(is_PalmOS(), "PalmOS"), array(is_WinMobile(), "Windows Mobile") );
	$mobileType = "Mobile";
	$isMobile=false;

	for ($i = 0; $i < count($deviceTypes); $i++) {
		if ($deviceTypes[$i][0]) {
			$isMobile = true;
		}
	}
	return $isMobile;
}
?>