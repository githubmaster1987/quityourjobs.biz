   <?php $playDefault = ((strstr($_SERVER['REQUEST_URI'],"index.php") == 'index.php' || strstr($_SERVER['REQUEST_URI'],".php") == '') && $deposit_status == 1) ? '1': '0'; ?>
   <div id="videoModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="h_iframe">
                <button class="close custom-close" aria-label="Close" data-dismiss="modal" type="button">
                    <span aria-hidden="true">×</span>
                </button>
                <!-- a transparent image is preferable -->
                <iframe id="videoiframe" class="videoiframe" src="https://selfmademillionaires.biz/members/welcome.php" frameborder="0" height="550" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>
<!-------Modal End ----------------->
   </div>
   <!-- END CONTAINER -->

   <!-- BEGIN FOOTER -->
   <!--<div class="blackfooter">
Copyright@ Self-Made Millionaire Biz | <a href="../terms.php" target="_blank">Terms & Conditions</a> | <a href="../privacy.php" target="_blank">Privacy Policy</a> | <a href="../disclaimer.php" target="_blank">Disclaimer</a> |   
   </div>-->
  <footer class="footer text-center"> Copyright@ Self-Made Millionaire Biz | <a class="footer-anchor" target="_blank" href="../terms.php">Terms &amp; Conditions</a> | <a class="footer-anchor" target="_blank" href="../privacy.php">Privacy Policy</a> | <a class="footer-anchor" target="_blank" href="../disclaimer.php">Disclaimer</a> | </footer>
   <!-- END FOOTER -->


    <!--common script for all pages-->

<script src="js/common-scripts.js"></script>
<script src="js/helper.js"></script>
   <!--script for this page only-->

<script>

$(document).ready(function() {
defaultVedio = '<?php echo $playDefault; ?>';
$( "#dashtable" ).load( "ajax/dash_table.php" ); 
 function tableupdate(){   
   $( "#dashtable" ).load( "ajax/dash_table.php" ); 
 }
   function showsignal(){   
    $( "#signalbox" ).load( "ajax/showuser.php" ); 
    $('#signalbox').toggleClass('signalshow');    
  
   }   

   function showtimer(){   
    $( "#timer" ).load( "ajax/timer.php" );   
   }   

   //setInterval(showsignal, 40000);
   //setTimeout(showsignal, 3000);
   setInterval(tableupdate, 40000);
   setInterval(showtimer,30000);
   $videoSrc = $("#videoiframe").attr('src');
	if(defaultVedio == '0') {
	    $("#videoiframe").attr('src', '');
	    $('.modal').hide();
	}
	$(".custom-close").click(function () {
	    $('.modal').hide();
	    $("#videoiframe").attr('src', '');
	    setTimeout(window.location="deposit.php", 1000);
	});
	$('#header-introduction').on('click', function () {
	    $('.modal').show();
	    $("#videoiframe").attr('src', $videoSrc);
	    $('#videoModal').modal({
	        show: 'false',
	        backdrop: 'static',
	        keyboard: false
	    });
	});
});
</script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-53264353-1', 'auto');
  ga('send', 'pageview');

</script>


   <!-- END JAVASCRIPTS -->   
</body>
<!-- END BODY -->
</html>
