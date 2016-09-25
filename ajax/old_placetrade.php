<?php
session_start();
require_once("../variables.php");

$getuser = mysql_query("select * from users where `id`='".$_SESSION['userid']."'");
$getuserrow=mysql_fetch_array($getuser);

$spotid = $getuserrow['spotid'];
$email=$getuserrow['email'];
$asset_id=$_POST['asset'];

//echo $spotid;

$getbrand =mysql_query("select * from brands where ref='".$_SESSION['brand']."'");
$getbrandrow=mysql_fetch_array($getbrand);
 

$getasset=mysql_query("SELECT * FROM `assets` WHERE id='$asset_id'");
$getassetrow=mysql_fetch_array($getasset);


$getcbrand=mysql_query("SELECT * FROM `country` WHERE `iso`='GB'") or die(mysql_error());
$getcbrandrow=mysql_fetch_array($getcbrand);


$amount=$_POST['investment'];
$action=strtolower($_POST['tradetype']);



// TIME SETTINGS FOR BROKER TYPE //

switch ($getbrandrow['api_type']) {
    case "SPOT":
        $min_time = 15; $max_time = 120;
        break;
    case "SPOT-KEY":
        $min_time = 15; $max_time = 120;
        break;
    case "SPOT-BDB":
        $min_time = 15; $max_time = 120;
        break;
    case "TECH FINANCIALS":
        $min_time = 5*60; $max_time = 120*60;
        break;
    case "green":
        echo "Your favorite color is green!";
        break;
    default:
        $min_time = 15; $max_time = 120;
}

// TIME SETTINGS FOR BROKER TYPE END //



// GET OPTION ID FOR BROKER TYPE //

