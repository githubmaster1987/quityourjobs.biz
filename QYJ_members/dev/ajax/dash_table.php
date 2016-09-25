<?php
require_once('../variables.php');
session_start();
date_default_timezone_set("UTC");
$timemain = date("Y-m-d H:i:s", time() - 65);

$getuser = mysql_query("select * from users where `id`='" . $_SESSION['userid'] . "'");
$getuserrow = mysql_fetch_array($getuser);
if ($getuserrow['deposit_status'] == 'LIVE') {

    $getbrand = mysql_query("select * from brands where ref='" . $_SESSION['brand'] . "'");
    $getbrandrow = mysql_fetch_array($getbrand);
    ?>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="../assets/bootstrap/css/bootstrap-responsive.min.css">
	<div class="container-fluid">
	<div class="row-fluid">
	<div class="span12" style="min-height:432px;">
	<div style="overflow-x:auto;">
    <table class="table table-striped table-bordered table-advance table-hover">
        <thead>
            <tr> 
                <th data-field="id">Asset</th>
                <th data-field="name">Current Rate</th>
                <th data-field="price">Type</th>
                <th data-field="total">Potential</th>
                <th data-field="status">Profit</th>
                <th data-field="status">Investment</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $getasset = mysql_query("SELECT * FROM `assets`")or die(mysql_error());
            while ($getassetrow = mysql_fetch_array($getasset)) {
                if ($getbrandrow['api_type'] == 'AIRSOFT') {
                    $assetId = $getassetrow['airsoft_id'];
                } else {
                    $assetId = $getassetrow['id'];
                }

                $data = "api_username=imoffer_usr&api_password=8kk8iT1ZpX&MODULE=AssetsHistory&COMMAND=view&FILTER[assetId]=" . $getassetrow['id'] . "&FILTER[date][min]=" . $timemain;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'http://www.empireoption.com/api/');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

                $result = curl_exec($ch);
                $xml = simplexml_load_string($result);

                $result = curl_exec($ch);
                $xml = simplexml_load_string($result);

                //print_r($xml);
                $rate = $xml->AssetsHistory->data_0->rate;
                // echo $rate;	

                $potentialnumber = rand(1, 8);
                if ($potentialnumber == '1') {
                    $potential = '75';
                } elseif ($potentialnumber == '2') {
                    $potential = '75';
                } elseif ($potentialnumber == '3') {
                    $potential = '82';
                } elseif ($potentialnumber == '4') {
                    $potential = '77';
                } elseif ($potentialnumber == '5') {
                    $potential = '90';
                } else {
                    $potential = '81';
                }
                if (($potentialnumber != '6') && ($potentialnumber != '7') && ($potentialnumber != '8')) {
                    ?>
                    <tr>
                        <td><img src="http://themillionaires.biz/dev/images/assetsLogos/<?php echo $getassetrow['id']; ?>.png" alt="USD/JPY"><?php echo $getassetrow['name']; ?></td>
                        <td><?php echo substr($rate, '0', '-3'); ?></td>
                        <td><a onclick="popit = false" class="btn waves-effect waves-light blue tradetype"><?php $cp = rand(1, 2);
            if ($cp == '1') {
                echo 'CALL';
            } else {
                echo 'PUT';
            } ?></a></td>
                        <td><span class="potential"><?php echo $potential; ?></span>%</td>
                        <td>$<span class="profit"><?php echo 25 + intval($potential * 25 / 100); ?></span></td>
                        <td> <input type="hidden" class="assetname" value="<?php echo $assetId; ?>">     <select class="tradedpdown" tabindex="1"> <option value="25">$25</option>
                                <option value="50">$50</option>
                                <option value="100">$100</option>
                                <option value="150">$150</option>
                                <option value="200">$200</option>
                                <option value="250">$250</option>                           
                            </select><button class="tradebtn" type="button">Trade</button></td>
                    </tr>

        <?php }
    } ?>
        </tbody>
    </table>
    </div>
<?php } else { ?>
    <div class="makedeposit" style="text-align:center; padding:50px;">
        <img class="img-responsive" src="../images/loader.gif" style="width:150px; margin-bottom:0px;">
        <h3><b>Please Make Deposit To Get Signals</b></h3>

        <button class="button-0" type="button" onclick="location.href = 'deposit.php'">Deposit Now!</button>

    </div> 
	</div><!--.span12-->
	</div><!--.row-fluid-->
	</div><!--.container-fluid-->
<?php } ?>



