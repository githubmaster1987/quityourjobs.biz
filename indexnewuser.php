<?php 


session_start();

$_SESSION['is_demo'] = false;

// checking 4 the weekend
if(gmdate("w") == 0 || gmdate("w") == 6) {
	//echo  "<meta http-equiv=\"Refresh\"content=\"0; URL=weekend.php\">";
 	//exit;	
}

if(isset($_SESSION['email']) || isset($_COOKIE['MillionaryMaker'])) {
	
	$email = $_SESSION['email'];
	
	require_once("variables.php");
	$db = new ezSQL_mysql($GLOBALS['database_username'],$GLOBALS['database_password'],$GLOBALS['database_database'],$GLOBALS['database_server']);
		
	$user = $db->get_row("select * from users where email='$email'");
	
	$spotid = $db->get_var("select spotid from users_spot where id_user='".$user->id."'");
	
	$brand = $db->get_row("select * from brands where ref='".$user->brand."'");
	
	
	if($brand->ref == "TRAD") {
		
		$url = $brand->api_url."customer/findAccounts";
							
		$affiliateUserName = $brand->username;
		$affiliatePassword = $brand->password;
			
		$data = "affiliateUserName=$affiliateUserName&affiliatePassword=$affiliatePassword&accountID=$spotid";
			
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result=curl_exec ($ch);
		$decoded = json_decode($result);

		$balance2 = $decoded->result{0}->apiaccountview_balance;
		$balance = substr_replace($balance2, ".", -2, 0);
		
	}
	else {
		// getting the client balance
		$data = "api_username=".$brand->username."&api_password=".$brand->password."&MODULE=Customer&COMMAND=view&FILTER[id]=$spotid";
		//echo $data;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $brand->api_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		//curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain')); 
		$result=curl_exec ($ch);
		$xml = simplexml_load_string($result);

		//print_r($xml);

		$balance = $xml->Customer->data_0->accountBalance;
	}
	
	
	if(empty($balance) || $balance == "0.00") {
		// redirect no balance users to demo page
		$_SESSION['isDemo'] = 1;
		echo  "<meta http-equiv=\"Refresh\"content=\"0; URL=main_live.php?firstTime=1\">";
		exit;

	}
	
	if($brand->ref == "TRAD") {
		$_SESSION['isDemo'] = 0;
		$currency = $decoded->result{0}->apiaccountview_currencyISO;
		
		$url = $brand->api_url."trading/getTrades";
							
		$affiliateUserName = $brand->username;
		$affiliatePassword = $brand->password;

		$now = gmdate("Y-m-d H:i:s");
		$data = "affiliateUserName=$affiliateUserName&affiliatePassword=$affiliatePassword&accountId=$spotid&fromDate=2015-06-01 00:00:00&toDate=$now&status=20,147";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result=curl_exec ($ch);
		
		//echo $result;

		$decoded = json_decode($result);

		$won = 0;
		$total = 0;
		$lost = 0;
		foreach($decoded->result as $t) {
			if($t->tradinghistory_status == 20) {
				$total++;
				$positionType = $t->tradinghistory_positionType;
				if($positionType == 49) { // call
					if($t->tradinghistory_closePrice >= $t->tradinghistory_strike) $won++;
					else $lost++;
				}
				elseif($positionType == 50) { // put
					if($t->tradinghistory_closePrice <= $t->tradinghistory_strike) $won++;
					else $lost++;
				}
				
			}
			
			
		}
		
	}
	else {
	
		$_SESSION['isDemo'] = 0;
		$currency = $xml->Customer->data_0->currency;
		
		$data = "api_username=".$brand->username."&api_password=".$brand->password."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $brand->api_url);
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
				continue;
			}
			elseif($p->status == "lost") {
				$lost++;
			}
			elseif($p->status == "won") {
				$won++;
				
				// search 4 today profits
				$position_id = $p->id;
				$position_today = $db->get_var("select count(*) from executed_trades_details where position_id='$position_id' and DATE(serverDate) = '".date("Y-m-d")."'");
				//echo "select count(*) from executed_trades_details where position_id='$position_id' and DATE(serverDate) = '".date("Y-m-d")."'";
				if($position_today > 0) {
					$today_p = $today_p + $p->winSum; 
				}
			}
			$total++;
		}
		if($total >= 500) {
			
			$data = "api_username=".$brand->username."&api_password=".$brand->password."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid&page=2";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $brand->api_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

			$result=curl_exec ($ch);
			$xml = simplexml_load_string($result);
			
			foreach($xml->Positions->children() as $p) {
				
				if($p->status == "open") {
					continue;
				}
				elseif($p->status == "lost") {
					$lost++;
				}
				elseif($p->status == "won") {
					$won++;
					
					// search 4 today profits
					$position_id = $p->id;
					$position_today = $db->get_var("select count(*) from executed_trades_details where position_id='$position_id' and DATE(serverDate) = '".date("Y-m-d")."'");
					//echo "select count(*) from executed_trades_details where position_id='$position_id' and DATE(serverDate) = '".date("Y-m-d")."'";
					if($position_today > 0) {
						$today_p = $today_p + $p->winSum; 
					}
				}
				$total++;
			
			}
		}
		if($total >= 1000) {
			
			$data = "api_username=".$brand->username."&api_password=".$brand->password."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid&page=3";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $brand->api_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

			$result=curl_exec ($ch);
			$xml = simplexml_load_string($result);
			
			foreach($xml->Positions->children() as $p) {
				
				if($p->status == "open") {
					continue;
				}
				elseif($p->status == "lost") {
					$lost++;
				}
				elseif($p->status == "won") {
					$won++;
					
					// search 4 today profits
					$position_id = $p->id;
					$position_today = $db->get_var("select count(*) from executed_trades_details where position_id='$position_id' and DATE(serverDate) = '".date("Y-m-d")."'");
					//echo "select count(*) from executed_trades_details where position_id='$position_id' and DATE(serverDate) = '".date("Y-m-d")."'";
					if($position_today > 0) {
						$today_p = $today_p + $p->winSum; 
					}
				}
				$total++;
			
			}
		}
		if($total >= 1500) {
			
			$data = "api_username=".$brand->username."&api_password=".$brand->password."&MODULE=Positions&COMMAND=view&FILTER[customerId]=$spotid&page=4";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $brand->api_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

			$result=curl_exec ($ch);
			$xml = simplexml_load_string($result);
			
			foreach($xml->Positions->children() as $p) {
				
				if($p->status == "open") {
					continue;
				}
				elseif($p->status == "lost") {
					$lost++;
				}
				elseif($p->status == "won") {
					$won++;
					
					// search 4 today profits
					$position_id = $p->id;
					$position_today = $db->get_var("select count(*) from executed_trades_details where position_id='$position_id' and DATE(serverDate) = '".date("Y-m-d")."'");
					//echo "select count(*) from executed_trades_details where position_id='$position_id' and DATE(serverDate) = '".date("Y-m-d")."'";
					if($position_today > 0) {
						$today_p = $today_p + $p->winSum; 
					}
				}
				$total++;
			
			}
		}
	
	}
	


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
    background-color: #4A8BC2;
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

