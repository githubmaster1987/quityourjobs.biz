<?php

require_once("AbstractBrand.php");

/**
 * AIRSOFT Brand
 */
class Airsoft extends AbstractBrand {

    public $response = array();
    public $curlResponse = '';
    public $service_url = '';

    public function __construct() {
        parent::__construct();
    }

    public function getBalance() {
        try {
            $this->setClientInfoUrl()->request();
            $jsonResponse = $this->getResponse();
            $balance = (!empty($jsonResponse['clientInfo']['balance'])) ? $jsonResponse['clientInfo']['balance'] : 0;
        } catch (Exception $ex) {
            $balance = 0;
        }
        return $balance;
    }

    public function setClientInfoUrl() {
        $this->service_url = $this->_brand['api_url'] . '?key=' . $this->_brand['password'] .
                '&method=clientInfo&client_id=' . $this->_user['spotid'];
        return $this;
    }

    public function setPositionsUrl() {
        $this->service_url = $this->_brand['api_url'] . '?key=' . $this->_brand['password'] .
                '&method=positions&client_id=' . $this->_user['spotid'];
        return $this;
    }

    public function setAssetsDataAndTimesFull() {
        $this->service_url = $this->_brand['api_url'] . '?key=' . $this->_brand['password'] .
                '&method=assetsDataAndTimesFull';
        return $this;
    }

    public function setBinaryPositionUrl($amount, $type, $asset_id) {
        $aAssets = $this->setAssetsDataAndTimesFull()->request()->getResponse();
        $assetsId = $payoutId = $duration = '';
        foreach ($aAssets['assetsData'] as $assetVal) {
            if ($asset_id == $assetVal['id']) {
                $assetsId = $assetVal['id'];
                foreach ($assetVal['times'] as $time) {
                    if ($time['is_default'] == 1) {
                        $duration = $time['duration'];
                    }
                }
                $payoutId = $assetVal['payouts'][0]['payout_id'];
                break;
            }
        }
        // Hard coded for testing; Remove afterwards		
//        $this->_brand['api_url'] = preg_replace('/affiliate(.*)/', 'affiliate/', $this->_brand['api_url']).'apiBinary/openTrade';
//        $this->_brand['api_url'] = preg_replace('/affiliate(.*)/', 'affiliate/', $this->_brand['api_url']).'apiBinary/openTrade';
        $this->service_url = $this->_brand['api_url'] . '?key=' . $this->_brand['password'] .
                '&client_id=' . $this->_user['spotid'] . '&method=openBinaryTrade&investment=' . $amount .
                '&type=' . $type . '&asset_id=' . $assetsId . '&payout_id=' . $payoutId .
                '&duration=' . $duration . '&game_type=shortTerm';
        return $this;
    }

    public function setAssetsTimesUrl() {
        $this->service_url = $this->_brand['api_url'] . '?key=' . $this->_brand['password'] .
                '&method=assetsTimes&client_id=' . $this->_user['spotid'];
        return $this;
    }

    public function setAssetsUrl() {
        $this->service_url = $this->_brand['api_url'] . '?key=' . $this->_brand['password'] .
                '&method=assetsDataAndTimes&client_id=' . $this->_user['spotid'];
        return $this;
    }

    public function getResponse() {
        return $this->response;
    }

    public function setResponse($curlResponse) {
        $this->response = ($this->isJson($curlResponse)) ? json_decode($curlResponse, true) : array();
    }

    public function request() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->service_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 0);
        $curlResponse = curl_exec($ch);
        curl_close($ch);
        $this->setResponse($curlResponse);
        return $this;
    }

    public function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public function getOpenPositions($aPositions, $aClientInfo) {
        if (isset($aPositions['positions']) && !empty($aClientInfo['clientInfo']['open_positions'])
        ) {
            return $aClientInfo['clientInfo']['open_positions'];
        }
    }

    public function getClosedPositions($aPositions, $aClientInfo) {
        if (!empty($aPositions['positions']) && isset($aClientInfo['clientInfo']['open_positions'])
        ) {
            return $aPositions['positions'];
        }
    }

}
