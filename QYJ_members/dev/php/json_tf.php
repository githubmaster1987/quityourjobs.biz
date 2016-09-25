<?php

//ini_set('display_errors', '0');

session_start();

header('Content-Type: application/json');

include("../variables.php");
$db = new ezSQL_mysql($GLOBALS['database_username'],$GLOBALS['database_password'],$GLOBALS['database_database'],$GLOBALS['database_server']);

$email = $_SESSION['email'];

$asset_name = addslashes($_GET['asset_name']);
$brand_ref = addslashes($_GET['brand_ref']);

$brand = $db->get_row("select * from brands where ref='$brand_ref'");

$url = $brand->api_url."trading/getAvailableOptions";
						
$affiliateUserName = $brand->username;
$affiliatePassword = $brand->password;

$my_ip = $_SERVER['REMOTE_ADDR'];
//$my_ip = "31.154.4.234";
list($octet1, $octet2, $octet3, $octet4) = explode(".", $my_ip);

$ip = ($octet1 * 16777216) + ($octet2 * 65536) + ($octet3 * 256) + ($octet4);
				
for($i=0; $i<30; $i++) {
	$data = "affiliateUserName=$affiliateUserName&affiliatePassword=$affiliatePassword&ip=$ip&numberOfDayes=1&instruments=HiLo&HiLo=HiLo&pageIndex=$i&PageSize=30";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	$result=curl_exec ($ch);
	$decoded = json_decode($result);

	//print_r($decoded);
					
	foreach($decoded->result as $d) {
						
		$assetName = $d->availableoptionsresponse_assetName;
		if($assetName == $asset_name) {	
			$rate = $d->availableoptionsresponse_strike;
			if($rate) { 
				echo json_encode(array("data" => $rate));
				break 2;
			}
		}
	}

}



?>