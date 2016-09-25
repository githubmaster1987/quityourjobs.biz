<?php
require_once("AbstractBrand.php");

/**
 * Other brands
 *
 */
class Others extends AbstractBrand
{
    public function __construct() {
        parent::__construct();
    }

    public function getBalance()
    {
        try {
            $data = "api_username=" . $this->_brand['username'] . "&api_password=" .
                $this->_brand['password'] . "&MODULE=Customer&COMMAND=view&FILTER[id]=" . $this->_user['spotid'];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->_brand['api_url']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $xml = simplexml_load_string($result);
            $balance = $xml->Customer->data_0->accountBalance;
        } catch (Exception $ex) {
            $balance = 0;
        }
        return $balance;
    }
}