<?php
require_once("AbstractBrand.php");
/**
 * Marketpulse Brand
 *
 */
class Marketpulse extends AbstractBrand
{
    public function __construct() {
        parent::__construct();
    }

    public function getBalance()
    {
        try{
            $service_url = $this->_brand['api_url'] . 'GetAccountBalance';
            $operatorName = $this->_brand['name'];
            $affilate_name = $this->_brand['username'];
            $operatorSecretKey = $this->_brand['password'];
            $affilate_id = $this->_brand['campaign_id'];

            //echo $service_url;
            list($username, $domain) = explode("@", $this->_user['email']);
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
                'username' => $username
            );

            $data_string = json_encode($curl_post_data);
            $curl = curl_init($service_url);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));
            $result = curl_exec($curl);
            curl_close($curl);

            $jsonResponse = json_decode($result);
            $balance = $jsonResponse->data->Balance;
        } catch (Exception $ex) {
            $balance = 0;
        }
        return $balance;
    }
}
