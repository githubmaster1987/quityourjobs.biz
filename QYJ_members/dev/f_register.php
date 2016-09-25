<?php
session_start();
require_once('variables.php');
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
  <title>Register | Self-made Millionaire System</title>

  <!-- Favicons-->
  <link rel="icon" href="images/favicon/favicon-32x32.png" sizes="32x32">
  <!-- Favicons-->
  <link rel="apple-touch-icon-precomposed" href="images/favicon/apple-touch-icon-152x152.png">
  <!-- For iPhone -->
  <meta name="msapplication-TileColor" content="#00bcd4">
  <meta name="msapplication-TileImage" content="images/favicon/mstile-144x144.png">
  <!-- For Windows Phone -->
<!-- jQuery Library -->
  <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
  

  <!--materialize js-->
  <script type="text/javascript" src="js/materialize.js"></script>
  <!--prism-->
  <script type="text/javascript" src="js/prism.js"></script>
  <!--scrollbar-->
  <script type="text/javascript" src="js/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>

  <!--plugins.js - Some Specific JS codes for Plugin Settings-->
  <script type="text/javascript" src="js/plugins.js"></script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

  <!-- CORE CSS-->
  
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
 
  <link href="css/page-center.css" type="text/css" rel="stylesheet" media="screen,projection">

  <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
  <link href="css/prism.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="js/plugins/perfect-scrollbar/perfect-scrollbar.css" type="text/css" rel="stylesheet" media="screen,projection">

<style>


#country_code{
    width: 10%;
    float: left;
}

#phone{
    float: right;
    width: 75%;
    margin-left: 0px;
}

.phonelabel{padding-left:63px;}


.login-form input[type=text],input[type=password], input[type=email]{
margin:0 0 3px 0;
margin-left:3rem;
}

.card-panel{
padding:10px 20px;
}

.input-field .prefix{
margin-top:8px;
}

.input-field {
    position: relative;
    margin-top: 0rem; 
}

.input-field label.active {

    top: 0.8rem;
    left: 0.75rem;
    font-size: 1rem;
    -webkit-transform: translateY(-140%);
    -moz-transform: translateY(-140%);
    -ms-transform: translateY(-140%);
    -o-transform: translateY(-140%);
    transform: translateY(-140%);
    opacity:0;
}

.input-field label {
    color: #9e9e9e;
    position: absolute;
    font-size: 0.8rem;
    cursor: text;
    -webkit-transition: 0.2s ease-out;
    -moz-transition: 0.2s ease-out;
    -o-transition: 0.2s ease-out;
    -ms-transition: 0.2s ease-out;
    transition: 0.2s ease-out;
}

.noticemessage{
    text-align: center;
    margin-top: 5px;
    font-weight: bold;
}

</style>  
</head>

<body class="">
 


  <div id="login-page" class="">
    <div class="col s12">
      <form class="login-form" action="registermaster.php" method="post" name="register" id="register" target="_top">		
		
        <div class="row margin">
       
          <div class="col-md-12 input-field">
            <i class="mdi-social-person-outline prefix"></i>           
            <input id="fname" type="text" name="fname" required value="">
	    <input id="param" type="hidden" name="param" value="<?php if(!empty($_REQUEST['param'])) { echo $_REQUEST['param']; } ?>">
            <label for="fname" class="">First Name</label>
          </div>

          <div class="col-md-12 input-field">
            <i class="mdi-social-person-outline prefix"></i>
            <input id="lname" type="text" name="lname" required value="">
            <label for="lname" class="">Last Name</label>
          </div>
       
          <div class="col-md-12 input-field">
            <i class="mdi-communication-email prefix"></i>
            <input id="email" type="email" name="email" required value="">
            <label for="email" class="">Email</label>
          </div>

          <div class="col-md-12 input-field ">
            <i class="mdi-action-lock-outline prefix"></i>
            <input id="password" type="password" name="password" value="" pattern="^(?=.*\d)(?=.*[A-Za-z])(?!.*\s).*$" onchange="this.setCustomValidity(this.validity.patternMismatch ? 'Password should include one number (0-9).' : ''); if(this.checkValidity()) register.confirm_password.pattern = this.value;" required="required">
            <label for="password" class="">Password</label>
          </div>
        
          <div class="col-md-12 input-field ">
            <i class="mdi-action-lock-outline prefix"></i>
            <input id="confirm_password" type="password" name="confirm_password" value="" pattern="^(?=.*\d)(?=.*[A-Za-z])(?!.*\s).*$" onchange="this.setCustomValidity(this.validity.patternMismatch ? 'Confirm Passwrod mismatch' : '');" required="required">
            <label for="confirm_password" class="">Password again</label>
          </div>

          <div class="col-md-12 input-field ">
            <i class="mdi-action-language prefix"></i>
		 <input type="text" name="country" id="country" value="<?php echo $country; ?>" readonly>                 
          </div>

          <div class="col-md-12 input-field ">
            <i class="mdi-communication-call prefix"></i>
            <input id="country_code" type="text" name="country_code" value="+<?php echo $getcountrycoderow['phonecode']; ?>" readonly>
            <input id="phone" type="text" name="phone" value="" required>
            <label for="phone" class="phonelabel">Phone number</label>
          </div>
        </div>

		
        <div class="row"> 
          <div class="" style="text-align: center;">
            <button type="submit" name="register" class="btn waves-effect waves-light " onClick="">Register Now</button>
          </div>
        

        </div>
      </form>
    </div>
  </div>



  <!-- ================================================
    Scripts
    ================================================ -->

  


  


</body>

</html>
