<?php 
session_start();
ob_start();
require_once('variables.php');

function sendmail($email,$firstname, $lastname, $password){

	// multiple recipients
	$to  = $email;

	// subject
	$subject = 'Congratulations and Welcome to Self-Made Millionaire System';

	// message
	$message = '
	Hello '.$firstname.',<br><br>

	We would like to welcome you and congratulate you for claiming the<br>
	free copy of this wonderful system for full 60 days of free trial period.<br><br>

	Your Self-Made Millionaire System login credentials are as below:<br><br>

	Login Page: <a href="https://www.google.com/url?hl=en-GB&q=http://selfmademillionaires.biz/members/login.php&source=gmail&ust=1463637539581000&usg=AFQjCNFG4-mcZH7yaMuG-Ttvk-t7CutUpA
">http://selfmademillionaires.biz/members/login.php</a><br>
	Email – '.$email.'<br>
	Pass – '.$password.'<br><br>

	Once you are inside Self-Made Millionaire System, follow the simple<br>
	steps to make profit on autopilot using SMM System.<br><br>

	These simple steps are as below:<br><br>

	1 - Click on \'Deposit\' button in the left hand side menu and make a<br>
	small deposit of $200 to $250 to fund your trading account to<br>
	activate the SMM System.<br><br>

	2 - Then, click on \'Dashboard\' in the left hand side menu, you will see<br>
	the deposited amount in the right hand side against \'Balance\' under<br>
	\'Account Summary\'.<br><br>

	3 - Then logout the SMM System and login again.<br><br>

	4 - That\'s it, you are ready to make profit. We have activated AutoTrading<br>
	(which is the simplest way to make profit hands free) by default. If you<br>
	see \'ON\' button against \'ACTIVATE AUTO TRADING\' in Blue color that<br>
	means AutoTrading is active and $25 selected as the amount of trade.<br>
	But you can change this amount if you wish. With AutoTrading ON, our<br>
	system picks the signals automatically provided by our team of market<br>
	analysts and places the trades on your behalf so that you have all the<br>
	time to enjoy your life with your loved ones while the system is working<br>
	for you and making profit for you without needing you to be in front of<br>
	computer all the time.<br><br>

	So, just sit back, relax and see the profit rolling in.<br><br>

	If you have any query, do visit the FAQ page or contact our Live Chat<br>
	Support, Telephonic Support or email Support that are here to help you<br>
	out in all the possible ways.<br><br>

	Happy Times!<br>
	- Self-Made Millionaire System Team<br>
	';

	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Additional headers
	$headers .= 'From: SMM Member <member@selfmademillionaires.biz>' . "\r\n";


	// Mail it
	mail($to, $subject, $message, $headers);


}

function sendsms($phone){

	$id = "AC11f50a10f8e953cbddd6d90fef116e1f";
	$token = "b3d4d74756b3eeecd4fb957b07e5e3ad";
	$url = "https://api.twilio.com/2010-04-01/Accounts/$id/SMS/Messages";
	$from = "+18052146333";
	$to = $phone; // twilio trial verified number
	$body = "Welcome to Self-Made Millionaire System. Follow the simple steps in the welcome email and see the profit rolling in. Visit here: http://theoffer.info/smm";
	$data = array (
	    'From' => $from,
	    'To' => $to,
	    'Body' => $body,
	);
	$post = http_build_query($data);
	$x = curl_init($url );
	curl_setopt($x, CURLOPT_POST, true);
	curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($x, CURLOPT_USERPWD, "$id:$token");
	curl_setopt($x, CURLOPT_POSTFIELDS, $post);
	$y = curl_exec($x);
	curl_close($x);

}



