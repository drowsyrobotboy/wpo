<!DOCTYPE html>
<html>
<head>
	<title>WPO Display</title>
</head>
<body>
<a href="wpo-io.php" target="myframe">Go!</a>
<iframe name="myframe"></iframe>
<div id="console"></div>
<script src="js/jquery.js"></script>
<script type="text/javascript">
	window.onload=function(){
		setInterval(function(){
            $('#console').load('temp/console.log');
    	},10);
	}
</script>
</body>
</html>
