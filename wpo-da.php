<?php
/*======================================================
----------------------------------------------------
****** - The Directory Analysis Module - ***********
----------------------------------------------------
This Module handles identification of files and creating wpo_temmp folder with php.xml, js.xml, css.xml, html.xml, img.xml and exclude.xml files.

-------------------------------
changelog
-------------------------------
- adds all php and html files to add-webpjs.xml by default (even if they dont have images)
- do not convert png/gif files
-------------------------------
Important Notes
-------------------------------

param2 in wpo_detect() method uses the following values to detect a particular type of files
1-PHP
2-JS
3-CSS
4-HTML
5-Images
6-Other files
=========================================================*/
?>

<html>
    <head>
        <style>
            body {
             font-family: monospace;
             color: #555;
             padding:10px;
            }
            h2 {
                font-weight: 300;
                font-size:18px;
            }
            h4 {
                font-weight: 200;
                font-size:16px;
            }
            .da-table {
              background: #ddd;
            }

            .da-header td {
              background: #2990e2;
            }

            .da-folder td {
                background: #73b4e8;
            }

            .da-folder td h4 {
              padding: 0px;
                margin:0px;
            }

            .da-file td {
              background: #efefef;
            }    
        </style>
    </head>
<body>
<?php
//function to create all XML files and open root element
function wpo_openXMLs() {
	file_put_contents("temp/php.xml","<wpo>\n", FILE_APPEND);
	file_put_contents("temp/js.xml","<wpo>\n", FILE_APPEND);
	file_put_contents("temp/css.xml","<wpo>\n", FILE_APPEND);
	file_put_contents("temp/html.xml","<wpo>\n", FILE_APPEND);
	file_put_contents("temp/img.xml","<wpo>\n", FILE_APPEND);
	file_put_contents("temp/exclude.xml","<wpo>\n", FILE_APPEND);
	file_put_contents("temp/add-webpjs.xml","<wpo>\n", FILE_APPEND);
    //add to log
    file_put_contents("temp/da.log","Initialized XML files for storage <br />", FILE_APPEND);
}

//function to close root element in all XML files
function wpo_closeXMLs() {
	file_put_contents("temp/php.xml","</wpo>", FILE_APPEND);
	file_put_contents("temp/js.xml","</wpo>", FILE_APPEND);
	file_put_contents("temp/css.xml","</wpo>", FILE_APPEND);
	file_put_contents("temp/html.xml","</wpo>", FILE_APPEND);
	file_put_contents("temp/img.xml","</wpo>", FILE_APPEND);
	file_put_contents("temp/exclude.xml","</wpo>", FILE_APPEND);
	file_put_contents("temp/add-webpjs.xml","</wpo>", FILE_APPEND);
    //add to log
    file_put_contents("temp/da.log","Closed XML files <br />", FILE_APPEND);
}

function wpo_initial($dir) {
	//creating the "temp" directory to store temporary files through out the process
	if(!mkdir("temp")) {
		die("<h1>Error in creating Temporary Storage Module</h1>");
	}
    //add to log
    file_put_contents("temp/da.log","Created 'temp' directory <br />", FILE_APPEND);
	//creating the "out" directory to store output files through out the process
	if(!mkdir("out")) {
		die("<h1>Error in creating output folder</h1>");
	}
	//add to log
    file_put_contents("temp/da.log","Created 'out' directory <br />", FILE_APPEND);
	//creating the "out_chrome" directory to store output files through out the process (specialized for Google Chrome)
	if(!mkdir("out_chrome")) {
		die("<h1>Error in creating output folder</h1>");
	}
	//add to log
    file_put_contents("temp/da.log","Created 'out_chrome' directory <br />", FILE_APPEND);
	//calling subfolders function
	wpo_initialsub($dir);
	wpo_initialsub2($dir);
	
}

//creating initial subfolders
function wpo_initialsub($dir){
	foreach (glob($dir.'/*') as $value) {
		if((is_dir($value))&&($value!="../wpo")){
			$new_value = str_replace("..", "out", $value);
			if(!mkdir($new_value)) {
				die("<h1>Error in creating".$new_value."folder</h1>");
			}
            //add to log
            file_put_contents("temp/da.log","Created ".$new_value." directory <br />", FILE_APPEND);
			wpo_initialsub($value);
		}
	}
}

