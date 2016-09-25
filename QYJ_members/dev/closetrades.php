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
	$title='Self-made Millionaire System Close Trade at '.$getbrandrow['name'];
	
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

.label-mini {
    font-size: 12px;
    text-transform:uppercase;
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
                    <h4 class="page-title">Close Trades</h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="index.php">Home</a></li>
                        <li class="active breadcrumb-active">Close Trades</li>
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
                        <div class="">
                           
                            <div class="widget-body" style="overflow-x:auto;">
                                <table class="table table-striped table-bordered table-advance table-hover">
                                    <thead>
                                    <tr>
                                        <th>Asset</th>
                                        <th class="hidden-phone">Option</th>
                                        <th>Entry price</th>
                                        <th>Entry time</th>
                                        <th>Exit price</th>
                                        <th>Exit time</th>
                                        <th>Amount</th>
                                        <th>%ROI</th>
                                        <th>Result</th>
                                    </tr>
                                    </thead>
                                    <tbody>
<?php

      
	switch ($getbrandrow['api_type']) {
	 
	   case "MARKETPULSE":

		$service_url = $getbrandrow['api_url'].'GetReport';
		$operatorName = $getbrandrow['name'];
		$affilate_name = $getbrandrow['username'];
		$operatorSecretKey = $getbrandrow['password'];
		$affilate_id =  $getbrandrow['campaign_id'];

		//echo $service_url;
                list($username, $domain) = explode("@", $email);
                $username = substr($username, 0, 15);

		date_default_timezone_set("UTC");
		$objDateTime = date('Y-m-d\TH:i:s\Z');
		$unsignedText = $affilate_name . $objDateTime . $operatorSecretKey;
		$utf_string = utf8_encode($unsignedText);
		$hash_string = hash_hmac("sha256", $utf_string, $operatorSecretKey, true);
		$hash_string_64 = base64_encode($hash_string);
		$urlEncode = urlencode($hash_string_64);

		$filters = array(
			'filterColumn' => 'OperatorID',
                        'filterOperator'=>"",
                        'filterValue'=>'1'
		);

		$curl_post_data = array(
			'operatorName' => $operatorName,
			'affiliateName' => $affilate_name,
			'timestamp' => $objDateTime,
			'signature' => $urlEncode,
			'affiliateID' => $affilate_id,
			'username' =>$username,
                        'reportName'=>'traderTradeActions',
                        'filters'=>$filters
		);

		$data_string=json_encode($curl_post_data); 
                //echo $data_string;

		$data_string='{"operatorName":"HugeOptions","timestamp":"'.$objDateTime.'","signature":"'.$urlEncode.'","affiliateName":"ABS","reportName":"traderTradeActions","columns":[],"filters":[{"filterColumn":"OperatorID","filterOperator":"=","filterValue":"'.$affilate_id.'"},{"filterColumn":"TraderID","filterOperator":"=","filterValue":"10666"}],"sortColumn":null,"sortDirection":"desc","top":"5","skip":"0","distinctColumn":null}';

		$curl = curl_init($service_url); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);                                                                   
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);                                                                  
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($curl, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));
		$result = curl_exec($curl);
		curl_close($curl);

                $jsonResponse=json_decode($result);
		print_r($result);
		$balance = $jsonResponse->data->Balance;
	       

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
                if($t->status=='3'){
	        ?>


            <tr>
               <td><a href="#"><img src="https://selfmademillionaires.biz/members/images/assetsLogos/<?php $assetID=$t->assetID; $getassetimage=mysql_query("SELECT * FROM `assets` WHERE `optech_id`='$assetID'")or die(mysql_error()); $getassetimagerow=mysql_fetch_array($getassetimage); echo $getassetimagerow['id']; ?>.png">&nbsp;&nbsp;<?php echo $getassetimagerow['name']; ?></a></td>      
                <td> <?php if($t->direction=='1'){ echo 'CALL'; }elseif($t->direction=='-1'){ echo 'PUT'; } ?> </td>
                <td class="hidden-phone"><?php echo $t->strike; ?></td>
                <td><?php echo $t->createdDate; ?></td>
                <td class=""><?php echo $t->strikeEnd; ?></td>
                <td><?php echo $t->expirationDate; ?></td>
                <td><?php echo intval($t->amount); ?>&nbsp;<?php echo $t->currency; ?> </td>
                <td><?php $profit=((($t->profit-$t->amount)/$t->amount)*100); if($profit>='0'){echo $profit.'% / 100%'; }else{ echo '0% / 100%';}  ?></td>
                <td><?php if($t->direction=='1') { // call
					if($t->strikeEnd > $t->strike){ echo '<span class="label label-info label-mini"><i class="icon-thumbs-up"></i> won'; }
                                        if($t->strikeEnd < $t->strike){ echo '<span class="label label-important label-important label-mini"><i class="icon-thumbs-down"></i> lost'; }
                                        if($t->strikeEnd == $t->strike){ echo '<span class="label label-success label-mini"><i class="icon-exchange"></i> tie'; }
				}elseif($t->direction=='-1') { // put
					if($t->strikeEnd < $t->strike){ echo '<span class="label label-info label-important label-mini"><i class="icon-thumbs-up"></i> won'; }
                                        if($t->strikeEnd > $t->strike){ echo '<span class="label label-important label-important label-mini"><i class="icon-thumbs-down"></i> lost';  }
                                        if($t->strikeEnd == $t->strike){ echo '<span class="label label-succes label-important label-mini"><i class="icon-exchange"></i> tie'; }
				} ?></span></td>
            </tr>

           <?php
			
		$open=$totalwopen-$total;
	
		} }

	  break;

          case "SPOT-USA":
            	
		$_SESSION['isDemo'] = 0;
		$currency = $xml->Customer->data_0->currency;
		
		$data = "api_username=".$getbrandrow['username']."&api_password=".$getbrandrow['password']."&MODULE=Position&COMMAND=view&FILTER[customerId]=$spotid&FILTER[status][]=Win&FILTER[status][]=Lost&FILTER[status][]=Tie";
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
                <td><a href="#"><img src="https://selfmademillionaires.biz/members/images/assetsLogos/<?php $assetName=$p->assetId; $getassetimage=mysql_query("SELECT * FROM `assets` WHERE `alt_name`='$assetName'")or die(mysql_error()); $getassetimagerow=mysql_fetch_array($getassetimage); echo $getassetimagerow['id'];; ?>.png">&nbsp;&nbsp;<?php echo $getassetimagerow['name']; ?></a></td>    
                <td> <?php if($p->type =='DigitalCall'){ echo 'CALL'; }elseif($p->type =='DigitalPut'){ echo 'PUT'; }; ?> </td>
                <td class="hidden-phone"><?php echo $p->openPrice; ?></td>
                <td><?php echo $p->openTime; ?></td>
                <td class="hidden-phone"><?php echo $p->closePrice; ?></td>
                <td><?php echo $p->expireTime; ?></td>
                <td><?php echo intval($p->amount/100); ?>&nbsp;<?php echo $p->currency; ?> </td>
                <td><?php $final=((($p->winReturn)-($p->amount))/100); if($final<='0'){ $final='0';} echo $final.'% / 100%'; ?></td>
        	<td><?php 	 if($p->status=='Win'){ echo '<span class="label label-info label-mini"><i class="icon-thumbs-up"></i> won'; }
                       		 elseif($p->status=='Lost'){ echo '<span class="label label-important label-important label-mini"><i class="icon-thumbs-down"></i> lost'; }
                       		 else{ echo '<span class="label label-success label-mini"><i class="icon-exchange"></i> tie'; } ?></span></td>
            </tr>

