<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
   <meta charset="utf-8" />
   <title><?php if(!empty($title)){ echo $title; }else{ echo 'Self-Made Millionaire System'; } ?></title>
   <meta content="width=device-width, initial-scale=1.0" name="viewport" />
   <meta content="" name="description" />
   <meta content="Mosaddek" name="author" />

  <!-- Favicons-->
  <link rel="icon" href="images/favicon/favicon-32x32.png" sizes="32x32">
  <!-- Favicons-->
  <link rel="apple-touch-icon-precomposed" href="images/favicon/apple-touch-icon-152x152.png">
  <!-- For iPhone -->
  <meta name="msapplication-TileColor" content="#00bcd4">
  <meta name="msapplication-TileImage" content="images/favicon/mstile-144x144.png">
  <!-- For Windows Phone -->

   <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
   <link href="assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
   <link href="assets/bootstrap/css/bootstrap-fileupload.css" rel="stylesheet" />
   <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
   <link href="css/style.css" rel="stylesheet" />
   <link href="css/style-responsive.css" rel="stylesheet" />
   <link href="css/style-default.css" rel="stylesheet" id="style_color" />
   <link href="assets/fullcalendar/fullcalendar/bootstrap-fullcalendar.css" rel="stylesheet" />
   <link rel="stylesheet" href="css/sample.css" />
   <script src="js/jquery-1.9.0.min.js"></script>
   <!-- <msdropdown> -->
   <link rel="stylesheet" type="text/css" href="css/dd.css" />
   <script src="js/jquery.dd.js"></script>
   <!-- </msdropdown> -->
   <link rel="stylesheet" type="text/css" href="css/skin2.css" />


<?php if($getuserrow['deposit_status']=='DEMO'){ echo '<script type="text/javascript" async="async" defer="defer" data-cfasync="false" src="https://mylivechat.com/chatinline.aspx?hccid=71364593"></script>'; } ?>


   <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
   <script type="text/javascript" src="assets/jquery-slimscroll/jquery-ui-1.9.2.custom.min.js"></script>
   <script type="text/javascript" src="assets/jquery-slimscroll/jquery.slimscroll.min.js"></script>
   <script src="assets/fullcalendar/fullcalendar/fullcalendar.min.js"></script>
   <script src="assets/bootstrap/js/bootstrap.min.js"></script>
   <script src="js/jquery.ddslick.min.js"></script>

   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="js/excanvas.js"></script>
   <script src="js/respond.js"></script>
   <![endif]-->


<script>
$(document).ready(function(e) {
	$("#payments").msDropdown({visibleRows:4});

$( "#payments" ).change(function() {
 $( "#changebroker" ).submit();
});
});

</script>



 <style>
 .supportnum{
    color: #FFB400;
    font-weight: bold;
    padding: 10px;
    margin-bottom: -12px;
}
 .supportnumber{
    margin-left: 25px;
    font-size: 15px;
    color: #FFF;
 }

.leftpopup {
    width: 350px;
    height: 130px;
    background: #C56767;
    bottom: 5px;
    left: -400px;
    position: fixed;
    border-radius: 5px;
    box-shadow: 0px 25px 10px -15px rgba(0, 0, 0, 0.05);
    transition: 1s;
    z-index: 200;
    color: #fff;
    text-align: center;
    font-size: 15px;    
}

.rightpopup {
    width: 320px;
    height: 38px;
    background: #4a8bc2;
    top: 125px;
    right: -400px;
    position: fixed;
    border-radius: 5px;
    box-shadow: 0px 25px 10px -15px rgba(0, 0, 0, 0.05);
    transition: 1s;
    z-index: 200;
    color: #fff;
    text-align: center;
    font-size: 15px;    
}

.signalshow{
left:0px;
}

.tradeshow{
right:0px;
}


.button-0 {
    position: relative;
    padding: 5px 10px;
    border-radius: 10px;
    font-family: 'Helvetica', cursive;
    font-size: 14px;
    color: #FFF;
    text-decoration: none;
    background-color: #74B749;
    border-bottom: 3px solid #74B749;
    text-shadow: 0px -2px #74B749;
    transition: all 0.1s;
    -webkit-transition: all 0.1s;
    margin-top: 2px;
}

