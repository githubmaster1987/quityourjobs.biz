$( document ).ready(function() {

$( document ).on("click",".closehelper", function(){ 
   $(this).closest("div").fadeOut( "slow" );
   $('html, body').css({'position': 'relative', 'width': '100%' });
 });

 $( document ).on("click",".helper", function(){ 
   $('.helpstep1').fadeIn("slow");
   $('html, body').css({ 'position': 'fixed', 'width': '100%' });
 });

 $( document ).on("click",".next1", function(){ 
   $('.helpstep1').fadeOut("slow");
   $('.helpstep2').fadeIn("slow");
 });

 $( document ).on("click",".prev1", function(){ 
   $('.helpstep2').fadeOut("slow");
   $('.helpstep1').fadeIn("slow");
 });

 $( document ).on("click",".next2", function(){ 
   $('.helpstep2').fadeOut("slow");
   $('.helpstep3').fadeIn("slow");
 });

 $( document ).on("click",".prev2", function(){ 
   $('.helpstep3').fadeOut("slow");
   $('.helpstep2').fadeIn("slow");
 });

 $( document ).on("click",".next3", function(){ 
   $('.helpstep3').fadeOut("slow");
   $('.helpstep4').fadeIn("slow");
 });

 $( document ).on("click",".prev3", function(){ 
   $('.helpstep4').fadeOut("slow");
   $('.helpstep3').fadeIn("slow");
 });

 $( document ).on("click",".next4", function(){ 
   $('.helpstep4').fadeOut("slow");
   $('.helpstep5').fadeIn("slow");
 });

 $( document ).on("click",".prev4", function(){ 
   $('.helpstep5').fadeOut("slow");
   $('.helpstep4').fadeIn("slow");
 });

 $( document ).on("click",".next5", function(){ 
   $('.helpstep5').fadeOut("slow");
   $('.helpstep6').fadeIn("slow");
 });

 $( document ).on("click",".prev5", function(){ 
   $('.helpstep6').fadeOut("slow");
   $('.helpstep5').fadeIn("slow");
 });

 $( document ).on("click",".next6", function(){ 
   $('.helpstep6').fadeOut("slow");
   $('.helpstep7').fadeIn("slow");
 });

 $( document ).on("click",".prev6", function(){ 
   $('.helpstep7').fadeOut("slow");
   $('.helpstep6').fadeIn("slow");
 });

 $( document ).on("click",".next7", function(){ 
   $('.helpstep7').fadeOut("slow");
   $('.helpstep8').fadeIn("slow");
 });

 $( document ).on("click",".prev7", function(){ 
   $('.helpstep8').fadeOut("slow");
   $('.helpstep7').fadeIn("slow");
 });

 $( document ).on("click",".next8", function(){ 
   $('.helpstep8').fadeOut("slow");
   $('.helpstep9').fadeIn("slow");
 });

 $( document ).on("click",".prev8", function(){ 
   $('.helpstep9').fadeOut("slow");
   $('.helpstep8').fadeIn("slow");
 });


 $( document ).on("click",".next9", function(){ 
   $('.helpstep9').fadeOut("slow");
   $('.helpstep10').fadeIn("slow");
 });

 $( document ).on("click",".prev9", function(){ 
   $('.helpstep10').fadeOut("slow");
   $('.helpstep9').fadeIn("slow");
 });

 $( document ).on("click",".next10", function(){ 
   $('.helpstep10').fadeOut("slow");
   $('.helpstep11').fadeIn("slow");
 });

 $( document ).on("click",".prev10", function(){ 
   $('.helpstep11').fadeOut("slow");
   $('.helpstep10').fadeIn("slow");
 });

 $( document ).on("click",".next11", function(){ 
   $('.helpstep11').fadeOut("slow");
   $('.helpstep12').fadeIn("slow");
 });

 $( document ).on("click",".prev11", function(){ 
   $('.helpstep12').fadeOut("slow");
   $('.helpstep11').fadeIn("slow");
 });

});
