<?php

ini_set('display_errors', '0');

session_start();

include("../variables.php");
$db = new ezSQL_mysql($GLOBALS['database_username'],$GLOBALS['database_password'],$GLOBALS['database_database'],$GLOBALS['database_server']);

$email = $_SESSION['email'];

$user = $db->get_row("select * from users where email='$email'");

$id_user = $user->id;

$brand_ref = $user->brand;

$asset_id = addslashes($_GET['asset_id']);
$price = addslashes($_GET['price']);

// check for auto trade
$subscribed = $db->get_var("select subscribed from users_settings where id_user='$id_user'");
//echo $subscribed;

// closing demo trades
$d_trades = $db->get_results("select id from trades where id_user='$id_user' and asset_id='$asset_id' and status='EXECUTED'");
//error_log("select id from trades where id_user='$id_user' and asset_id='$asset_id' and status='EXECUTED'", 0);
foreach($d_trades as $dt) {
	checkDemoClose($price, $dt->id);
}

if($subscribed == 1) {

	$trades = $db->get_results("select * from trades where id_user='$id_user' and asset_id='$asset_id' and status='PENDING'");
	//echo "select * from trades where id_user='$id_user' and asset_id='$asset_id' and status='PENDING'";
	//print_r($trades);
	if(!empty($trades)) {
		foreach($trades as $t) {
			
			
			
			if($t->type == "CALL") {
				if((double)$price > (double)$t->target) {
					//if(1==1){
					//$db->query("update trades set status='TESTING' where id='".$t->id."'");
					//echo "CALL";

					 trade($brand_ref, $t->asset_id, "call", $id_user, $t->id, $price);
					
					
					break;
					
				}
				
			}
			elseif($t->type == "PUT") {
				if((double)$price < (double)$t->target) {
					//$db->query("update trades set status='TESTING' where id='".$t->id."'");
					//echo "PUT";
					 trade($brand_ref, $t->asset_id, "put", $id_user, $t->id, $price);
					break;
				}
				
			}
		}
	}
}
else {
	
	$trades = $db->get_results("select * from trades where id_user='$id_user' and asset_id='$asset_id' and status='PENDING'");
	if(!empty($trades)) {
		foreach($trades as $t) {
			
			if($t->type == "CALL") {
				if((double)$price > (double)$t->target) {
					 manual($brand_ref, $t->asset_id, "call", $id_user, $t->id, $price);
					break;
					
				}
				
			}
			elseif($t->type == "PUT") {
				if((double)$price < (double)$t->target) {
					 manual($brand_ref, $t->asset_id, "put", $id_user, $t->id, $price);
					break;
				}				
			}
		}
	}
}
function trade($brand_ref, $asset_id, $action, $id_user, $id_trade, $price4demo) 
{
	
	global $db;
	
	if($brand_ref == "TRAD") {
		$min_time = 5*60; $max_time = 120*60;
	}
	else {
		$min_time = 15; $max_time = 120;
	}
	$brand = $db->get_row("select * from brands where ref='$brand_ref'");
	
	$amount = $db->get_var("select amount from users_settings where id_user='$id_user'");
	$spotid = $db->get_var("select spotid from users_spot where id_user='$id_user'");
	
	//echo $amount;
	//exit;
	
	// first we get the option id
	if($brand_ref == "TRAD") {
		
		$url = $brand->api_url."trading/getAvailableOptions";
						
		$affiliateUserName = $brand->username;
		$affiliatePassword = $brand->password;

		$my_ip = $_SERVER['REMOTE_ADDR'];
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

			//error_log($result);						
			foreach($decoded->result as $d) {
								
				$assetName = $d->availableoptionsresponse_assetName;
				
				$asset_name = $db->get_var("select name from assets where id='$asset_id'");
				
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
	}
	else {
		$option_date = gmdate("Y_m_d H:i:s");
		$min_gmt_date = gmdate("Y-m-d H:i:00", gmmktime() + ($min_time * 60));
		$max_gmt_date = gmdate("Y-m-d H:i:00", gmmktime() + ($max_time * 60));
									
		$data = "api_username=".$brand->username."&api_password=".$brand->password."&MODULE=Options&COMMAND=view&FILTER[assetId]=$asset_id&FILTER[status]=open&FILTER[startDate][max]=$option_date&FILTER[endDate][min]=$min_gmt_date&FILTER[endDate][max]=$max_gmt_date";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $brand->api_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result=curl_exec ($ch);
		$xml = simplexml_load_string($result);
								
		//print_r($xml);
		$optionId = $xml->Options->data_0->id;
		$forDemo_profit = $xml->Options->data_0->profit;
		$forDemo_multiplier = $xml->Options->data_0->loss;
	}
	
	//error_log($optionId);
	
	if(!empty($optionId))  {
		
		// getting the client balance
		if($brand_ref == "TRAD") {
			
			//error_log("here!");
			
			$url = $brand->api_url."customer/findAccounts";
							
			$affiliateUserName = $brand->username;
			$affiliatePassword = $brand->password;
			
			$data = "affiliateUserName=$affiliateUserName&affiliatePassword=$affiliatePassword&accountID=$spotid";
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			$result=curl_exec ($ch);
			$decoded = json_decode($result);

			$balance2 = $decoded->result{0}->apiaccountview_balance;
			$balance = substr_replace($balance2, ".", -2, 0);	
		}
		else {
			$data = "api_username=".$brand->username."&api_password=".$brand->password."&MODULE=Customer&COMMAND=view&FILTER[id]=$spotid";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $brand->api_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			$result=curl_exec ($ch);
			$xml = simplexml_load_string($result);
			$balance = $xml->Customer->data_0->accountBalance;
		}
		
		if(empty($balance) || $balance == "0.00") {
			
		/*
			
			// we need to make a demo trade here
			$position_amount =  "25";
			$position_position = "";
			$position_currency = "USD";
			$position_rate = "1.1";
			$position_originalRateTimestamp = "";
			$position_status = "open";
			$position_date = "";
			$position_opstartDate = "";
			$position_opendDate = "";
			$position_opprofit = rand(65,80);
			
			$position_opmultiplier = "";
			$position_opruleId = "";
			$position_id = "";
			$forDemo_profit = $position_opprofit;
				
			$db->query("update trades set executionPrice='$price4demo', status='EXECUTED' where id='$id_trade'");	
			$asset_name = $db->get_var("select name from assets where id='$asset_id'");
			$db->query("insert into executed_trades_details values('', '$id_user', '$position_id', '$id_trade', '$position_amount', '$position_position', '$position_currency', '$price4demo', '$position_originalRateTimestamp', '$position_status', '$position_date', '$position_opstartDate', '$position_opendDate', '$forDemo_profit', '$forDemo_multiplier', '$position_opruleId', NOW(), '1', '', '')");

			$asset_name = $db->get_var("select name from assets where id='$asset_id'");
			echo "SUCCESS|$position_amount|$position_currency|$price4demo|$position_originalRateTimestamp|$position_status|$position_date|$position_opstartDate|$position_opendDate|$position_opprofit|$position_opmultiplier|$position_opruleId|$position_id|$asset_id|$asset_name";
		*/
		}
		else {

			if($brand_ref == "TRAD") {
				
				$amount = $amount."00";
				$affiliateUserName = $brand->username;
				$affiliatePassword = $brand->password;
				
				if($action == "call") $direction = 49;
				elseif($action == "put") $direction = 50;
							
				$url = $brand->api_url."trading/openTrade";
				
				$my_ip = $_SERVER['REMOTE_ADDR'];
				list($octet1, $octet2, $octet3, $octet4) = explode(".", $my_ip);
				
				$ip = ($octet1 * 16777216) + ($octet2 * 65536) + ($octet3 * 256) + ($octet4);
				
				$data = "affiliateUserName=$affiliateUserName&affiliatePassword=$affiliatePassword&ip=$ip&accountId=$spotid&optionId=$optionId&direction=$direction&amount=$amount&strike=0";

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

				$result2=curl_exec ($ch);
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
						
					$db->query("update trades set executionPrice='$price4demo', status='EXECUTED' where id='$id_trade'");	
					$asset_name = $db->get_var("select name from assets where id='$asset_id'");
					$db->query("insert into executed_trades_details values('', '$id_user', '$position_id', '$id_trade', '$position_amount', '$position_position', '$position_currency', '$price4demo', '$position_originalRateTimestamp', '$position_status', '$position_date', '$position_opstartDate', '$position_opendDate', '$forDemo_profit', '$forDemo_multiplier', '$position_opruleId', NOW(), '1', '', '')");

					$asset_name = $db->get_var("select name from assets where id='$asset_id'");
					echo "SUCCESS|$position_amount|$position_currency|$price4demo|$position_originalRateTimestamp|$position_status|$position_date|$position_opstartDate|$position_opendDate|$position_opprofit|$position_opmultiplier|$position_opruleId|$position_id|$asset_id|$asset_name";
					
				
				
				
				}
				else {
					$db->query("update trades set status='FAILED' where id='$id_trade'");
					echo "FAILED";				
				}
			
			}
			else {

		
				$exp_test = gmmktime() + ($min_time * 60);
				$data = "api_username=".$brand->username."&api_password=".$brand->password."&MODULE=Positions&COMMAND=add&product=regular&customerId=$spotid&position=$action&amount=$amount&endDate=$exp_test&assetId=$asset_id&optionId=$optionId";
					
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $brand->api_url);
											
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

				$result=curl_exec ($ch);
				$xml_position = simplexml_load_string($result);
				
				// success message
				if($xml_position->operation_status == "successful") {
					
					$position_amount =  $xml_position->Positions->amount;
					$position_position = $xml_position->Positions->position;
					$position_currency = $xml_position->Positions->currency;
					$position_rate = $xml_position->Positions->rate;
					$position_originalRateTimestamp = $xml_position->Positions->originalRateTimestamp;
					$position_status = $xml_position->Positions->status;
					$position_date = $xml_position->Positions->date;
					$position_opstartDate = $xml_position->Positions->opstartDate;
					$position_opendDate = $xml_position->Positions->opendDate;
					$position_opprofit = $xml_position->Positions->opprofit;
					$position_opmultiplier = $xml_position->Positions->opmultiplier;
					$position_opruleId = $xml_position->Positions->opruleId;
					$position_id = $xml_position->Positions->id;
					
					$db->query("update trades set executionPrice='$position_rate', status='EXECUTED' where id='$id_trade'");
					
					$asset_name = $db->get_var("select name from assets where id='$asset_id'");
					
					echo "SUCCESS|$position_amount|$position_currency|$position_rate|$position_originalRateTimestamp|$position_status|$position_date|$position_opstartDate|$position_opendDate|$position_opprofit|$position_opmultiplier|$position_opruleId|$position_id|$asset_id|$asset_name";
					
					// add this data somewhere
					$db->query("insert into executed_trades_details values('', '$id_user', '$position_id', '$id_trade', '$position_amount', '$position_position', '$position_currency', '$position_rate', '$position_originalRateTimestamp', '$position_status', '$position_date', '$position_opstartDate', '$position_opendDate', '$position_opprofit', '$position_opmultiplier', '$position_opruleId', NOW(), '0', '', '')");
				}
				else {
					$db->query("update trades set status='FAILED' where id='$id_trade'");
					echo "FAILED";
				}
			}
		}
	}
}


