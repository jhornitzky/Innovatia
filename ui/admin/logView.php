<?php
require_once('thinConnector.php');
$filename = ini_get('error_log');
echo '<h1>grablog @ '.$filename.'</h1>';

if (isset($_REQUEST['lineMax'])) $lineMax = $_REQUEST['lineMax'];
else $lineMax = 200;

$fl = fopen($filename, "r");
for($x_pos = 0, $ln = 0, $output = array(); fseek($fl, $x_pos, SEEK_END) !== -1; $x_pos--) {
	if ($ln > $lineMax) break; 
    $char = fgetc($fl);
    if ($char === "\n") {
        // analyse completed line $output[$ln] if need be
        $ln++;
        continue;
        }
    $output[$ln] = $char . ((array_key_exists($ln, $output)) ? $output[$ln] : '');
    }
fclose($fl);

foreach($output as $line) {
	echo $line.'<hr/>';
}
?>
