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
include('header.php'); 

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

wpo_replace_links("temp/php.xml"); // now replace resource references in all php files
wpo_replace_links("temp/html.xml"); // now replace resource references in all html files
wpo_replace_links("temp/css.xml"); // now replace resource references in all css files
wpo_replace_links("temp/js.xml"); // now replace resource references in all php files
?>
<div class="button" style="float:right;"><a href="wpo-io3.php">Proceed to Next Step</a></div>
<?php
include('footer.php'); 
?>