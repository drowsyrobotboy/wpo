<?php
/*======================================================
----------------------------------------------------
****** - The WebP Implementation Module - Part II **********
----------------------------------------------------
This Module replaces images references accordingly and also adds the weppy.js file to the required files.
-------------------------------
Important Notes
-------------------------------

=========================================================*/
include('header.php'); 

//function to add the weppy.js fille to all files listed in add-weppy.xml file
function wpo_addweppy() {
	return 1;
}

//function to replace local image extensions with .webp extension
function wpo_replace_links() {
	return 1;
}

include('footer.php'); 
?>