//creating initial subfolders for out_chrome
function wpo_initialsub2($dir){
	foreach (glob($dir.'/*') as $value) {
		if((is_dir($value))&&($value!="../wpo")){
			$new_value = str_replace("..", "out_chrome", $value);
			if(!mkdir($new_value)) {
				die("<h1>Error in creating".$new_value."folder</h1>");
			}
            //add to log
            file_put_contents("temp/da.log","Created ".$new_value." directory <br />", FILE_APPEND);
			wpo_initialsub2($value);
		}
	}
}
//function to detect images in a file and add it to its XML tag. Cannot simply replace .jpg with .webp because the files may include hyperlinks which won't work after making them .webp. It also returns the count using which the calling func adds the file to add-webpjs.xml.
function wpo_detect_jpg_image($path,$xml_path) {
	$no_jpg_images=0;
	$contents = file_get_contents($path);
	$escape_quotes = array("\"","'","`","(",")"); //contains three types of qoutes to remove from $contents and brackets
	$contents = str_replace($escape_quotes, " ", $contents); //replaces escape quotes with space
	$contents = preg_replace('/\s+/', ' ', $contents); //removes whitespaces, tabs, and newlines
	$list = explode(" ",$contents); //insert each string as an item in a new array 
	foreach( $list as $list_item) {
		if(((wpo_ext($list_item)=="jpg")||(wpo_ext($list_item)=="jpeg")||(wpo_ext($list_item)=="png")||(wpo_ext($list_item)=="gif"))&&(preg_match("/^(http|https|ftp|ftps)\:\/\/*/", $list_item)==0))
		{
		//the above condition satisfies if the string is a reference to a local image
		file_put_contents($xml_path,"\t\t<rname>".$list_item."</rname>\n", FILE_APPEND);
		$no_jpg_images++;
		}
	}
	return $no_jpg_images;
}
	
//get extension of a file - used to extract "other" files
function wpo_ext($path) {
	return pathinfo($path, PATHINFO_EXTENSION);
}

