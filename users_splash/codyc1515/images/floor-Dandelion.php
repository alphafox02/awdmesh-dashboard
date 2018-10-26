<?php
$dir = "../../../entry/img/";

if($dh = opendir($dir)) {
	while(($file = readdir($dh)) !== false) {
		if($file != "." && $file != ".." && !is_dir($dir . $file)) {
			//echo $file . "<br />\n";
			echo $file . "|" . base64_encode(file_get_contents($dir . $file)) . "\n\n";
		}
	}
	closedir($dh);
}
?>