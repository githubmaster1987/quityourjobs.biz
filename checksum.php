<?php


//$service_url = 'http://platformapi.huge-options.com/Integration.svc/CreateAccount';
$service_url = 'http://platformapi.huge-options.com/Integration.svc/GetAccountBalance';
$operatorName = "HugeOptions";
$affilate_name = 'ABS';
$operatorSecretKey = "ABSSecret";

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
	'affiliateID' => '12',
	'username' => 'zyxjha',
	'email' => 'zyxjha@outlook.com',
	'firstName' => 'Saurabh',
	'lastName' => 'Jha',
	'currency' => 'USD',
	'country' =>  'UK',
	'phone' => '7870525212',
	'password' => $pwd,
	'languageID' => 1033,
	"RegistrationIP" => $ip
		
);

$data_string=json_encode($curl_post_data); 


print_r($data_string);

$curl = curl_init($service_url); 
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);                                                                   
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);                                                                  
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($curl, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));
$curl_response = curl_exec($curl);
print_r($curl_response);
curl_close($curl);


/*
$service_url = 'http://platformapi.huge-options.com/Integration.svc/CreateAccount';
$operatorName = "HugeOptions";
$affilate_name = 'ABS';
$operatorSecretKey = "ABSSecret";
$affilate_id =  '12'
//$username = 'zyxjhaa';
//echo $service_url;
//list($username, $domain) = explode("@", $email);
//$username = substr($username, 0, 15);



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
	'username' => 'zyxjhaa'	
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
print_r($result);

*/


?>