// manual trading
function manual($brand_ref, $asset_id, $action, $id_user, $id_trade, $price4demo) 
{
	
	global $db;
	
	if($brand_ref == "TRAD") {
		$min_time = 5*60; $max_time = 120*60;
	}
	else {
		$min_time = 15; $max_time = 120;
	}
	
	$brand = $db->get_row("select * from brands where ref='$brand_ref'");
	
	$amount = $db->get_var("select amount from users_settings where id_user='$id_user'");
	$spotid = $db->get_var("select spotid from users_spot where id_user='$id_user'");
	
	//echo $amount;
	//exit;
	
	// first we get the option id
	if($brand_ref == "TRAD") {
		
		$url = $brand->api_url."trading/getAvailableOptions";
						
		$affiliateUserName = $brand->username;
		$affiliatePassword = $brand->password;

		$my_ip = $_SERVER['REMOTE_ADDR'];
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

			//error_log($result);						
			foreach($decoded->result as $d) {
								
				$assetName = $d->availableoptionsresponse_assetName;
				
				$asset_name = $db->get_var("select name from assets where id='$asset_id'");
				
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
	}
	
	else {
		// first we get the option id		
		$option_date = gmdate("Y_m_d H:i:s");
		$min_gmt_date = gmdate("Y-m-d H:i:00", gmmktime() + ($min_time * 60));
		$max_gmt_date = gmdate("Y-m-d H:i:00", gmmktime() + ($max_time * 60));
									
		$data = "api_username=".$brand->username."&api_password=".$brand->password."&MODULE=Options&COMMAND=view&FILTER[assetId]=$asset_id&FILTER[status]=open&FILTER[startDate][max]=$option_date&FILTER[endDate][min]=$min_gmt_date&FILTER[endDate][max]=$max_gmt_date";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $brand->api_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result=curl_exec ($ch);
		$xml = simplexml_load_string($result);
								
		//print_r($xml);
		$optionId = $xml->Options->data_0->id;
		$forDemo_profit = $xml->Options->data_0->profit;
		$forDemo_multiplier = $xml->Options->data_0->loss;
	}
	
	if(!empty($optionId))  {
		
		$db->query("update trades set status='MANUAL' where id='$id_trade'");
		
		$asset_name = $db->get_var("select name from assets where id='$asset_id'");
		
		// getting the client balance
		
		if($brand_ref == "TRAD") {
			
			//error_log("here!");
			
			$url = $brand->api_url."customer/findAccounts";
							
			$affiliateUserName = $brand->username;
			$affiliatePassword = $brand->password;
			
			$data = "affiliateUserName=$affiliateUserName&affiliatePassword=$affiliatePassword&accountID=$spotid";
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			$result=curl_exec ($ch);
			$decoded = json_decode($result);

			$balance2 = $decoded->result{0}->apiaccountview_balance;
			$balance = substr_replace($balance2, ".", -2, 0);	
		}
		else {
		
			$data = "api_username=".$brand->username."&api_password=".$brand->password."&MODULE=Customer&COMMAND=view&FILTER[id]=$spotid";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $brand->api_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			$result=curl_exec ($ch);
			$xml = simplexml_load_string($result);
			$balance = $xml->Customer->data_0->accountBalance;
		}
		
		if(empty($balance) || $balance == "0.00") {
		
			//echo "MANUAL|$asset_id|$asset_name|$id_trade|$action|$optionId|demo|$price4demo|$forDemo_profit|$forDemo_multiplier";
		}
		else {
			echo "MANUAL|$asset_id|$asset_name|$id_trade|$action|$optionId|live|$price4demo|$forDemo_profit|$forDemo_multiplier";
		}
	}
}

function checkDemoClose($price, $id_trade) {
	
	/*
	global $db;
	
	//echo $price." ".$id_trade;
	
	$trade = $db->get_row("select * from trades where id='$id_trade'");
	$trade_details = $db->get_row("select * from executed_trades_details where id_trade='$id_trade'");
	//echo "select * from executed_trades_details where id_trade='$id_trade'";
	
	//print_r($trade_details);
	
	if($trade_details->is_demo == 1 && $trade_details->position_status == "open") {

		//error_log("HEREEEE", 0);
	
		$serverDate = $trade_details->serverDate;
		$executionPrice = $trade->executionPrice;
		
		$action = $trade->type;
		
		// check here if 15 minutes since open has passed
		$now = strtotime("now");
		$server_ts = strtotime($serverDate);
		//5 mins - 15 mins
		$brand_ref = $db->get_var("select brand from users where id='".$trade_details->id_user."'");
		if($brand_ref == "TRAD") $secondss = 300;
		else $secondss = 900;
		if($now > $server_ts + $secondss) {
		
			// now check if we are winning
			if($action == "CALL") {
				//error_log("HEREEEE22222", 0);
				if((double)$price > (double)$executionPrice) {
					// close the trade
					$db->query("update executed_trades_details set position_status='closed', position_exitRate='$price', position_exitDate=NOW() where id_trade='$id_trade'");
					
					// move balance
					$amount = $trade->amount;
					
					$brand_ref = $db->get_var("select brand from users where id='".$trade_details->id_user."'");
					if($brand_ref == "TRAD") $opprofit = rand(70,80);
					//else $opprofit = $trade_details->position_opprofit;
					 $opprofit = $trade_details->position_opprofit;
					
					$sum = ($opprofit*$amount)/100;
					
					$get_balance = $db->get_var("select balance from demo_balances where id_user='".$trade_details->id_user."'");
					$new_balance = intval($get_balance + $sum);
					$db->query("update demo_balances set balance='$new_balance' where id_user='".$trade_details->id_user."'");
				}
				else {
					/*
					// check if we have it open for too much just close it and assume loss
					if($now > $server_ts + 14400) {
						$db->query("update executed_trades_details set position_status='closed', position_exitRate='$price', position_exitDate=NOW() where id_trade='$id_trade'");
						
						// move balance
						$amount = $trade->amount;
						$get_balance = $db->get_var("select balance from demo_balances where id_user='".$trade_details->id_user."'");
						$new_balance = intval($get_balance - $amount);
						$db->query("update demo_balances set balance='$new_balance' where id_user='".$trade_details->id_user."'");
					}
					*/
					/*
				}
			}
		
		
			elseif($action == "PUT") {
				if((double)$price < (double)$executionPrice) {
					// close the trade
					$db->query("update executed_trades_details set position_status='closed', position_exitRate='$price', position_exitDate=NOW() where id_trade='$id_trade'");
					
					// move balance
					$amount = $trade->amount;
					$opprofit = $trade_details->position_opprofit;
					$sum = ($opprofit*$amount)/100;
					
					$get_balance = $db->get_var("select balance from demo_balances where id_user='".$trade_details->id_user."'");
					$new_balance = intval($get_balance + $sum);
					$db->query("update demo_balances set balance='$new_balance' where id_user='".$trade_details->id_user."'");
				
				}
				else {
					/*
					// check if we have it open for too much just close it and assume loss
					if($now > $server_ts + 14400) {
						$db->query("update executed_trades_details set position_status='closed', position_exitRate='$price', position_exitDate=NOW() where id_trade='$id_trade'");
						
						// move balance
						$amount = $trade->amount;
						$get_balance = $db->get_var("select balance from demo_balances where id_user='".$trade_details->id_user."'");
						$new_balance = intval($get_balance - $amount);
						$db->query("update demo_balances set balance='$new_balance' where id_user='".$trade_details->id_user."'");
					}
					*/
				//}
		//	}
	//	}
//	}
		
	
	
	
	
}

?>