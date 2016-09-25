<?php
session_start();
require_once('variables.php');
$selectedPhoneCode = '1';
foreach ($aCountry as $countryName):
    if ($countryName['nicename'] == $country):
        $selectedPhoneCode = $countryName['phonecode'];
    endif;
endforeach;
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
        <title>Register | Quit Your Jobs</title>

        <!-- Favicons-->
        <link rel="icon" href="images/favicon/favicon-32x32.png" sizes="32x32">
        <!-- Favicons-->
        <link rel="apple-touch-icon-precomposed" href="images/favicon/apple-touch-icon-152x152.png">
        <!-- For iPhone -->
        <meta name="msapplication-TileColor" content="#00bcd4">
        <meta name="msapplication-TileImage" content="images/favicon/mstile-144x144.png">
        <!-- For Windows Phone -->
        <!-- jQuery Library -->
        <script type="text/javascript" src="js/jquery-1.11.2.min.js" async></script>


        <!--materialize js-->
        <!--prism-->
        <script type="text/javascript" src="js/prism.js" async></script>
        <!--scrollbar-->
        <script type="text/javascript" src="js/plugins/perfect-scrollbar/perfect-scrollbar.min.js" async></script>

        <!--plugins.js - Some Specific JS codes for Plugin Settings-->
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
        

        <!-- Latest compiled and minified JavaScript -->
        <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>-->

        <!-- CORE CSS-->

        <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
        <link href="css/prism.css" type="text/css" rel="stylesheet" media="screen,projection">
        <link href="js/plugins/perfect-scrollbar/perfect-scrollbar.css" type="text/css" rel="stylesheet" media="screen,projection">
        <link href="css/sweetalert.css" type="text/css" rel="stylesheet" media="screen,projection">
        <style>
		    .container{
				margin-left: 0;
				margin-right: 0;
				padding-left: 0;
				padding-right: 0;
				width: 100%;
			}
			
			.panel-body{
				width: 100%;
			}
		    .btn{
				background: rgb(255, 153, 0);
				border-color: #fff;
			}
			.btn:hover{
				background-color: #e68a00;
				border-color: #fff;
			}
           
            .inner-addon { 
                position: relative; 
            }

            /* style icon */
            .inner-addon .glyphicon {
                position: absolute;
                padding: 8px;
                pointer-events: none;
            }

            /* align icon */
            .left-addon .glyphicon  { right: 0px;}
            .right-addon .glyphicon { right: 0px;}
            .panel-body{
                background: #5cb85c;
            }
            @font-face {
  font-family: 'Glyphicons Halflings';
  src: url('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/fonts/glyphicons-halflings-regular.eot');
  src: url('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/fonts/glyphicons-halflings-regular.eot?#iefix') format('embedded-opentype'), url('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/fonts/glyphicons-halflings-regular.woff') format('woff'), url('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/fonts/glyphicons-halflings-regular.ttf') format('truetype'), url('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/fonts/glyphicons-halflings-regular.svg#glyphicons-halflingsregular') format('svg');
}

        </style>
    </head>
    <body class="loaded">
        <div class="container">
            <div class="row centered-form">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form class="login-form" action="registermaster.php" method="post" name="register" id="register">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group inner-addon left-addon">
                                            <i class="glyphicon glyphicon-user"></i>
                                            <input 
                                                type="text"
                                                onchange="try {
                                                            setCustomValidity('')
                                                        } catch (e) {
                                                        }"
                                                oninvalid="setCustomValidity('Please enter your correct name')"
                                                pattern="^[A-z\s]+$"
                                                placeholder="First Name"
                                                value="" required=""
                                                class="form-control input-sm"
                                                name="fname"
                                                id="fname">
                                            <input id="param" type="hidden" name="param" value="<?php
                                            if (!empty($_REQUEST['param'])) {
                                                echo $_REQUEST['param'];
                                            }
                                            ?>">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group inner-addon left-addon">
                                            <i class="glyphicon glyphicon-user"></i>
                                            <input 
                                                type="text"
                                                onchange="try {
                                                            setCustomValidity('')
                                                        } catch (e) {
                                                        }"
                                                oninvalid="setCustomValidity('Please enter your correct name')"
                                                pattern="^[A-z\s]+$"
                                                placeholder="Last Name"
                                                value="" required=""
                                                class="form-control input-sm"
                                                name="lname" id="lname">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group inner-addon left-addon">
                                            <i class="glyphicon glyphicon-envelope"></i>
                                            <input 
                                                type="email"
                                                placeholder="Email"
                                                class="form-control input-sm"
                                                value=""
                                                required=""
                                                name="email"
                                                id="email">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group inner-addon left-addon">
                                            <i class="glyphicon glyphicon-lock"></i>
                                            <input 
                                                type="password"
                                                placeholder="Password"
                                                class="form-control input-sm"
                                                required=""
                                                value=""
                                                name="password"
                                                id="password">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group inner-addon left-addon">
                                            <i class="glyphicon glyphicon-earphone"></i>
                                            <input 
                                                id="phone" 
                                                class="form-control bfh-phone" 
                                                data-format="+<?php echo $selectedPhoneCode; ?> dddddddddddddd"
                                                type="text"
                                                name="phone"
                                                placeholder="Phone Number"
                                                value=""
                                                required=""
                                                onkeypress="return isNumber(event)">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <input class="btn btn-info btn-block btn-danger"
                                                   type="submit"
                                                   name="register"
                                                   value="Register Now"
                                                   onclick="validate()">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="display: none;" id="errorDiv"><?php echo $_SESSION['registration_failure_message']; ?></div>
        <?php
        if (!empty($_SESSION['registration_failure_message'])) {
            unset($_SESSION['registration_failure_message']);
        }
        ?>
        <!-- ================================================
          Scripts
          ================================================ -->
        <script type="text/javascript" src="js/sweetalert.min.js" async></script>
        <script type="text/javascript" src="js/registration.js" async></script>
        <script type="text/javascript" src="js/bootstrap-formhelpers.js" async></script>
        <script>
            function validate() {
                $('#phone').get(0).setCustomValidity('');
                phoneWithCountryCode = $('#phone').val();
                phoneArray = phoneWithCountryCode.split(" ");
                if (phoneArray[1] === '') {
                    $('#phone').get(0).setCustomValidity('Please fill out your phone number');
                }
                else if (!phoneArray[1].match(/^\d{4,14}$/)) {
                    $('#phone').get(0).setCustomValidity('Please enter a valid phone number');
                }
            }
        </script>
    </body>

</html>
