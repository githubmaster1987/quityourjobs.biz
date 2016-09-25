<?php

ini_set('display_errors', '0');

session_start();

if(isset($_SESSION['email']) || isset($_COOKIE['MillionaryMaker'])) {
	
	$email = $_SESSION['email'];
	
	require_once("variables.php");
	$db = new ezSQL_mysql($GLOBALS['database_username'],$GLOBALS['database_password'],$GLOBALS['database_database'],$GLOBALS['database_server']);
		
	$user = $db->get_row("select * from users where email='$email'");
	
	$spotid = $db->get_var("select spotid from users_spot where id_user='".$user->id."'");
	
	$brand = $db->get_row("select * from brands where ref='".$user->brand."'");
	
	// getting the client balance
	$data = "api_username=".$brand->username."&api_password=".$brand->password."&MODULE=Customer&COMMAND=view&FILTER[id]=$spotid";
	//echo $data;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $brand->api_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	//curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain')); 
	$result=curl_exec ($ch);
	$xml = simplexml_load_string($result);

	//print_r($xml);
	/*
	$balance = $xml->Customer->data_0->accountBalance;
	
	if(empty($balance)) {
		// redirect no balance users to demo page
		echo  "<meta http-equiv=\"Refresh\"content=\"0; URL=main_demo.php?firstTime=1\">";
		exit;

	}
	*/
	$balance = $db->get_var("select balance from demo_balances where id_user='".$user->id."' and spotid='$spotid'");
	
	
	$currency = $xml->Customer->data_0->currency;
	
	$total_trades = $db->get_var("select count(*) from trades where id_user='".$user->id."' and status='EXECUTED'");

	
	$new_traders = $db->get_var("select sum(value) from new_traders where new_traders.date='".date("Y-m-d")."'");

	$data = "api_username=".$brand->username."&api_password=".$brand->password."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid&FILTER[date][min]=".date("Y-m-d")." 00:00&FILTER[date][max]=".date("Y-m-d")." 23:59";
	//echo $data;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $brand->api_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	$result=curl_exec ($ch);
	$xml = simplexml_load_string($result);
	
	$payout = 0;
	$won = 0;
	$lost = 0;
	$total = 0;
	
	foreach($xml->Positions->children() as $p) {
		if($p->status == "open") {
			continue;
		}
		if($p->status == "lost") {
			continue;
		}
		$payout = $payout + $p->payout;
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
	<title>Dashboard | The Millionaires Biz Software</title>

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
    <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection">


    <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
    <link href="js/plugins/material-preloader/materialPreloader.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="js/plugins/perfect-scrollbar/perfect-scrollbar.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="js/plugins/jvectormap/jquery-jvectormap.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="js/plugins/chartist-js/chartist.min.css" type="text/css" rel="stylesheet" media="screen,projection">


</head>

<body>
    <!-- Start Page Loading -->
    <div id="loader-wrapper">
        <div id="loader"></div>        
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>
    <!-- End Page Loading -->

    <!-- //////////////////////////////////////////////////////////////////////////// -->

	<?php include("header.php"); ?>

    <!-- //////////////////////////////////////////////////////////////////////////// -->

    <!-- START MAIN -->
    <div id="main">
        <!-- START WRAPPER -->
        <div class="wrapper">

			<?php include("sidebar.php"); ?>

            <!-- //////////////////////////////////////////////////////////////////////////// -->

            <!-- START CONTENT -->
            <section id="content">

                <!--start container-->
                <div class="container">

                    <!-- //////////////////////////////////////////////////////////////////////////// -->

					<?php include("cards.php"); ?>
					
					
					<?php include("main_dashboard.php"); ?>
				

                    <!-- //////////////////////////////////////////////////////////////////////////// -->
			<div id="signals">
			<div class="row">
				<div class="col s12">
					<div class="card">
						<!--responsive table-->
             
							<span class="card-title">Signals</span>
              
                
						  <table class="centered">
						  <thead>
							<tr>
								<th data-field="id">Asset</th>
								<th data-field="name">CALL Strike</th>
								<th data-field="price">PUT Strike</th>
								<th data-field="total">Current Rate</th>
								<th data-field="status">Subscribed</th>
							</tr>
						  </thead>
							<tbody>
							<?php 
							$assets = $db->get_results("select * from assets");
							foreach($assets as $a) { 
							
								$series = $db->get_row("select * from series where asset_id='".$a->id."' and stampt>NOW() - INTERVAL 1 HOUR");
							?>
							<tr>
							  <td valign="center"><img src="<?= $brand->logos_url.$a->id; ?>.png" alt="<?= $a->name; ?>"></td>
							  <td><?= $series->high; ?></td>
							  <td><?= $series->low; ?></td>
							  <td><span id="rate_<?= $a->id; ?>"></span></td>
							  <td><a class="btn waves-effect waves-light blue">YES</a></td>
							</tr>
							<?php } ?>
						  </tbody>
						</table>
                
					</div>
				</div>
			</div>
			</div>
                <!--end container-->
				</div>
            </section>
            <!-- END CONTENT -->

            <!-- //////////////////////////////////////////////////////////////////////////// -->

        </div>
        <!-- END WRAPPER -->

    </div>
    <!-- END MAIN -->
	<?php if($_GET['firstTime'] == 1) {

		$user = $db->get_row("select * from users where email='$email'");
		
		$password = $user->password;
		//$brand = $user->brand;
		
		//$brand_data = $db->get_row("select name,autologin_url from brands where ref='$brand'");
		//$brand_name = $brand->name;
		$login_url = $brand->autologin_url;

	?>

	<form action="<?php echo $login_url; ?>" method="post" name="loginNow" id="loginNow" autocomplete="off" target="myFrame">
	<input type="hidden" name="email" value="<?= $email; ?>" />
	<input type="hidden" name="password" value="<?= $password; ?>" />
	</form>
	<iframe height="0" width="0" name="myFrame" style="visibility:hidden;display:none"></iframe>
	<script>
	document.forms["loginNow"].submit();
	</script>
	<?php } ?>
	</div>


	<?php include("footer.php"); ?>

</body>

</html>
<?php
}
else {
	echo  "<meta http-equiv=\"Refresh\"content=\"0; URL=login.php\">";
 	exit;
}
?>