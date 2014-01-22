<?php
/*======================================================
-------------------------------
The Directory Analysis Module
-------------------------------
This Module handles identification of files and creating wpo_temmp folder with php.xml, js.xml, css.xml, html.xml, img.xml and exclude.xml files.


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

include('header.php'); 

//creating the wpo_temp directory to store temporary files through out the process
if(!mkdir("../wpo_temp")) {
	die("<h1>Error in creating Temporary Storage Module</h1>");
}

//creating initial files to store temporary data
	//not required as file_put_contents() handles it

/*========= function to detect files - old and not being used ===========
function wpo_files($dir) {
    if(glob($dir.'/{*.css,*.js,*.jpg,*.png,*.gif,*.bmp}', GLOB_BRACE)){
	echo "<tr class='da-folder'><td colspan='2'><h4>".$dir."</h4></td></tr>";}
	foreach (glob($dir.'/{*.css,*.js,*.jpg,*.png,*.gif,*.bmp}', GLOB_BRACE) as $filename) {
		echo "<tr class='da-file'><td>$filename</td><td>" . filesize($filename) . "</td></tr>";
    }
	wpo_dir($dir);
}*/

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
		
		}
		wpo_dir($dir,1);
		break;
	}
	case 2: {
		if(glob($dir.'/*.js')){echo "<tr class='da-folder'><td colspan='2'><h4>".$dir."</h4></td></tr>";}
		foreach (glob($dir.'/*.js') as $filename) {
		echo "<tr class='da-file'><td>$filename</td><td>" . filesize($filename) . "</td></tr>";
		}
		wpo_dir($dir,2);
		break;
	}
	case 3: {
		if(glob($dir.'/*.css')){echo "<tr class='da-folder'><td colspan='2'><h4>".$dir."</h4></td></tr>";}
		foreach (glob($dir.'/*.css') as $filename) {
		echo "<tr class='da-file'><td>$filename</td><td>" . filesize($filename) . "</td></tr>";
		}
		wpo_dir($dir,3);
		break;
	}
	case 4: {
		if(glob($dir.'/{*.html,*.htm,*.shtml,*.shtm,*.xhtml}', GLOB_BRACE)){echo "<tr class='da-folder'><td colspan='2'><h4>".$dir."</h4></td></tr>";}
		foreach (glob($dir.'/{*.html,*.htm,*.shtml,*.shtm,*.xhtml}', GLOB_BRACE) as $filename) {
		echo "<tr class='da-file'><td>$filename</td><td>" . filesize($filename) . "</td></tr>";
		}
		wpo_dir($dir,4);
		break;
	}	
	case 5: {
		if(glob($dir.'/{*.jpg,*.png,*.gif,*.bmp}', GLOB_BRACE)){echo "<tr class='da-folder'><td colspan='2'><h4>".$dir."</h4></td></tr>";}
		foreach (glob($dir.'/{*.jpg,*.png,*.gif,*.bmp}', GLOB_BRACE) as $filename) {
		echo "<tr class='da-file'><td>$filename</td><td>" . filesize($filename) . "</td></tr>";
		}
		wpo_dir($dir,5);
		break;
	}	
	case 6: {
		foreach (glob($dir.'/*.*') as $filename) {
		if((wpo_ext($filename)!="php")&&(wpo_ext($filename)!="php3")&&(wpo_ext($filename)!="phtml")&&(wpo_ext($filename)!="js")&&(wpo_ext($filename)!="css")&&(wpo_ext($filename)!="bmp")&&(wpo_ext($filename)!="jpg")&&(wpo_ext($filename)!="png")&&(wpo_ext($filename)!="gif")&&(wpo_ext($filename)!="html")&&(wpo_ext($filename)!="htm")&&(wpo_ext($filename)!="shtml")&&(wpo_ext($filename)!="shtm")&&(wpo_ext($filename)!="xhtml")) {
			echo "<tr class='da-file'><td>$filename</td><td>" . filesize($filename) . "</td></tr>";
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

//final function to detect
function wpo_detect($dir,$param2) {
	//list files of the $dir first
	wpo_files($dir,$param2);
	//traverse sub-directories
	wpo_dir($dir,$param2);
}
?>

<!-- Listing all HTML files-->
<h2>The following HTML files will be Optimized</h2>
<table class="da-table" width="100%" cellpadding="10px">
<tr class='da-header'><td>Filename</td><td>File Size (in Bytes) </td></tr>
<?php wpo_detect("..",4); ?>
</table>

<!-- Listing all PHP files-->
<h2>The following PHP files will be Optimized</h2>
<table class="da-table" width="100%" cellpadding="10px">
<tr class='da-header'><td>Filename</td><td>File Size (in Bytes) </td></tr>
<?php wpo_detect("..",1); ?>
</table>

<!-- Listing all JS files-->
<h2>The following JS files will be Optimized</h2>
<table class="da-table" width="100%" cellpadding="10px">
<tr class='da-header'><td>Filename</td><td>File Size (in Bytes) </td></tr>
<?php wpo_detect("..",2); ?>
</table>

<!-- Listing all CSS files-->
<h2>The following CSS files will be Optimized</h2>
<table class="da-table" width="100%" cellpadding="10px">
<tr class='da-header'><td>Filename</td><td>File Size (in Bytes) </td></tr>
<?php wpo_detect("..",3); ?>
</table>

<!-- Listing all Images-->
<h2>The following Images will be Optimized</h2>
<table class="da-table" width="100%" cellpadding="10px">
<tr class='da-header'><td>Filename</td><td>File Size (in Bytes) </td></tr>
<?php wpo_detect("..",5); ?>
</table>

<!-- Listing all Other files-->
<h2>The following files will NOT be Optimized</h2>
<table class="da-table" width="100%" cellpadding="10px">
<tr class='da-header'><td>Filename</td><td>File Size (in Bytes) </td></tr>
<?php wpo_detect("..",6); ?>
</table>

<br /><br />
<div class="button" style="float:right;"><a href="wpo-da.php">Proceed to Next Step</a></div>

<?php  include('footer.php'); ?>