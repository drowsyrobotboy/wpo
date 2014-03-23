<?php
/*======================================================
----------------------------------------------------
****** - The Image optimization Module - Part II **********
----------------------------------------------------
This Module replaces images references accordingly and also adds the webpjs.js file to the required files.
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

//function to replace local image extensions with .webp extension
function wpo_replace_links($dir) {
	$list = wpo_read($dir);
	foreach($list as $value) {
		if(!($value->rlist->rname)) { //escape files not containing resources
			continue;
		}
		else {
			foreach($value->rlist->rname as $inner_value) { //for each rname element
				$path = str_replace("..", "out_chrome", $value->path); // target file in "out" file
				$contents = file_get_contents($path); // get contents of the target file
				$changed_value = str_replace(pathinfo($inner_value, PATHINFO_EXTENSION), "webp", $inner_value); // prepare new image link
				$contents = str_replace($inner_value, $changed_value, $contents); //replace image link
				file_put_contents($path,$contents); // place new content in the target file
			}
			
		}
	}
}
//add to log
file_put_contents("temp/io2.log","Initializing replacement process <br/>", FILE_APPEND);
wpo_replace_links("temp/php.xml"); // now replace resource references in all php files
//add to log
file_put_contents("temp/io2.log","Replaced image links in all PHP files <br/>", FILE_APPEND);
wpo_replace_links("temp/html.xml"); // now replace resource references in all html files
//add to log
file_put_contents("temp/io2.log","Replaced image links in all HTML files <br/>", FILE_APPEND);
wpo_replace_links("temp/css.xml"); // now replace resource references in all css files
//add to log
file_put_contents("temp/io2.log","Replaced image links in all CSS files <br/>", FILE_APPEND);
wpo_replace_links("temp/js.xml"); // now replace resource references in all js files
//add to log
file_put_contents("temp/io2.log","Replaced image links in all JS files <br/>", FILE_APPEND);
wpo_replace_links("temp/exclude.xml"); // now replace resource references in all excluded files
//add to log
file_put_contents("temp/io2.log","Replaced image links in all other files <br/>", FILE_APPEND);
file_put_contents("temp/io2.log","Done! Kindly proceed to next step. <br />", FILE_APPEND);
?>
