<?php
require_once("AbstractBrand.php");
/**
 * TRADOLOGIC Brand
 *
 */
class Tradologic extends AbstractBrand
{
    public function __construct() {
        parent::__construct();
    }

    public function getBalance()
    {
        try{
            $b2bApiUrl = $this->_brand['api_url'];
            $accountId = $this->_brand['campaign_id'];
            $affiliateUsername = $this->_brand['username'];
            $authenticationKey = $this->_brand['password'];
            $my_ip = $_SERVER['REMOTE_ADDR'];
            $userId = $this->_user['spotid'];

            $dataParameters = [
                'userId' => $userId,
                'affiliateUsername' => $affiliateUsername,
                'accountId' => $accountId
            ];

            $checksum = $this->getChecksum($dataParameters, $authenticationKey);

            $dataParameters['checksum'] = $checksum;
            $postData = json_encode($dataParameters);

            $url = $b2bApiUrl . 'v1/affiliate/users/' . $userId . '?accountId=' . $accountId . '&affiliateUsername=' . $affiliateUsername . '&checksum=' . $checksum;


            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);

            $jsonResponse = json_decode($response);
            $balance = $jsonResponse->data->balance;
        } catch (Exception $ex) {
            $balance = 0;
        }
        return $balance;
    }

    public function getChecksum($parameters, $authenticationKey)
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

}
