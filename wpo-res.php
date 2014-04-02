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
	return $bytes;
}
//extending execution time limit 
set_time_limit (120);
//calling each function
$initial_size = wpo_get_size_new("..");
$out_size = wpo_get_size_new("out");
$chrome_size = wpo_get_size_new("out_chrome");
$out_percent = round((($initial_size-$out_size)/$initial_size)*100);
$chrome_percent = round((($initial_size-$chrome_size)/$initial_size)*100);
$data = '
<table cellpadding="10">
	<tr><td style="background:#ddd;">Initial Size of Host Site</td><td>'.$initial_size.' bytes</td></tr>
	<tr><td style="background:#ddd;">Size after optimization for chrome</td><td>'.$chrome_size.' bytes</td></tr>
	<tr>
		<td style="background:#ddd;">Percentage of Optimization for chrome</td>
		<td>'.$chrome_percent.'%</td>
	</tr>
	<tr><td style="background:#ddd;">Size after optimization for other browsers</td><td>'.$out_size.' bytes</td></tr>
	<tr>
		<td style="background:#ddd;">Percentage of Optimization for other browsers</td>
		<td>'.$out_percent.'%</td>
	</tr>
</table>
<br />
Optimization Graph<br />
<div style="width: 502px; height: 82px; padding: 40px 0px; background: #ededed url(../images/graph.svg); margin:10px;">
	<div style="padding: 5px 10px; width: '.($chrome_percent*5).'px; background:#2990E2; color:#efefef;">
		'.$chrome_percent.'% Chrome
	</div><br />
	<div style="padding: 5px 10px; width: '.($out_percent*5).'px; background:#2990E2; color:#efefef;">
		'.$out_percent.'% Firefox
	</div>
</div>
';
file_put_contents("temp/res.log", $data, FILE_APPEND);
?>