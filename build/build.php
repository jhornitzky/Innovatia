<?php
////CONFIG/////////////////
$parts = array('core', 'ui', 'users', 'xi', 'index.php', 'slide.php', 'favicon.ico'); //Files from basedir to copy

/////FUNCTIONS /////////
function recursiveCopy($src,$dst) {
	if (is_dir($src)) {
		$dir = opendir($src);
		@mkdir($dst);
		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				if ( is_dir($src . '/' . $file) ) {
					recursiveCopy($src . '/' . $file,$dst . '/' . $file);
				}
				else {
					copy($src . '/' . $file,$dst . '/' . $file);
				}
			}
		}
		closedir($dir);
	} else if (is_file($src)) {
		copy($src,$dst);
	}
}

function recursiveDelete($str){
	if (is_file($str)) {
		return @unlink($str);
	} else if (is_dir($str)){
		$scan = glob(rtrim($str,'/').'/*');
		foreach($scan as $index=>$path){
			recursiveDelete($path);
		}
		return @rmdir($str);
	}
}

/**
 * Recursively find files
 */
function recursiveFileFind($url, $query, $replace, &$files, $strict = false) {
	$path = rtrim(str_replace("\\", "/", $url), '/') . '/*';
	foreach (scandir($url) as $name) {
		$fullname = $url.'/'.$name;
		if (!preg_match('/^\.$|^\.\.$/',basename($fullname))) {
			if ($strict) {
				if (basename($fullname) == $query) {
					if (isset($replace))
					$fullname = str_replace($replace, '', $fullname);
					array_push($files,$fullname);
				}
			} else {
				if (stripos(basename($fullname), $query) !== false) {
					if (isset($replace))
					$fullname = str_replace($replace, '', $fullname);
					array_push($files,$fullname);
				}
			}
			if (is_dir($fullname)) {
				recursiveFileFind($url.'/'.$name, $query, $replace, $files, $strict);
			}
		}
	}
}

/////MAIN///////////////////
echo "the nexos builder for simple php projects";
echo "syntax  : php build.php [--mode='<mode>'] [--zipDir='<zipDir>'] [--basepath='<pathLocation>']";
echo "example : php build.php --zipDir='../build_XXX' --basepath='/core/other'";

$shortopts = "";
$longopts  = array(
    "mode::",    
    "zipDir::",
    "basepath::",
);

$options = getopt($shortopts, $longopts);

if (isset($options['mode']))
$mode = $options['mode']; //FIXME get mode stuff happening

echo "------------------------------------------------------\n";
echo 'Building with args: ' . implode(' ', $_SERVER['argv']);
echo "\n\n";

$zipDir='';
if (isset($options['zipDir']))
$zipDir = $options['zipDir'];
else
$zipDir = dirname(__FILE__)."/../../build".str_replace(' ', '', microtime());
echo $zipDir;

//start with clearing files
echo "Preclean\n";
recursiveDelete($zipDir);
mkdir($zipDir);

//then copy
echo "Copying\n";
foreach ($parts as $part) {
	recursiveCopy(dirname(__FILE__)."/../".$part, $zipDir.'/'.$part);
}

//and clean
echo "Postclean\n";
shell_exec('git rm -r '.$zipDir);

//update paths
if (isset($options['basepath'])) {
	echo "Update paths\n";

	//find files
	$files = array();
	recursiveFileFind($zipDir, 'connector', '', $files);

	//now for each file overwrite require_once
	foreach ($files as $file) {
		$pInfo = pathinfo($file);
		if ($pInfo['extension'] == 'php') {
			$fText = '';

			//Read the file into memory line by line and modify
			$filePointer = fopen($file, "r");
			if (!$filePointer) {
				echo "Couldn't open: " . $file . "\n";
			} else{
				//Output a line of the file until the end is reached
				while(!feof($filePointer))
				{
					$line = fgets($filePointer);
					if (preg_match('/innoworks\.connector\.php/', $line)) { //if has the connector string...
						echo 'Rewrite basepath in file: ' . $file . ' : ' . $options['basepath'] . "\n";
						$fText .= 'require_once($_SERVER["DOCUMENT_ROOT"]."'.$options['basepath'].'core/innoworks.connector.php");';
					} else {
						$fText .= $line;
					}
				}
				fclose($filePointer);
					
				//Now overwrite
				if(!file_put_contents($file, $fText))
					echo 'Failed writing to file: ' . $file;
			}
		}
	}
}

echo "Done!\n";
/////////////////////////////
?>