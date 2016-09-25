<?php

session_start();

require_once("variables.php");

if(isset($_POST['email'])) {

	require("validation.php");
	
   	$rules[] = "required,email,Please put your correct email.";
	
   	$errors = validateFields($_POST, $rules);
   
   	if(empty($errors)) {
		$email = addslashes($_POST['email']);

                $getuser = mysql_query("select * from users where email='$email' ORDER BY `id` DESC");
		$getuserrow = mysql_fetch_array($getuser);
		$check = mysql_num_rows($getuser);
		
		if(!empty($check)) {
			
			$to  = $getuserrow['email'];
			
			// subject
			$subject = $getuserrow['name'].', Your New Password';

			// message
			$message = 'Hello '.$getuserrow['name'].',<br><br>

			We\'re sorry you are having trouble logging into your PBM account<br><br>

			Here Is your Password= '.$getuserrow['password'].'<br><br>

			If you continue having problems logging in you can contact us at support@playboymillionaires.co<br><br>


			Happy Times!<br>
			- Playboy Millionaires Team';

			// To send HTML mail, the Content-type header must be set
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

			// Additional headers
			$headers .= 'From: PBM Member <member@playboymillionaires.co>' . "\r\n";


			// Mail it
			$done=mail($to, $subject, $message, $headers);

			if($done){
			   $msg='Please check your Inbox';
			}
	
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
	Item Name: Quit Your Jobs
================================================================================ -->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="msapplication-tap-highlight" content="no">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <title>Forget Password | Playboy Millionaires </title>

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

  <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
  <link href="css/prism.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="js/plugins/perfect-scrollbar/perfect-scrollbar.css" type="text/css" rel="stylesheet" media="screen,projection">


<style>
.img-login{
		margin-top:15px;
	}

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
width: 400px; 
margin: 0 auto;
/*float:right;*/
} 

#login-page{ margin-right:15%; margin-top: 5%; }

}

img.responsive-img, video.responsive-video {
    max-width: 75%;
    height: auto;
}

html i {
    color: #FFA74F;
}

.input-field label {
    color: #FFA74F;
}
[type="checkbox"] + label:before {
    border: 2px solid #FFA74F;
}

input[type=text], input[type=password], input[type=email], input[type=url], input[type=time], input[type=date], input[type=datetime-local], input[type=tel], input[type=number], input[type=search], textarea.materialize-textarea{
color: #708090;
font-weight: bold;
background-color: #d3d3d3;
border-radius: 5px;

}
.medium-small a{
	color: #708090;
}
.medium-small a:hover{
	color: #009933;
}

</style> 
</head>

<body class="loginbody cyan" oncontextmenu="return">
  

  <div id="login-form" class="row">
    <div class="col s12 z-depth-4 card-panel">
      <form class="login-form" action="" method="post" name="fpassword" id="fpassword">
	  <input type="hidden" name="login" value="1">
        <div class="row">
          <div class="input-field col s12 center">
            <!--<img src="images/login-logo.png" alt="" class="circle responsive-img valign profile-image-login" style="border-radius:0px;">-->
			<div class="img-login"><img src="images/login.png" alt="" class="circle responsive-img valign profile-image-login" style="border-radius:0px;"> </div>
            <p class="center login-form-text" style="color:green;">Playboy Millionaires</p>
          </div>
        </div>
		

		
<?php if(isset($msg)) { ?>
<div class="card-panel green "><font size="-1"><?php echo $msg; ?></font></div>
<?php } elseif(isset($errors) && !empty($errors)) {
	//$errors = explode(",",$errors);
?>



					
<div class="card-panel red "><font size="-1">
<?php foreach ($errors as $error) {
   echo "* $error<br>"; } ?>
</font></div>
<?php } elseif(isset($invalid) && $invalid == 1) { ?>
<div class="card-panel red "><font size="-1">
  Please put your correct email
</font></div>
<?php } ?>

	<br><br><br>	
        <div class="row margin">
          <div class="input-field col s12">
            <!--<i class="mdi-social-person-outline prefix"></i>-->
            <input id="email" type="text" name="email" placeholder="Your Email">
            <!--<label for="email" class="center-align">Your Email</label>-->
          </div>
        </div>
	<br><br><br>
        <div class="row">
          <div class="input-field col s12">
            <a onClick='popit=false' href="javascript: document.getElementById('fpassword').submit();" class="btn waves-effect waves-light col s12">Submit</a>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s6 m6 l6">
            <p class="margin right-align medium-small"><a onClick='popit=false' href="login.php">Back to Login</a></p>
          </div>        
        </div>

      </form>
    </div>
  </div>



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

/*(function() 
{
    var bgCounter = 0,
        backgrounds = [
           "images/loginback1.jpg",
           "images/loginback1.jpg",
           "images/loginback1.jpg"
        ];
    function changeBackground()
    {
        bgCounter = (bgCounter+1) % backgrounds.length;
        $('body').css('background', '#000 url('+backgrounds[bgCounter]+') repeat-x');
        setTimeout(changeBackground, 10000);

    }
    changeBackground();
})();
*/

$( document ).ready(function() {

var width = $(window).width();
//alert(width);
    console.log( "ready!" );
});
</script>

</body>

</html>
