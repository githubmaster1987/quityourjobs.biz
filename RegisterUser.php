<?php
// -- Configuration Section --
	// These variables must be set with the URL of Tradologic B2B API and the affiliate's credentials
	$b2bApiUrl = 'https://b2b-api.tradologic.net/';
	$accountId = 570;
	$affiliateUsername = 'transcenduniversal';
	$authenticationKey = 'Password1';

	// These variables are used for configuration of the redirection to the brand's cashier page
	$redirectToCashier = false;                          // Indicates if a redirect to the brand's cashier page is performed after the new user is registered
	$brandUrl = 'https://www.speartrader.com/';    // The brand URL is needed only when $redirectToCashier = true
// -- End of Configuration Section --

$userRegistrationResult = sendUserRegistrationRequest($b2bApiUrl, $accountId, $affiliateUsername, $authenticationKey);
$isUserRegistered = $userRegistrationResult['isUserRegistered']; 
$message = $userRegistrationResult['message']; 
$displayForm = $isUserRegistered ? 'none' : 'inline';

if ($redirectToCashier && $isUserRegistered && $userRegistrationResult['sessionId']) {
	$redirectUrl = trim($brandUrl, '/') . '/cashier/?hash=' . $userRegistrationResult['sessionId'];
	header('Location: ' . $redirectUrl);
}

function sendUserRegistrationRequest($baseUrl, $accountId, $affiliateUsername, $authenticationKey)
{
    $hasErrors = false;
    $message = '';

    if ($_POST) {
        $email = trim($_POST['email']);
        $userPassword = trim($_POST['userPassword']);
        $userFirstName = trim($_POST['userFirstName']);
        $userLastName = trim($_POST['userLastName']);
        $phone = trim($_POST['phone']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $hasErrors = true;
            $message = $message . 'The email is invalid.<BR/>'; 
        }

        if (mb_strlen($userPassword, 'UTF-8') < 6) {
            $hasErrors = true;
            $message = $message . 'The password must be at least 6 symbols long.<BR/>';
        }
        
        if (empty($userFirstName)) {
            $hasErrors = true;
            $message = $message . 'The first name is empty.<BR/>';
        }
        
        if (empty($userLastName)) {
            $hasErrors = true;
            $message = $message . 'The last name is empty.<BR/>';
        }

        if (empty($phone)) {
            $hasErrors = true;
            $message = $message . 'The phone is empty.<BR/>';
        }

        if (!$hasErrors) {
            $dataParameters = [
                'email' => $email,
                'userPassword' => $userPassword,
                'userFirstName' => $userFirstName,
                'userLastName' => $userLastName,
                'phone' => $phone,                
                'affiliateUsername' => $affiliateUsername,
                'accountId' => $accountId
            ];

            $ipAddress = getClientIp();
            if ($ipAddress != 'UNKNOWN') {
                $dataParameters['userIpAddress'] = $ipAddress;
            }

            $json = sendPostRequest($baseUrl, "/v1/affiliate/users", $dataParameters, $authenticationKey);
            $hasErrors = $json -> {'messageType'} != "Registration_SuccessfulWithSession";
            $message = $json -> {'messageText'} . '<BR/>';
        }
    }
    else {
        $hasErrors = true;
    }
    
    $result = [
        'isUserRegistered' => !$hasErrors,
        'message' => $message
    ];
    
    if (!$hasErrors) {
        $result['sessionId'] = $json -> {'messageParameters'} -> {'sessionId'};
    }

    return $result;
}

function sendPostRequest($baseUrl, $resourceRoute, $dataParameters, $authenticationKey)
{
    $checksum = getChecksum($dataParameters, $authenticationKey);
    $dataParameters['checksum'] = $checksum;
    $postData = json_encode($dataParameters);

    $url = trim($baseUrl, '/') . '/' . trim($resourceRoute, '/');

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);

    // Override the default headers
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    // 0 do not include header in output, 1 include header in output
    curl_setopt($curl, CURLOPT_HEADER, 0);

    // Set the post variables
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);

    // Set so curl_exec returns the result instead of outputting it.
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Get the response and close the channel.
    $response = curl_exec($curl);
    curl_close($curl);

    $jsonResponse = json_decode($response);
    return $jsonResponse;
}

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

function getClientIp() {
    $ipAddress = '';
    if ($_SERVER['HTTP_CLIENT_IP']) {
        $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
    }
    else if($_SERVER['HTTP_X_FORWARDED_FOR']) {
        $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else if($_SERVER['HTTP_X_FORWARDED']) {
        $ipAddress = $_SERVER['HTTP_X_FORWARDED'];
    }
    else if($_SERVER['HTTP_FORWARDED_FOR']) {
        $ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
    }
    else if($_SERVER['HTTP_FORWARDED']) {
        $ipAddress = $_SERVER['HTTP_FORWARDED'];
    }
    else if($_SERVER['REMOTE_ADDR']) {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
    }
    else {
        $ipAddress = 'UNKNOWN';
    }

    return $ipAddress;
}

$ip= getClientIp();
echo $ip;
 $my_ip = $_SERVER['REMOTE_ADDR'];
echo $my_ip;
?>

<!DOCTYPE html>
<html>
    <head>
        <title>User Registration</title>
        <meta charset="UTF-8">
    </head>
    <body>
        <div><?=$message?></div>
        <form method="POST" style="display:<?=$displayForm?>">
            <label for="email">Email:</label><input type="text" name="email" id="email" /><br/>
            <label for="userPassword">Password:</label><input type="password" name="userPassword" id="userPassword" /><br/>
            <label for="userFirstName">First name:</label><input type="text" name="userFirstName" id="userFirstName" /><br/>
            <label for="userLastName">Last name:</label><input type="text" name="userLastName" id="userLastName" /><br/>
            <label for="phone">Phone:</label><input type="text" name="phone" id="phone" /><br/>
            <input type="submit" value="Register" />
        </form>
    </body>
</html>
