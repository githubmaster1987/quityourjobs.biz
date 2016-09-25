   <?php $playDefault = ((strstr($_SERVER['REQUEST_URI'],"index.php") == 'index.php' || strstr($_SERVER['REQUEST_URI'],".php") == '') && $deposit_status == 1) ? '1': '0'; ?>
   <div id="videoModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="h_iframe">
                <button class="close custom-close" aria-label="Close" data-dismiss="modal" type="button">
                    <span aria-hidden="true">×</span>
                </button>
                <!-- a transparent image is preferable -->
                <iframe id="videoiframe" class="videoiframe" src="http://quityourjobs.biz/members/welcome.php" frameborder="0" height="550" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>
<!-------Modal End ----------------->
   </div>
   <!-- END CONTAINER -->

   <!-- BEGIN FOOTER -->
   <!--<div class="blackfooter">
Copyright@ Quit Your Jobs | <a href="http://quityourjobs.biz/terms-of-use" target="_blank">Terms & Conditions</a> | <a href="http://quityourjobs.biz/privacy-policy" target="_blank">Privacy Policy</a> | <a href="http://quityourjobs.biz/earnings-disclaimer" target="_blank">Disclaimer</a> |   
   </div>-->
  <footer class="footer text-center"> Copyright@ Quit Your Jobs | <a class="footer-anchor" target="_blank" href="http://quityourjobs.biz/terms-of-use">Terms &amp; Conditions</a> | <a class="footer-anchor" target="_blank" href="http://quityourjobs.biz/privacy-policy">Privacy Policy</a> | <a class="footer-anchor" target="_blank" href="http://quityourjobs.biz/earnings-disclaimer">Disclaimer</a> | </footer>
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




   <!-- END JAVASCRIPTS -->   
</body>
<!-- END BODY -->
</html>
