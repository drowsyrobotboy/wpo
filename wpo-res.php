<?php
/*======================================================
----------------------------------------------------
****** - Optimization Results **********
----------------------------------------------------
This Module converts optimizes jpg/png/gif images.
-------------------------------
Changelog
-------------------------------
-------------------------------
Important Notes
-------------------------------

=========================================================*/
//another approach
function wpo_get_size_new($dir){
	$bytes = 0;
	$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
	foreach ($iterator as $i) 
	{
		if((!strstr($i,"\wpo"))&&(!strstr($i,"\.."))&&(!strstr($i,"\."))) {
			$bytes += $i->getSize();
		}
	}
	echo $bytes;
}
//extending execution time limit 
set_time_limit (120);
//calling each function
wpo_get_size_new("..");
echo "<br />";
wpo_get_size_new("out");
echo "<br />";
wpo_get_size_new("out_chrome");
echo "<br />";

?>
