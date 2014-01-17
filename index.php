<?php
echo "<h2>The following files will be Optimized </h2>";
//function to detect files
function wpo_files($dir) {
	echo "<h3>".$dir." </h3>";
	foreach (glob($dir.'/{*.css,*.js,*.jpg,*.png,*.gif,*.bmp}', GLOB_BRACE) as $filename) {
		echo "$filename of " . filesize($filename) . " bytes<br />";
	}
	wpo_dir($dir);
}
function wpo_dir($dir) {
	foreach (glob($dir.'/*') as $value) {
		if((is_dir($value))&&($value!="../wpo")){
			wpo_files($value);
		}
	}
}
wpo_dir("..");
?>