switch ($getbrandrow['api_type']) {
    case "SPOT":
	$option_date = gmdate("Y_m_d H:i:s");
	$min_gmt_date = gmdate("Y-m-d H:i:00", gmmktime() + ($min_time * 60));
	$max_gmt_date = gmdate("Y-m-d H:i:00", gmmktime() + ($max_time * 60));
	 							
	$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Options&COMMAND=view&FILTER[assetId]=$asset_id&FILTER[status]=open&FILTER[startDate][max]=$option_date&FILTER[endDate][min]=$min_gmt_date&FILTER[endDate][max]=$max_gmt_date";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	$result=curl_exec ($ch);
	$xml = simplexml_load_string($result);
							
	//print_r($xml);
	$optionId = $xml->Options->data_0->id;
	$forDemo_profit = $xml->Options->data_0->profit;
	$forDemo_multiplier = $xml->Options->data_0->loss;
        break;

    case "SPOT-KEY":
	$option_date = gmdate("Y_m_d H:i:s");
	$min_gmt_date = gmdate("Y-m-d H:i:00", gmmktime() + ($min_time * 60));
	$max_gmt_date = gmdate("Y-m-d H:i:00", gmmktime() + ($max_time * 60));
	 							
	$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Options&COMMAND=view&FILTER[assetId]=$asset_id&FILTER[status]=open&FILTER[startDate][max]=$option_date&FILTER[endDate][min]=$min_gmt_date&FILTER[endDate][max]=$max_gmt_date";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	$result=curl_exec ($ch);
	$xml = simplexml_load_string($result);
							
	//print_r($xml);
	$optionId = $xml->Options->data_0->id;
	$forDemo_profit = $xml->Options->data_0->profit;
	$forDemo_multiplier = $xml->Options->data_0->loss;
        break;

    case "SPOT-BDB":
	$option_date = gmdate("Y_m_d H:i:s");
	$min_gmt_date = gmdate("Y-m-d H:i:00", gmmktime() + ($min_time * 60));
	$max_gmt_date = gmdate("Y-m-d H:i:00", gmmktime() + ($max_time * 60));

	$headers = array(
	'Content-Type' => 'application/x-www-form-urlencoded',
	);		

        $url=$getbrandrow['api_url'];

        if($getcbrandrow['reg']=='1'){ 
          $url=$getbrandrow['api_url'].'/?integration=bdb_eu';
        }else{
          $url=$getbrandrow['api_url'].'/?integration=bdb_com';
        }

	 							
	$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Options&COMMAND=view&FILTER[assetId]=$asset_id&FILTER[status]=open&FILTER[startDate][max]=$option_date&FILTER[endDate][min]=$min_gmt_date&FILTER[endDate][max]=$max_gmt_date";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	$result=curl_exec ($ch);
	$xml = simplexml_load_string($result);
							
	//print_r($xml);
	$optionId = $xml->Options->data_0->id;
	$forDemo_profit = $xml->Options->data_0->profit;
	$forDemo_multiplier = $xml->Options->data_0->loss;
        break;

    case "TECH FINANCIALS":

                $api_url=$getbrandrow['api_url'];	
		$url = $api_url."trading/getAvailableOptions";						
		$affiliateUserName = $getbrandrow['username'];
		$affiliatePassword = $getbrandrow['password'];
		$my_ip = $_SERVER['REMOTE_ADDR'];		
		$ip = ip2long($my_ip);
		
		for($i=0; $i<30; $i++) {
			$data = "affiliateUserName=$affiliateUserName&affiliatePassword=$affiliatePassword&ip=$ip&numberOfDayes=1&instruments=HiLo&HiLo=HiLo&pageIndex=$i&PageSize=30";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			$result=curl_exec ($ch);
			$decoded = json_decode($result);
								
			foreach($decoded->result as $d) {	
							
				$assetName = $d->availableoptionsresponse_assetName; 
				$asset_name = $getassetrow['name'];
				
				if($assetName == $asset_name) {	                                      
					$expiration = strtotime($d->availableoptionsresponse_expires);					
					$min_gmt_date = strtotime(gmdate("Y-m-d H:i:00", gmmktime() + ($min_time)));
					$max_gmt_date = strtotime(gmdate("Y-m-d H:i:00", gmmktime() + ($max_time)));
					
					if($expiration > $min_gmt_date && $expiration < $max_gmt_date) {
						$optionId = $d->availableoptionsresponse_id;
						break 2;
					}
				}

			}
		}	

        break;

    case "TRADOLOGIC":

		$b2bApiUrl = $getbrandrow['api_url'];
		$accountId = $getbrandrow['campaign_id'];	
		$affiliateUsername = $getbrandrow['username'];
		$authenticationKey = $getbrandrow['password'];
		$my_ip = $_SERVER['REMOTE_ADDR'];
		$userId=$spotid;
		$tradologic_Id =$getassetrow['tradologic_Id'];

		function getChecksum($parameters, $authenticationKey)
		{
		    ksort($parameters);

		    $dataString = '';
		    foreach ($parameters as $value) {
			$dataString = $dataString . $value;
		    }    
		    
		    $dataString = $dataString . $authenticationKey;
		    $checksum = hash('sha256', $dataString);
		    return $checksum;
		}


	    $dataParameters = [         
		'affiliateUsername' => $affiliateUsername,
		'accountId' => $accountId,
		'tradableAssetIds' => $tradologic_Id
	    ];

	   $checksum=getChecksum($dataParameters, $authenticationKey);

	    $url=$b2bApiUrl.'v1/affiliate/options/binary?accountId='.$accountId.'&affiliateUsername='.$affiliateUsername.'&tradableAssetIds='.$tradologic_Id.'&checksum='.$checksum;

	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    $response = curl_exec($curl);
	    curl_close($curl);

	    $jsonResponse = json_decode($response);
	    //print_r($jsonResponse);
            //echo $jsonResponse->data[0]->optionId;

	    $optionId=$jsonResponse->data[0]->optionId;	

        break;

    default:
       $min_time = 5*60; $max_time = 120*60;
}
       //echo $optionId;

// GET OPTION ID FOR BROKER TYPE END //



