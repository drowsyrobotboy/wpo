<?php
/*======================================================
----------------------------------------------------
****** - The Image optimization Module - Part I for Google Chrome**********
----------------------------------------------------
This Module converts all image files to WebP format 
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

//the main function
function wpo_webp_main($dir) {
	$xml = simplexml_load_file($dir); // load the xml file
	$wp_list = array(); // create an empty array for SimpleXMLObjects
	$wp_list = array_merge($wp_list, $xml->xpath("/wpo/file")); // add paths of files to $list array
	foreach($wp_list as $value) {
		$path = str_replace("..", "out_chrome", $value->path);// path to destination file in "out_chrome" folder 
		unlink($path); //delete unconverted image file in "out_chrome" folder
		$path = str_replace(pathinfo($path, PATHINFO_EXTENSION), "webp", $path); // prepeare destination file with .webp extension
		exec("cd bin && cwebp ../".$value->path." -o ../".$path. " -q 100"); // execute external command cwebp to convert given image to webp
	}
}

//extending execution time limit 
set_time_limit (120);
//calling main function
wpo_webp_main("temp/img.xml");
?>
<div class="button" style="float:right;"><a href="wpo-io2.php">Proceed to Next Step</a></div>
<?php include('footer.php'); ?>