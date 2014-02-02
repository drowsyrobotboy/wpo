<?php
/*======================================================
----------------------------------------------------
****** - The WebP Implementation Module - **********
----------------------------------------------------
THIS MODULE HAS NO VIEW. IT IS ONLY A COLLECTION OF FUNCTIONS. This Module converts image files to WebP format and replaces their references accordingly. This module also adds the weppy.js file to the required files.
-------------------------------
Important Notes
-------------------------------

=========================================================*/
//function that reads xml file and returns array of SimpleXMLObjects
function wpo_read($dir) {
	$xml = simplexml_load_file($dir); // load the xml file
	$list = array(); // create an empty array for SimpleXMLObjects
	$list = array_merge($list, $xml->xpath("/wpo/file")); // add paths of files to $list array
	return $list; //return the array
}

//function to add the weppy.js fille to all files listed in add-weppy.xml file
function wpo_addweppy() {
	return 1;
}

//function to replace local image extensions with .webp extension
function wpo_replace_links() {
	return 1;
}

//function to convert given image to webp
function wpo_webp($source, $destination) {
	exec("cwebp ".$source." -o ".$destination. " -q 80"); // execute external command cwebp
}

//the main function
function wpo_webp_main($dir) {
	wpo_addweppy(); // function to add the weppy.js fille to all files listed in add-weppy.xml file
	$wp_list = wpo_read($dir); // array with SimpleXMLObjects in XML file
	foreach($wp_list as $value) {
		$path = str_replace("..", "out", $value->path);// path to destination file in "out" folder 
		unlink($path); //delete unconverted image file in out folder
		$path = str_replace(pathinfo($path, PATHINFO_EXTENSION), "webp", $path); // prepeare destination file with .webp extension
		wpo_webp($value->path, $path);
	}
	wpo_replace_links(); //function to replace local image extensions with .webp extension
}
?>