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
                            <li id="item-start" style="background: #2990e2;color: #efefef;">Start</li>
                            <li id="item-da">Directory Analysis</li>
                            <li id="item-cr">Code Reorganization</li>
                            <li id="item-io">Image Optimization for Chrome</li>
                            <li id="item-io2">Replace image links</li>
                            <li id="item-io3">Image Optimization for Others</li>
                            <li id="item-res">Results</li>
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
        //tokens to control setinterval and clearinterval functions based on respective boolean values
        var da_token=true;
        var cr_token=true;
        var io_token=true;
        var io2_token=true;
        var io3_token=true;
        var res_token=true;
        function wpo_da_out(){
            var da_timer = setInterval(function(){
                $('#output').load('temp/da.log');
                if(!da_token) {
                    clearInterval(da_timer);
                }
            },10);
            $('#phpFrame').css({'height':'800px'});
            $('#item-start').css({'background':'#ededed','color':'#2990e2'});
            $('#item-da').css({'background':'#2990e2','color':'#efefef'});
            $('.button').html('<a href="wpo-cr.php" target="phpOut" onclick="wpo_cr_out()" >Reorganize Code</a>');
	       }  
        function wpo_cr_out(){
            da_token=false; //stop previous timer by setting global variable to false
            var cr_timer = setInterval(function(){
                $('#output').load('temp/cr.log');
                if(!cr_token) {
                    clearInterval(cr_timer);
                }
            },10);
            $('#phpFrame').css({'height':'60px'});
            $('#item-da').css({'background':'#ededed','color':'#2990e2'});
            $('#item-cr').css({'background':'#2990e2','color':'#efefef'});
            $('.button').html('<a href="wpo-io.php" target="phpOut" onclick="wpo_io_out()" >Optimize Images for Chrome</a>');
	       }  
        function wpo_io_out(){
            cr_token=false; //stop previous timer by setting global variable to false
            var io_timer = setInterval(function(){
                $('#output').load('temp/io.log');
                if(!io_token) {
                    clearInterval(io_timer);
                }
            },10);
            $('#item-cr').css({'background':'#ededed','color':'#2990e2'});
            $('#item-io').css({'background':'#2990e2','color':'#efefef'});
            $('.button').html('<a href="wpo-io2.php" target="phpOut" onclick="wpo_io2_out()" >Replace Image Links</a>');
	       }
        function wpo_io2_out(){
            io_token=false; //stop previous timer by setting global variable to false
            var io2_timer = setInterval(function(){
                $('#output').load('temp/io2.log');
                if(!io2_token) {
                    clearInterval(io2_timer);
                }
            },10);
            $('#item-io').css({'background':'#ededed','color':'#2990e2'});
            $('#item-io2').css({'background':'#2990e2','color':'#efefef'});
            $('.button').html('<a href="wpo-io3.php" target="phpOut" onclick="wpo_io3_out()" >Optimize Images for other browsers</a>');
	       }
        function wpo_io3_out(){
            io2_token=false; //stop previous timer by setting global variable to false
            var io3_timer = setInterval(function(){
                $('#output').load('temp/io3.log');
                if(!io3_token) {
                    clearInterval(io3_timer);
                }
            },10);
            $('#item-io').css({'background':'#ededed','color':'#2990e2'});
            $('#item-io2').css({'background':'#2990e2','color':'#efefef'});
            $('.button').html('<a href="#">View Optimization Results!</a>');
	       }
    </script>
</body>
</html>

