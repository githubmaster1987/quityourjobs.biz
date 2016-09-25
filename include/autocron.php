<?php

require_once('../variables.php');


if($brand_ref == "TRAD") {
		$min_time = 5*60; $max_time = 120*60;
	}
	else {
		$min_time = 15; $max_time = 120;
	}






		$option_date = gmdate("Y_m_d H:i:s");
		$min_gmt_date = gmdate("Y-m-d H:i:00", gmmktime() + ($min_time * 60));
		$max_gmt_date = gmdate("Y-m-d H:i:00", gmmktime() + ($max_time * 60));
		$asset_id = '91';							
		$data = "api_username=imoffer_usr&api_password=8kk8iT1ZpX&MODULE=Options&COMMAND=view&FILTER[assetId]=$asset_id&FILTER[status]=open&FILTER[startDate][max]=$option_date&FILTER[endDate][min]=$min_gmt_date&FILTER[endDate][max]=$max_gmt_date";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://www.empireoption.com/api/');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result=curl_exec ($ch);
		$xml = simplexml_load_string($result);
								
		print_r($xml);
		$optionId = $xml->Options->data_0->id;
		$forDemo_profit = $xml->Options->data_0->profit;
		$forDemo_multiplier = $xml->Options->data_0->loss;

                echo $optionId;


if(!empty($optionId)){

                                $exp_test = gmmktime() + ($min_time * 60);
                    		$action = 'call';
                                $amount = '25';
				$spotid = '805794';


				$data = "api_username=imoffer_usr&api_password=8kk8iT1ZpX&MODULE=Positions&COMMAND=add&product=regular&customerId=$spotid&position=$action&amount=$amount&endDate=$exp_test&assetId=$asset_id&optionId=$optionId";
					
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'http://www.empireoption.com/api/');
											
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

				$result=curl_exec ($ch);
                                echo $result.'0000000000';

				$xml_position = simplexml_load_string($result);
				
				// success message
				if($xml_position->operation_status == "successful") {
					
					$position_amount =  $xml_position->Positions->amount;
					$position_position = $xml_position->Positions->position;
					$position_currency = $xml_position->Positions->currency;
					$position_rate = $xml_position->Positions->rate;
					$position_originalRateTimestamp = $xml_position->Positions->originalRateTimestamp;
					$position_status = $xml_position->Positions->status;
					$position_date = $xml_position->Positions->date;
					$position_opstartDate = $xml_position->Positions->opstartDate;
					$position_opendDate = $xml_position->Positions->opendDate;
					$position_opprofit = $xml_position->Positions->opprofit;
					$position_opmultiplier = $xml_position->Positions->opmultiplier;
					$position_opruleId = $xml_position->Positions->opruleId;
					$position_id = $xml_position->Positions->id;
					

					
					echo "SUCCESS|$position_amount|$position_currency|$position_rate|$position_originalRateTimestamp|$position_status|$position_date|$position_opstartDate|$position_opendDate|$position_opprofit|$position_opmultiplier|$position_opruleId|$position_id|$asset_id|$asset_name";

} }

?>
