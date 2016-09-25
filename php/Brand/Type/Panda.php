<?php
require_once("AbstractBrand.php");
/**
 * PANDA Brand
 *
 */
class Panda extends AbstractBrand
{
    public function __construct() {
        parent::__construct();
    }

    public function getBalance()
    {
        try {
            date_default_timezone_set('UTC');

            $PARTNER_ID = $this->_brand['username'];
            $PARTNER_SECRET_KEY = $this->_brand['password'];

            // Registration example
            $request = array(
                'login' => $this->_user['email'],
                'password' => $this->_user['password'],
                'source' => '1',
            );

            // register with the broker
            $url = $this->_brand['api_url'] . '/api/v1/Login?' .
                $this->createApiRequest($request, $PARTNER_ID, $PARTNER_SECRET_KEY);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 0);
            $result = curl_exec($ch);
            $decoded = json_decode($result);

            $balance = $decoded->data->balance;
            $token = $decoded->data->token;
        } catch (Exception $ex) {
            $balance = 0;
        }
        return $balance;
    }
    
    public function createApiRequest($requestArray = array(), $partnerId, $partnerSecretKey)
    {
        $requestArray['partnerId'] = $partnerId;
        $requestArray['time'] = time();

        $values = array_values($requestArray);
        sort($values, SORT_STRING);
        $string = join('', $values);
        $requestArray['transactionKey'] = sha1($string . $partnerSecretKey);
        return http_build_query($requestArray);
    }

}
