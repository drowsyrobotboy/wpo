<!DOCTYPE html>
<html>
    <head>
        <title>Web Performance Optimization tool</title>
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <script src="js/script.js" type="text/javascript"></script>
        <script src="js/jquery.js" type="text/javascript"></script>
        
    </head>
<body>
    <div class="wrapper">
    <div class="logo"><img src="images/logo.svg" width="100%" /></div>
    <div class="content">
        <table>
            <tr>
                <td valign="top">
                    <div class="left-menu">
                        <ul>
                            <li style="background: #2990e2;color: #efefef;">Start</li>
                            <li>Directory Analysis</li>
                            <li>Code Reorganization</li>
                            <li>Image Optimization for Chrome</li>
                            <li>Replace image links</li>
                            <li>Image Optimization for Others</li>
                            <li>Results</li>
                        </ul>
                    </div>
                </td>
                <td valign="top">
                    <div id="output">
                         Please press the "Start Optimizing" button below to begin optimizing.
                    </div>
                </td>
            </tr>
        </table>
        <div class="button"><a href="wpo-da.php" target="phpOut" onclick="wpo_da_out()" >Start Optimizing</a></div>
    </div>
        <br /><br />
        <iframe id="phpFrame" name="phpOut" width="100%" frameborder="0"></iframe>
    </div>
    <script type="text/javascript">
        function wpo_da_out(){
            setInterval(function(){
                $('#output').load('temp/da.log');
            },10);
            alert("next");
	       }  
    </script>
</body>
</html>

