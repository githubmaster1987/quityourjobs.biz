<?php

session_start();

require_once("variables.php");

if(isset($_POST['login'])) {

	require("validation.php");
	
   	$rules[] = "required,email,Please insert your email.";
	$rules[] = "required,password,Please insert your password.";
	
   	$errors = validateFields($_POST, $rules);
   
   	if(empty($errors)) {
		$email = addslashes($_POST['email']);
		$password = addslashes($_POST['password']);

                $getuser = mysql_query("select * from users where email='$email' and password='$password' ORDER BY `id` DESC");
		$getuserrow = mysql_fetch_array($getuser);
		$check = mysql_num_rows($getuser);
		
		if(!empty($check)) {

                        $_SESSION['userid'] = $getuserrow['id'];
                        $_SESSION['brand'] = $getuserrow['brand'];

                        header('Location: loginmaster.php');			
			//echo "<meta http-equiv=\"Refresh\"content=\"0; URL=index.php?firstTime=1\">";
			exit;	
			
		}
		else {
			$invalid = 1;
			
		}
	}
}

?>

<!DOCTYPE html>
<html lang="en">

<!--================================================================================
	Item Name: Materialize - Material Design Admin Template
	Version: 1.0
	Author: GeeksLabs
	Author URL: http://www.themeforest.net/user/geekslabs
================================================================================ -->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="msapplication-tap-highlight" content="no">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <title>Login | Self-Made Millionaire System </title>

  <!-- Favicons-->
  <link rel="icon" href="images/favicon/favicon-32x32.png" sizes="32x32">
  <!-- Favicons-->
  <link rel="apple-touch-icon-precomposed" href="images/favicon/apple-touch-icon-152x152.png">
  <!-- For iPhone -->
  <meta name="msapplication-TileColor" content="#00bcd4">
  <meta name="msapplication-TileImage" content="images/favicon/mstile-144x144.png">
  <!-- For Windows Phone -->


  <!-- CORE CSS-->
  
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
  
  <link href="css/login.css" type="text/css" rel="stylesheet" media="screen,projection">

  <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
  <link href="css/prism.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="js/plugins/perfect-scrollbar/perfect-scrollbar.css" type="text/css" rel="stylesheet" media="screen,projection">


<style>
@media (max-width:1023px) and (min-width:100px) {

	html {
	display: table;
	margin: auto;
	}

	.loginbody{
	width: 350px;
	} 	


	body {
	display: table-cell;
	vertical-align: middle;
	}
	
}​



body {
display: table-cell;
vertical-align: middle;
}
@media (max-width:10230px) and (min-width:1024px) {
.loginbody{
width: 350px; 
margin: 0 auto; 
} 

#login-page{ margin-right:15%; margin-top: 5%; }

}

img.responsive-img, video.responsive-video {
    max-width: 55%;
    height: auto;
}



input[type=text], input[type=password], input[type=email], input[type=url], input[type=time], input[type=date], input[type=datetime-local], input[type=tel], input[type=number], input[type=search], textarea.materialize-textarea{

}
#modal2{ width: 32% !important; min-height: 620px; top: 5% !important;}
 #modal2 iframe{ height: 500px;  overflow: hidden;} 
  #modal2 iframe body, #modal2 iframe html{ overflow: hidden !important;}
.pull-right{ float: right !important;}

#modal3{width:80% !important; min-height: 180px; top: 25% !important;}
#modal3 iframe{height: 180x; overflow:scroll;}

  #modal3 iframe body, #modal2 iframe html{ overflow: scroll !important;}
  
.pull-right{ float: right !important;}
.sweet-alert {margin-top: -26px !important;}

@media screen only and (max-width: 480px){
	
}
</style> 
</head>

<body class="loginbody yellow" oncontextmenu="return">
  
  
	<div class="login-form">
	  <div class="login-top">
      <form class="login-form" action="login.php" method="post" name="login" id="login">
	  <input type="hidden" name="login" value="1">
        <div class="row">
          <div class="input-field col s12 center">
            <img src="images/login-logo.png" alt="" class="circle responsive-img valign profile-image-login" style="border-radius: 0;">
            <p class="center login-form-text" style="font-family: monospace;">Quit Your Jobs</p>
          </div>
        </div>

