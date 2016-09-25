<?php
require_once("AbstractBrand.php");

/**
 * SPOT-RALLY Brand
 *
 */
class Spotrally extends AbstractBrand
{
    public function __construct() {
        parent::__construct();
    }

    public function getBalance()
    {
        try {
            $headers = array(
                'Content-Type' => 'application/x-www-form-urlencoded',
            );

            $getcbrand=mysql_query("SELECT * FROM `country` WHERE `iso`='GB'");
            $getcbrandrow=mysql_fetch_array($getcbrand);
            
            $url = ($getcbrandrow['reg'] == '1')
                ? $this->_brand['api_url'] . '/?integration=bdb_eu'
                : $this->_brand['api_url'] . '/?integration=bdb_com';
            
            $data = "api_username=" . $this->_brand['username'] . "&api_password=" .
                $this->_brand['password'] . "&MODULE=Customer&COMMAND=view&FILTER[id]=" . $this->_user['spotid'];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
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
