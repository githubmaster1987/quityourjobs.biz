<?php

require_once("../variables.php");

// WE Will USE EMPI Details to get Data //
	
$api_url = 'http://www.empireoption.com/api/';
$api_username = 'imoffer_usr';
$api_password = '8kk8iT1ZpX';

$getassets = mysql_query("SELECT * FROM `assets`");

while($getassetsrow=mysql_fetch_array($getassets)) {

	$min_gmt_date = gmdate("Y-m-d H:i:00", gmmktime() - 3600);
	$data ="api_username=$api_username&api_password=$api_password&MODULE=AssetsHistory&COMMAND=view&FILTER[assetId]=".$getassetsrow['id']."&FILTER[date][min]=$min_gmt_date";
	echo $data;					
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$api_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			
	$result=curl_exec($ch);
				
	$xml = simplexml_load_string($result);
	
	//print_r($xml);
		
	$high = 0;
	$low = 0;
	$close = 0;
	$open = 0;
			
	$counter = 0;

	if(count($xml->AssetsHistory->children()) > 0) {
		foreach($xml->AssetsHistory->children() as $aa) {
			//$date = $aa->date;
			$rate = $aa->rate;
			if($counter == 0) { $open = $rate; $high = $rate; $close = $rate; $low = $rate; }
				
			if((double)$rate > (double)$high) $high = $rate;
			if((double)$rate < (double)$low) $low = $rate;
				
			$counter++;
					
			//echo "insert into series values('', '$asset', '$date', '$rate')";
			//exit;
		}
		$close = $rate;

		// $db->query("insert into series values(NOW(), '$brand', '".$a->id."', '".$a->name."', '$open', '$high', '$low', '$close')");
		echo "<b>".$a->name."</b><br>";
		echo "Open:$open<br>";
		echo "High:$high<br>";
		echo "Close:$close<br>";
		echo "Low:$low<br><br>";
		
	
		
		
	}
}




?>
