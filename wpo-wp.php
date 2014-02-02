<?php
/*======================================================
----------------------------------------------------
****** - The WebP Implementation Module - Part I **********
----------------------------------------------------
This Module converts image files to WebP format.
-------------------------------
Changelog
-------------------------------
version 1: directory under test contains 119 images and takes roughly 43 seconds to convert. 
version 2: directory under test contains 119 images and takes roughly 33 seconds to convert. 

Note: THE TIME TAKEN TO CONVERT CAN VARY BASED UPON THE FORMAT OF IMAGE PRESENT IN MAJORITY
-------------------------------
Important Notes
-------------------------------
set_time_limit used extend time limit to 120s
all functions have been reduced to single function to reduce execution time
=========================================================*/
include('header.php'); 

//the old main function
function wpo_webp_main_old($dir) {
	$xml = simplexml_load_file($dir); // load the xml file
	$wp_list = array(); // create an empty array for SimpleXMLObjects
	$wp_list = array_merge($wp_list, $xml->xpath("/wpo/file")); // add paths of files to $list array
	foreach($wp_list as $value) {
		$path = str_replace("..", "out", $value->path);// path to destination file in "out" folder 
		unlink($path); //delete unconverted image file in "out" folder
		$path = str_replace(pathinfo($path, PATHINFO_EXTENSION), "webp", $path); // prepeare destination file with .webp extension
		exec("cwebp ".$value->path." -o ".$path. " -q 80"); // execute external command cwebp to convert given image to webp
	}
}

//the new main function - faster conversion
function wpo_webp_main($dir) {
	$xml = simplexml_load_file($dir); // load the xml file
	$wp_list = array(); // create an empty array for SimpleXMLObjects
	$wp_list = array_merge($wp_list, $xml->xpath("/wpo/file")); // add paths of files to $list array
	foreach($wp_list as $value) {
		$path = str_replace("..", "out", $value->path);// path to destination file in "out" folder 
		unlink($path); //delete unconverted image file in "out" folder
		$path = str_replace(pathinfo($path, PATHINFO_EXTENSION), "webp", $path); // prepeare destination file with .webp extension
		/*let php - GD function handle jpg conversions */
		if(((pathinfo($value->path, PATHINFO_EXTENSION)) == "jpg")||((pathinfo($value->path, PATHINFO_EXTENSION)) == "jpeg")) {
			imagewebp(imagecreatefromjpeg($value->path), $path);
		}
		/*cwebp tool handles png/gif conversions to preserve transparency and smoothness*/
		else { 
			exec("cwebp ".$value->path." -o ".$path. " -q 80"); // execute external command cwebp to convert given image to webp
		}
	}
}

//extending execution time limit 
set_time_limit (120);
//calling main function
wpo_webp_main("temp/img.xml");
?>
<div class="button" style="float:right;"><a href="wpo-wp2.php">Proceed to Next Step</a></div>
<?php include('footer.php'); ?>