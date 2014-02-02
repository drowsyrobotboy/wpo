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

//function to add the webpjs.js fille to all files listed in add-webpjs.xml file
function wpo_addwebpjs() {
	if(!copy("lib/webpjs-0.0.2.min.js","out/webpjs.js")) { die ("error in copying webpjs to output folder");} //copy webpjs.min.js file to "out" directory
	$list = wpo_read('temp/add-webpjs.xml');
	foreach($list as $value) {
		/* escape js or css files as they don't need webpjs.js */
		if(((pathinfo($value, PATHINFO_EXTENSION)) == "js")||((pathinfo($value, PATHINFO_EXTENSION)) == "css")) {
			continue;
		}
		$level = (substr_count($value,"/"))-1; //get the level of the file ... no of levels from the root directory
		$path = str_replace("..", "out", $value); // path to target file
		$script="<script src='";
		while($level>0) { 
			$script.="../"; // place "../" for each level
			$level--;
		} 
		$script.="webpjs.js' type='text/javascript'></script>";
		$contents = file_get_contents($path);
		/* add script tag before body tag if body tag is found else (if file is a partial) place at the end of the file*/
		if(stristr($contents, "</body>")) {
			$script.="\n</body>";
			$contents = str_replace("</body>", $script, $contents);
			file_put_contents($path,$contents);
		}
		else { 
			file_put_contents($path,$script,FILE_APPEND);
		}
		
	}
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
				$path = str_replace("..", "out", $value->path); // target file in "out" file
				if(((pathinfo($inner_value, PATHINFO_EXTENSION)) == "jpg")||((pathinfo($inner_value, PATHINFO_EXTENSION)) == "jpeg")) {
					$contents = file_get_contents($path); // get contents of the target file
					$changed_value = str_replace(pathinfo($inner_value, PATHINFO_EXTENSION), "webp", $inner_value); // prepare new image link
					$contents = str_replace($inner_value, $changed_value, $contents); //replace image link
					file_put_contents($path,$contents); // place new content in the target file
				}
			}
			
		}
	}
}

wpo_addwebpjs(); //call the addwebpjs function
wpo_replace_links("temp/php.xml"); // now replace resource references in all php files
wpo_replace_links("temp/html.xml"); // now replace resource references in all html files
wpo_replace_links("temp/css.xml"); // now replace resource references in all css files
wpo_replace_links("temp/js.xml"); // now replace resource references in all php files
include('footer.php'); 
?>