<?php
			

			$total++;
		}

	  break;


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
                     if( $t->tradeState!='TradeActive'){
 			$totalwopen++;
	        ?>

            <tr>
                <td><a href="#"><img src="https://selfmademillionaires.biz/members/images/assetsLogos/<?php $assetName=$t->tradableAsset; $getassetimage=mysql_query("SELECT * FROM `assets` WHERE `name`='$assetName'")or die(mysql_error()); $getassetimagerow=mysql_fetch_array($getassetimage); echo $getassetimagerow['id'];; ?>.png">&nbsp;&nbsp;<?php echo $t->tradableAsset; ?></a></td>                
                <td> <?php echo strtoupper($t->action); ?> </td>
                <td class=""><?php echo $t->rate; ?></td>
                <td><?php echo $t->activeAt; ?></td>
                <td class=""><?php echo $t->expiryRate; ?></td>
                <td><?php echo $t->expireAt; ?></td>
                <td><?php echo $t->volume; ?> <?php echo $t->currency; ?> </td>
                <td><?php echo $t->payout.'% / 100%'; ?></td>
                <td><?php if($t->action == 'Call') { // call
					if($t->expiryRate > $t->rate){ echo '<span class="label label-info label-mini"><i class="icon-thumbs-up"></i> won'; }
                                        if($t->expiryRate < $t->rate){ echo '<span class="label label-important label-important label-mini"><i class="icon-thumbs-down"></i> lost'; }
                                        if($t->expiryRate == $t->rate){ echo '<span class="label label-success label-mini"><i class="icon-exchange"></i> tie'; }
				}elseif($t->action == 'Put') { // put
					if($t->expiryRate < $t->rate){ echo '<span class="label label-info label-important label-mini"><i class="icon-thumbs-up"></i> won'; }
                                        if($t->expiryRate > $t->rate){ echo '<span class="label label-important label-important label-mini"><i class="icon-thumbs-down"></i> lost';  }
                                        if($t->expiryRate == $t->rate){ echo '<span class="label label-succes label-important label-mini"><i class="icon-exchange"></i> tie'; }
				} ?></span></td>
            </tr>

           <?php
			
		$open=$totalwopen-$total;
	
		} }

	  break;



          case "TECH FINANCIALS":
                
		$_SESSION['isDemo'] = 0;
		$currency = $decoded->result{0}->apiaccountview_currencyISO;
		
		$url = $getbrandrow['api_url']."trading/getTrades";
							
		$affiliateUserName = $getbrandrow['username'];
		$affiliatePassword = $getbrandrow['password'];

		$now = gmdate("Y-m-d H:i:s");
		$data = "affiliateUserName=$affiliateUserName&affiliatePassword=$affiliatePassword&accountId=$spotid&fromDate=2015-06-01 00:00:00&toDate=$now&status=20";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
         
		$result=curl_exec ($ch);
		
		//echo $result;

		$decoded = json_decode($result);
                $totalwopen = 0;

		foreach($decoded->result as $t) {
                        $totalwopen++;

	        ?>

            <tr>
                <td><a href="#"><img src="https://selfmademillionaires.biz/members/images/assetsLogos/<?php $assetName=$t->tradinghistory_assetName; $getassetimage=mysql_query("SELECT * FROM `assets` WHERE `name`='$assetName'")or die(mysql_error()); $getassetimagerow=mysql_fetch_array($getassetimage); echo $getassetimagerow['id'];; ?>.png">&nbsp;&nbsp;<?php echo $t->tradinghistory_assetName; ?></a></td>                
                <td> <?php if($t->tradinghistory_positionType=='49'){ echo 'CALL'; }elseif($t->tradinghistory_positionType=='50'){ echo 'PUT'; } ?> </td>
                <td class=""><?php echo $t->tradinghistory_strike; ?></td>
                <td><?php echo $t->tradinghistory_orderTime; ?></td>
                <td class=""><?php echo $t->tradinghistory_closePrice; ?></td>
                <td><?php echo $t->tradinghistory_closeTime; ?></td>
                <td><?php echo intval($t->tradinghistory_amount)/100; ?>&nbsp;<?php echo $t->tradinghistory_currencyISO; ?> </td>
                <td><?php echo ($t->tradinghistory_returnOnSuccess-100).'% / 100%'; ?></td>
                <td><?php if($t->tradinghistory_positionType == 49) { // call
					if($t->tradinghistory_closePrice > $t->tradinghistory_strike){ echo '<span class="label label-info label-mini"><i class="icon-thumbs-up"></i> won'; }
                                        if($t->tradinghistory_closePrice < $t->tradinghistory_strike){ echo '<span class="label label-important label-important label-mini"><i class="icon-thumbs-down"></i> lost'; }
                                        if($t->tradinghistory_closePrice == $t->tradinghistory_strike){ echo '<span class="label label-success label-mini"><i class="icon-exchange"></i> tie'; }
				}elseif($t->tradinghistory_positionType == 50) { // put
					if($t->tradinghistory_closePrice < $t->tradinghistory_strike){ echo '<span class="label label-info label-important label-mini"><i class="icon-thumbs-up"></i> won'; }
                                        if($t->tradinghistory_closePrice > $t->tradinghistory_strike){ echo '<span class="label label-important label-important label-mini"><i class="icon-thumbs-down"></i> lost';  }
                                        if($t->tradinghistory_closePrice == $t->tradinghistory_strike){ echo '<span class="label label-succes label-important label-mini"><i class="icon-exchange"></i> tie'; }
				} ?></span></td>
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
		
		//print_r($xml);
		
		$payout = 0;
		$won = 0;
		$lost = 0;
		$total = 0;
		$today_p = 0;

		foreach($xml->Positions->children() as $p) {
			
			if($p->status != "open") {				
                                $open++;
?>
            <tr>
                <td><a href="#"><img src="https://selfmademillionaires.biz/members/images/assetsLogos/<?php echo $p->assetId; ?>.png">&nbsp;&nbsp;<?php echo $p->name; ?></a></td>                
                <td> <?php echo strtoupper($p->position); ?> </td>
                <td class="hidden-phone"><?php echo $p->entryRate; ?></td>
                <td><?php echo $p->date; ?></td>
                <td class="hidden-phone"><?php echo $p->endRate; ?></td>
                <td><?php echo $p->endDate; ?></td>
                <td><?php echo intval($p->amount); ?>&nbsp;<?php echo $p->currency; ?> </td>
                <td><?php echo $p->profit.'% / '.$p->loss.'%'; ?></td>
                <td><?php if($p->status=='won'){  echo '<span class="label label-info label-important label-mini">&nbsp;<i class="icon-thumbs-up"></i>';  }elseif($p->status=='tie'){ echo '<span class="label label-succes label-important label-mini">&nbsp;<i class="icon-exchange"></i>'; }else{ echo '<span class="label label-important label-important label-mini">&nbsp;<i class="icon-thumbs-down"></i>'; } echo $p->status; ?></span></td>
            </tr>

<?php
			}

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
			
			if($p->status != "open") {				
                                $open++;
?>
            <tr>
                <td><a href="#"><img src="https://selfmademillionaires.biz/members/images/assetsLogos/<?php echo $p->assetId; ?>.png">&nbsp;&nbsp;<?php echo $p->name; ?></a></td>                
                <td> <?php echo strtoupper($p->position); ?> </td>
                <td class="hidden-phone"><?php echo $p->entryRate; ?></td>
                <td><?php echo $p->date; ?></td>
                <td class="hidden-phone"><?php echo $p->endRate; ?></td>
                <td><?php echo $p->endDate; ?></td>
                <td><?php echo intval($p->amount); ?>&nbsp;<?php echo $p->currency; ?> </td>
                <td><?php echo $p->profit.'% / '.$p->loss.'%'; ?></td>
                <td><?php if($p->status=='won'){  echo '<span class="label label-info label-important label-mini">&nbsp;<i class="icon-thumbs-up"></i>';  }elseif($p->status=='tie'){ echo '<span class="label label-succes label-important label-mini">&nbsp;<i class="icon-exchange"></i>'; }else{ echo '<span class="label label-important label-important label-mini">&nbsp;<i class="icon-thumbs-down"></i>'; } echo $p->status; ?></span></td>
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
			
			if($p->status != "open") {				
                                $open++;
?>
            <tr>
                <td><a href="#"><img src="https://selfmademillionaires.biz/members/images/assetsLogos/<?php echo $p->assetId; ?>.png">&nbsp;&nbsp;<?php echo $p->name; ?></a></td>                
                <td> <?php echo strtoupper($p->position); ?> </td>
                <td class="hidden-phone"><?php echo $p->entryRate; ?></td>
                <td><?php echo $p->date; ?></td>
                <td class="hidden-phone"><?php echo $p->endRate; ?></td>
                <td><?php echo $p->endDate; ?></td>
                <td><?php echo intval($p->amount); ?>&nbsp;<?php echo $p->currency; ?> </td>
                <td><?php echo $p->profit.'% / '.$p->loss.'%'; ?></td>
                <td><?php if($p->status=='won'){  echo '<span class="label label-info label-important label-mini">&nbsp;<i class="icon-thumbs-up"></i>';  }elseif($p->status=='tie'){ echo '<span class="label label-succes label-important label-mini">&nbsp;<i class="icon-exchange"></i>'; }else{ echo '<span class="label label-important label-important label-mini">&nbsp;<i class="icon-thumbs-down"></i>'; } echo $p->status; ?></span></td>
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

		$url = $getbrandrow['api_url'].'/api/v1/TradesHistory?token='.$token;    

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
                <td><a href="#"><img src="https://selfmademillionaires.biz/members/images/assetsLogos/<?php $assetName=$p->symbol; $getassetimage=mysql_query("SELECT * FROM `assets` WHERE `alt_name`='$assetName'")or die(mysql_error()); $getassetimagerow=mysql_fetch_array($getassetimage); echo $getassetimagerow['id']; ?>.png">&nbsp;&nbsp;<?php echo $p->outputName; ?></a></td>                
                <td> <?php if($p->action =='1'){ echo 'CALL'; }elseif($p->action =='0'){ echo 'PUT'; }; ?> </td>
                <td class="hidden-phone"><?php echo $p->targetPrice; ?></td>
                <td><?php echo gmdate("Y-m-d H:i:s", $p->creationDate); ?></td>
                <td><?php echo $p->closePrice; ?></td>
                <td><?php echo gmdate("Y-m-d H:i:s", $p->endTime); ?></td>
                <td><?php echo intval($p->amount); ?>&nbsp;USD </td>
                <td><?php echo $p->payoutIfWin.'% / 100%'; ?></td>
		<td><?php if($p->action =='1') { // call
							if($p->closePrice > $p->targetPrice){ echo '<span class="label label-info label-mini"><i class="icon-thumbs-up"></i> won'; }
				                        if($p->closePrice < $p->targetPrice){ echo '<span class="label label-important label-important label-mini"><i class="icon-thumbs-down"></i> lost'; }
				                        if($p->closePrice == $p->targetPrice){ echo '<span class="label label-success label-mini"><i class="icon-exchange"></i> tie'; }
						}elseif($p->action =='0') { // put
							if($p->closePrice < $p->targetPrice){ echo '<span class="label label-info label-important label-mini"><i class="icon-thumbs-up"></i> won'; }
				                        if($p->closePrice > $p->targetPrice){ echo '<span class="label label-important label-important label-mini"><i class="icon-thumbs-down"></i> lost';  }
				                        if($p->closePrice == $p->targetPrice){ echo '<span class="label label-succes label-important label-mini"><i class="icon-exchange"></i> tie'; }
						} ?></span></td>
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
            $aClosedPositions = $airsoft->getClosedPositions($aPositions, $aClientInfo);
            if (!empty($aClosedPositions)) :
                ?>
                <?php foreach ($aClosedPositions as $data): ?>
                    <?php
                    $getassetimage = mysql_query("SELECT * FROM `assets` WHERE `alt_name`='" . $data['asset_id'] . "'");
                    $getassetimagerow = mysql_fetch_array($getassetimage);
                    ?>
                                                    <tr> 
                                                        <td>
                    <?php if ($getassetimagerow['id']) { ?>
                                                                <a href="#"><img src="https://selfmademillionaires.biz/members/images/assetsLogos/<?php echo $getassetimagerow['id']; ?>.png">
                    <?php } ?>
                                                                &nbsp;&nbsp;<?php echo $data['asset_name']; ?>
                                                            </a>
                                                        </td>
                                                        <td><?php echo $data['type']; ?></td>
                                                        <td class="hidden-phone"><?php echo $data['entry_rate'] ?></td>
                                                        <td><?php echo $data['open_date']; ?></td>
                                                        <td><?php echo $data['expiry_rate']; ?></td>
                                                        <td><?php echo $data['expire_date']; ?></td>
                                                        <td><?php echo intval($data['amount']); ?>&nbsp;USD </td>
                                                        <td><?php echo $data['payout'] . '% / 100%'; ?></td>
                                                        <td>
                    <?php
                    if (strtolower($data['status']) == 'win') {
                        echo '<span class="label label-info label-important label-mini">&nbsp;'
                        . '<i class="icon-thumbs-up"></i>';
                    } elseif (strtolower($data['status']) == 'tie') {
                        echo '<span class="label label-succes label-important label-mini">&nbsp;'
                        . '<i class="icon-exchange"></i>';
                    } else {
                        echo '<span class="label label-important label-important label-mini">&nbsp;'
                        . '<i class="icon-thumbs-down"></i>';
                    }
                    echo $data['status'] . '</span>';
                    ?>
                                                        </td>
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

