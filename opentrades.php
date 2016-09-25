<?php 
error_reporting(1);
session_start();

$_SESSION['is_demo'] = false;


if(isset($_SESSION['userid'])){

	require_once("variables.php");
		
	$getuser = mysql_query("select * from users where `id`='".$_SESSION['userid']."'");
        $getuserrow=mysql_fetch_array($getuser);
	
	$spotid = $getuserrow['spotid'];
        $email=$getuserrow['email'];
        $password=$getuserrow['password'];

      // echo $spotid;
	
	$getbrand =mysql_query("select * from brands where ref='".$_SESSION['brand']."'");
        $getbrandrow=mysql_fetch_array($getbrand);
       // echo $getbrandrow['ref'];
	$title='Quit Your Jobs Open Trade at '.$getbrandrow['name'];
	
        $getcbrand=mysql_query("SELECT * FROM `country` WHERE `iso`='GB'") or die(mysql_error());
	$getcbrandrow=mysql_fetch_array($getcbrand);

require_once('include/header.php'); ?>

<!-- BEGIN PAGE -->  
<style>
.collection{
 list-style-type: square;
 font-size: 13px;
}

.collection-item{
 margin-bottom: 7px;
}

.collection-item .badge{
float:right;
}

.howtotrade{
 text-align: center;
 text-transform: uppercase;
}

.howtotrade h3{
    font-weight: bold;
    color: #FFFFFF;
    letter-spacing: 2px;
}

.hometopwidget{
border-bottom: 2px solid #fff;
}

.hometopwidgetbody{
 background: #DE577B;
 color: #fff;
}

.alert-homes{
 background: #4090db;
 color: #fff;
}

.table-advance thead tr th {
    background-color: #00bcd4;
    color: #FFF;
    line-height: 25px;
    text-align: center;
}

.table-advance tr td {
    border-left-width: 0px;
    vertical-align: middle;
    text-align: center;
}

.widget-title {
    background: #4A8BC2;
    height: 39px;
}

.widget-body a, .widget-body a:hover {
    text-shadow: none !important;
    color: #4A8BC2;
}

</style>

<div id="main-content" style="margin-left: 0px; margin-bottom: 0px;">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
           <div class="row-fluid">
              <div class="row bg-title bredcrums-menu">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Open Trades</h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="index.php">Home</a></li>
                        <li class="active breadcrumb-active">Open Trades</li>
                    </ol>
                </div>
                <!-- /.col-lg-12 -->
            </div>
           </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
		<div class="row-fluid">
		<!--start container-->
                    <div class="span12" style="min-height:432px;">
                        <!-- BEGIN BASIC PORTLET-->
                        <div class="col-lg-12 col-md-12 col-sm-12">
                           
                            <div class="widget-body" style="overflow-x:auto;">
                                <table class="table table-striped table-bordered table-advance table-hover">
                                    <thead>
                                    <tr>
                                        <th>Asset</th>
                                        <th class="hidden-phone">Option</th>
                                        <th>Entry price</th>
                                        <th>Entry time</th>
                                        <th>Exit time</th>
                                        <th>Amount</th>
                                        <th>%ROI</th>
                                    </tr>
                                    </thead>
                                    <tbody>
<?php

      
	switch ($getbrandrow['api_type']) {

          case "TRADOLOGIC":
                
		$b2bApiUrl = $getbrandrow['api_url'];
		$accountId = $getbrandrow['campaign_id'];
		$affiliateUsername = $getbrandrow['username'];
		$authenticationKey = $getbrandrow['password'];
		$my_ip = $_SERVER['REMOTE_ADDR'];
		$userId=$spotid;

		function getChecksum($parameters, $authenticationKey)
		{
		    ksort($parameters);

		    $dataString = '';
		    foreach ($parameters as $value) {
			$dataString = $dataString . $value;
		    }    
		    
		    $dataString = $dataString . $authenticationKey;
		    $checksum = hash('sha256', $dataString);
		    return $checksum;
		}


	    $dataParameters = [
		'userId' => $userId,              
		'affiliateUsername' => $affiliateUsername,
		'accountId' => $accountId
	    ];

	   $checksum=getChecksum($dataParameters, $authenticationKey);

	   $dataParameters['checksum'] = $checksum;
	   $postData = json_encode($dataParameters);

	    $url=$b2bApiUrl.'v1/affiliate/users/'.$userId.'/trades/regular?accountId='.$accountId.'&affiliateUsername='.$affiliateUsername.'&checksum='.$checksum;




	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    $response = curl_exec($curl);
	    curl_close($curl);

	    $jsonResponse = json_decode($response);
	    //print_r($jsonResponse);

		foreach($jsonResponse->data as $t) {                       
                     if( $t->tradeState=='TradeActive'){
 			$totalwopen++;
	        ?>

            <tr>
                <td><a href="#"><img src="http://quityourjobs.biz/members/images/assetsLogos/<?php $assetName=$t->tradableAsset; $getassetimage=mysql_query("SELECT * FROM `assets` WHERE `name`='$assetName'")or die(mysql_error()); $getassetimagerow=mysql_fetch_array($getassetimage); echo $getassetimagerow['id'];; ?>.png">&nbsp;&nbsp;<?php echo $t->tradableAsset; ?></a></td>                
                <td> <?php echo strtoupper($t->action); ?> </td>
                <td class="hidden-phone"><?php echo $t->rate; ?></td>
                <td><?php echo $t->activeAt; ?></td>
                <td><?php echo $t->expireAt; ?></td>
                <td><?php echo $t->volume; ?> </td>
                <td><?php echo $t->payout.'% / 100%'; ?></td>
            </tr>

           <?php
			
		$open=$totalwopen-$total;
	         
		} }

	  break;

          case "OPTECK":
                
                require_once('include/OpteckAPI.php');
                $affiliateID = $getbrandrow['username'];
                $partnerID = $getbrandrow['password'];

                $affiliate = new OpteckAPI($affiliateID, $partnerID);

		$result = $affiliate->request('instances', array('email' => $email));

		$decoded = json_decode($result);

		//print_R($decoded);

                $totalwopen = 0;

		foreach($decoded->data as $t) {
                        $totalwopen++;
                if($t->status=='1'){
	        ?>

            <tr>
                <td><a href="#"><img src="http://quityourjobs.biz/members/images/assetsLogos/<?php $assetID=$t->assetID; $getassetimage=mysql_query("SELECT * FROM `assets` WHERE `optech_id`='$assetID'")or die(mysql_error()); $getassetimagerow=mysql_fetch_array($getassetimage); echo $getassetimagerow['id']; ?>.png">&nbsp;&nbsp;<?php echo $getassetimagerow['name']; ?></a></td>                
                <td> <?php if($t->direction=='1'){ echo 'CALL'; }elseif($t->direction=='-1'){ echo 'PUT'; } ?> </td>
                <td class="hidden-phone"><?php echo $t->strike; ?></td>
                <td><?php echo $t->createdDate; ?></td>
                <td><?php echo $t->expirationDate; ?></td>
                <td><?php echo intval($t->amount); ?>&nbsp;<?php echo $t->currency; ?> </td>
                <td>N/A</td>
            </tr>

           <?php
			
		$open=$totalwopen-$total;
	
		}}

	  break;

          case "TECH FINANCIALS":
                
		$_SESSION['isDemo'] = 0;
		$currency = $decoded->result{0}->apiaccountview_currencyISO;
		
		$url = $getbrandrow['api_url']."trading/getTrades";
							
		$affiliateUserName = $getbrandrow['username'];
		$affiliatePassword = $getbrandrow['password'];

		$now = gmdate("Y-m-d H:i:s");
		$data = "affiliateUserName=$affiliateUserName&affiliatePassword=$affiliatePassword&accountId=$spotid&fromDate=2015-06-01 00:00:00&toDate=$now&status=18";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
         
		$result=curl_exec ($ch);
		
		

		$decoded = json_decode($result);
                $totalwopen = 0;

		foreach($decoded->result as $t) {
                        $totalwopen++;

	        ?>

            <tr>
                <td><a href="#"><img src="http://quityourjobs.biz/members/images/assetsLogos/<?php $assetName=$t->tradinghistory_assetName; $getassetimage=mysql_query("SELECT * FROM `assets` WHERE `name`='$assetName'")or die(mysql_error()); $getassetimagerow=mysql_fetch_array($getassetimage); echo $getassetimagerow['id'];; ?>.png">&nbsp;&nbsp;<?php echo $t->tradinghistory_assetName; ?></a></td>                
                <td> <?php if($t->tradinghistory_positionType=='49'){ echo 'CALL'; }elseif($t->tradinghistory_positionType=='50'){ echo 'PUT'; } ?> </td>
                <td class="hidden-phone"><?php echo $t->tradinghistory_strike; ?></td>
                <td><?php echo $t->tradinghistory_orderTime; ?></td>
                <td><?php echo $t->tradinghistory_closeTime; ?></td>
                <td><?php echo intval($t->tradinghistory_amount)/100; ?>&nbsp;<?php echo $t->tradinghistory_currencyISO; ?> </td>
                <td><?php echo ($t->tradinghistory_returnOnSuccess-100).'% / 100%'; ?></td>
            </tr>

           <?php
			
		$open=$totalwopen-$total;
	
		}

	  break;
	
          case "SPOT":
            	
		$_SESSION['isDemo'] = 0;
		$currency = $xml->Customer->data_0->currency;
		
		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result=curl_exec ($ch);
              
		$xml = simplexml_load_string($result);
		
		//print_r($result);
		
		$payout = 0;
		$won = 0;
		$lost = 0;
		$total = 0;
		$today_p = 0;

		foreach($xml->Positions->children() as $p) {
			
			if($p->status == "open") {				
                                $open++;
?>
            <tr>
                <td><a href="#"><img src="http://quityourjobs.biz/members/images/assetsLogos/<?php echo $p->assetId; ?>.png">&nbsp;&nbsp;<?php echo $p->name; ?></a></td>                
                <td> <?php echo strtoupper($p->position); ?> </td>
                <td class="hidden-phone"><?php echo $p->entryRate; ?></td>
                <td><?php echo $p->date; ?></td>
                <td><?php echo $p->endDate; ?></td>
                <td><?php echo intval($p->amount); ?>&nbsp;<?php echo $p->currency; ?> </td>
                <td><?php echo $p->profit.'% / '.$p->loss.'%'; ?></td>
            </tr>

<?php
			}

			$total++;
		}

	break;

          case "SPOT-USA":
            	
		$_SESSION['isDemo'] = 0;
		$currency = $xml->Customer->data_0->currency;
		
		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Position&COMMAND=view&FILTER[customerId]=$spotid";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result=curl_exec ($ch);
              
		$xml = simplexml_load_string($result);
		
		//print_r($xml);
		
		$payout = 0;
		$won = 0;
		$lost = 0;
		$total = 0;
		$today_p = 0;

		foreach($xml->Position->children() as $p) {
			
						
                                $open++;
?>
            <tr>
                <td><a href="#"><img src="http://quityourjobs.biz/members/images/assetsLogos/<?php $assetName=$p->assetId; $getassetimage=mysql_query("SELECT * FROM `assets` WHERE `alt_name`='$assetName'")or die(mysql_error()); $getassetimagerow=mysql_fetch_array($getassetimage); echo $getassetimagerow['id'];; ?>.png">&nbsp;&nbsp;<?php echo $getassetimagerow['name']; ?></a></td>    
                <td> <?php if($p->type =='DigitalCall'){ echo 'CALL'; }elseif($p->type =='DigitalPut'){ echo 'PUT'; }; ?> </td>
                <td class="hidden-phone"><?php echo $p->openPrice; ?></td>
                <td><?php echo $p->openTime; ?></td>
                <td><?php echo $p->expireTime; ?></td>
                <td><?php echo intval($p->amount/100); ?>&nbsp;<?php echo $p->currency; ?> </td>
                <td><?php echo (($p->winReturnFactor)-100).'% / 100%'; ?></td>
            </tr>

<?php
			

			$total++;
		}

	  break;

          case "SPOT-BDB":
            	
		$_SESSION['isDemo'] = 0;

		$headers = array(
		'Content-Type' => 'application/x-www-form-urlencoded',
		);		

	        $url=$getbrandrow['api_url'];

	        if($getcbrandrow['reg']=='1'){ 
	          $url=$getbrandrow['api_url'].'/?integration=bdb_eu';
	        }else{
	          $url=$getbrandrow['api_url'].'/?integration=bdb_com';
	        }
		
		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result=curl_exec ($ch);
              
		$xml = simplexml_load_string($result);
		
		//print_r($xml);
		
		$payout = 0;
		$won = 0;
		$lost = 0;
		$total = 0;
		$today_p = 0;

		foreach($xml->Positions->children() as $p) {
			
			if($p->status == "open") {				
                                $open++;
?>
            <tr>
                <td><a href="#"><img src="http://quityourjobs.biz/members/images/assetsLogos/<?php echo $p->assetId; ?>.png">&nbsp;&nbsp;<?php echo $p->name; ?></a></td>                
                <td> <?php echo strtoupper($p->position); ?> </td>
                <td class="hidden-phone"><?php echo $p->entryRate; ?></td>
                <td><?php echo $p->date; ?></td>
                <td><?php echo $p->endDate; ?></td>
                <td><?php echo intval($p->amount); ?>&nbsp;<?php echo $p->currency; ?> </td>
                <td><?php echo $p->profit.'% / '.$p->loss.'%'; ?></td>
            </tr>

<?php
			}

			$total++;
		}

	break;

          case "SPOT-KEY":
            	
		$_SESSION['isDemo'] = 0;
		$currency = $xml->Customer->data_0->currency;
		
		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result=curl_exec ($ch);
              
		$xml = simplexml_load_string($result);
		
		//print_r($xml);
		
		$payout = 0;
		$won = 0;
		$lost = 0;
		$total = 0;
		$today_p = 0;

		foreach($xml->Positions->children() as $p) {
			
			if($p->status == "open") {				
                                $open++;
?>
            <tr>
                <td><a href="#"><img src="http://quityourjobs.biz/members/images/assetsLogos/<?php echo $p->assetId; ?>.png">&nbsp;&nbsp;<?php echo $p->name; ?></a></td>                
                <td> <?php echo strtoupper($p->position); ?> </td>
                <td class="hidden-phone"><?php echo $p->entryRate; ?></td>
                <td><?php echo $p->date; ?></td>
                <td><?php echo $p->endDate; ?></td>
                <td><?php echo intval($p->amount); ?>&nbsp;<?php echo $p->currency; ?> </td>
                <td><?php echo $p->profit.'% / '.$p->loss.'%'; ?></td>
            </tr>

<?php
			}

			$total++;
		}

	break;

          case "SPOT-RALLY":
            	
		$_SESSION['isDemo'] = 0;
		$currency = $xml->Customer->data_0->currency;
		
		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $getbrandrow['api_url']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result=curl_exec ($ch);
              
		$xml = simplexml_load_string($result);
		
		//print_r($xml);
		
		$payout = 0;
		$won = 0;
		$lost = 0;
		$total = 0;
		$today_p = 0;

		foreach($xml->Positions->children() as $p) {
			
			if($p->status == "open") {				
                                $open++;
?>
            <tr>
                <td><a href="#"><img src="http://quityourjobs.biz/members/images/assetsLogos/<?php echo $p->assetId; ?>.png">&nbsp;&nbsp;<?php echo $p->name; ?></a></td>                
                <td> <?php echo strtoupper($p->position); ?> </td>
                <td class="hidden-phone"><?php echo $p->entryRate; ?></td>
                <td><?php echo $p->date; ?></td>
                <td><?php echo $p->endDate; ?></td>
                <td><?php echo intval($p->amount); ?>&nbsp;<?php echo $p->currency; ?> </td>
                <td><?php echo $p->profit.'% / '.$p->loss.'%'; ?></td>
            </tr>

<?php
			}

			$total++;
		}

	 break;

          case "PANDA":
            	
		date_default_timezone_set('UTC');

		function createApiRequest ($requestArray=array(), $partnerId, $partnerSecretKey) {
			$requestArray['partnerId'] = $partnerId;
			$requestArray['time'] = time();

			$values = array_values($requestArray);
			sort($values, SORT_STRING);
			$string = join('', $values);
			$requestArray['transactionKey'] = sha1($string . $partnerSecretKey);
			return http_build_query($requestArray);
		}

		$PARTNER_ID = $getbrandrow['username'];
		$PARTNER_SECRET_KEY = $getbrandrow['password'];

		// Registration example
		$request = array(
			'login' => $email,
			'password' =>  $password,
			'source' => '1',
		);


		// register with the broker

		$url = $getbrandrow['api_url'].'/api/v1/Login?' . createApiRequest($request, $PARTNER_ID, $PARTNER_SECRET_KEY);    
                //echo $url; 

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 0);
		$result=curl_exec ($ch);
		$decoded = json_decode($result);
		//print_r($decoded);      
		
		$token=$decoded->data->token;


		// register with the broker

		$url = $getbrandrow['api_url'].'/api/v1/ActiveTrades?token='.$token;    

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 0);
		$result=curl_exec ($ch);
		$decoded = json_decode($result);
		//print_r($decoded);  


		$payout = 0;
		$won = 0;
		$lost = 0;
		$total = 0;
		$today_p = 0;

		foreach($decoded->data as $p) {			
		
?>
            <tr>
                <td><a href="#"><img src="http://quityourjobs.biz/members/images/assetsLogos/<?php $assetName=$p->symbol; $getassetimage=mysql_query("SELECT * FROM `assets` WHERE `alt_name`='$assetName'")or die(mysql_error()); $getassetimagerow=mysql_fetch_array($getassetimage); echo $getassetimagerow['id']; ?>.png">&nbsp;&nbsp;<?php echo $p->outputName; ?></a></td>                
                <td> <?php if($p->action =='1'){ echo 'CALL'; }elseif($p->action =='0'){ echo 'PUT'; }; ?> </td>
                <td class="hidden-phone"><?php echo $p->targetPrice; ?></td>
                <td><?php echo gmdate("Y-m-d H:i:s", $p->creationDate); ?></td>
                <td><?php echo gmdate("Y-m-d H:i:s", $p->endTime); ?></td>
                <td><?php echo intval($p->amount); ?>&nbsp;USD </td>
                <td><?php echo $p->payoutIfWin.'% / 100%'; ?></td>
            </tr>

<?php
			

			$total++;
		}

	break;

        case 'AIRSOFT':
            require_once("php/Brand/Type/Airsoft.php");
            $airsoft = new Airsoft();
            $aClientInfo = $airsoft->setClientInfoUrl()->request()->getResponse();
            $aPositions = $airsoft->setPositionsUrl()->request()->getResponse();
            $aOpenPositions = $airsoft->getOpenPositions($aPositions, $aClientInfo);
            if (!empty($aOpenPositions)) :
                                                            ?>
                <?php foreach ($aOpenPositions as $data): ?>
                    <?php
                    $getassetimage = mysql_query("SELECT * FROM `assets` WHERE `alt_name`='" . $data['asset_id'] . "'");
                    $getassetimagerow = mysql_fetch_array($getassetimage);
                    ?>
                                                    <tr>
                                                        <td>
                                                    <?php if ($getassetimagerow['id']) { ?>
                                                                <a href="#"><img src="http://quityourjobs.biz/members/images/assetsLogos/<?php echo $getassetimagerow['id']; ?>.png">
                                                    <?php } ?>
                                                                &nbsp;&nbsp;<?php echo $data['asset_label']; ?>
                                                            </a>
                                                        </td>
                                                        <td><?php echo $data['type']; ?></td>
                                                        <td class="hidden-phone"><?php echo $data['open_price'] ?></td>
                                                        <td><?php echo $data['open_date']; ?></td>
                                                        <td><?php echo $data['dateCloture']; ?></td>
                                                        <td><?php echo intval($data['investment']); ?>&nbsp;USD </td>
                                                        <td><?php echo $data['payoutWinPercent'] . '% / 100%'; ?></td>
                                                    </tr>
                <?php endforeach;
            else:
                ?>
                                                <tr>
                                                    <td colspan="10">
                                                        No Records Found!!!
                                                    </td>
                                                </tr>
            <?php
            endif;
            break;

        default:
           echo "Test okey !";

	}
	


?>
                                  


                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- END BASIC PORTLET-->
                    </div>
		

		</div>
		<!--end container-->
             <!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>	
	
      <!-- END PAGE -->  
<?php require_once('include/footer.php'); ?>
<?php
}
else {
	echo  "<meta http-equiv=\"Refresh\"content=\"0; URL=login.php\">";
 	exit;
}
?>

