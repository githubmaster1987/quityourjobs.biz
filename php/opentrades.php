<?php

ini_set('display_errors', '0');

session_start();

include("../variables.php");
$db = new ezSQL_mysql($GLOBALS['database_username'],$GLOBALS['database_password'],$GLOBALS['database_database'],$GLOBALS['database_server']);

$email = $_SESSION['email'];

$user = $db->get_row("select * from users where email='$email'");

$id_user = $user->id;

$brand_ref = $user->brand;

$brand = $db->get_row("select * from brands where ref='$brand_ref'");

//$open = $db->get_var("select count(*) from trades where id_user='$id_user' and ");

$spotid = $db->get_var("select spotid from users_spot where id_user='".$user->id."'");



//echo $data;
if(isset($_SESSION['isDemo']) && $_SESSION['isDemo'] == 1) {

	$count = $db->get_var("select count(*) from executed_trades_details where position_status='open' and id_user='$id_user'");
	echo $count;
	
}
else {
	
	
	if($brand->ref == "TRAD") {
		
		$url = $brand->api_url."trading/getTrades";
							
		$affiliateUserName = $brand->username;
		$affiliatePassword = $brand->password;

		$now = gmdate("Y-m-d H:i:s");
		$data = "affiliateUserName=$affiliateUserName&affiliatePassword=$affiliatePassword&accountId=$spotid&fromDate=2015-06-01 00:00:00&toDate=$now&status=18,41,42,43,234";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result=curl_exec ($ch);
		
		//echo $result;

		$decoded = json_decode($result);

		$open = 0;
		
		foreach($decoded->result as $t) {
			$open++;
		}
		echo $open;
		
	}
	else {
	
	
		$data = "api_username=".$brand->username."&api_password=".$brand->password."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid&FILTER[status]=open";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $brand->api_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result=curl_exec ($ch);
		$xml = simplexml_load_string($result);

		//echo $result;
		//print_r($xml);

		$c = 0;
		if($xml->Positions->children()) {
			foreach($xml->Positions->children() as $p) {
				$c++;
			}
		}
		echo $c;
	}
}

?>