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

//function that removes comments and replaces with one blank character
function wpo_remcomments($path) {
	$contents = file_get_contents($path);
	$contents = preg_replace('!^[ \t]*/\*.*?\*/[ \t]*[\r\n]!s', ' ', $contents); //  Removes multi-line comments
	$contents = preg_replace('![ \t]*//.*[ \t]*[\r\n]!', ' ', $contents); //  Removes single line '//' comments, treats blank characters
	$contents = preg_replace('!\/\*[^*]*\*+([^/*][^*]*\*+)*\/!', ' ', $contents); // css-comments removal - creepy :P
	file_put_contents($path,$contents);// copy contents to destination file
}

// function that removes the white spaces contained in the files present in the $dir XML file
function wpo_remspaces($path) {
	$contents = file_get_contents($path); // get the file contents
	$contents = preg_replace('/\s+/', ' ', $contents);// replace multiple spaces with single space
	file_put_contents($path,$contents);// copy contents to destination file
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
		$path = str_replace("..", "out", $value->path);// path to destination file in "out" folder 
		wpo_remcomments($path); //remove comments
		if ($dir == "temp/php.xml"){return 1;} //do not remove blank spaces for php files
		else { wpo_remspaces($path);} //remove blank spaces for other files
	}
}

//calling XML files for reorganisation
wpo_reorg("temp/php.xml"); 
wpo_reorg("temp/js.xml");
wpo_reorg("temp/css.xml");

include('footer.php'); ?>