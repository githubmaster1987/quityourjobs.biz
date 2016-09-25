<?php
require_once("GenericModel.php");
require_once("Brand/Manager.php");

/**
 * Class for balance related activities
 *
 */
class Balance extends GenericModel
{

    /**
     * Get customer account balance
     * 
     * @return int
     */
    public function getBalance()
    {
    	$getbrand = mysql_query("select * from brands where ref='" . $_SESSION['brand'] . "'");
        $getbrandrow = mysql_fetch_array($getbrand);
        $manager = new Manager();
        return $manager->setBrand($getbrandrow['api_type'])->getBalance();
    }

    /**
     * Update credit status of customer from DEMO to LIVE
     * 
     * @return boolean
     */
    public function updateCreditStatus()
    {
        $return = false;
        $updateQuery = "update users set deposit_status='LIVE' where id='" .
            $this->_user['id'] . "' AND brand='" . $this->_session['brand'] . "'";
        if(mysql_query($updateQuery)) {
            $return = true;
        }
        return $return;
    }
}