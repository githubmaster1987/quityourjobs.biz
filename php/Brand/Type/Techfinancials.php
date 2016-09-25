<?php
require_once("AbstractBrand.php");

/**
 * TECH FINANCIALS Brand
 *
 */
class Techfinancials extends AbstractBrand
{
    public function __construct() {
        parent::__construct();
    }

    public function getBalance()
    {
        try {
            $url = $this->_brand['api_url'] . "customer/findAccounts";

            $affiliateUserName = $this->_brand['username'];
            $affiliatePassword = $this->_brand['password'];

            $data = "affiliateUserName=$affiliateUserName&affiliatePassword=$affiliatePassword&accountID=" . $this->_user['spotid'];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $decoded = json_decode($result);

            $balanceStr = $decoded->result{0}->apiaccountview_balance;
            $balance = substr_replace($balanceStr, ".", -2, 0);
        } catch (Exception $ex) {
            $balance = 0;
        }
        return $balance;
    }
}
