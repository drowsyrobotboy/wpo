<?php
/*======================================================
----------------------------------------------------
****** - The Image optimization Module - Part III -- For other browsers **********
----------------------------------------------------
This Module converts optimizes jpg/png/gif images.
-------------------------------
Changelog
-------------------------------
-------------------------------
Important Notes
-------------------------------
set_time_limit used extend time limit to 120s
all functions have been reduced to single function to reduce execution time
=========================================================*/
include('header.php'); 

//the new main function - faster conversion
function wpo_webp_main($dir) {
	$xml = simplexml_load_file($dir); // load the xml file
	$wp_list = array(); // create an empty array for SimpleXMLObjects
	$wp_list = array_merge($wp_list, $xml->xpath("/wpo/file")); // add paths of files to $list array
	foreach($wp_list as $value) {
		$path = str_replace("..", "out", $value->path);// path to destination file in "out" folder
		if(((pathinfo($value->path, PATHINFO_EXTENSION)) == "jpg")||((pathinfo($value->path, PATHINFO_EXTENSION)) == "jpeg")) {
			unlink($path); //delete unconverted jpg image file in "out" folder
			imagejpeg(imagecreatefromjpeg($value->path), $path, 70);
		}
		else if((pathinfo($value->path, PATHINFO_EXTENSION)) == "png") {
			unlink($path); //delete unconverted png image file in "out" folder
			$png = imagecreatefrompng($value->path);
			imagealphablending($png, false);
			imagesavealpha($png, true);  
			imagepng($png,$path,9);
		}
	}
}

//extending execution time limit 
set_time_limit (120);
//calling main function
wpo_webp_main("temp/img.xml");
?>
<div class="button" style="float:right;"><a href="#">Done!</a></div>
<?php include('footer.php'); ?>