if(isset($_POST['register'])){

 if(isset($_POST['name'])){
     $name = $_POST['name'];
     $names = explode(" ", $name);
     $first_name=$names[0];
     $last_name=$names[1];
 }else{
     $first_name=$_POST['fname'];
     $last_name=$_POST['lname'];
     $name = $first_name.' '.$last_name;
 }
 $param = $_POST['param'];
 $email = $_POST['email'];
 $password = $_POST['password'];
 $country = $_POST['country'];
 $phone = $_POST['phone'];

 if(isset($_POST['currency'])){
     $currency = $_POST['currency'];
 }else{
     $currency = 'USD';
 }

 echo $countryiso;
 $getcbrand=mysql_query("SELECT * FROM `country` WHERE `iso`='$countryiso'") or die(mysql_error());
 //$getcbrand=mysql_query("SELECT * FROM `country` WHERE `iso`='GB'") or die(mysql_error());
 $getcbrandrow=mysql_fetch_array($getcbrand);
 $brand=$getcbrandrow['brand_ref1'];
 $country = addslashes($getcbrandrow['id']);
 echo $country.$brand.'<br>';

 $getuser=mysql_query("SELECT * FROM `users` WHERE `email`='$email' AND `brand`='$brand'") or die(mysql_error());
 $count=mysql_num_rows($getuser);
 
 $getbrand = mysql_query("SELECT * FROM `brands` WHERE `ref`='$brand'") or die(mysql_error());
 $getbrandrow=mysql_fetch_array($getbrand);

 echo $getbrandrow['api_type'];

 $phone='+'.$getcbrandrow['phonecode'].$phone;  

 if($count=='0'){

	switch ($getbrandrow['api_type']) {

	    case "BLANK":

		header('Location: http://www.google.com/');
		break;

	    case "SPOT":
		// register with the broker
		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Customer&COMMAND=add&FirstName=$first_name&LastName=$last_name&email=$email&password=$password&Phone=$phone&Country=$country&currency=$currency&birthday=1970-01-01&campaignId=".$getbrandrow['campaign_id']."&isDemo=0";
		echo $data;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$getbrandrow['api_url']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result=curl_exec($ch);	
		$xml = simplexml_load_string($result);

		//echo '<br>'.$result; 			
	       
			if($xml->operation_status == "successful") {
			 $spotid = $xml->Customer->id;
			 $insert = mysql_query("INSERT INTO `users`(`id`, `name`, `email`, `password`, `country`, `phone`, `currency`, `brand`, `spotid`, `user_ip`, `param`) VALUES (NULL, '$name', '$email', '$password', '$country', '$phone', '$currency', '$brand', '$spotid', '$client_ip', '$param')")or die(mysql_error());
                          $_SESSION['userid'] = mysql_insert_id();
                          $_SESSION['brand'] =  $brand;
			  sendmail($email,$first_name, $last_name, $password);			                  sendsms($phone);
			  header('Location: index.php');
			  exit;
			}else{
			  header('Location: ../index.php');
			}
		break;

	    case "SPOT-USA":
		// register with the broker
		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Customer&COMMAND=add&FirstName=$first_name&LastName=$last_name&email=$email&password=$password&PhoneNumber=$phone&Country=$countryiso&currency=$currency&birthday=1970-01-01&a_cid=".$getbrandrow['campaign_id']."&a_aid=".$getbrandrow['campaign_id']."&isDemo=0&siteLanguage=EN";
		echo $data;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$getbrandrow['api_url']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result=curl_exec($ch);	
		$xml = simplexml_load_string($result);
                print_R($xml);  

		echo '<br>'.$result;
	       
			if($xml->operation_status == "successful") {
			 $spotid = $xml->Customer->id;
			 $CustomerSessionId = $xml->CustomerSessionId;
			 $insert = mysql_query("INSERT INTO `users`(`id`, `name`, `email`, `password`, `country`, `phone`, `currency`, `brand`, `spotid`, `user_ip`, `param`) VALUES (NULL, '$name', '$email', '$password', '$country', '$phone', '$currency', '$brand', '$spotid', '$client_ip', '$param')")or die(mysql_error());
                         $_SESSION['userid'] = mysql_insert_id();
                         $_SESSION['brand'] =  $brand;
			 sendmail($email,$first_name, $last_name, $password);			                  sendsms($phone);
			 header('Location: index.php');
			}else{
			  echo 'Email Already Exist'; 
			}
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
	    
           // register with the broker
		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Customer&COMMAND=add&FirstName=$first_name&LastName=$last_name&email=$email&password=$password&Phone=$phone&Country=$country&currency=$currency&birthday=1970-01-01&campaignId=".$getbrandrow['campaign_id']."&isDemo=0";
		echo $data;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result=curl_exec($ch);	
		$xml = simplexml_load_string($result);

		echo '<br>'.$result;
	       
			if($xml->operation_status == "successful") {
			 $spotid = $xml->Customer->id;
			 $insert = mysql_query("INSERT INTO `users`(`id`, `name`, `email`, `password`, `country`, `phone`, `currency`, `brand`, `spotid`, `user_ip`, `param`) VALUES (NULL, '$name', '$email', '$password', '$country', '$phone', '$currency', '$brand', '$spotid', '$client_ip', '$param')")or die(mysql_error());
                         $_SESSION['userid'] = mysql_insert_id();
                         $_SESSION['brand'] =  $brand;
			 sendmail($email,$first_name, $last_name, $password);			                  sendsms($phone);
			 header('Location: index.php');
			}else{
			  echo 'Email Already Exist'; 
			}
		break;

	    case "SPOT-RALLY":

		// register with the broker
		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Customer&COMMAND=add&FirstName=$first_name&LastName=$last_name&email=$email&password=$password&Phone=$phone&Country=$country&currency=$currency&birthday=1970-01-01&campaignId=".$getbrandrow['campaign_id']."&isDemo=0";
		echo $data;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$getbrandrow['api_url']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result=curl_exec($ch);	
		$xml = simplexml_load_string($result);

		echo '<br>'.$result;
	       
			if($xml->operation_status == "successful") {
			 $spotid = $xml->Customer->id;
			 $insert = mysql_query("INSERT INTO `users`(`id`, `name`, `email`, `password`, `country`, `phone`, `currency`, `brand`, `spotid`, `user_ip`, `param`) VALUES (NULL, '$name', '$email', '$password', '$country', '$phone', '$currency', '$brand', '$spotid', '$client_ip', '$param')")or die(mysql_error());
                         $_SESSION['userid'] = mysql_insert_id();
                         $_SESSION['brand'] =  $brand;
			 sendmail($email,$first_name, $last_name, $password);			                  sendsms($phone);
			 header('Location: index.php');
			}else{
			  echo 'Email Already Exist'; 
			}
		break;

	    case "SPOT-KEY":
		
           // register with the broker
		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Customer&COMMAND=add&FirstName=$first_name&LastName=$last_name&email=$email&password=$password&Phone=$phone&Country=$country&currency=$currency&birthday=1970-01-01&campaignId=".$getbrandrow['campaign_id']."&isDemo=0&regulateStatus=approve&regulateType=".$getcbrandrow['reg'];
		echo $data;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$getbrandrow['api_url']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result=curl_exec($ch);	
		$xml = simplexml_load_string($result);

		echo '<br>'.$result;
	       
			if($xml->operation_status == "successful") {
			 $spotid = $xml->Customer->id;
			 $insert = mysql_query("INSERT INTO `users`(`id`, `name`, `email`, `password`, `country`, `phone`, `currency`, `brand`, `spotid`, `user_ip`, `param`) VALUES (NULL, '$name', '$email', '$password', '$country', '$phone', '$currency', '$brand', '$spotid', '$client_ip', '$param')")or die(mysql_error());
                         $_SESSION['userid'] = mysql_insert_id();
                         $_SESSION['brand'] =  $brand;
			 sendmail($email,$first_name, $last_name, $password);			                  sendsms($phone);
			 header('Location: index.php');
			}else{
			  echo 'Email Already Exist'; 
			}
		break;

	    case "TECH FINANCIALS":
		
             $url_register = $getbrandrow['api_url']."customer/registerTrader";
             echo  $url_register;

             $my_ip = $_SERVER['REMOTE_ADDR'];
             $ip = ip2long($_SERVER['REMOTE_ADDR']);
             echo $ip;

             list($username, $domain) = explode("@", $email);
             $username = substr($username, 0, 15);
             $countryId = $getcbrand['iso'];
             $phonePrefixCountryId=$getcbrandrow['phonecode'];
             $brandID='89';
             $trackingCodeParam1 = "";
             $trackingCodeParam2 = "";
             $trackingCodeParam3 = "";
             $trackingCodeParam4 = "";
             $externalid = "Transcend"; 
$data = "affiliateUserName=".$getbrandrow['username']."&affiliatePassword=".$getbrandrow['password']."&userName=$username&firstName=$first_name&lastName=$last_name&phoneNumber=$phone&phonePrefixCountryId=$phonePrefixCountryId&phoneNumber2&phone2PrefixCountryId&countryId=$countryId&birthDate=1980-01-01&email=$email&subscribeToNewsLetter=true&accountLevelID=9&trackingCode=".$getbrandrow['campaign_id']."&registrationIP=$ip&siteLanguage=EN&brandID=$brandID&currencyId=$currency&password=$password&trackingCodeParam1=$trackingCodeParam1&trackingCodeParam2=$trackingCodeParam2&trackingCodeParam3=$trackingCodeParam3&trackingCodeParam4=$trackingCodeParam4&externalid=$externalid";

             echo $data;

             $ch = curl_init();
             curl_setopt($ch, CURLOPT_URL, $url_register);
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
             curl_setopt($ch, CURLOPT_POST, 1);
             curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
             $result=curl_exec ($ch);
             echo $result;
             $decoded = json_decode($result);

             $spotid = $decoded->result->apiaccountview_id;

             if($spotid) {
		$insert = mysql_query("INSERT INTO `users`(`id`, `name`, `email`, `password`, `country`, `phone`, `currency`, `brand`, `spotid`, `user_ip`, `param`) VALUES (NULL, '$name', '$email', '$password', '$country', '$phone', '$currency', '$brand', '$spotid', '$client_ip', '$param')")or die(mysql_error());
                $_SESSION['userid'] = mysql_insert_id();
                $_SESSION['brand'] =  $brand;
		sendmail($email,$first_name, $last_name, $password);			                  sendsms($phone);
		header('Location: index.php');
		}else{
			  echo 'Email Already Exist'; 
		}

		break;

	    case "TRADOLOGIC":
             
	     $b2bApiUrl = $getbrandrow['api_url'];
	     $accountId = $getbrandrow['campaign_id'];
	     $affiliateUsername = $getbrandrow['username'];
	     $authenticationKey = $getbrandrow['password'];
             $my_ip = $_SERVER['REMOTE_ADDR'];
             $deal_id=$getbrandrow['deal_id'];
            
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
		'email' => $email,
		'userPassword' => $password,
		'userFirstName' => $first_name,
		'userLastName' => $last_name,
		'phone' => $phone,                
		'affiliateUsername' => $affiliateUsername,
		'accountId' => $accountId,
		'userIpAddress' => $my_ip,
                'dealId' => $deal_id,
                'comment' => 'SMM'
	     ];

	     $checksum=getChecksum($dataParameters, $authenticationKey);
	     $dataParameters['checksum'] = $checksum;
	     $postData = json_encode($dataParameters);
	     $baseUrl=$b2bApiUrl;
	     $resourceRoute='/v1/affiliate/users';
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
	    print_r($jsonResponse);
            $spotid=$jsonResponse->messageParameters->userId;
            $sessionId=$jsonResponse->messageParameters->sessionId;

             if($spotid) {
		$insert = mysql_query("INSERT INTO `users`(`id`, `name`, `email`, `password`, `country`, `phone`, `currency`, `brand`, `spotid`, `user_ip`, `session_id`, `param`) VALUES (NULL, '$name', '$email', '$password', '$country', '$phone', '$currency', '$brand', '$spotid', '$client_ip', '$sessionId', '$param')")or die(mysql_error());
                $_SESSION['userid'] = mysql_insert_id();
                $_SESSION['brand'] =  $brand;
		sendmail($email,$first_name, $last_name, $password);			                  sendsms($phone); 
		header('Location: index.php');
		}else{
	          echo 'Email Already Exist'; 
		}

		break;

	    case "OPTECK":

                require_once('include/OpteckAPI.php');
                $affiliateID = $getbrandrow['username'];
                $partnerID = $getbrandrow['password'];

                $affiliate = new OpteckAPI($affiliateID, $partnerID);

		$createLead = $affiliate->request('createLead', array(
		    'email' => $email,      // test@email.com
		    'firstName' => $first_name,  // John
		    'lastName' => $last_name,   // Doe
		    'phone' => $phone,      // +380501234567
		    'language' => 'en',   // EN
		    'country' => $countryiso,    // UA
		    'password' => $password    // UA
		));

                
		$jsonResponse = json_decode($createLead);
                print_R($jsonResponse);
                $spotid = $jsonResponse->data->leadID;
                echo $spotid;

		if(!empty($spotid)) {
		
		 $insert = mysql_query("INSERT INTO `users`(`id`, `name`, `email`, `password`, `country`, `phone`, `currency`, `brand`, `spotid`, `user_ip`, `param`) VALUES (NULL, '$name', '$email', '$password', '$country', '$phone', '$currency', '$brand', '$spotid', '$client_ip', '$param')")or die(mysql_error());
                 $_SESSION['userid'] = mysql_insert_id();
                 $_SESSION['brand'] =  $brand;
		sendmail($email,$first_name, $last_name, $password);			                  sendsms($phone);
		 header('Location: index.php');
		}else{
		  echo 'Email Already Exist'; 
		}
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
			'email' => $email,
			'password' =>  $password,
			'ip' => $client_ip,
			'newsletter' => '0',
			'source' => '1',
			'countryId' => $countryiso,
			'firstName' => $first_name,
			'lastName' => $last_name,
		);


		// register with the broker

		$url = $getbrandrow['api_url'].'/api/v1/Registration?' . createApiRequest($request, $PARTNER_ID, $PARTNER_SECRET_KEY);	
		
		echo $url;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 0);
		$result=curl_exec($ch);	
		$jsonResponse = json_decode($result);
                print_R($jsonResponse);
	       
			if(!empty($jsonResponse->data->userId)) {
			 $spotid = $jsonResponse->data->userId;
			 $insert = mysql_query("INSERT INTO `users`(`id`, `name`, `email`, `password`, `country`, `phone`, `currency`, `brand`, `spotid`, `user_ip`, `param`) VALUES (NULL, '$name', '$email', '$password', '$country', '$phone', '$currency', '$brand', '$spotid', '$client_ip', '$param')")or die(mysql_error());
                         $_SESSION['userid'] = mysql_insert_id();
                         $_SESSION['brand'] =  $brand;
			 sendmail($email,$first_name, $last_name, $password);			                  sendsms($phone);
			 header('Location: index.php');
			}else{
			  echo 'Email Already Exist'; 
			}
		break;

	    case "MARKETPULSE":

		$service_url = $getbrandrow['api_url'].'CreateAccount';
		$operatorName = $getbrandrow['name'];
		$affilate_name = $getbrandrow['username'];
		$operatorSecretKey = $getbrandrow['password'];
		$affilate_id =  $getbrandrow['campaign_id'];
