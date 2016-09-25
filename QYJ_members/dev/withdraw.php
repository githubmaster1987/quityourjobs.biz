<? 

session_start();

if(isset($_SESSION['userid'])){
	
	require_once("variables.php");
		
	$getuser = mysql_query("select * from users where `id`='".$_SESSION['userid']."'");
        $getuserrow=mysql_fetch_array($getuser);
	
	$spotid = $getuserrow['spotid'];
        $email= $getuserrow['email'];
	$password = $getuserrow['password'];
        //echo $spotid.$password;
	
	$getbrand =mysql_query("select * from brands where ref='".$_SESSION['brand']."'");
        $getbrandrow=mysql_fetch_array($getbrand);
        //echo $getbrandrow['ref'];
	$title='Self-made Millionaire System Withdraw at '.$getbrandrow['name'];

        $getcbrand=mysql_query("SELECT * FROM `country` WHERE `iso`='GB'") or die(mysql_error());
	$getcbrandrow=mysql_fetch_array($getcbrand);

require_once('include/header.php'); ?>

<!-- BEGIN PAGE -->  
<style>
.collection{
 list-style-type: square;
 font-size: 13px;
}

.collection-item{
 margin-bottom: 7px;
}

.collection-item .badge{
float:right;
}

.howtotrade{
 text-align: center;
 text-transform: uppercase;
}

.howtotrade h3{
    font-weight: bold;
    color: #FFFFFF;
    letter-spacing: 2px;
}

.hometopwidget{
border-bottom: 2px solid #fff;
}

.hometopwidgetbody{
 background: #DE577B;
 color: #fff;
}

.alert-homes{
 background: #4090db;
 color: #fff;
}

.table-advance thead tr th {
    background-color: #4A8BC2;
    color: #FFF;
    line-height: 25px;
    text-align: center;
}

.table-advance tr td {
    border-left-width: 0px;
    vertical-align: middle;
    text-align: center;
}

.widget-title {
    background: #4A8BC2;
    height: 39px;
}

</style>

<div id="main-content" style="margin-left: 0px; margin-bottom: 0px;">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
           <div class="row-fluid">
               <div class="row bg-title bredcrums-menu">
                 <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                     <h4 class="page-title">Withdraw</h4>
                 </div>
                 <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                     <ol class="breadcrumb">
                         <li><a href="index.php">Home</a></li>
                         <li class="active breadcrumb-active">Withdraw</li>
                     </ol>
                 </div>
                 <!-- /.col-lg-12 -->
             </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
		<div class="row-fluid">
		<div class="section">
		<?php

		switch ($getbrandrow['api_type']) {

		    case "TECH FINANCIALS":
			 list($username, $domain) = explode("@", $email);
			$username = substr($username, 0, 15);
			?>
			<form action="<?php echo $getbrandrow['autologin_url'].$getbrandrow['withdraw_url']; ?>" method="post" name="loginNow" id="loginNow" autocomplete="off" target="myFrame">
			<input type="hidden" id="" name="allowNc" value="true" class="loginInput hidden">
			<input type="hidden" name="username" value="<?= $username; ?>" />
			<input type="hidden" name="userpass" value="<?= $password; ?>" />
			<input type="hidden" value="<?php echo $getbrandrow['withdraw_url']; ?>" name="r">
		        <input type="hidden" name="redirect" value="<?php echo $getbrandrow['withdraw_url']; ?>">    
			</form>
			<div class="col-lg-12 col-md-12 col-sm-12 iframe-container">
			<iframe name="myFrame" scrolling="yes"></iframe></div> <?php
			break;

		    case "SPOT": ?>
			<form action="<?php echo $getbrandrow['autologin_url']; ?>" method="post" name="loginNow" id="loginNow" autocomplete="off" target="myFrame">
			<input type="hidden" name="email" value="<?php echo $email; ?>" />
			<input type="hidden" name="password" value="<?php echo $password; ?>" />
			<input type="hidden" name="redirect" value="<?php echo $getbrandrow['withdraw_url']; ?>">
			<input type="hidden" name="extRedir" value="<?php echo $getbrandrow['withdraw_url']; ?>">
			</form>
			<div class="col-lg-12 col-md-12 col-sm-12 iframe-container">
			<iframe id="iframe" name="myFrame" style="" scrolling="yes"></iframe></div> <?php
                        break;

		    case "SPOT-KEY": ?>
			<form action="<?php echo $getbrandrow['autologin_url']; ?>" method="post" name="loginNow" id="loginNow" autocomplete="off" target="myFrame">
			<input type="hidden" name="email" value="<?php echo $email; ?>" />
			<input type="hidden" name="password" value="<?php echo $password; ?>" />
			<input type="hidden" name="redirect" value="<?php echo $getbrandrow['withdraw_url']; ?>">
			<input type="hidden" name="extRedir" value="<?php echo $getbrandrow['withdraw_url']; ?>">
			</form>
			<div class="col-lg-12 col-md-12 col-sm-12 iframe-container">
			<iframe id="iframe" name="myFrame" style="" scrolling="yes"></iframe></div> <?php
                        break;

		    case "SPOT-RALLY": ?>
			<form action="<?php echo $getbrandrow['autologin_url']; ?>" method="post" name="loginNow" id="loginNow" autocomplete="off" target="myFrame">
			<input type="hidden" name="email" value="<?php echo $email; ?>" />
			<input type="hidden" name="password" value="<?php echo $password; ?>" />
			<input type="hidden" name="redirect" value="<?php echo $getbrandrow['withdraw_url']; ?>">
			<input type="hidden" name="extRedir" value="<?php echo $getbrandrow['withdraw_url']; ?>">
			</form>
			<div class="col-lg-12 col-md-12 col-sm-12 iframe-container">
			<iframe id="iframe" name="myFrame" style="" scrolling="yes"></iframe></div> <?php
                        break;

		  case "SPOT-BDB":

			$headers = array(
			'Content-Type' => 'application/x-www-form-urlencoded',
			);		

			$url=$getbrandrow['api_url'];

			if($getcbrandrow['reg']=='1'){ 
			  $url=$getbrandrow['api_url'].'/?integration=bdb_eu';
			}else{
			  $url=$getbrandrow['api_url'].'/?integration=bdb_com';
			}
		
			$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Customer&COMMAND=validate&FILTER[email]=$email&FILTER[password]=$password";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			$result=curl_exec ($ch);
			$xml = simplexml_load_string($result);
                        //print_r($xml);

			$customerId=$xml->Customer->id;
			$authKey=$xml->Customer->authKey;

			if($getcbrandrow['reg']=='1'){ 
			    $platformurl='https://eu.bancdebinary.com/?rs=1&action=enterAccount&goto='.$getbrandrow['withdraw_url'].'&customerId='.$customerId.'&uKey='.$authKey.'&lang=en';
			}else{
			    $platformurl='https://bancdebinary.com/?rs=1&action=enterAccount&goto='.$getbrandrow['withdraw_url'].'&customerId='.$customerId.'&uKey='.$authKey.'&lang=en';
			}

			echo '<div class="col-lg-12 col-md-12 col-sm-12 iframe-container"><iframe id="iframe" name="myFrame" style="" scrolling="yes" src="'.$platformurl.'"></iframe></div>';
	 		break;


		  case "SPOT-USA":		

			echo '<div class="col-lg-12 col-md-12 col-sm-12 iframe-container"><iframe id="iframe" name="myFrame" style="" scrolling="yes" src="'.$getbrandrow['autologin_url'].$getuserrow['session_id'].'&extRedir='.$getbrandrow['withdraw_url'].'"></iframe></div>';
	 		break;

		  case "OPTECK":
		        require_once('include/OpteckAPI.php');
		        $affiliateID = $getbrandrow['username'];
		        $partnerID = $getbrandrow['password'];

		        $affiliate = new OpteckAPI($affiliateID, $partnerID);

			$getLeadDetails = $affiliate->request('getLeadDetails', array('email' => $email));
		        $decoded = json_decode($getLeadDetails); 
			//print_R($decoded);
			$url = $decoded->data->websiteLink;
			echo '<div class="col-lg-12 col-md-12 col-sm-12 iframe-container"><iframe id="iframe" name="myFrame" style="" scrolling="yes" src="'.$url.'"></iframe></div>';
		       break;

		  case "MARKETPULSE":
			$service_url = $getbrandrow['api_url'].'CreateToken';
			$operatorName = $getbrandrow['name'];
			$affilate_name = $getbrandrow['username'];
			$operatorSecretKey = $getbrandrow['password'];
			$affilate_id =  $getbrandrow['campaign_id'];

			//echo $service_url;
			list($username, $domain) = explode("@", $email);
			$username = substr($username, 0, 15);

			date_default_timezone_set("UTC");
			$objDateTime = date('Y-m-d\TH:i:s\Z');
			$unsignedText = $affilate_name . $objDateTime . $operatorSecretKey;
			$utf_string = utf8_encode($unsignedText);
			$hash_string = hash_hmac("sha256", $utf_string, $operatorSecretKey, true);
			$hash_string_64 = base64_encode($hash_string);
			$urlEncode = urlencode($hash_string_64);

			$curl_post_data = array(
				'operatorName' => $operatorName,
				'affiliateName' => $affilate_name,
				'timestamp' => $objDateTime,
				'signature' => $urlEncode,
				'affiliateID' => $affilate_id,
				'username' =>$username	
			);

			$data_string=json_encode($curl_post_data); 
			$curl = curl_init($service_url); 
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);                                                                   
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);                                                                  
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                                                      
			curl_setopt($curl, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));
			$result = curl_exec($curl);
			curl_close($curl);
			$jsonResponse=json_decode($result);
			//print_r($result);
			$token = $jsonResponse->data->token;
			echo '<div class="col-lg-12 col-md-12 col-sm-12 iframe-container"><iframe id="iframe" name="myFrame" style="" scrolling="yes" src="'.$getbrandrow['autologin_url'].$token.'&redirectUrl='.$getbrandrow['withdraw_url'].'?ShowMessage=True"></iframe></div>';
		        break;

		  case "PANDA":		

			echo '<div class="col-lg-12 col-md-12 col-sm-12 iframe-container"><iframe id="iframe" name="myFrame" style="" scrolling="yes" src="'.$getbrandrow['autologin_url'].$getuserrow['email'].'&password='.$getuserrow['password'].'&url='.$getbrandrow['withdraw_url'].'"></iframe></div>';
	 		break;


		  case "TRADOLOGIC":		

			echo '<div class="col-lg-12 col-md-12 col-sm-12 iframe-container"><iframe id="iframe" name="myFrame" style="" scrolling="yes" src="'.$getbrandrow['withdraw_url'].$getuserrow['session_id'].'"></iframe></div>';
	 		break;


                  case "AIRSOFT":
                        $url = $getbrandrow['autologin_url'] . '&key=' . $getbrandrow['password'] . '&client_id=' . $getuserrow['spotid'] .
                            '&redirect_url=' . $getbrandrow['withdraw_url'];
						$curl = curl_init($url); 
						curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);					
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                                                      					
						$result = curl_exec($curl);
						curl_close($curl);
						$jsonResponse=json_decode($result);
						$url = $jsonResponse->autoLoginClient->link;
						echo '<div class="col-lg-12 col-md-12 col-sm-12 iframe-container"><iframe id="iframe" name="myFrame" style="" scrolling="yes" src="'. $url .'"></iframe></div>';
	 		break;

		    default:
			echo "Your favorite color is neither red, blue, nor green!";
		}
?>

		</div>



            
            
            </div>
            <!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>	
	
      <!-- END PAGE -->  
<script>
$( document ).ready(function() {
  $("#loginNow").submit();
});
</script>

<?php require_once('include/footer.php'); ?>

<?php
if($_SESSION['is_demo'])
echo "<script type='text/javascript' async='async' defer='defer' data-cfasync='false' src='https://mylivechat.com/chatinline.aspx?hccid=71364593'></script>";

}
else {
	echo  "<meta http-equiv=\"Refresh\"content=\"0; URL=login.php\">";
 	exit;
}
?>