.button-0:hover, 
.button-0:focus {
    text-decoration: none;
    color: #fff;
}

.button-0:active {
    transform: translate(0px,5px);
    -webkit-transform: translate(0px,5px);
    border-bottom: 1px solid;
}

.headershow{
 background: #74B749;
 padding:7px;
 margin-bottom:5px;
 font-weight:bold;
}

#main-content{ margin-bottom:5px; }

.tradeboxtext {font-weight: bold;}

.showtext{margin-bottom:5px;}

.helpershow{ 
    position: fixed;
    background: #FCFBE3;
    padding: 10px;
    font-size: 15px;
    width: 270px;
    text-transform: capitalize;
    line-height: 20px;
    z-index: 100;
    color: #468847;
    text-align: left;
    display:none;
}

.helpershow::before { 
    position: absolute;
    bottom: 100%;
    margin-right: -9px;
    content: ' ';
    width: 0;
    height: 0;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-bottom: 10px solid #FCFBE3;
    border-top: 10px solid transparent;
}

.laststep{ 
    position: fixed;
    background: #FCFBE3;
    padding: 10px;
    font-size: 15px;
    width: 270px;
    text-transform: capitalize;
    line-height: 20px;
    z-index: 100;
    color: #468847;
    text-align: left;
    display:none;
    right:45px;
    bottom: 60px;
}

.laststep::after { 
    position: absolute;
    top: 100%;
    right: 30px;
    content: ' ';
    width: 0;
    height: 0;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-top: 10px solid #FCFBE3;
    border-bottom: 10px solid transparent;
}

.helperbtn{    
    font-size: 15px;
    font-family: monospace;
    font-weight: bold;
    background: rgb(6, 156, 90);
    line-height: 30px;
    text-align: center;
    color: #fff;
    cursor: pointer;
    margin-top: 10px;
    float: right;
    margin-left: 5px;
    margin-right: 5px;
    padding: 0px 8px;
}

.helperbtn:hover{
    background: rgba(6, 156, 90, 0.71);
}


.margin50{ margin-left: 50px; }

#sidebar > ul > li {
    border-bottom: 1px solid #fff;
}

#sidebar > ul > li:first-of-type{
    border-bottom: 1px solid #fff;
    border-top: 1px solid #fff;
}


 </style>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-top">

<!-- Fixed left Box to show signals -->
<div id="signalbox" class="leftpopup">
    
 </div>

<!-- Fixed left Box to show signals End -->

<!-- Fixed Right Box to show signals -->
<div id="tradebox" class="rightpopup">
    <h4 class="tradeboxtext"> Please Wait..</h4>
 </div>