echo $service_url;
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
			'username' => $username,
			'email' => $email,
			'firstName' => $first_name,
			'lastName' => $last_name,
			'currency' => 'USD',
			'country' =>  $countryiso,
			'phone' => $phone,
			'password' => $password,
			'languageID' => 1033,
			"RegistrationIP" => $client_ip
		
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
		print_r($jsonResponse);
	       
			if(!empty($jsonResponse->data->traderID)) {
			 $spotid = $jsonResponse->data->traderID;
			 $insert = mysql_query("INSERT INTO `users`(`id`, `name`, `email`, `password`, `country`, `phone`, `currency`, `brand`, `spotid`, `user_ip`, `param`) VALUES (NULL, '$name', '$email', '$password', '$country', '$phone', '$currency', '$brand', '$spotid', '$client_ip', '$param')")or die(mysql_error());
                         $_SESSION['userid'] = mysql_insert_id();
                         $_SESSION['brand'] =  $brand;
			 sendmail($email,$first_name, $last_name, $password);			                  sendsms($phone);
			 header('Location: index.php');
			}else{
			  echo 'Email Already Exist'; 
			}
		break;



	    default:

		// register with the broker
		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Customer&COMMAND=add&FirstName=$first_name&LastName=$last_name&email=$email&password=$password&Phone=$phone&Country=$country&currency=$currency&birthday=1970-01-01&campaignId=".$getbrandrow['campaign_id']."&isDemo=0";
		echo $data;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$getbrandrow['api_url']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result=curl_exec($ch);	
		$xml = simplexml_load_string($result);

		//echo '<br>'.$result; 			
	       
		if($xml->operation_status == "successful") {
		 $spotid = $xml->Customer->id;
		 $insert = mysql_query("INSERT INTO `users`(`id`, `name`, `email`, `password`, `country`, `phone`, `currency`, `brand`, `spotid`, `user_ip`, `param`) VALUES (NULL, '$name', '$email', '$password', '$country', '$phone', '$currency', '$brand', '$spotid', '$client_ip', '$param')")or die(mysql_error());
                  $_SESSION['userid'] = mysql_insert_id();
                  $_SESSION['brand'] =  $brand;
		  sendmail($email,$first_name, $last_name, $password);			                  sendsms($phone);
		  header('Location: index.php');
		  exit;
		}else{
		  echo 'Email Already Exist'; 
		}

	}
    
 
  
 }else{ // Count Zero END //

echo 'User Already Have Account';

}

} // POST REGISTER BUTTON FUNCTION END //




?>
