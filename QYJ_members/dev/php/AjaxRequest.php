<?php
require_once("Balance.php");


$balance = new Balance();
echo '<pre>';print_r($_SESSION);
$b = $balance->getBalance();
echo $b;exit;

// Validate an ajax request
if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])
    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
) {
    $checkType = $_GET['check'];
    switch ($checkType) {
        case 'depositstatus':
            $status = false;
            $balance = new Balance();
            if ($balance->getBalance()) {
                $status = $balance->updateCreditStatus();
            }
            $response = array('status' => $status);
            echo json_encode($response);exit;
            break;
        default:
            $response = array('status' => 'invaild_request');
            echo json_encode($response);exit;
            break;
    }
}