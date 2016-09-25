<?php 
error_reporting(0);
session_start();

$_SESSION['is_demo'] = false;

if(isset($_SESSION['userid'])){

	require_once("variables.php");
		
	$getuser = mysql_query("select * from users where `id`='".$_SESSION['userid']."'");
        $getuserrow=mysql_fetch_array($getuser);
	
	$spotid = $getuserrow['spotid'];
        $email=$getuserrow['email'];
        $password= $getuserrow['password'];

      // echo $spotid;
	
	$getbrand =mysql_query("select * from brands where ref='".$_SESSION['brand']."'");
    $getbrandrow=mysql_fetch_array($getbrand);
       // echo $getbrandrow['ref'];
	$title='Quit Your Jobs dashboard at '.$getbrandrow['name'];

    $getcbrand=mysql_query("SELECT * FROM `country` WHERE `iso`='GB'") or die(mysql_error());
	$getcbrandrow=mysql_fetch_array($getcbrand);
 //Updating user deposit status
		/*echo "Spot $spotid";
		echo "Brand : ".$getuserrow["brand"];
		echo "API TYPE :". $getbrandrow['api_type'];*/
    if (!empty($spotid) && !empty($getuserrow["brand"]))
   {
       $sqlQuery = mysql_query("select * from brands where ref='".$_SESSION['brand']."'");
        $brand = mysql_fetch_array($sqlQuery);

        $api_url = $brand["api_url"];
        $api_username = $brand["username"];
        $api_password = $brand["password"];

        $data = "api_username=$api_username&api_password=$api_password&MODULE=Customer&COMMAND=view&FILTER[id]=".$getuserrow['spotid'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        $result=curl_exec ($ch);        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $xml = simplexml_load_string($result);
        $balance = $xml->Customer->data_0->accountBalance;
   }        

	switch ($getbrandrow['api_type']) {

	    case "MARKETPULSE":

		$service_url = $getbrandrow['api_url'].'GetAccountBalance';
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
		$balance = $jsonResponse->data->Balance;
	       

	  break;


          case "TRADOLOGIC":

		$b2bApiUrl = $getbrandrow['api_url'];
		$accountId = $getbrandrow['campaign_id'];
		$affiliateUsername = $getbrandrow['username'];
		$authenticationKey = $getbrandrow['password'];
		$my_ip = $_SERVER['REMOTE_ADDR'];
		$userId=$spotid;

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
			'userId' => $userId,              
			'affiliateUsername' => $affiliateUsername,
			'accountId' => $accountId
		    ];

		   $checksum=getChecksum($dataParameters, $authenticationKey);

		   $dataParameters['checksum'] = $checksum;
		   $postData = json_encode($dataParameters);

		    $url=$b2bApiUrl.'v1/affiliate/users/'.$userId.'?accountId='.$accountId.'&affiliateUsername='.$affiliateUsername.'&checksum='.$checksum;


		    $curl = curl_init();
		    curl_setopt($curl, CURLOPT_URL, $url);
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		    $response = curl_exec($curl);
		    curl_close($curl);

		    $jsonResponse = json_decode($response);
		    //print_r($response);

		$balance = $jsonResponse->data->balance;

          break;

          case "OPTECK":

                require_once('include/OpteckAPI.php');
                $affiliateID = $getbrandrow['username'];
                $partnerID = $getbrandrow['password'];

                $affiliate = new OpteckAPI($affiliateID, $partnerID);

		$getLeadDetails = $affiliate->request('getLeadDetails', array('email' => $email));
                $decoded = json_decode($getLeadDetails);              

                $balance2 = $decoded->data->balance;
		$balance = substr_replace($balance2, ".", -2, 0);

          break;

          case "PANDA":

		date_default_timezone_set('UTC');

		function createApiRequest ($requestArray=array(), $partnerId, $partnerSecretKey) {
			$requestArray['partnerId'] = $partnerId;
			$requestArray['time'] = time();

			$values = array_values($requestArray);
			sort($values, SORT_STRING);
			$string = join('', $values);
			$requestArray['transactionKey'] = sha1($string . $partnerSecretKey);
			return http_build_query($requestArray);
		}

		$PARTNER_ID = $getbrandrow['username'];
		$PARTNER_SECRET_KEY = $getbrandrow['password'];

		// Registration example
		$request = array(
			'login' => $email,
			'password' =>  $password,
			'source' => '1',
		);


		// register with the broker

		$url = $getbrandrow['api_url'].'/api/v1/Login?' . createApiRequest($request, $PARTNER_ID, $PARTNER_SECRET_KEY);    
                //echo $url; 

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 0);
		$result=curl_exec ($ch);
		$decoded = json_decode($result);
		//print_r($decoded);        

                $balance = $decoded->data->balance;
		$token=$decoded->data->token;

          break;

          case "TECH FINANCIALS":

		$url = $getbrandrow['api_url']."customer/findAccounts";
							
		$affiliateUserName = $getbrandrow['username'];
		$affiliatePassword = $getbrandrow['password'];
			
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

            break;

            case "SPOT-BDB":

		// getting the client balance

		
		$headers = array(
		'Content-Type' => 'application/x-www-form-urlencoded',
		);		

	        $url=$getbrandrow['api_url'];

	        if($getcbrandrow['reg']=='1'){ 
	          $url=$getbrandrow['api_url'].'/?integration=bdb_eu';
	        }else{
	          $url=$getbrandrow['api_url'].'/?integration=bdb_com';
	        }


		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Customer&COMMAND=view&FILTER[id]=$spotid";
		//echo $data;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		//curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain')); 
		$result=curl_exec ($ch);
		$xml = simplexml_load_string($result);

		//print_r($xml);

		$balance = $xml->Customer->data_0->accountBalance;
                //echo $balance.'1111111111111111111111111111';

            break;

            case "SPOT-KEY":

		// getting the client balance

		
		$headers = array(
		'Content-Type' => 'application/x-www-form-urlencoded',
		);		

	        $url=$getbrandrow['api_url'];

	        if($getcbrandrow['reg']=='1'){ 
	          $url=$getbrandrow['api_url'].'/?integration=bdb_eu';
	        }else{
	          $url=$getbrandrow['api_url'].'/?integration=bdb_com';
	        }


		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Customer&COMMAND=view&FILTER[id]=$spotid";
		//echo $data;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		//curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain')); 
		$result=curl_exec ($ch);
		$xml = simplexml_load_string($result);

		//print_r($xml);

		$balance = $xml->Customer->data_0->accountBalance;
                //echo $balance.'1111111111111111111111111111';

            break;

            case "SPOT-RALLY":

		// getting the client balance

		
		$headers = array(
		'Content-Type' => 'application/x-www-form-urlencoded',
		);		

	        $url=$getbrandrow['api_url'];

	        if($getcbrandrow['reg']=='1'){ 
	          $url=$getbrandrow['api_url'].'/?integration=bdb_eu';
	        }else{
	          $url=$getbrandrow['api_url'].'/?integration=bdb_com';
	        }


		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Customer&COMMAND=view&FILTER[id]=$spotid";
		//echo $data;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		//curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain')); 
		$result=curl_exec ($ch);
		$xml = simplexml_load_string($result);

		//print_r($xml);

		$balance = $xml->Customer->data_0->accountBalance;
                //echo $balance.'1111111111111111111111111111';

            break;

            case "SPOT-USA":

		// getting the client balance		

		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Customer&COMMAND=view&FILTER[Id]=$spotid";
		//echo $data;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		//curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain')); 
		$result=curl_exec ($ch);
		$xml = simplexml_load_string($result);

		//print_r($xml);

		$balance = $xml->Customer->data_0->accountBalance/100;
                //echo $balance.'1111111111111111111111111111';

            break;

	    case 'AIRSOFT':
                $service_url = $getbrandrow['api_url'].'?key=' . $getbrandrow['password'] .
                    '&method=clientInfo&client_id=' . $getuserrow['spotid'];
//                $service_url = 'http://www.capitalbankmarkets.com/back.php/affiliate/externalSorce/api?key=9JJ0Ppe6J2&method=clientInfo&client_id=108544';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $service_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 0);
                $result = curl_exec($ch);
//echo $service_url;echo $result;exit;
                $jsonResponse = json_decode($result, true);
                if (!empty($jsonResponse['clientInfo']['balance'])) {
                    $balance = $jsonResponse['clientInfo']['balance'];
                }
            break;

          default:

            
		// getting the client balance

		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Customer&COMMAND=view&FILTER[id]=$spotid";
		//echo $data;MODULE=Customer&COMMAND=view&FILTER[Id]=123

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		//curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain')); 
		$result=curl_exec ($ch);
		$xml = simplexml_load_string($result);

		//print_r($xml);

		$balance = $xml->Customer->data_0->accountBalance;
                //echo $balance.'1111111111111111111111111111';

        }
        
       if ($balance > 0 && $getuserrow['deposit_status'] == 'DEMO') {
            $uId = $getuserrow['id'];
            $uBrand = $_SESSION['brand'];
            $updateQuery = "update users set deposit_status='LIVE' where id='$uId' AND brand='$uBrand'";
            $affectedRows = mysql_query($updateQuery);
            $getuser = mysql_query("select * from users where `id`='" . $_SESSION['userid'] . "'");
            $getuserrow = mysql_fetch_array($getuser);
        }

	
	
	if(empty($balance) || $balance == "0.00") {
		// redirect no balance users to demo page
		$_SESSION['isDemo'] = 1;
		//echo  "<meta http-equiv=\"Refresh\"content=\"0; URL=indexnewuser.php?firstTime=1\">";
                //echo  "<meta http-equiv=\"Refresh\"content=\"0; URL=index.php\">";
		//exit;

	}
	
      
	switch ($getbrandrow['api_type']) {


          case "OPTECK":
                
                require_once('include/OpteckAPI.php');
                $affiliateID = $getbrandrow['username'];
                $partnerID = $getbrandrow['password'];

                $affiliate = new OpteckAPI($affiliateID, $partnerID);

		$result = $affiliate->request('instances', array('email' => $email));

		$decoded = json_decode($result);

		//print_R($decoded);

                $totalwopen = 0;

		$won = 0;
		$total = 0;
		$lost = 0;
                $totalwopen = 0;
                $tie = 0;
                $open ='0';

		foreach($decoded->data as $t) {	  

                               $total++; 
                                 
       				 if($t->status=='1'){
	 			   $open++;
				}
	 
             			if($t->direction=='1') { // call
					if (($t->status=='3') && ($t->strikeEnd > $t->strike)){ $won++; }
                                        if (($t->status=='3') && ($t->strikeEnd < $t->strike)){ $lost++; }
                                        if (($t->status=='3') && ($t->strikeEnd == $t->strike)){ $tie++; }
				}elseif($t->direction=='-1') { // put
					if (($t->status=='3') && ($t->strikeEnd < $t->strike)){ $won++; }
                                        if (($t->status=='3') && ($t->strikeEnd > $t->strike)){ $lost++; }
                                        if (($t->status=='3') && ($t->strikeEnd == $t->strike)){ $tie++; }
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


	    $dataParameters = [
		'userId' => $userId,              
		'affiliateUsername' => $affiliateUsername,
		'accountId' => $accountId
	    ];

	   $checksum=getChecksum($dataParameters, $authenticationKey);

	   $dataParameters['checksum'] = $checksum;
	   $postData = json_encode($dataParameters);

	    $url=$b2bApiUrl.'v1/affiliate/users/'.$userId.'/trades/regular?accountId='.$accountId.'&affiliateUsername='.$affiliateUsername.'&checksum='.$checksum;




	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    $response = curl_exec($curl);
	    curl_close($curl);

	    $jsonResponse = json_decode($response);
	    //print_r($jsonResponse);

		   

		$won = 0;
		$total = 0;
		$lost = 0;
                $totalwopen = 0;
                $tie = 0;
                $open ='0';

		foreach($jsonResponse->data as $t) { 

                               $total++; 
                                 
       				if( $t->tradeState=='TradeActive'){
	 			   $open++;
				}
	 
             			if($t->action == 'Call') { // call
					if ((!empty($t->expiryRate)) && ($t->expiryRate > $t->rate)){ $won++; }
                                        if ((!empty($t->expiryRate)) && ($t->expiryRate < $t->rate)){ $lost++; }
                                        if ((!empty($t->expiryRate)) && ($t->expiryRate == $t->rate)){ $tie++; }
				}elseif($t->action == 'Put') { // put
					if ((!empty($t->expiryRate)) && ($t->expiryRate < $t->rate)){ $won++; }
                                        if ((!empty($t->expiryRate)) && ($t->expiryRate > $t->rate)){ $lost++; }
                                        if ((!empty($t->expiryRate)) && ($t->expiryRate == $t->rate)){ $tie++; }
				} 
				
			}			
	
	  break;	

          case "TECH FINANCIALS":
                
		$_SESSION['isDemo'] = 0;
		$currency = $decoded->result{0}->apiaccountview_currencyISO;
		
		$url = $getbrandrow['api_url']."trading/getTrades";
							
		$affiliateUserName = $getbrandrow['username'];
		$affiliatePassword = $getbrandrow['password'];

		$now = gmdate("Y-m-d H:i:s");
		$data = "affiliateUserName=$affiliateUserName&affiliatePassword=$affiliatePassword&accountId=$spotid&fromDate=2015-06-01 00:00:00&toDate=$now&status=18,20,147";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
         
		$result=curl_exec ($ch);
		
		

		$decoded = json_decode($result);
                //print_r($decoded);
		$won = 0;
		$total = 0;
		$lost = 0;
                $totalwopen = 0;
                $tie = 0;

		foreach($decoded->result as $t) {
                        $totalwopen++;
			if($t->tradinghistory_status == 20) {
				$total++;
				$positionType = $t->tradinghistory_positionType;
				if($positionType == 49) { // call
					if($t->tradinghistory_closePrice > $t->tradinghistory_strike){ $won++; }
                                        if($t->tradinghistory_closePrice < $t->tradinghistory_strike){ $lost++; }
                                        if($t->tradinghistory_closePrice == $t->tradinghistory_strike){ $tie++; }
				}elseif($positionType == 50) { // put
					if($t->tradinghistory_closePrice < $t->tradinghistory_strike){ $won++; }
                                        if($t->tradinghistory_closePrice > $t->tradinghistory_strike){ $lost++; }
                                        if($t->tradinghistory_closePrice == $t->tradinghistory_strike){ $tie++; }
				}
				
			}
			
		$open=$totalwopen-$total;
	
		}

	  break;


          case "PANDA":
                
		
		$url = $getbrandrow['api_url'].'/api/v1/ActiveTrades?token='.$token;    
                //echo $url;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 0);
		$result=curl_exec ($ch);
		$decoded = json_decode($result);
		//print_r($decoded);  

		$won = 0;
		$total = 0;
		$lost = 0;
                $open = 0;
                $tie = 0;
		$wtotal='0';

		foreach($decoded->data as $p) {	
		  $open++;		
		}

		$url = $getbrandrow['api_url'].'/api/v1/TradesHistory?token='.$token;    

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 0);
		$result=curl_exec ($ch);
		$decoded = json_decode($result);
		//print_r($decoded);  

		foreach($decoded->data as $p) {	                      
				$wtotal++;
				if($p->action =='1') { // call
					if($p->closePrice > $p->targetPrice){ $won++; }
                                        if($p->closePrice < $p->targetPrice){ $lost++; }
                                        if($p->closePrice == $p->targetPrice){ $tie++; }
				}elseif($p->action =='0') { // put
					if($p->closePrice < $p->targetPrice){ $won++; }
                                        if($p->closePrice > $p->targetPrice){ $lost++; }
                                        if($p->closePrice == $p->targetPrice){ $tie++; }
				}
				
			}

		$total=$wtotal+$open;

	  break;	

          case "SPOT":
            	
		$_SESSION['isDemo'] = 0;
		$currency = $xml->Customer->data_0->currency;
		
		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result=curl_exec ($ch);
              
		$xml = simplexml_load_string($result);
		
		//print_r($xml);
		
		$payout = 0;
		$won = 0;
		$lost = 0;
		$total = 0;
		$today_p = 0;

		foreach($xml->Positions->children() as $p) {
			
			if($p->status == "open") {				
                                $open++;
			}elseif($p->status == "lost") {
				$lost++;
			}
			elseif($p->status == "won") {
				$won++;
			}elseif($p->status == "tie") {
				$tie++;
			}

			$total++;
		}

		if($total >= 500) {
			
			$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid&page=2";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

			$result=curl_exec ($ch);
			$xml = simplexml_load_string($result);
			
			foreach($xml->Positions->children() as $p) {
				
				if($p->status == "open") {
					continue;
				}
				elseif($p->status == "lost") {
					$lost++;
				}
				elseif($p->status == "won") {
					$won++;
					
					// search 4 today profits
					$position_id = $p->id;

					$todaytrade=mysql_query("select * from executed_trades_details where position_id='$position_id' and DATE(serverDate) = '".date("Y-m-d")."'");
				$position_today = mysql_num_rows($todaytrade);

					if($position_today > 0) {
						$today_p = $today_p + $p->winSum; 
					}
				}
				$total++;
			
			}
		}
		if($total >= 1000) {
			
			$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid&page=3";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

			$result=curl_exec ($ch);
			$xml = simplexml_load_string($result);
			
			foreach($xml->Positions->children() as $p) {
				
				if($p->status == "open") {
					continue;
				}
				elseif($p->status == "lost") {
					$lost++;
				}
				elseif($p->status == "won") {
					$won++;
					
					// search 4 today profits
					$position_id = $p->id;
					
					$todaytrade=mysql_query("select * from executed_trades_details where position_id='$position_id' and DATE(serverDate) = '".date("Y-m-d")."'");
				$position_today = mysql_num_rows($todaytrade);

					if($position_today > 0) {
						$today_p = $today_p + $p->winSum; 
					}
				}
				$total++;
			
			}
		}
		if($total >= 1500) {
			
			$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid&page=4";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

			$result=curl_exec ($ch);
			$xml = simplexml_load_string($result);
			
			foreach($xml->Positions->children() as $p) {
				
				if($p->status == "open") {
					continue;
				}
				elseif($p->status == "lost") {
					$lost++;
				}
				elseif($p->status == "won") {
					$won++;
					
					// search 4 today profits
					$position_id = $p->id;					
					
					$todaytrade=mysql_query("select * from executed_trades_details where position_id='$position_id' and DATE(serverDate) = '".date("Y-m-d")."'");
				$position_today = mysql_num_rows($todaytrade);

					if($position_today > 0) {
						$today_p = $today_p + $p->winSum; 
					}
				}
				$total++;
			
			}
		}
	break;

        case "SPOT-USA":
            	
		//MODULE=Positions&COMMAND=view&FILTER[customerId]= 243a8606-6b5c-49f1-ab13-747f13e9166b
		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Position&COMMAND=view&FILTER[customerId]=$spotid&FILTER[status][]=Active&FILTER[status][]=Win&FILTER[status][]=Lost&FILTER[status][]=Tie";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result=curl_exec ($ch);
              
		$xml = simplexml_load_string($result);
		
		//print_r($xml);
		
		$payout = 0;
		$won = 0;
		$lost = 0;
		$total = 0;
		$today_p = 0;

		foreach($xml->Position->children() as $p) {
			
			if($p->status=='Active') {				
                                $open++;
			}elseif($p->status=='Lost') {
				$lost++;
			}
			elseif($p->status=='Win') {
				$won++;
			}else{
				$tie++;
			}

			$total++;
		}

		if($total >= 500) {
			
			$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid&page=2";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

			$result=curl_exec ($ch);
			$xml = simplexml_load_string($result);
			
			foreach($xml->Positions->children() as $p) {
				
				if($p->status == "open") {
					continue;
				}
				elseif($p->status == "lost") {
					$lost++;
				}
				elseif($p->status == "won") {
					$won++;
					
					// search 4 today profits
					$position_id = $p->id;

					$todaytrade=mysql_query("select * from executed_trades_details where position_id='$position_id' and DATE(serverDate) = '".date("Y-m-d")."'");
				$position_today = mysql_num_rows($todaytrade);

					if($position_today > 0) {
						$today_p = $today_p + $p->winSum; 
					}
				}
				$total++;
			
			}
		}
		if($total >= 1000) {
			
			$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid&page=3";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

			$result=curl_exec ($ch);
			$xml = simplexml_load_string($result);
			
			foreach($xml->Positions->children() as $p) {
				
				if($p->status == "open") {
					continue;
				}
				elseif($p->status == "lost") {
					$lost++;
				}
				elseif($p->status == "won") {
					$won++;
					
					// search 4 today profits
					$position_id = $p->id;
					
					$todaytrade=mysql_query("select * from executed_trades_details where position_id='$position_id' and DATE(serverDate) = '".date("Y-m-d")."'");
				$position_today = mysql_num_rows($todaytrade);

					if($position_today > 0) {
						$today_p = $today_p + $p->winSum; 
					}
				}
				$total++;
			
			}
		}
		if($total >= 1500) {
			
			$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid&page=4";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

			$result=curl_exec ($ch);
			$xml = simplexml_load_string($result);
			
			foreach($xml->Positions->children() as $p) {
				
				if($p->status == "open") {
					continue;
				}
				elseif($p->status == "lost") {
					$lost++;
				}
				elseif($p->status == "won") {
					$won++;
					
					// search 4 today profits
					$position_id = $p->id;					
					
					$todaytrade=mysql_query("select * from executed_trades_details where position_id='$position_id' and DATE(serverDate) = '".date("Y-m-d")."'");
				$position_today = mysql_num_rows($todaytrade);

					if($position_today > 0) {
						$today_p = $today_p + $p->winSum; 
					}
				}
				$total++;
			
			}
		}
	break;

        case "SPOT-BDB":
            	
		$_SESSION['isDemo'] = 0;
		
		$headers = array(
		'Content-Type' => 'application/x-www-form-urlencoded',
		);		

	        $url=$getbrandrow['api_url'];

	        if($getcbrandrow['reg']=='1'){ 
	          $url=$getbrandrow['api_url'].'/?integration=bdb_eu';
	        }else{
	          $url=$getbrandrow['api_url'].'/?integration=bdb_com';
	        }

		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid";                

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result=curl_exec ($ch);
              
		$xml = simplexml_load_string($result);
		
		//print_r($xml);
		
		$payout = 0;
		$won = 0;
		$lost = 0;
		$total = 0;
		$today_p = 0;

		foreach($xml->Positions->children() as $p) {
			
			if($p->status == "open") {				
                                $open++;
			}elseif($p->status == "lost") {
				$lost++;
			}
			elseif($p->status == "won") {
				$won++;
			}elseif($p->status == "tie") {
				$tie++;
			}

			$total++;
		}

		if($total >= 500) {
			
			$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid&page=2";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

			$result=curl_exec ($ch);
			$xml = simplexml_load_string($result);
			
			foreach($xml->Positions->children() as $p) {
				
				if($p->status == "open") {
					continue;
				}
				elseif($p->status == "lost") {
					$lost++;
				}
				elseif($p->status == "won") {
					$won++;
					
					// search 4 today profits
					$position_id = $p->id;

					$todaytrade=mysql_query("select * from executed_trades_details where position_id='$position_id' and DATE(serverDate) = '".date("Y-m-d")."'");
				$position_today = mysql_num_rows($todaytrade);

					if($position_today > 0) {
						$today_p = $today_p + $p->winSum; 
					}
				}
				$total++;
			
			}
		}
		if($total >= 1000) {
			
			$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid&page=3";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

			$result=curl_exec ($ch);
			$xml = simplexml_load_string($result);
			
			foreach($xml->Positions->children() as $p) {
				
				if($p->status == "open") {
					continue;
				}
				elseif($p->status == "lost") {
					$lost++;
				}
				elseif($p->status == "won") {
					$won++;
					
					// search 4 today profits
					$position_id = $p->id;
					
					$todaytrade=mysql_query("select * from executed_trades_details where position_id='$position_id' and DATE(serverDate) = '".date("Y-m-d")."'");
				$position_today = mysql_num_rows($todaytrade);

					if($position_today > 0) {
						$today_p = $today_p + $p->winSum; 
					}
				}
				$total++;
			
			}
		}
		if($total >= 1500) {
			
			$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid&page=4";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

			$result=curl_exec ($ch);
			$xml = simplexml_load_string($result);
			
			foreach($xml->Positions->children() as $p) {
				
				if($p->status == "open") {
					continue;
				}
				elseif($p->status == "lost") {
					$lost++;
				}
				elseif($p->status == "won") {
					$won++;
					
					// search 4 today profits
					$position_id = $p->id;					
					
					$todaytrade=mysql_query("select * from executed_trades_details where position_id='$position_id' and DATE(serverDate) = '".date("Y-m-d")."'");
				$position_today = mysql_num_rows($todaytrade);

					if($position_today > 0) {
						$today_p = $today_p + $p->winSum; 
					}
				}
				$total++;
			
			}
		}
	break;


        case "SPOT-KEY":
            	
		$_SESSION['isDemo'] = 0;
		
		$headers = array(
		'Content-Type' => 'application/x-www-form-urlencoded',
		);		

	        $url=$getbrandrow['api_url'];

	        if($getcbrandrow['reg']=='1'){ 
	          $url=$getbrandrow['api_url'].'/?integration=bdb_eu';
	        }else{
	          $url=$getbrandrow['api_url'].'/?integration=bdb_com';
	        }

		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid";                

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result=curl_exec ($ch);
              
		$xml = simplexml_load_string($result);
		
		//print_r($xml);
		
		$payout = 0;
		$won = 0;
		$lost = 0;
		$total = 0;
		$today_p = 0;

		foreach($xml->Positions->children() as $p) {
			
			if($p->status == "open") {				
                                $open++;
			}elseif($p->status == "lost") {
				$lost++;
			}
			elseif($p->status == "won") {
				$won++;
			}elseif($p->status == "tie") {
				$tie++;
			}

			$total++;
		}

		if($total >= 500) {
			
			$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid&page=2";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

			$result=curl_exec ($ch);
			$xml = simplexml_load_string($result);
			
			foreach($xml->Positions->children() as $p) {
				
				if($p->status == "open") {
					continue;
				}
				elseif($p->status == "lost") {
					$lost++;
				}
				elseif($p->status == "won") {
					$won++;
					
					// search 4 today profits
					$position_id = $p->id;

					$todaytrade=mysql_query("select * from executed_trades_details where position_id='$position_id' and DATE(serverDate) = '".date("Y-m-d")."'");
				$position_today = mysql_num_rows($todaytrade);

					if($position_today > 0) {
						$today_p = $today_p + $p->winSum; 
					}
				}
				$total++;
			
			}
		}
		if($total >= 1000) {
			
			$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid&page=3";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

			$result=curl_exec ($ch);
			$xml = simplexml_load_string($result);
			
			foreach($xml->Positions->children() as $p) {
				
				if($p->status == "open") {
					continue;
				}
				elseif($p->status == "lost") {
					$lost++;
				}
				elseif($p->status == "won") {
					$won++;
					
					// search 4 today profits
					$position_id = $p->id;
					
					$todaytrade=mysql_query("select * from executed_trades_details where position_id='$position_id' and DATE(serverDate) = '".date("Y-m-d")."'");
				$position_today = mysql_num_rows($todaytrade);

					if($position_today > 0) {
						$today_p = $today_p + $p->winSum; 
					}
				}
				$total++;
			
			}
		}
		if($total >= 1500) {
			
			$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid&page=4";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

			$result=curl_exec ($ch);
			$xml = simplexml_load_string($result);
			
			foreach($xml->Positions->children() as $p) {
				
				if($p->status == "open") {
					continue;
				}
				elseif($p->status == "lost") {
					$lost++;
				}
				elseif($p->status == "won") {
					$won++;
					
					// search 4 today profits
					$position_id = $p->id;					
					
					$todaytrade=mysql_query("select * from executed_trades_details where position_id='$position_id' and DATE(serverDate) = '".date("Y-m-d")."'");
				$position_today = mysql_num_rows($todaytrade);

					if($position_today > 0) {
						$today_p = $today_p + $p->winSum; 
					}
				}
				$total++;
			
			}
		}
	break;


        case "SPOT-RALLY":
            	
		$_SESSION['isDemo'] = 0;
		
		$headers = array(
		'Content-Type' => 'application/x-www-form-urlencoded',
		);		

	        $url=$getbrandrow['api_url'];

	        if($getcbrandrow['reg']=='1'){ 
	          $url=$getbrandrow['api_url'].'/?integration=bdb_eu';
	        }else{
	          $url=$getbrandrow['api_url'].'/?integration=bdb_com';
	        }

		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid";                

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result=curl_exec ($ch);
              
		$xml = simplexml_load_string($result);
		
		//print_r($xml);
		
		$payout = 0;
		$won = 0;
		$lost = 0;
		$total = 0;
		$today_p = 0;

		foreach($xml->Positions->children() as $p) {
			
			if($p->status == "open") {				
                                $open++;
			}elseif($p->status == "lost") {
				$lost++;
			}
			elseif($p->status == "won") {
				$won++;
			}elseif($p->status == "tie") {
				$tie++;
			}

			$total++;
		}

		if($total >= 500) {
			
			$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid&page=2";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

			$result=curl_exec ($ch);
			$xml = simplexml_load_string($result);
			
			foreach($xml->Positions->children() as $p) {
				
				if($p->status == "open") {
					continue;
				}
				elseif($p->status == "lost") {
					$lost++;
				}
				elseif($p->status == "won") {
					$won++;
					
					// search 4 today profits
					$position_id = $p->id;

					$todaytrade=mysql_query("select * from executed_trades_details where position_id='$position_id' and DATE(serverDate) = '".date("Y-m-d")."'");
				$position_today = mysql_num_rows($todaytrade);

					if($position_today > 0) {
						$today_p = $today_p + $p->winSum; 
					}
				}
				$total++;
			
			}
		}
		if($total >= 1000) {
			
			$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid&page=3";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

			$result=curl_exec ($ch);
			$xml = simplexml_load_string($result);
			
			foreach($xml->Positions->children() as $p) {
				
				if($p->status == "open") {
					continue;
				}
				elseif($p->status == "lost") {
					$lost++;
				}
				elseif($p->status == "won") {
					$won++;
					
					// search 4 today profits
					$position_id = $p->id;
					
					$todaytrade=mysql_query("select * from executed_trades_details where position_id='$position_id' and DATE(serverDate) = '".date("Y-m-d")."'");
				$position_today = mysql_num_rows($todaytrade);

					if($position_today > 0) {
						$today_p = $today_p + $p->winSum; 
					}
				}
				$total++;
			
			}
		}
		if($total >= 1500) {
			
			$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid&page=4";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

			$result=curl_exec ($ch);
			$xml = simplexml_load_string($result);
			
			foreach($xml->Positions->children() as $p) {
				
				if($p->status == "open") {
					continue;
				}
				elseif($p->status == "lost") {
					$lost++;
				}
				elseif($p->status == "won") {
					$won++;
					
					// search 4 today profits
					$position_id = $p->id;					
					
					$todaytrade=mysql_query("select * from executed_trades_details where position_id='$position_id' and DATE(serverDate) = '".date("Y-m-d")."'");
				$position_today = mysql_num_rows($todaytrade);

					if($position_today > 0) {
						$today_p = $today_p + $p->winSum; 
					}
				}
				$total++;
			
			}
		}
	break;

        case "TRADOLOGIC":
		
		$url = $getbrandrow['api_url']."trading/getTrades";
							
		$affiliateUserName = $getbrandrow['username'];
		$affiliatePassword = $getbrandrow['password'];

		$now = gmdate("Y-m-d H:i:s");
		$data = "affiliateUserName=$affiliateUserName&affiliatePassword=$affiliatePassword&accountId=$spotid&fromDate=2015-06-01 00:00:00&toDate=$now&status=18,20,147";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
         
		$result=curl_exec ($ch);
		
		

		$decoded = json_decode($result);
                //print_r($decoded);
		$won = 0;
		$total = 0;
		$lost = 0;
                $totalwopen = 0;
                $tie = 0;

		foreach($decoded->result as $t) {
                        $totalwopen++;
			if($t->tradinghistory_status == 20) {
				$total++;
				$positionType = $t->tradinghistory_positionType;
				if($positionType == 49) { // call
					if($t->tradinghistory_closePrice > $t->tradinghistory_strike){ $won++; }
                                        if($t->tradinghistory_closePrice < $t->tradinghistory_strike){ $lost++; }
                                        if($t->tradinghistory_closePrice == $t->tradinghistory_strike){ $tie++; }
				}elseif($positionType == 50) { // put
					if($t->tradinghistory_closePrice < $t->tradinghistory_strike){ $won++; }
                                        if($t->tradinghistory_closePrice > $t->tradinghistory_strike){ $lost++; }
                                        if($t->tradinghistory_closePrice == $t->tradinghistory_strike){ $tie++; }
				}
				
			}
			
		$open=$totalwopen-$total;
	
		}

	  break;
	  
	  case "AIRSOFT":
            require_once("php/Brand/Type/Airsoft.php");
            $airsoft = new Airsoft();
            $aClientInfo = $airsoft->setClientInfoUrl()->request()->getResponse();
            $aPositions = $airsoft->setPositionsUrl()->request()->getResponse();
            $aOpenPositions = $airsoft->getOpenPositions($aPositions, $aClientInfo);

            $aClosedPositions = $airsoft->getClosedPositions($aPositions, $aClientInfo);
            $won = 0;
            $lost = 0;
            $total = $open = count($aOpenPositions);
            $tie = 0;
            foreach ($aClosedPositions as $position) {
                if($position['status'] == 'LOSE') {
                    $lost++;
                }
                if($position['status'] == 'WIN') {
                    $won++;
                }
                if($position['status'] == 'TIE') {
                    $tie++;
                }
            }
            $total += $lost + $won + $tie;
            break;

        default:
          // echo "<!--Your favorite color is neither red, blue, nor green! -->";

	}
	

// Form Process Start Here //

$loggedemail = $getuserrow['email'];

if(isset($_POST['autotrade_on'])){
$query=mysql_query("UPDATE `users` SET `autotrade`='1' WHERE `email`='$loggedemail'")or die(mysql_error());
 header('Location: '.$_SERVER['REQUEST_URI']);
}

if(isset($_POST['autotrade_off'])){
$query=mysql_query("UPDATE `users` SET `autotrade`='0' WHERE `email`='$loggedemail'")or die(mysql_error());
 header('Location: '.$_SERVER['REQUEST_URI']);
}


if(isset($_POST['form25'])){
$query=mysql_query("UPDATE `users` SET `a_amount`='25' WHERE `email`='$loggedemail'")or die(mysql_error());
header('Location: '.$_SERVER['REQUEST_URI']);
}

if(isset($_POST['form50'])){
$query=mysql_query("UPDATE `users` SET `a_amount`='50' WHERE `email`='$loggedemail'")or die(mysql_error());
header('Location: '.$_SERVER['REQUEST_URI']);
}

if(isset($_POST['form100'])){
$query=mysql_query("UPDATE `users` SET `a_amount`='100' WHERE `email`='$loggedemail'")or die(mysql_error());
header('Location: '.$_SERVER['REQUEST_URI']);
}

if(isset($_POST['form150'])){
$query=mysql_query("UPDATE `users` SET `a_amount`='150' WHERE `email`='$loggedemail'")or die(mysql_error());
header('Location: '.$_SERVER['REQUEST_URI']);
}

if(isset($_POST['form200'])){
$query=mysql_query("UPDATE `users` SET `a_amount`='200' WHERE `email`='$loggedemail'")or die(mysql_error());
header('Location: '.$_SERVER['REQUEST_URI']);
}

if(isset($_POST['form250'])){
$query=mysql_query("UPDATE `users` SET `a_amount`='250' WHERE `email`='$loggedemail'")or die(mysql_error());
header('Location: '.$_SERVER['REQUEST_URI']);
}




// Form Process END Here //
$_SESSION['balance'] = $balance;
$_SESSION['spotid'] = $spotid;
require_once('include/header.php'); ?>
      <!-- BEGIN PAGE -->  
<!-- BEGIN PAGE -->
    
    <style>



        body {
            background: #fff none repeat scroll 0 0;
        }
        #main-content {
            background: #fcfcfc none repeat scroll 0 0;
        }
        .collection {
            font-size: 16px;
            list-style-type: square;
        }
        .label, .badge {
            font-size: 14px;
        }
        .collection-item {
            margin-bottom: 15px;
        }
        .collection-item .badge {
            float: right;
            max-width: 195px;
            overflow: hidden;
        }
        .howtotrade {
            text-align: center;
            text-transform: uppercase;
        }
        .howtotrade h3 {
            color: #ffffff;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .hometopwidget {
            border-bottom: 2px solid #fff;
        }
        .hometopwidgetbody {
            background: #74b749 none repeat scroll 0 0;
            color: #fff;
        }
        .dark-cyan .hometopwidgetbody {
            background: #00acc1 none repeat scroll 0 0;
        }
        .teal .hometopwidgetbody {
            background: #009688 none repeat scroll 0 0;
        }
        .alert-homes {
            background: #4090db none repeat scroll 0 0;
            color: #fff;
        }
        .table-advance thead tr th {
            background-color: #00bcd4;
            color: #fff;
            line-height: 25px;
            text-align: center;
        }
        .table-advance tr td {
            border-left-width: 0;
            text-align: center;
            vertical-align: middle;
        }
        .widget-title {
            background: #4a8bc2 none repeat scroll 0 0;
            height: 39px;
        }
        .autotradestat {
            float: right;
        }
        .ddcommon .ddChild li img {
            margin-right: 15px;
            max-width: 130px;
            min-height: 35px;
        }
        .ddcommon .ddTitle .ddTitleText img {
            margin-right: 15px;
            max-width: 95px;
            min-height: 35px;
        }
        .brokerdrop {
            border: 1px solid #74b749;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
            margin-top: 0px;
            width:70%; float:right;
        }
        .dd .ddTitle, .dd .ddChild li .ddlabel {
            color: #dd7c00;
            font-weight: bold;
        }
        .dd .ddTitle .description, .dd .ddChild li .description {
            color: #74b749;
        }
        .autotradestat {
            margin-right: 10px;
            margin-top: 5px;
        }
        .metro-nav .metro-nav-block.double {
            width: 22.3%;
        }
        .tradebtn {
            background: #ffb400 none repeat scroll 0 0;
            border: 2px solid #74b749;
            color: #fff;
            font-weight: bold;
            height: 33px;
        }
        .tradedpdown {
            background: #00bcd4 none repeat scroll 0 0;
            color: #ffffff;
            height: 35px;
            margin: 0;
            width: 75px;
        }
        .tradeplaced {
            font-weight: bold;
        }
        .cardtable {
            background-color: rgba(87, 210, 222, 0.5);
            margin-bottom: 10px;
            min-height: 360px;
            position: relative;
        }
        .aloader {
            left: 50%;
            margin-left: -32px;
            margin-top: -32px;
            position: absolute;
            top: 50%;
            width: 100px;
        }
        .videoiframe {
            min-height: 540px;
            width: 100%;
        }
        .modal .modal-body {
            overflow: hidden;
        }
        @media screen and (min-width: 767px) and (max-width: 90000px) {
            .modal {
                left: 31%;
                width: 1050px;
            }
            .modal.fade.in {
                bottom: 10%;
                top: 5%;
            }
            .modal .modal-body {
                max-height: 800px;
                padding: 0;
            }
        }
        #myModal {
            text-align: right;
        }
        .buttonclose {
            background: #00bcd4 none repeat scroll 0 0;
            color: #fff;
            font-size: 11px;
            margin-right: -11px;
            margin-top: -22px;
            padding: 0 7px;
        }
        .btn {
            /*color: #6ab4ff;*/
            font-weight: bold;
        }
        .dropdown-menu > li > a {
            color: #00bcd4;
        }
        .blackfooter {
            background-color: #00bcd4;
            margin-left: 180px;
        }
		
    </style>

    <?php $deposit_status = 0; if (($balance * 100 == '0') || ($getuserrow['deposit_status'] == 'DEMO')) { $deposit_status = 1; ?>
   <!----------Modal Here -------------->

        <?php
    } else {

        if ($getuserrow['deposit_date'] == '') {
            $update = mysql_query("UPDATE `users` SET `deposit_status`='LIVE', `balance`='$balance', `deposit_date`=now() WHERE `id`='" . $getuserrow['id'] . "'");
        } else {
            $update = mysql_query("UPDATE `users` SET `balance`='$balance' WHERE `id`='" . $getuserrow['id'] . "'");
        }
    }
    ?>


    <div>
        <!-- BEGIN PAGE CONTAINER-->
        <div class="container-fluid">
            <div class="row bg-title-dashboard bredcrums-menu">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Dashboard</h4>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- BEGIN PAGE HEADER-->
            <div class="row-fluid">
                <div class="span12" style="min-height: 20px;">
                   

                </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->

            <div class="row-fluid row-wrapper">
			    
                <?php include("cards.php"); ?>
                


                <div class="span4 autotradeform-container">
                    <form name="autotradeform" method="POST" style="margin: 0;">
                        <div class="white-boxes-traders">
                            <h4>Activate Account Trading</h4>
                            <p>Before you can activate your trading system, You must <a href="http://quityourjobs.biz/members/deposit.php">Fund Your Account</a></p>


                            <div class="span4">
                                <button type="submit" name="autotrade_on" class="btn btn-block btn-default <?php
                                if ($getuserrow['autotrade'] == "1") {
                                    echo "active";
                                }
                                ?>">Active</button>
                            </div>

                            <div class="span4">
                                <button type="submit" name="autotrade_off"  class="btn btn-block btn-danger <?php
                                if ($getuserrow['autotrade'] == "1") {
                                    echo "active";
                                }
                                ?>">Deactive</button>
                            </div>

                            <div class="clear">&nbsp;</div>

                            <p><span class="text-danger">Autotrader will be available after completing Step-1</span></p>

                        </div>



                        <div style="height: 155px;overflow-x: hidden;" class="white-boxes-traders-account">
                             <?php if ($getuserrow['autotrade'] == "1") { ?>
                                <h4>Select Amount of Trade</h4>
                                        <?php $amount = $getuserrow['a_amount']; ?>

                                <button class="btn waves-effect waves-light <?php
                                        if ($amount == "25") {
                                            echo "active";
                                        }
                                        ?>" name="form25" type="submit">$25</button>
                                <button class="btn waves-effect waves-light <?php
                                if ($amount == "50") {
                                    echo "active";
                                }
                                ?>" name="form50" type="submit">$50</button>
                                <button class="btn waves-effect waves-light <?php
                                if ($amount == "100") {
                                    echo "active";
                                }
                                ?>" name="form100" type="submit">$100</button>
                                <button class="btn waves-effect waves-light <?php
                                if ($amount == "150") {
                                    echo "active";
                                }
                                ?>" name="form150" type="submit">$150</button>
                                <button class="btn waves-effect waves-light <?php
                                if ($amount == "200") {
                                    echo "active";
                                }
                                ?>" name="form200" type="submit">$200</button>
                                <button class="btn waves-effect waves-light <?php
                                    if ($amount == "250") {
                                        echo "active";
                                    }
                                    ?>" name="form250" type="submit">$250</button>
                        </form>
                        <div class="clear">&nbsp;</div>

                        <p class="trader-partner"><span class="text-danger">Trading Partner</span></p>

                        <div class="brokerdrop">
                            <form id="changebroker" method="POST" action="loginmaster.php" style="margin:0px;">
                                <input type="hidden" name="email" value="<?php echo $getuserrow['email']; ?>">
                                <select id="payments" name="brand" style="width:100%;">
        <?php
        $getuserbroker = mysql_query("SELECT DISTINCT brand FROM users WHERE email='" . $getuserrow['email'] . "' AND password='" . $getuserrow['password'] . "'");
        while ($getuserbrokerrow = mysql_fetch_array($getuserbroker)) {
            $branddetails = mysql_query("SELECT * FROM brands WHERE ref='" . $getuserbrokerrow['brand'] . "'");
            $branddetailsrow = mysql_fetch_array($branddetails);
            ?>
                                        <option value="<?php echo $getuserbrokerrow['brand']; ?>" <?php
            if ($getuserbrokerrow['brand'] == $_SESSION['brand']) {
                echo 'selected';
            }
            ?> data-image="images/brokers/<?php echo $getuserbrokerrow['brand']; ?>.png" data-description="<?php echo $branddetailsrow['name']; ?>"><?php echo $branddetailsrow['name']; ?></option>
        <?php } ?>               
                                </select></form></div>

                        <div class="clear" style="height:8px;">&nbsp;</div>
                    </div>
    <?php } else { ?>
                    <br><br><br><br><br>
                    <span class="chart-title white-text">AUTO TRADING IS OFF</span>

    <?php } ?>


                <div class="clear">&nbsp;</div>


            </div>


        </div>


    </div>



    <div class="container-fluid">



        <div class="row-fluid">
            <!-- BEGIN TAB PORTLET-->



            <div id="dashtable" class="cardtable" style="overflow-x:auto;">
                <img src="images/loader.gif" class="aloader">
                <h3 class="" style="left:40%; position: absolute; top:30%; font-weight:bold;">Searching for new winning trades</h3>
            </div>

        </div>

    </div>





    <!-- END PAGE CONTENT-->
    </div>
    <!-- END PAGE CONTAINER-->

    </div>

