<?php
require_once(__DIR__ . "/../../../variables.php");
/**
 * Abstract Class  Brands
 *
 */
class AbstractBrand
{
    protected $_session;
    protected $_user;
    protected $_brand;

    public function __construct()
    {
        session_start();
        $this->setSession()->setUser()->setBrand();
    }

    private function setSession()
    {
        $this->_session = $_SESSION;
        return $this;
    }

    private function setUser()
    {
        $getuser = mysql_query("select * from users where `id`='" . $this->_session['userid'] . "'");
        $this->_user = mysql_fetch_array($getuser);
        return $this;
    }

    private function setBrand()
    {
        $sqlQuery = mysql_query("select * from brands where ref='" . $this->_session['brand'] . "'");
        $this->_brand = mysql_fetch_array($sqlQuery);
    }
}
