<?php
	$siteURL = "http://www.exbino.com";
	$sitePlatform = "http://spotplatform.exbino.com/";
	$cookieDomain = ".exbino.com";
$_POST['email']='zyxjhaa@gmail.com';
$_POST['password'] = '123456789';
$_GET['token'] ='100FUCKddd';
	/*
	usage:
	
	Login with credential:
	Create a form with 2 fields and POST method:
	1. email (the input name must be email)
	2. Password
	The form action should direct to domain/signin.php
	
	Login With Token
	Create a link in this format
	http://www.example.com/signin.php?token=YOURSICRETOKEN
	
	

	*/
	
	if(isset($_POST['email']) && isset($_POST['password'])   ) {
		define('LOGINTYPE','byCredentials');
		$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
		$password = $_POST['password'];
		if($email == false) {
			header('Location: '.$siteURL);	
		}
	}
	else if(isset($_GET['token'])){
		define('LOGINTYPE','byToken');
		$token = $_GET['token'];	
	}
	else
		header('Location: '.$siteURL);
	
	$lang=(isset($_REQUEST['lang']) && $_REQUEST['lang'] !== "")? $_REQUEST['lang'] : 'en';
	
	if(isset($_REQUEST['url']) && $_REQUEST['url'] !== "")
		$redirect = $_REQUEST['url'];
	else {
		$redirect= "";
	}	
		
		?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>

        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <title>Platform Test</title>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.js"></script>
        <script type="text/javascript" src="<?php echo $sitePlatform;?>/SpotOptionPlugin.js?ver=3.6.1"></script>

        <script type="text/javascript">
            SO.load({
                lang : 'en',
                cookieOptions : {
                    domain : '<?php echo $cookieDomain;?>'
                },
                packages : {                   
                },
                googleFonts: {
                    families: [ 'Roboto+Condensed:400,300,700:latin,latin-ext,cyrillic' ]
                }
            });
        </script>
    </head> 
<style>
.loader {
  width: 150px;
  height: 150px;
  line-height: 150px;
  margin: 15px auto;
  position: relative;
  box-sizing: border-box;
  text-align: center;
  z-index: 0;
  text-transform: uppercase;
}

.loader:before,
.loader:after {
  opacity: 0;
  box-sizing: border-box;
  content: "\0001";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border-radius: 100px;
  border: 5px solid #050505;
  box-shadow: 0 0 50px #050505, inset 0 0 50px #050505;
}

.loader:after {
  z-index: 1;
  -webkit-animation: gogoloader 3s infinite 1s;
}

.loader:before {
  z-index: 2;
  -webkit-animation: gogoloader 3s infinite;
}

@-webkit-keyframes gogoloader {
  0% {
    -webkit-transform: scale(0);
    opacity: 0;
  }
  50% {
    opacity: 1;
  }
  100% {
    -webkit-transform: scale(1);
    opacity: 0;
  }
}

</style>
    <body>
        <!-- Clock and Balance packages will go here -->
      
        
        <!-- Platform package will go in here -->
     <center> 
<div class="loader">Loading</div>
        <div class="loading" style="font-size: 20px;">
			Welcome to exbino.</br>
			Please wait for the page to redirect. Thank you.
		</div>
		</center>

 <script type="text/javascript">
 $(document).bind('spotoptionPlugin.ready', logMeIn);
	function logMeIn(){
		<?php if(LOGINTYPE == 'byCredentials' ) :?>
		          SO.model.Customer.login({
                    email: '<?php echo $_POST['email']; ?>',
                    password: '<?php echo $_POST['password']; ?>',
                    onSuccess: function() {
                        window.location = '<?php if (isset($_POST['extRedir'])) {
					    echo $_POST['extRedir'];
						} else {
					    echo "https://www.exbino.com/my-account/?activeZone=Deposit&lang=en";
						} ?>';
             	  	  },
	               	  onFail: function() {
	               	  	window.location ='<?php echo $siteURL ?>';
	               	  }
                });
								
                <?php elseif(LOGINTYPE == 'byToken'):?>
				SO.model.Customer.loginByAuth({
									auth: '<?php echo $token;?>', 									
									onSuccess : function(){
										<?php if ($lang == 'en') { ?>
											window.location='<?php  echo $url=='' ? "$siteURL" : "$siteURL/$url";  ?>';
										<?php } else { ?>
											window.location='<?php  echo $url=='' ? "$siteURL/$lang" : "$siteURL/$url/$lang";  ?>';
										<?php } ?>
										},
										onFail: function() {
						               	window.location ='<?php echo $siteURL ?>';
						             	},
						               	onError: function() {
						               	window.location ='<?php echo $siteURL ?>';
						               	}
								});
                <?endif;?>		
								
	}

</script>
</body>
</html>

