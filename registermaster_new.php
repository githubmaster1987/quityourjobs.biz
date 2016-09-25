<?php 
session_start();
ob_start();
require_once('variables.php');

function sendmail($email,$firstname, $lastname, $password){

	// multiple recipients
	$to  = $email;

	// subject
	$subject = 'Congratulations! Welcome to Quit Your Jobs';

	// message
	$message = '
	Hello '.$firstname.',<br><br>

	----------------------------------------------------------<br><br>

	Your next step is to note down your login URL:<br><br>

	Login Page - <a href="http://quityourjobs.biz/members/index.php">http://quityourjobs.biz/members/index.php</a><br>
	Email – '.$email.'<br>
	Password – '.$password.'<br><br>

	Please Bookmark the page for quick access!<br><br>

	----------------------------------------------------------<br><br>

	And finally, please make sure you contact us if you have any questions!<br><br>

	Happy Times!<br>
	-  Quit Your Jobs Team<br>
	';

	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Additional headers
	$headers .= 'From: Quit Your Jobs <members@quityourjobs.biz>' . "\r\n";


	// Mail it
	mail($to, $subject, $message, $headers);


}

function sendsms($phone){

	$id = "AC11f50a10f8e953cbddd6d90fef116e1f";
	$token = "b3d4d74756b3eeecd4fb957b07e5e3ad";
	$url = "https://api.twilio.com/2010-04-01/Accounts/$id/SMS/Messages";
	$from = "+18052146333";
	$to = $phone; // twilio trial verified number
	$body = "Welcome to Quit Your Jobs. Follow the simple steps in the welcome email and see the profit rolling in. Visit here: http://theoffer.info/qyj";
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

function redirect() {
	echo '<script type="text/javascript">window.top.location.href = "/members/index.php";</script>';
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


//echo $countryiso;
 if(empty($country)) {
	$whereCond = "`iso`='$countryiso'";
 }
 else {
	 $whereCond = "`nicename`='$country'";
 }

 $getcbrand=mysql_query("SELECT * FROM `country` WHERE $whereCond") or die(mysql_error());
 //$getcbrand=mysql_query("SELECT * FROM `country` WHERE `iso`='GB'") or die(mysql_error());
 $getcbrandrow=mysql_fetch_array($getcbrand);
 if (!empty($getcbrandrow)) {
     $totalBrands = 0;
     foreach ($getcbrandrow as $key => $value) {
         if (is_string($key) && strstr($key, 'brand_ref')) {
             $totalBrands++;
         }
     }
 }
 
 $countryiso = $getcbrandrow['iso'];
 /*
 $temp_phone = substr($phone, -10);
 
 if (!preg_match("/^[0-9]{10}$/", $temp_phone)) {
	 $_SESSION['registration_failure_message'] = 'Please enter a valid phone number';
	 header('Location: f_register_b.php');
	 return;
 }
 */
 
$phone = "+".str_replace(' ', '', $phone);

 // MULTIBROKER CODE START //


$maxTries = 10;
$blankCount = 0;
if ($_SERVER['REMOTE_ADDR'] == '119.82.78.202') {
     //echo '<pre>';print_r($_POST);exit;
}
for ($try=1; $try<=$maxTries; $try++) { // FOR LOOP STARTED //
 $currentbrand='brand_ref'.$try;
 $brand=$getcbrandrow[$currentbrand];
 $country = addslashes($getcbrandrow['id']);
//echo $country.$brand.'<br>';

 $getuser=mysql_query("SELECT * FROM `users` WHERE `email`='$email' AND `brand`='$brand'") or die(mysql_error());
 $count=mysql_num_rows($getuser);
 
 $getbrand = mysql_query("SELECT * FROM `brands` WHERE `ref`='$brand'") or die(mysql_error());
 $getbrandrow=mysql_fetch_array($getbrand);
//echo $getbrandrow['api_type'];

 if($count=='0'){

	switch ($getbrandrow['api_type']) {

	    case "BLANK":
		$blankCount++;
		continue;
		break;

	    case "SPOT":
		// register with the broker
		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Customer&COMMAND=add&FirstName=$first_name&LastName=$last_name&email=$email&password=$password&Phone=$phone&Country=$country&currency=$currency&birthday=1970-01-01&campaignId=".$getbrandrow['campaign_id']."&isDemo=0";
	//echo $data;
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
			  redirect(); 
			  exit;
			}else{
			  continue;
                        }

		break;

	    case "SPOT-USA":
		// register with the broker
		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Customer&COMMAND=add&FirstName=$first_name&LastName=$last_name&email=$email&password=$password&PhoneNumber=$phone&Country=$countryiso&currency=$currency&birthday=1970-01-01&a_cid=".$getbrandrow['campaign_id']."&a_aid=".$getbrandrow['campaign_id']."&isDemo=0&siteLanguage=EN";
		//echo $data;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$getbrandrow['api_url']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result=curl_exec($ch);	
		$xml = simplexml_load_string($result);
                //print_R($xml);  

		//echo '<br>'.$result;
	       
			if($xml->operation_status == "successful") {
			 $spotid = $xml->Customer->id;
			 $CustomerSessionId = $xml->CustomerSessionId;
			 $insert = mysql_query("INSERT INTO `users`(`id`, `name`, `email`, `password`, `country`, `phone`, `currency`, `brand`, `spotid`, `user_ip`, `session_id`, `param`) VALUES (NULL, '$name', '$email', '$password', '$country', '$phone', '$currency', '$brand', '$spotid', '$client_ip', '$CustomerSessionId', '$param')")or die(mysql_error());
                         $_SESSION['userid'] = mysql_insert_id();
                         $_SESSION['brand'] =  $brand;
			 sendmail($email,$first_name, $last_name, $password);			                  sendsms($phone);
			 redirect(); 
			 exit;
			}else{
			  continue;
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
		//echo $data;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
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
			 redirect(); 
			 exit;
			}else{
			  continue;
                        }
		break;

	    case "SPOT-RALLY":

		// register with the broker
		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Customer&COMMAND=add&FirstName=$first_name&LastName=$last_name&email=$email&password=$password&Phone=$phone&Country=$country&currency=$currency&birthday=1970-01-01&campaignId=".$getbrandrow['campaign_id']."&isDemo=0";
		//echo $data;
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
			 redirect(); 
			 exit;
			}else{
			  continue;
                        }
		break;

	    case "SPOT-KEY":
		
           // register with the broker
		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Customer&COMMAND=add&FirstName=$first_name&LastName=$last_name&email=$email&password=$password&Phone=$phone&Country=$country&currency=$currency&birthday=1970-01-01&campaignId=".$getbrandrow['campaign_id']."&isDemo=0&regulateStatus=approve&regulateType=".$getcbrandrow['reg'];
	//echo $data;
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
			 redirect(); 
			 exit;
			}else{
			  continue;
                        }
		break;

	    case "TECH FINANCIALS":
		
             $url_register = $getbrandrow['api_url']."customer/registerTrader";
            //echo  $url_register;

             $my_ip = $_SERVER['REMOTE_ADDR'];
             $ip = ip2long($_SERVER['REMOTE_ADDR']);
            //echo $ip;

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

            //echo $data;

             $ch = curl_init();
             curl_setopt($ch, CURLOPT_URL, $url_register);
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
             curl_setopt($ch, CURLOPT_POST, 1);
             curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
             $result=curl_exec ($ch);
            //echo $result;
             $decoded = json_decode($result);

             $spotid = $decoded->result->apiaccountview_id;

             if($spotid) {
		$insert = mysql_query("INSERT INTO `users`(`id`, `name`, `email`, `password`, `country`, `phone`, `currency`, `brand`, `spotid`, `user_ip`, `param`) VALUES (NULL, '$name', '$email', '$password', '$country', '$phone', '$currency', '$brand', '$spotid', '$client_ip', '$param')")or die(mysql_error());
                $_SESSION['userid'] = mysql_insert_id();
                $_SESSION['brand'] =  $brand;
		sendmail($email,$first_name, $last_name, $password);			                  sendsms($phone);
		redirect(); 
		exit;
		}else{
		 continue;
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
	    //print_r($jsonResponse);
            $spotid=$jsonResponse->messageParameters->userId;
            $sessionId=$jsonResponse->messageParameters->sessionId;

             if($spotid) {
		$insert = mysql_query("INSERT INTO `users`(`id`, `name`, `email`, `password`, `country`, `phone`, `currency`, `brand`, `spotid`, `user_ip`, `session_id`, `param`) VALUES (NULL, '$name', '$email', '$password', '$country', '$phone', '$currency', '$brand', '$spotid', '$client_ip', '$sessionId', '$param')")or die(mysql_error());
                $_SESSION['userid'] = mysql_insert_id();
                $_SESSION['brand'] =  $brand;
		sendmail($email,$first_name, $last_name, $password);			                  sendsms($phone); 
		redirect(); 
	        exit;
		}else{
		  continue;
                }

		break;

	    case "OPTECK":

                require_once('include/OpteckAPI.php');
                $affiliateID = $getbrandrow['username'];
                $partnerID = $getbrandrow['password'];

                $affiliate = new OpteckAPI($affiliateID, $partnerID);

		$createLead = $affiliate->request('createLead', array(
      'email' => $email,      
      'firstName' => $first_name,  
      'lastName' => $last_name,  
      'phone' => $phone,     
      'language' => 'en',  
      'country' => $countryiso,    
      'password' => $password,
      'marker' => "Quit Your Jobs http://quityourjobs.biz"
  ));

                
		$jsonResponse = json_decode($createLead);
                //print_R($jsonResponse);
                $spotid = $jsonResponse->data->leadID;
               //echo $spotid;

		if(!empty($spotid)) {
		
		 $insert = mysql_query("INSERT INTO `users`(`id`, `name`, `email`, `password`, `country`, `phone`, `currency`, `brand`, `spotid`, `user_ip`, `param`) VALUES (NULL, '$name', '$email', '$password', '$country', '$phone', '$currency', '$brand', '$spotid', '$client_ip', '$param')")or die(mysql_error());
                 $_SESSION['userid'] = mysql_insert_id();
                 $_SESSION['brand'] =  $brand;
		sendmail($email,$first_name, $last_name, $password);			                  sendsms($phone);
		 redirect(); 
		 exit;
		 }else{
		  continue;
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
			'newsletter' => '1',
			'source' => '1',
			'countryId' => $countryiso,
			'firstName' => $first_name,
			'lastName' => $last_name,
			'phoneNumber' => $phone,
			'referral'=> $getbrandrow['campaign_id'],
		);


		// register with the broker

		$url = $getbrandrow['api_url'].'/api/v1/Registration?' . createApiRequest($request, $PARTNER_ID, $PARTNER_SECRET_KEY);	
		
	//echo $url;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 0);
		$result=curl_exec($ch);	
		$jsonResponse = json_decode($result);
                //print_R($jsonResponse);
	       
			if(!empty($jsonResponse->data->userId)) {
			 $spotid = $jsonResponse->data->userId;
			 $insert = mysql_query("INSERT INTO `users`(`id`, `name`, `email`, `password`, `country`, `phone`, `currency`, `brand`, `spotid`, `user_ip`, `param`) VALUES (NULL, '$name', '$email', '$password', '$country', '$phone', '$currency', '$brand', '$spotid', '$client_ip', '$param')")or die(mysql_error());
                         $_SESSION['userid'] = mysql_insert_id();
                         $_SESSION['brand'] =  $brand;
			 sendmail($email,$first_name, $last_name, $password);			                  sendsms($phone);
			 redirect(); 
			 exit;
			}else{
			  continue;
                        }
		break;

	    case "MARKETPULSE":

		$service_url = $getbrandrow['api_url'].'CreateAccount';
		$operatorName = $getbrandrow['name'];
		$affilate_name = $getbrandrow['username'];
		$operatorSecretKey = $getbrandrow['password'];
		$affilate_id =  $getbrandrow['campaign_id'];//echo $service_url;
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
		//print_r($jsonResponse);
	       
			if(!empty($jsonResponse->data->traderID)) {
			 $spotid = $jsonResponse->data->traderID;
			 $insert = mysql_query("INSERT INTO `users`(`id`, `name`, `email`, `password`, `country`, `phone`, `currency`, `brand`, `spotid`, `user_ip`, `param`) VALUES (NULL, '$name', '$email', '$password', '$country', '$phone', '$currency', '$brand', '$spotid', '$client_ip', '$param')")or die(mysql_error());
                         $_SESSION['userid'] = mysql_insert_id();
                         $_SESSION['brand'] =  $brand;
			 sendmail($email,$first_name, $last_name, $password);			                  sendsms($phone);
			 redirect(); 
			 exit;
			}else{
			  continue;
                        }

		break;
		
		case 'AIRSOFT':
                $service_url = $getbrandrow['api_url'].'?key=' . $getbrandrow['password'] .
                    '&method=createLead&first_name=' . $first_name .
                    '&last_name=' . $last_name . '&email_address=' . $email .
                    '&phone=' . $phone . '&countryISO=' . $countryiso .'&currency='.$currency .
                    '&campaign_id=' . $getbrandrow['campaign_id'] .
                    '&comment=http://quityourjobs.biz'.
					//'&makePassword='.$password. '&is_lead_only=0';
					'&is_lead_only=0';
                $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $service_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 0);
		$result = curl_exec($ch);
		$jsonResponse = json_decode($result, true);
                if (!empty($jsonResponse['createLead']['client_id'])) {
                    $spotid = $jsonResponse['createLead']['client_id'];
                    $insert = mysql_query("INSERT INTO `users`(`id`, `name`, `email`, `password`, `country`, `phone`, `currency`, `brand`, `spotid`, `user_ip`, `param`) VALUES (NULL, '$name', '$email', '$password', '$country', '$phone', '$currency', '$brand', '$spotid', '$client_ip', '$param')")or die(mysql_error());
                    $_SESSION['userid'] = mysql_insert_id();
                    $_SESSION['brand'] = $brand;
                    sendmail($email, $first_name, $last_name, $password);
                    sendsms($phone);
                    redirect();
                    exit;
                } else {
                    continue;
                }
                break;

	    default:

		// register with the broker
		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Customer&COMMAND=add&FirstName=$first_name&LastName=$last_name&email=$email&password=$password&Phone=$phone&Country=$country&currency=$currency&birthday=1970-01-01&campaignId=".$getbrandrow['campaign_id']."&isDemo=0";
	//echo $data;
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
		  redirect(); 
		  exit;
                  }else{
		     continue;
                  }

	}
    
 
  
 } // Count Zero END //
  if ($try == $totalBrands) {
     $message = ($blankCount == $totalBrands)
        ? 'Customer from your country are not allowed to register right now!'
        : 'Email already exits please use another email!';
     $_SESSION['registration_failure_message'] = $message;
     header('Location: f_register_b_new.php'.$url_param); 
  }
 // if(!empty($spotid)){ break; }

} // FOR LOOP ENDS //


} // POST REGISTER BUTTON FUNCTION END //


?>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-53264353-1', 'auto');
  ga('send', 'pageview');

</script>