if(!empty($optionId)){

// PLACE TRADE FOR BROKER TYPE //
	
  switch ($getbrandrow['api_type']) {

    case "SPOT":
        $exp_test = gmmktime() + ($min_time * 60);

	$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Positions&COMMAND=add&product=regular&customerId=$spotid&position=$action&amount=$amount&endDate=$exp_test&assetId=$asset_id&optionId=$optionId";
		
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
								
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	$result=curl_exec ($ch);
        //echo $result.'0000000000';

	$xml_position = simplexml_load_string($result);
	
	// success message

	if($xml_position->operation_status == "successful") {
	  echo '<h4 class="tradeplaced">TRADE PLACED</h4>';
        }else{
          echo '<h4 class="tradeplaced">TRADE FAILED! TRY AGAIN</h4>';
        } 
        break;

    case "SPOT-KEY":
        $exp_test = gmmktime() + ($min_time * 60);

	$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Positions&COMMAND=add&product=regular&customerId=$spotid&position=$action&amount=$amount&endDate=$exp_test&assetId=$asset_id&optionId=$optionId";
		
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
								
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	$result=curl_exec ($ch);
        //echo $result.'0000000000';

	$xml_position = simplexml_load_string($result);
	
	// success message

	if($xml_position->operation_status == "successful") {
	  echo '<h4 class="tradeplaced">TRADE PLACED</h4>';
        }else{
          echo '<h4 class="tradeplaced">TRADE FAILED! TRY AGAIN</h4>';
        } 
        break;

    case "SPOT-BDB":
        $exp_test = gmmktime() + ($min_time * 60);

	$headers = array(
	'Content-Type' => 'application/x-www-form-urlencoded',
	);		

        $url=$getbrandrow['api_url'];

        if($getcbrandrow['reg']=='1'){ 
          $url=$getbrandrow['api_url'].'/?integration=bdb_eu';
        }else{
          $url=$getbrandrow['api_url'].'/?integration=bdb_com';
        }


	$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Positions&COMMAND=add&product=regular&customerId=$spotid&position=$action&amount=$amount&endDate=$exp_test&assetId=$asset_id&optionId=$optionId";
		
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
								
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	$result=curl_exec ($ch);
        //echo $result.'0000000000';

	$xml_position = simplexml_load_string($result);
	
	// success message

	if($xml_position->operation_status == "successful") {
	  echo '<h4 class="tradeplaced">TRADE PLACED</h4>';
        }else{
          echo '<h4 class="tradeplaced">TRADE FAILED! TRY AGAIN</h4>';
        } 
        break;

    case "TECH FINANCIALS":

	$amount = $amount."00";	

	if($action == "call"){ $direction = 49;}elseif($action == "put"){ $direction = 50; }

        $api_url=$getbrandrow['api_url'];	
	$url = $api_url."trading/openTrade";

	$affiliateUserName = $getbrandrow['username'];
	$affiliatePassword = $getbrandrow['password'];
	
		$data = "affiliateUserName=$affiliateUserName&affiliatePassword=$affiliatePassword&ip=$ip&accountId=$spotid&optionId=$optionId&direction=$direction&amount=$amount&strike=0";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result2=curl_exec ($ch);
		//echo $result2;

		$decoded2 = json_decode($result2);
		//error_log($result2);
		if($decoded2->result{0}->opentraderesponse_positionId > 0) {

			$position_amount =  $amount;
			$position_position = "";
			$position_currency = "USD";
			$position_rate = $decoded2->result{0}->opentraderesponse_strike;
			$position_originalRateTimestamp = $decoded2->result{0}->opentraderesponse_orderTime;
			$position_status = "open";
			$position_date = "";
			$position_opstartDate = "";
			$position_opendDate = "";
			$position_opprofit =  "";
			$position_opmultiplier = "";
			$position_opruleId = "";
			$position_id = $decoded2->result{0}->opentraderesponse_positionId;

		
	          echo '<h4 class="tradeplaced">TRADE PLACED</h4>';
		}else{
		  echo '<h4 class="tradeplaced">TRADE FAILED! TRY AGAIN</h4>';
		} 

        break;

    case "TRADOLOGIC":
	      
        $volume=$amount;
	if($action == "call"){ $isCall='1'; }elseif($action == "put"){ $isCall='0'; }

	    $dataParameters = [         
		'affiliateUsername' => $affiliateUsername,
		'optionId' => $optionId,
		'isCall' => $isCall,
		'volume'=> $volume,
		'userId' => $spotid,
		'accountId' => $accountId        
	    ];

	     $checksum=getChecksum($dataParameters, $authenticationKey);
	     $dataParameters['checksum'] = $checksum;
	     $postData = json_encode($dataParameters);
	     $baseUrl=$b2bApiUrl;
	     $resourceRoute='/v1/affiliate/trades/binary';
	     $url = trim($baseUrl, '/') . '/' . trim($resourceRoute, '/');

	     $curl = curl_init();
	     curl_setopt($curl, CURLOPT_URL, $url);	    
	     curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));	    
	     curl_setopt($curl, CURLOPT_HEADER, 0);
	     curl_setopt($curl, CURLOPT_POST, 1);
      	     curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
	     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	     $response = curl_exec($curl);
	     curl_close($curl);
	     $jsonResponse = json_decode($response);
	     //print_r($jsonResponse);


	     if($jsonResponse->messageType =='TradeOrder_Successful') {		
               echo '<h4 class="tradeplaced">TRADE PLACED</h4>';
	     }else{
	       echo '<h4 class="tradeplaced">TRADE FAILED! TRY AGAIN</h4>';
	     } 

        break;


    default:
        $min_time = 15; $max_time = 120;
  }
}
         
?>