<!-- Fixed Right Box to show signals End -->



   <!-- BEGIN HEADER -->
   <div id="header" class="navbar navbar-inverse navbar-fixed-top">
       <!-- BEGIN TOP NAVIGATION BAR -->
       <div class="navbar-inner">
           <div class="container-fluid">
               <!--BEGIN SIDEBAR TOGGLE-->
               <div class="sidebar-toggle-box hidden-phone">
                   <div class="" data-placement="right" data-original-title="Toggle Navigation">
			               <a class="brand" href="index.php">
					   <img src="img/logo.png" alt="Metro Lab" />
				       </a>
                   </div>
               </div>
               <!--END SIDEBAR TOGGLE-->
               <!-- BEGIN LOGO -->
               <a class="hidden-desktop brand" href="index.php">
                   <img src="img/logo.png" alt="Metro Lab" />
               </a>
               <!-- END LOGO -->
               <!-- BEGIN RESPONSIVE MENU TOGGLER -->
               <a class="btn btn-navbar collapsed" id="main_menu_trigger" data-toggle="collapse" data-target=".nav-collapse">
                   <span class="icon-bar"></span>
                   <span class="icon-bar"></span>
                   <span class="icon-bar"></span>
                   <span class="arrow"></span>
               </a>
               <!-- END RESPONSIVE MENU TOGGLER -->
               
               <div class="top-nav ">
                   <ul class="nav pull-right top-menu" > 
              <?php if((basename($_SERVER['PHP_SELF'])=='index.php') && ($getuserrow['deposit_status']=='DEMO')){  ?>
                       <li class="dropdown mtop5">

                           <a class="intro dropdown-toggle element" data-placement="bottom" href="#">
                               Introduction
                           </a>
			  <div class="helpstep1 helpershow">Here you will find a short video explaining how to activate you SMM system.<br><h3 class="next1 helperbtn">NEXT<h3><h3 class="closehelper helperbtn">CLOSE<h3></div>
                       </li>

                       <li class="dropdown mtop5">

                           <a class="helper dropdown-toggle element" data-placement="bottom" href="#" style="margin-right: 15px;">
                               Help
                           </a>
                       </li>
                  <?php } ?>
                       <!-- BEGIN SUPPORT -->
                       <li class="dropdown mtop5">
                              <h4 style="font-weight:bold; margin-right: 30px;"><i class="icon-globe"></i><span id="timer">  <?php date_default_timezone_set("UTC");  echo date("D, F j, Y, g:i a", time()); ?><span> </h4>
                           
                       </li>



                       <!-- END SUPPORT -->
                       <!-- BEGIN USER LOGIN DROPDOWN -->
                       <li class="dropdown">
                           <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                               <img src="https://selfmademillionaires.biz/members/images/avatar-login.png" style="max-width:30px;" alt="">
                               <span class="username"><?php echo $getuserrow['name']; ?></span>
                               <b class="caret"></b>
                           </a>
                           <ul class="dropdown-menu extended logout">
                               <li><a href="account.php"><i class="icon-user"></i> My Profile</a></li>
                               <li><a href="logout.php"><i class="icon-key"></i> Log Out</a></li>
                           </ul>
                       </li>
                       <!-- END USER LOGIN DROPDOWN -->
                   </ul>
                   <!-- END TOP NAVIGATION MENU -->
               </div>
           </div>
       </div>
       <!-- END TOP NAVIGATION BAR -->
   </div>
   <!-- END HEADER -->
   <!-- BEGIN CONTAINER -->
   <div id="container" class="row-fluid">
      <!-- BEGIN SIDEBAR -->
      <div class="sidebar-scroll">
        <div id="sidebar" class="nav-collapse collapse">

         <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
         <div class="navbar-inverse">
            <form class="navbar-search visible-phone">
               <input type="text" class="search-query" placeholder="Search" />
            </form>
         </div>
         <!-- END RESPONSIVE QUICK SEARCH FORM -->
         <!-- BEGIN SIDEBAR MENU -->
          <ul class="sidebar-menu">
              <li class="sub-menu <?php if(basename($_SERVER['PHP_SELF'])=='index.php'){ echo 'active'; }?>">
                  <a class="" href="index.php">
                      <i class="icon-dashboard"></i>
                      <span>Dashboard</span>
                  </a>
              </li>
              <li class="sub-menu <?php if(basename($_SERVER['PHP_SELF'])=='deposit.php'){ echo 'active'; }?>">
                  <a href="deposit.php" class="">
                      <i class="icon-book"></i>
                      <span>Deposit</span>                      
                  </a>             
              </li>
 		<div class="helpstep2 helpershow margin50">To activate your account, you must first fund it. Here, you could make your