<script>
$( document ).ready(function() {
<?php if($deposit_status==1) { ?>
var h =$("#videoModal").height();
$("#videoiframe").height(h-50);

$('#videoModal').modal({
 show: 'false',
 backdrop: 'static',
 keyboard: false  
}); 
<?php } ?>
$( document ).on("change",".tradedpdown", function(){ 
var investment=parseInt($(this).val());
var potential =parseInt($(this).closest('tr').find('.potential').text());
var profit = investment+(investment*potential/100);
$(this).closest('tr').find('.profit').text(profit);
}); 

$( document ).on("click",".tradebtn", function(){ 
var investment=parseInt($(this).closest('tr').find('.tradedpdown').val());
var tradetype=$(this).closest('tr').find('.tradetype').text();
var asset = $(this).closest('tr').find('.assetname').val();
//alert(investment);
   $("#tradebox").addClass('tradeshow');
       $.ajax({
	type: "POST",
	url: "ajax/placetrade.php",
	data: {investment: investment, asset: asset, tradetype: tradetype},	
	success: function(html){		
	  $("#tradebox").html(html);
           if(html=='<h4 class="tradeplaced">TRADE PLACED</h4>'){
	      setTimeout(function() { $("#tradebox").removeClass('tradeshow'); }, 500);             
	   }	
	},
        beforeSend:function(){
          $("#tradebox").html('<h4 class="tradeboxtext"> Please Wait..</h4>');
        },
        error:function(){
          $("#tradebox").html('<h4 class="tradeboxtext"> Error! Please Wait..</h4>');
        }
      });    
 }); 



<?php if(($balance*100=='0') || ($getuserrow['deposit_status']=='DEMO')){ ?>

$('#main-content, #sidebar').click(function() {
   if(!$('.helpershow').is(':visible'))
   {  
      document.location.href = "http://quityourjobs.biz/members/deposit.php";    
   }
 });
<?php } ?>
 
});

</script>
	
	
      <!-- END PAGE --> 


<?php require_once('include/footer.php'); ?>

<?php
}
else {
	echo  "<meta http-equiv=\"Refresh\"content=\"0; URL=login.php\">";
 	exit;
}
?>