<?php if(isset($_GET['register'])) { ?>
		
<div class="card-panel green "><font size="-1">* Registration successful, please login.</font></div>
<?php } elseif(isset($msg) && $msg == 1) { ?>
<div class="card-panel green "><font size="-1">* Login success - Redirecting ...</font></div>
<?php } elseif(isset($errors) && !empty($errors)) {
	//$errors = explode(",",$errors);
?>



					
<div class="card-panel red "><font size="-1">
<?php foreach ($errors as $error) {
   echo "* $error<br>"; } ?>
</font></div>
<?php } elseif(isset($invalid) && $invalid == 1) { ?>
<div class="card-panel red "><font size="-1">
   E-mail or password incorrect
</font></div>
<?php } ?>

		
        <!--<div class="row margin">-->
          <!--<div class="input-field col s12">-->
            <!--<i class="mdi-social-person-outline prefix"></i>-->
			<div class="login-ic">
			<i ></i>
            <input id="email" type="text" name="email" placeholder="Email">
            <!--<label for="email" class="center-align">Email</label>-->
			<div class="clear"></div>
			</div>
          <!--</div>-->
        <!--</div>-->
       <!-- <div class="row margin">-->
          <!--<div class="input-field col s12">-->
            <!--<i class="mdi-action-lock-outline prefix"></i>-->
			<div class="login-ic">
			<i class="icon"></i>
            <input id="password" type="password" name="password" placeholder="Password">
			<div class="clear"></div>
            <!--<label for="password">Password</label>-->
			</div>
          <!--</div>-->
        <!--</div>-->
        <div class="row">          
          <div class="input-field col s12 m12 l12  login-text">
              <input type="checkbox" id="remember-me" />
              <label for="remember-me">Remember me</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s12">
            <a onClick='popit=false' href="javascript: document.getElementById('login').submit();" class="btn waves-effect waves-light col s12 log-bwn">Login</a>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s6 m6 l6">
            <p class="margin medium-small"><a onClick="registration()" href="javascript:void(0);">Register Now!</a></p>
          </div>
          <div class="input-field col s6 m6 l6">
            <p class="margin right-align medium-small"><a onClick='popit=false' href="forgot.php">Forgot&nbsp;password&nbsp;?</a></p>
          </div>          
        </div>

      </form>
	  </div><!-- login-top -->
	  </div><!--login-form -->
   

		
        <!----------Modal Here -------------->
        <div id="modal3" class="modal">
            <div class="modal-header">
                <a class="modal-action modal-close waves-effect waves-red btn-flat pull-right">X</a>
            </div>
            <div id="myModal" class="modal-content">
                <p class="center login-form-text" style="font-family: monospace;">Quit Your Jobs</p>
				<div class="videoframe-container" style="overflow-x:auto;">
                <iframe id="videoiframe" class="videoiframe" src="f_register_b.php?param=login" frameborder="0" width="100%" scrolling="yes" allowfullscreen></iframe>
                </div>
			</div>
        </div>
        <!-------Modal End ----------------->
		


  <!-- ================================================
    Scripts
    ================================================ -->

  <!-- jQuery Library -->
  <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
  <!--materialize js-->
  <script type="text/javascript" src="js/materialize.js"></script>
  <!--prism-->
  <script type="text/javascript" src="js/prism.js"></script>
  <!--scrollbar-->
  <script type="text/javascript" src="js/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>

  <!--plugins.js - Some Specific JS codes for Plugin Settings-->


<script>

(function() 
{
    var bgCounter = 0,
        backgrounds = [
           "images/loginback2.jpg",
           "images/loginback2.jpg",
           "images/loginback2.jpg"
        ];
    function changeBackground()
    {
        bgCounter = (bgCounter+1) % backgrounds.length;
        $('body').css('background', '#000 url('+backgrounds[bgCounter]+')');
        setTimeout(changeBackground, 10000);

    }
    changeBackground();
})();


$( document ).ready(function() {

var width = $(window).width();
//alert(width);
    console.log( "ready!" );
});
function registration() {
    jQuery('#modal3').openModal();
}
</script>

</body>

</html>