//new function to detect files
function wpo_files($dir,$param2) {
	switch($param2) {
	case 1: {
		if(glob($dir.'/{*.php,*.php3,*.phtml}', GLOB_BRACE)){echo "<tr class='da-folder'><td colspan='2'><h4>".$dir."</h4></td></tr>";}
		foreach (glob($dir.'/{*.php,*.php3,*.phtml}', GLOB_BRACE) as $filename) {
		echo "<tr class='da-file'><td>$filename</td><td>" . filesize($filename) . "</td></tr>";
		// copy to out folder
		$destination = str_replace("..", "out", $filename);
		if(!copy($filename,$destination)) { die ("error in copying to output folder");}
		// copy to out_chrome folder
		$destination = str_replace("..", "out_chrome", $filename);
		if(!copy($filename,$destination)) { die ("error in copying to output folder");}
		
		//add to xml files
		file_put_contents("temp/php.xml","<file>\n\t<path>".$filename."</path>\n\t<size>".filesize($filename)."</size>\n\t<rlist>\n", FILE_APPEND);
		file_put_contents("temp/add-webpjs.xml","<file>".$filename."</file>\n", FILE_APPEND);
		$no = wpo_detect_jpg_image($filename,"temp/php.xml");
		if($no>0) {file_put_contents("temp/php.xml","\t</rlist>\n</file>\n", FILE_APPEND);}
		else {file_put_contents("temp/php.xml","\t\t none \n\t</rlist>\n</file>\n", FILE_APPEND);}
		}
		wpo_dir($dir,1);
		break;
	}
	case 2: {
		if(glob($dir.'/*.js')){echo "<tr class='da-folder'><td colspan='2'><h4>".$dir."</h4></td></tr>";}
		foreach (glob($dir.'/*.js') as $filename) {
		echo "<tr class='da-file'><td>$filename</td><td>" . filesize($filename) . "</td></tr>";
		// copy to out folder
		$destination = str_replace("..", "out", $filename);
		if(!copy($filename,$destination)) { die ("error in copying to output folder");}
		
		// copy to out_chrome folder
		$destination = str_replace("..", "out_chrome", $filename);
		if(!copy($filename,$destination)) { die ("error in copying to output folder");}
		
		//add to xml file
		file_put_contents("temp/js.xml","<file>\n\t<path>".$filename."</path>\n\t<size>".filesize($filename)."</size>\n\t<rlist>\n", FILE_APPEND);
		$no = wpo_detect_jpg_image($filename,"temp/js.xml");
		if($no>0) {file_put_contents("temp/js.xml","\t</rlist>\n</file>\n", FILE_APPEND);}
		else {file_put_contents("temp/js.xml","\t\t none \n\t</rlist>\n</file>\n", FILE_APPEND);}
		}
		wpo_dir($dir,2);
		break;
	}
	case 3: {
		if(glob($dir.'/*.css')){echo "<tr class='da-folder'><td colspan='2'><h4>".$dir."</h4></td></tr>";}
		foreach (glob($dir.'/*.css') as $filename) {
		echo "<tr class='da-file'><td>$filename</td><td>" . filesize($filename) . "</td></tr>";
		// copy to out folder
		$destination = str_replace("..", "out", $filename);
		if(!copy($filename,$destination)) { die ("error in copying to output folder");}
		
		// copy to out_chrome folder
		$destination = str_replace("..", "out_chrome", $filename);
		if(!copy($filename,$destination)) { die ("error in copying to output folder");}
		
		//add to xml file
		file_put_contents("temp/css.xml","<file>\n\t<path>".$filename."</path>\n\t<size>".filesize($filename)."</size>\n\t<rlist>\n", FILE_APPEND);
		$no = wpo_detect_jpg_image($filename,"temp/css.xml");
		if($no>0) {file_put_contents("temp/css.xml","\t</rlist>\n</file>\n", FILE_APPEND);}
		else {file_put_contents("temp/css.xml","\t\t none \n\t</rlist>\n</file>\n", FILE_APPEND);}
		}
		wpo_dir($dir,3);
		break;
	}
	case 4: {
		if(glob($dir.'/{*.html,*.htm,*.shtml,*.shtm,*.xhtml}', GLOB_BRACE)){echo "<tr class='da-folder'><td colspan='2'><h4>".$dir."</h4></td></tr>";}
		foreach (glob($dir.'/{*.html,*.htm,*.shtml,*.shtm,*.xhtml}', GLOB_BRACE) as $filename) {
		echo "<tr class='da-file'><td>$filename</td><td>" . filesize($filename) . "</td></tr>";
		// copy to out folder
		$destination = str_replace("..", "out", $filename);
		if(!copy($filename,$destination)) { die ("error in copying to output folder");}
		
		// copy to out_chrome folder
		$destination = str_replace("..", "out_chrome", $filename);
		if(!copy($filename,$destination)) { die ("error in copying to output folder");}
		
		//add to xml files
		file_put_contents("temp/html.xml","<file>\n\t<path>".$filename."</path>\n\t<size>".filesize($filename)."</size>\n\t<rlist>\n", FILE_APPEND);
		file_put_contents("temp/add-webpjs.xml","<file>".$filename."</file>\n", FILE_APPEND);
		$no = wpo_detect_jpg_image($filename,"temp/html.xml");
		if($no>0) {file_put_contents("temp/html.xml","\t</rlist>\n</file>\n", FILE_APPEND);}
		else {file_put_contents("temp/html.xml","\t\t none \n\t</rlist>\n</file>\n", FILE_APPEND);}
		}
		wpo_dir($dir,4);
		break;
	}	
	case 5: {
		if(glob($dir.'/{*.jpg,*.png,*.gif,*.bmp,*.jpeg}', GLOB_BRACE)){echo "<tr class='da-folder'><td colspan='2'><h4>".$dir."</h4></td></tr>";}
		foreach (glob($dir.'/{*.jpg,*.png,*.gif,*.bmp,*.jpeg}', GLOB_BRACE) as $filename) {
		echo "<tr class='da-file'><td>$filename</td><td>" . filesize($filename) . "</td></tr>";
		// copy to out folder
		$destination = str_replace("..", "out", $filename);
		if(!copy($filename,$destination)) { die ("error in copying to output folder");}
		
		// copy to out_chrome folder
		$destination = str_replace("..", "out_chrome", $filename);
		if(!copy($filename,$destination)) { die ("error in copying to output folder");}
		
		//add to xml file
		file_put_contents("temp/img.xml","<file>\n\t<path>".$filename."</path>\n\t<size>".filesize($filename)."</size>\n</file>\n", FILE_APPEND);
		}
		wpo_dir($dir,5);
		break;
	}	
	case 6: {
		foreach (glob($dir.'/*.*') as $filename) {
		if((wpo_ext($filename)!="php")&&(wpo_ext($filename)!="php3")&&(wpo_ext($filename)!="phtml")&&(wpo_ext($filename)!="js")&&(wpo_ext($filename)!="css")&&(wpo_ext($filename)!="bmp")&&(wpo_ext($filename)!="jpg")&&(wpo_ext($filename)!="jpeg")&&(wpo_ext($filename)!="png")&&(wpo_ext($filename)!="gif")&&(wpo_ext($filename)!="html")&&(wpo_ext($filename)!="htm")&&(wpo_ext($filename)!="shtml")&&(wpo_ext($filename)!="shtm")&&(wpo_ext($filename)!="xhtml")) {
			echo "<tr class='da-file'><td>$filename</td><td>" . filesize($filename) . "</td></tr>";
			// copy to out folder
			$destination = str_replace("..", "out", $filename);
			if(!copy($filename,$destination)) { die ("error in copying to output folder");}
			
			// copy to out_chrome folder
			$destination = str_replace("..", "out_chrome", $filename);
			if(!copy($filename,$destination)) { die ("error in copying to output folder");}
			
			//add to xml file
			file_put_contents("temp/exclude.xml","<file>\n\t<path>".$filename."</path>\n\t<size>".filesize($filename)."</size>\n\t<rlist>\n", FILE_APPEND);
			$no = wpo_detect_jpg_image($filename,"temp/exclude.xml");
			if($no>0) {file_put_contents("temp/exclude.xml","\t</rlist>\n</file>\n", FILE_APPEND);file_put_contents("temp/add-webpjs.xml","<file>".$filename."</file>\n", FILE_APPEND);}
			else {file_put_contents("temp/exclude.xml","\t\t none \n\t</rlist>\n</file>\n", FILE_APPEND);}
			}
		}
		wpo_dir($dir,6);
		break;
		
	}		
}
}

