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

//the main function
function wpo_webp($dir) {
	wpo_addweppy(); // function to add the weppy.js fille to all files listed in add-weppy.xml file
	$wp_list = wpo_read($dir); // array with SimpleXMLObjects in XML file
	foreach($wp_list as $value) {
		$path = str_replace("..", "out", $value->path);// path to destination file in "out" folder 
		switch(pathinfo($path, PATHINFO_EXTENSION)) {
			case "jpg": {
				unlink($path); //delete unconverted image file in out folder
				$path = str_replace("jpg", "webp", $path); // prepeare destination file with .webp extension
				imagewebp(imagecreatefromjpeg($value->path), $path);
				break;
			}
			case "jpeg": {
				unlink($path); //delete unconverted image file in out folder
				$path = str_replace("jpeg", "webp", $path); // prepeare destination file with .webp extension
				imagewebp(imagecreatefromjpeg($value->path), $path);
				break;
			}
			case "png": {
				unlink($path); //delete unconverted image file in out folder
				$path = str_replace("png", "webp", $path); // prepeare destination file with .webp extension
				imagewebp(imagecreatefrompng($value->path), $path);
				break;
			}
			case "gif": {
				unlink($path); //delete unconverted image file in out folder
				$path = str_replace("gif", "webp", $path); // prepeare destination file with .webp extension
				imagewebp(imagecreatefromgif($value->path), $path);
				break;
			}
			default: { die ("Unknown Image Extension added to XML file"); break; }
		}
	}
	wpo_replace_links(); //function to replace local image extensions with .webp extension
}
?>