<?php
require_once("AbstractBrand.php");
/**
 * OPTECK Brand
 *
 */
class Opteck extends AbstractBrand
{
    public function __construct() {
        parent::__construct();
    }

    public function getBalance()
    {
        try{
            require_once('include/OpteckAPI.php');
            $affiliateID = $this->_brand['username'];
            $partnerID = $this->_brand['password'];

            $affiliate = new OpteckAPI($affiliateID, $partnerID);

            $getLeadDetails = $affiliate->request('getLeadDetails', array('email' => $this->_user['email']));
            $decoded = json_decode($getLeadDetails);

            $balanceStr = $decoded->data->balance;
            $balance = substr_replace($balanceStr, ".", -2, 0);
        } catch (Exception $ex) {
            $balance = 0;
        }
        return $balance;
    }
}
