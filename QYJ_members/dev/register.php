<?php
require_once('variables.php');

$getcountrycode=mysql_query("SELECT * FROM `country` WHERE `iso`='$countryiso'");
$getcountrycoderow=mysql_fetch_array($getcountrycode);


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
  <title>Register | Self-Made Millionaire System</title>

  <!-- Favicons-->
  <link rel="icon" href="images/favicon/favicon-32x32.png" sizes="32x32">
  <!-- Favicons-->
  <link rel="apple-touch-icon-precomposed" href="images/favicon/apple-touch-icon-152x152.png">
  <!-- For iPhone -->
  <meta name="msapplication-TileColor" content="#00bcd4">
  <meta name="msapplication-TileImage" content="images/favicon/mstile-144x144.png">
  <!-- For Windows Phone -->

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
@media (max-width:1023px) and (min-width:100px) {
.registerbody{}

}

@media (max-width:10230px) and (min-width:1023px) {
.registerbody{ width: 80%; }
}

#country_code{
    width: 10%;
    float: left;
}

#phone{
    float: right;
    width: 70%;
    margin-left: 0px;
}

.phonelabel{padding-left:63px;}



html i {
    color: #FFA74F;
}

.input-field label {
    color: #FFA74F;
}
[type="checkbox"] + label:before {
    border: 2px solid #FFA74F;
}
h4, p{ color: #74B749;}

.row{ margin-bottom: 20px; }

input[type=text], input[type=password], input[type=email], input[type=url], input[type=time], input[type=date], input[type=datetime-local], input[type=tel], input[type=number], input[type=search], textarea.materialize-textarea{
margin: 0 0 0px 0;
color: #FFA74F;
}

.btn:hover, .btn-large:hover, .btn-flat:hover{ color: #74B749; }


</style>  
</head>

<body class="loginbody" oncontextmenu="return" style="background: url(&quot;images/loginback1.jpg&quot;) repeat-x; background-position: -390px 0px;">
  
 


  <div id="login-page" class="">
    <div class="col s12 z-depth-4 card-panel">
      <form class="login-form" action="registermaster.php" method="post" name="register" id="register">
	  <input type="hidden" name="register" value="1">
        <div class="row">
          <div class="input-field col s12 center">
            <h4>Register</h4>
           </div>
        </div>
		
		
        <div class="row margin">
       
          <div class="col-md-6 input-field">
            <i class="mdi-social-person-outline prefix"></i>
            <input id="name" type="text" name="name" value="">
            <label for="name" class="">Complete Name</label>
          </div>
       
          <div class="col-md-6 input-field">
            <i class="mdi-communication-email prefix"></i>
            <input id="email" type="email" name="email" value="">
            <label for="email" class="">Email</label>
          </div>
        </div>
        <div class="row margin">
          <div class="col-md-6 input-field ">
            <i class="mdi-action-lock-outline prefix"></i>
            <input id="password" type="password" name="password" value="" pattern="^\S{6,}$" onchange="this.setCustomValidity(this.validity.patternMismatch ? 'Must have at least 6 characters' : ''); if(this.checkValidity()) register.confirm_password.pattern = this.value;" required="required">
            <label for="password" class="">Password</label>
          </div>
        
          <div class="col-md-6 input-field ">
            <i class="mdi-action-lock-outline prefix"></i>
            <input id="confirm_password" type="password" name="confirm_password" value="" pattern="^\S{6,}$" onchange="this.setCustomValidity(this.validity.patternMismatch ? 'Conf. Passwrod mismatch' : '');" required="required">
            <label for="confirm_password" class="">Password again</label>
          </div>
        </div>
		
        <div class="row margin">
          <div class="col-md-6 input-field ">


		<select name="country" id="country">
                            <option value="<?php echo $countryiso; ?>"><?php echo $country; ?></option>
                          
                            
                        </select>

          </div>

          <div class="col-md-6 input-field ">
            <i class="mdi-communication-call prefix"></i>
            <input id="country_code" type="text" name="country_code" value="<?php echo $getcountrycoderow['phonecode']; ?>" readonly>
            <input id="phone" type="text" name="phone" value="">
            <label for="phone" class="phonelabel">Phone number</label>
          </div>
        </div>
		
        <div class="col-md-12 margin">
          <div class="input-field ">
                    <select name="currency" required>	
                        <option value="USD">USD</option>		
			<option value="EUR">EUR</option>			
		    </select>
	  </div>
        </div>
		
        <div class="row">
          <div class="input-field col s6">
            <p class="margin center medium-small sign-up">Already have an account? <a onClick='popit=false' href="login.php">Login</a></p>
          </div>

          <div class="input-field col s6" style="text-align: right;">
            <button type="submit" name="register" class="btn waves-effect waves-light " onClick="">Register Now</button>
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
  <script type="text/javascript" src="js/plugins.js"></script>
  
  

  

</body>

</html>