initial deposit with the best trading platform recommended by SMM system.<br><h3 class="next2 helperbtn">NEXT<h3><h3 class="prev1 helperbtn">PREVIOUS<h3><h3 class="closehelper helperbtn">CLOSE<h3></div>

              <li class="sub-menu <?php if(basename($_SERVER['PHP_SELF'])=='opentrades.php'){ echo 'active'; }?>">
                  <a href="opentrades.php" class="">
                      <i class="icon-cogs"></i>
                      <span>Open trades</span>                      
                  </a>               
              </li>
		<div class="helpstep4 helpershow margin50">Here you can see your open trades.<br><h3 class="next4 helperbtn">NEXT<h3><h3 class="prev3 helperbtn">PREVIOUS<h3><h3 class="closehelper helperbtn">CLOSE<h3></div>

              <li class="sub-menu <?php if(basename($_SERVER['PHP_SELF'])=='closetrades.php'){ echo 'active'; }?>">
                  <a href="closetrades.php" class="">
                      <i class="icon-tasks"></i>
                      <span>Close trades</span>                      
                  </a>               
              </li>
		<div class="helpstep5 helpershow margin50">Here you can see your closed trades.<br><h3 class="next5 helperbtn">NEXT<h3><h3 class="prev4 helperbtn">PREVIOUS<h3><h3 class="closehelper helperbtn">CLOSE<h3></div>
              <li class="sub-menu <?php if(basename($_SERVER['PHP_SELF'])=='account.php'){ echo 'active'; }?>">
                  <a href="account.php" class="">
                      <i class="icon-th"></i>
                      <span>Account details</span>                     
                  </a>            
              </li>
		<div class="helpstep9 helpershow margin50">Here you can find information about your personal account.<br><h3 class="next9 helperbtn">NEXT<h3><h3 class="prev8 helperbtn">PREVIOUS<h3><h3 class="closehelper helperbtn">CLOSE<h3></div>

              <li class="sub-menu <?php if(basename($_SERVER['PHP_SELF'])=='withdraw.php'){ echo 'active'; }?>">
                  <a href="withdraw.php" class="">
                      <i class="icon-dollar"></i>
                      <span>Withdraw</span>                     
                  </a>            
              </li>
		<div class="helpstep7 helpershow margin50">Here you can withdraw your money at any time.<br><h3 class="next7 helperbtn">NEXT<h3><h3 class="prev6 helperbtn">PREVIOUS<h3><h3 class="closehelper helperbtn">CLOSE<h3></div>

              <li class="sub-menu <?php if(basename($_SERVER['PHP_SELF'])=='trading-are.php'){ echo 'active'; }?>">
                  <a href="trading-are.php" class="">
                      <i class="icon-fire"></i>
                      <span>Trading Area</span>                      
                  </a>              
              </li>
		<div class="helpstep8 helpershow margin50">If you even do not want to follow our signals and want to trade independently directly on the Trading Platform.<br><h3 class="next8 helperbtn">NEXT<h3><h3 class="prev7 helperbtn">PREVIOUS<h3><h3 class="closehelper helperbtn">CLOSE<h3></div>

              <li class="sub-menu <?php if(basename($_SERVER['PHP_SELF'])=='contacts.php'){ echo 'active'; }?>">
                  <a class="" href="contacts.php">
                      <i class="icon-trophy"></i>
                      <span>Contact US</span>                      
                  </a>                 
              </li>    
		<div class="helpstep11 helpershow margin50">Still have questions? Please Leave your question Here.<br><h3 class="next11 helperbtn">NEXT<h3><h3 class="helperbtn">PREVIOUS<h3><h3 class="prev10 closehelper helperbtn">CLOSE<h3></div> 

 		<div class="helpstep6 helpershow margin50" style="right:45px;">Want to see how much you made so far?<br>
Watch your account balance right here..<br><h3 class="next6 helperbtn">NEXT<h3><h3 class="prev5 helperbtn">PREVIOUS<h3><h3 class="closehelper helperbtn">CLOSE<h3></div>


              <li class="sub-menu <?php if(basename($_SERVER['PHP_SELF'])=='faq.php'){ echo 'active'; }?>">
                  <a class="" href="faq.php">
                      <i class="icon-book"></i>
                      <span>FAQs</span>                      
                  </a>                 
              </li>
		<div class="helpstep10 helpershow margin50">Read the answers of most of the frequently asked questions here.<br><h3 class="next10 helperbtn">NEXT<h3><h3 class="prev9 helperbtn">PREVIOUS<h3><h3 class="closehelper helperbtn">CLOSE<h3></div>                 

              <li>
                  <a class="" href="logout.php">
                    <i class="icon-user"></i>
                    <span>Logout</span>
                  </a>
              </li>
		

              <li>
                 <h4 class="supportnum">Support Number</h4>
                    <span class="supportnumber" style="margin-left: 10px;"><?php echo $getbrandrow['support_no']; ?></span>
                  </a>
              </li>

		<div class="helpstep12 laststep margin50">Still have questions? Talk to our Live support.<br><h3 class="prev11 helperbtn">PREVIOUS<h3><h3 class="closehelper helperbtn">CLOSE<h3></div>   


          </ul>
         <!-- END SIDEBAR MENU -->
      </div>
      </div>
      <!-- END SIDEBAR -->