//function to traverse directories
function wpo_dir($dir,$param2) {
	foreach (glob($dir.'/*') as $value) {
		if((is_dir($value))&&($value!="../wpo")){
			wpo_files($value,$param2);
		}
	}
}

?>
<?php 
//extending execution time limit 
set_time_limit (120);
//create initial directories 
wpo_initial(".."); 
?>

<!-- Create XML files -->
<?php wpo_openXMLs(); ?>

<!-- Listing all HTML files-->
<h2>The following HTML files will be Optimized</h2>
<table class="da-table" width="100%" cellpadding="10px">
<tr class='da-header'><td>Filename</td><td>File Size (in Bytes) </td></tr>
<?php wpo_files("..",4); 
//add to log
file_put_contents("temp/da.log","Copied and listed all HTML files <br />", FILE_APPEND);    
?>
</table>

<!-- Listing all PHP files-->
<h2>The following PHP files will be Optimized</h2>
<table class="da-table" width="100%" cellpadding="10px">
<tr class='da-header'><td>Filename</td><td>File Size (in Bytes) </td></tr>
<?php wpo_files("..",1); 
//add to log
file_put_contents("temp/da.log","Copied and listed all PHP files <br />", FILE_APPEND);  
?>
</table>

<!-- Listing all JS files-->
<h2>The following JS files will be Optimized</h2>
<table class="da-table" width="100%" cellpadding="10px">
<tr class='da-header'><td>Filename</td><td>File Size (in Bytes) </td></tr>
<?php wpo_files("..",2);  
//add to log
file_put_contents("temp/da.log","Copied and listed all JS files <br />", FILE_APPEND);  
?>
</table>

<!-- Listing all CSS files-->
<h2>The following CSS files will be Optimized</h2>
<table class="da-table" width="100%" cellpadding="10px">
<tr class='da-header'><td>Filename</td><td>File Size (in Bytes) </td></tr>
<?php wpo_files("..",3);  
//add to log
file_put_contents("temp/da.log","Copied and listed all CSS files <br />", FILE_APPEND);  
?>
</table>

<!-- Listing all Images-->
<h2>The following Images will be Optimized</h2>
<table class="da-table" width="100%" cellpadding="10px">
<tr class='da-header'><td>Filename</td><td>File Size (in Bytes) </td></tr>
<?php wpo_files("..",5);  
//add to log
file_put_contents("temp/da.log","Copied and listed all Images <br />", FILE_APPEND);  
?>
</table>

<!-- Listing all Other files-->
<h2>The following files will NOT be Optimized</h2>
<table class="da-table" width="100%" cellpadding="10px">
<tr class='da-header'><td>Filename</td><td>File Size (in Bytes) </td></tr>
<?php wpo_files("..",6); 
 //add to log
file_put_contents("temp/da.log","Copied and listed all other files <br />", FILE_APPEND);  
?>
</table>

<br /><br />

<!-- close XML files -->
<?php wpo_closeXMLs(); 
//add to log
file_put_contents("temp/da.log","Done! Kindly Check the PHP output below for result of the analysis before proceeding to next step.", FILE_APPEND); 
?>
</body>
</html>

