<?php
/*======================================================
----------------------------------------------------
****** - The Code Reorganisation Module - ***********
----------------------------------------------------
This Module reorganises the code to reduce the file size
-------------------------------
Changelog
-------------------------------
01/02 - Removes new-lines and multiple blank spaces
-------------------------------
Important Notes
-------------------------------

=========================================================*/

include('header.php'); 

// function that removes the white spaces contained in the files present in the $dir XML file
function wpo_remspaces($path) {
	$contents = file_get_contents($path); // get the file contents
	$contents = preg_replace('/\s+/', ' ', $contents);// replace multiple spaces with single space
	$destination = str_replace("..", "out", $path);// path to destination file in "out" folder
	file_put_contents($destination,$contents);// copy contents to destination file
}

//function that reads xml file and returns array of SimpleXMLObjects
function wpo_read($dir) {
	$xml = simplexml_load_file($dir); // load the xml file
	$list = array(); // create an empty array for SimpleXMLObjects
	$list = array_merge($list, $xml->xpath("/wpo/file")); // add paths of files to $list array
	return $list; //return the array
}

//function that invokes all reorganisation functions
function wpo_reorg($dir) {
	$list = wpo_read($dir); // array with SimpleXMLObjects in XML file
	foreach($list as $value) {
		wpo_remspaces($value->path);
	}
}

//calling XML files for reorganisation
//wpo_reorg("temp/php.xml"); need to add exclusion of HTML part 
wpo_reorg("temp/js.xml");
wpo_reorg("temp/css.xml");

include('footer.php'); ?>