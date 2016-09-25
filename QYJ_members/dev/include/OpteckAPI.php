<?php

class OpteckAPI {

    public $affiliateID;
    public $partnerID;
    public $baseURL = 'https://api.optaffiliates.com/v1';
    public $url;

    /**
     * 
     * @param int $affiliateID
     * @param string $partnerID
     */
    public function __construct($affiliateID, $partnerID) {
        $this->affiliateID = $affiliateID;
        $this->partnerID = $partnerID;
    }

    /**
     * 
     * @param string $method
     * @param array $params
     */
    public function request($method = '', array $params = array()) {
        if (method_exists($this, $method)) {
            $this->$method();
        } else {
            return 'Method "' . $method . '" doesn\'t exist';
        }
        return $this->send($params);
    }
    
    /**
     * Set URL for auth method
     */
    public function auth() {
        $this->url = $this->baseURL.'/trade/auth';
    }
    
    /**
     * Set URL for Creat Lead method
     */
    public function createLead() {
        $this->url = $this->baseURL.'/lead/create';
    }

    /**
     * Set URL for Get Comments method
     */
    public function getComments() {
        $this->url = $this->baseURL.'/lead/getComments';
    }

    /**
     * Set URL for Get Comments method
     */
    public function getConversions() {
        $this->url = $this->baseURL.'/lead/getConversions';
    }

    /**
     * Set URL for Get Comments method
     */
    public function getRegistrations() {
        $this->url = $this->baseURL.'/lead/getRegistrations';
    }

    /**
     * Set URL for Get Comments method
     */
    public function getDeposits() {
        $this->url = $this->baseURL.'/lead/getDeposits';
    }

    /**
     * Set URL for getOptionTypes method
     */
    public function getOptionTypes() {
        $this->url = $this->baseURL.'/trade/getOptionTypes';
    }
    
    /**
     * Set URL for getMarkets method
     */
    public function getMarkets() {
        $this->url = $this->baseURL.'/trade/getMarkets';
    }
    
    /**
     * Set URL for getAssets method
     */
    public function getAssets() {
        $this->url = $this->baseURL.'/trade/getAssets';
    }
    
    /**
     * Set URL for getDefinitions method
     */
    public function getDefinitions() {
        $this->url = $this->baseURL.'/trade/getDefinitions';
    }
    
    /**
     * Set URL for getRate method
     */
    public function getRate() {
        $this->url = $this->baseURL.'/trade/getRate';
    }
    
    /**
     * Set URL for action method
     */
    public function action() {
        $this->url = $this->baseURL.'/trade/action';
    }
    
    /**
     * Set URL for instances method
     */
    public function instances() {
        $this->url = $this->baseURL.'/trade/instances';
    }
    
    /**
     * Set URL for getLeadDetails method
     */
    public function getLeadDetails() {
        $this->url = $this->baseURL.'/trade/getLeadDetails';
    }

    public function generateChecksum($data)
    {
        return strtoupper(
            md5(
                $this->partnerID . http_build_query($data)
            )
        );
    }
    
    /**
     * Function for send request
     * @param array $data
     * @return string
     */
    public function send($data) {
        $data['affiliateID'] = $this->affiliateID;
        $data['checksum'] = $this->generateChecksum($data);
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return curl_exec($ch);
    }

}
