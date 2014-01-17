<?php include('header.php'); 
//function to detect files
function wpo_files($dir) {
    if(glob($dir.'/{*.css,*.js,*.jpg,*.png,*.gif,*.bmp}', GLOB_BRACE)){
	echo "<tr class='da-folder'><td colspan='2'><h4>".$dir."</h4></td></tr>";}
	foreach (glob($dir.'/{*.css,*.js,*.jpg,*.png,*.gif,*.bmp}', GLOB_BRACE) as $filename) {
		echo "<tr class='da-file'><td>$filename</td><td>" . filesize($filename) . "</td></tr>";
    }
	wpo_dir($dir);
}
//function to traverse directories
function wpo_dir($dir) {
	foreach (glob($dir.'/*') as $value) {
		if((is_dir($value))&&($value!="../wpo")){
			wpo_files($value);
		}
	}
}
?>

<h2>The following files will be Optimized</h2>
<table class="da-table" width="100%" cellpadding="10px">
<tr class='da-header'><td>Filename</td><td>File Size (in Bytes) </td></tr>


<?php 
//passing the parent directory as parameter to begin traversing
wpo_dir("..");

?>

</table>

<?php  include('footer.php'); ?>