</style>

<div id="main-content" style="margin-left: 180px;">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
               
                
               </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row-fluid">
                <!--BEGIN METRO STATES-->
                <div class="metro-nav">

.                    <div class="metro-nav-block nav-block-orange double">
                        <a data-original-title="" href="#">
                            <i class="icon-thumbs-up"></i>
                            <div class="info">0</div>
                            <div class="status">Open Trades</div>
                        </a>
                    </div>



                    <div class="metro-nav-block nav-olive double">
                        <a data-original-title="" href="#">
                            <i class="icon-thumbs-down"></i>
                            <div class="info">+970</div>
                            <div class="status">Trades Won</div>
                        </a>
                    </div>


                   <div class="metro-nav-block nav-block-yellow double">
                        <a data-original-title="" href="#">
                            <i class="icon-time"></i>
                            <div class="info">49</div>
                            <div class="status">Trades Lost</div>
                        </a>
                    </div>


                   
                    <div class="metro-nav-block nav-block-green double">
                        <a data-original-title="" href="#">
                            <i class="icon-exclamation-sign"></i>
                            <div class="info">+897</div>
                            <div class="status">Lifetime Trades</div>
                        </a>
                    </div>
                   
                </div>
                <div class="space10"></div>
                <!--END METRO STATES-->
            </div>
            
           <div class="row-fluid">
                <div class="span8 howtotrade">
                  <!-- BEGIN BUTTON PORTLET-->
                  <div class="widget red">
                        <div class="hometopwidget widget-title">
                           <h4><i class="icon-reorder"></i> How to trade? </h4>                         
                        </div>
                        <div class="hometopwidgetbody widget-body">
                            <p>
                             <h3> Activate auto trading </h3>
                              <button class="btn btn-large" type="button"> YES</button> 
                              <button class="btn btn-large" type="button"> NO</button>                            
                            </p>
                        </div>
                  </div>
                  <!-- END BUTTON PORTLET-->
                </div>



                <div class="span4">
                  <!-- BEGIN BUTTON PORTLET-->
                  <div class="widget red">
                        <div class="hometopwidget widget-title">
                           <h4><i class="icon-reorder"></i> Account Summary </h4>
                        </div>
                        <div class="hometopwidgetbody widget-body">
			<ul class="collection">
			  <li class="collection-item">Broker ID:<span class="badge">#70803</span></li>
			  <li class="collection-item">E-Mail:<span class="badge">1111absgf@gmail.com</span></li>
			  <li class="collection-item">Balance:<span class="badge">$4,816.25</span></li>
			  <li class="collection-item">Today Profit:<span class="badge">$180.00</span></li>
			</ul>
                        </div>
                  </div>
                  <!-- END BUTTON PORTLET-->
                </div>


            </div>



           <div class="row-fluid">



             <div class="span7">
                    <!-- BEGIN TAB PORTLET-->
                  
                        
               
					<div class="card">
						 <table class="table table-striped table-bordered table-advance table-hover">
						  <thead>
							<tr> 
								<th data-field="id">Asset</th>
								<th data-field="name">CALL Strike</th>
								<th data-field="price">PUT Strike</th>
								<th data-field="total">Current Rate</th>
								<th data-field="status">Subscribed</th>
							</tr>
						  </thead>
							<tbody>
								<tr>
								<td valign="center"><img src="http://themillionaires.biz/members/images/assetsLogos/2.png" alt="USD/JPY"></td>
								<td>115.362</td>
								<td>114.903</td>
								<td><span id="rate_2">117.261</span></td>
								<td><a onclick="popit=false" class="btn waves-effect waves-light blue">YES</a></td>
								</tr>
								<tr>
								<td valign="center"><img src="http://themillionaires.biz/members/images/assetsLogos/35.png" alt="AUD/USD"></td>
								<td></td>
								<td></td>
								<td><span id="rate_35">0.6964</span></td>
								<td><a onclick="popit=false" class="btn waves-effect waves-light blue">YES</a></td>
								</tr>
								<tr>
								<td valign="center"><img src="http://themillionaires.biz/members/images/assetsLogos/46.png" alt="EUR/JPY"></td>
								<td></td>
								<td></td>
								<td><span id="rate_46">128.07</span></td>
								<td><a onclick="popit=false" class="btn waves-effect waves-light blue">YES</a></td>
								</tr>
								<tr>
								<td valign="center"><img src="http://themillionaires.biz/members/images/assetsLogos/91.png" alt="EUR/USD"></td>
								<td></td>
								<td></td>
								<td><span id="rate_91">1.092956</span></td>
								<td><a onclick="popit=false" class="btn waves-effect waves-light blue">YES</a></td>
								</tr>
								<tr>
								<td valign="center"><img src="http://themillionaires.biz/members/images/assetsLogos/95.png" alt="GBP/USD"></td>
								<td></td>
								<td></td>
								<td><span id="rate_95">1.45195</span></td>
								<td><a onclick="popit=false" class="btn waves-effect waves-light blue">YES</a></td>
								</tr>
								<tr>
								<td valign="center"><img src="http://themillionaires.biz/members/images/assetsLogos/157.png" alt="USD/CHF"></td>
								<td></td>
								<td></td>
								<td><span id="rate_157">0.99528</span></td>
								<td><a onclick="popit=false" class="btn waves-effect waves-light blue">YES</a></td>
								</tr>
						     </tbody>
						</table>

					</div>

                                    </div>




	      <div class="span5">
		<!-- BEGIN BASIC CHART PORTLET-->
		 <div class="widget orange">
		    <div class="widget-title">
		        <h4><i class="icon-reorder"></i> Selection Chart</h4>
		    </div>
		    <div class="widget-body">
		        <div id="chart-3" class="chart">123</div>
		    </div>
		 </div>
               </div>






                                
                    
           
            </div>
            
            
            
            

            <!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
	<?php if($_GET['firstTime'] == 1) {

		$user = $db->get_row("select * from users where email='$email'");
	
		$password = $user->password;
		//$brand = $user->brand;
	
		//$brand_data = $db->get_row("select name,autologin_url from brands where ref='$brand'");
		//$brand_name = $brand->name;
		$login_url = $brand->autologin_url;
		$deposit_url = $brand->deposit_url;

	?>

	<?php if($brand->ref != "TRAD" && $brand->ref != "TRUSH") { ?>
	<form action="<?php echo $login_url; ?>" method="post" name="loginNow" id="loginNow" autocomplete="off" target="myFrame">
	<input type="hidden" name="email" value="<?= $email; ?>" />
	<input type="hidden" name="password" value="<?= $password; ?>" />
	</form>
	<iframe height="0" width="0" name="myFrame" style="visibility:hidden;display:none"></iframe>
	<script>
	document.forms["loginNow"].submit();
	</script>

	<?php } elseif($brand->ref == "TRUSH") { ?>



	<?php } else { 

			list($username, $domain) = explode("@", $email);
			$username = substr($username, 0, 15);
	?>
	<form action="<?php echo $login_url; ?>" method="post" name="loginForm" id="loginForm" autocomplete="off" target="myFrame">
	<input type="hidden" name="username" value="<?= $username; ?>" />
	<input type="hidden" name="userpass" value="<?= $password; ?>" />
	</form>
	<iframe height="0" width="0" name="myFrame" style="visibility:hidden;display:none"></iframe>
	<script>
	document.forms["loginForm"].submit();
	</script>
	<?php } ?>
	<?php } ?>
      <!-- END PAGE -->  
<?php require_once('include/footer.php'); ?>

<?php
}
else {
	echo  "<meta http-equiv=\"Refresh\"content=\"0; URL=login.php\">";
 	exit;
}
?>



