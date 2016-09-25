function getRate(asset_id, json_url) {
	
	var d = new Date();
	var n = d.getUTCDay();
	//alert(n);
	if(n != 0 && n != 6) {
	
		$.getJSON(json_url, function (data) {
			
			price = data.data;
			
			$("#rate_" + asset_id).replaceWith("<span id='rate_"+asset_id+"'>"+price+"</span>");
			
			var str = "";
			str += "&asset_id=" + asset_id;
			str += "&price=" + price;
			
			$.ajax({
					 type: "GET",
					 url: "php/trade.php",
					 data: str,
					 success:function(data) {
						//
						if(data) {
							splitted = data.split("|");
							var position_message = splitted[0];
							
							if(position_message == "SUCCESS") {
								var position_amount = splitted[1];
								var position_currency = splitted[2];
								var position_rate = splitted[3];
								var position_originalRateTimestamp = splitted[4];
								var position_status = splitted[5];
								var position_date = splitted[6];
								var position_opstartDate = splitted[7];
								var position_opendDate = splitted[8];
								var position_opprofit = splitted[9];
								var position_opmultiplier = splitted[10];
								var position_opruleId = splitted[11];
								var position_id = splitted[12];
								var asset_id = splitted[13];
								var asset_name = splitted[14];
								
								var build_html = "TRADE EXECUTED! - Asset Name: " + asset_name + " - Rate: " + position_rate;
								
								
								Materialize.toast(build_html, 50000);
								//alert(data);
							}
							else if(position_message == "MANUAL") {
								var asset_id = splitted[1];
								var asset_name = splitted[2];
								var id_trade = splitted[3];
								var action = splitted[4];
								var optionId = splitted[5];
								var accountType = splitted[6];
								var price = splitted[7];
								var opprofit = splitted[8];
								var opmultiplier = splitted[9];
								var build_html = "TRADE MANUAL - Asset Name: " + asset_name + " - Action: " + action + " - <a class='btn waves-effect waves-light blue' href='manual_trade.php?id_trade="+id_trade+"&optionId="+optionId+"&accountType="+accountType+"&action="+action+"&asset_id="+asset_id+"&price="+price+"&opprofit="+opprofit+"&opmultiplier="+opmultiplier+"'>TRADE SIGNAL</a>";
								Materialize.toast(build_html, 50000);
							}
						}
					 }	 
			 })	
			
		})
	}
	else {
		$("#rate_" + asset_id).replaceWith("<span id='rate_"+asset_id+"'>Markets are closed</span>");
		
	}
}
window.setInterval(function(){
  getRate('2', 'http://themillionaires.biz/members/php/json_tf.php?brand_ref=TRAD&asset_name=USD/JPY');
  getRate('35', 'http://themillionaires.biz/members/php/json_tf.php?brand_ref=TRAD&asset_name=AUD/USD');
  getRate('46', 'http://themillionaires.biz/members/php/json_tf.php?brand_ref=TRAD&asset_name=EUR/JPY');
  getRate('91', 'http://themillionaires.biz/members/php/json_tf.php?brand_ref=TRAD&asset_name=EUR/USD');
  getRate('95', 'http://themillionaires.biz/members/php/json_tf.php?brand_ref=TRAD&asset_name=GBP/USD');
  getRate('157', 'http://themillionaires.biz/members/php/json_tf.php?brand_ref=TRAD&asset_name=USD/CHF');
  
  openTrades();
  
}, 30000);


function openTrades() {
		
	var str = "";
	$.ajax({
		 type: "GET",
		 url: "php/opentrades.php",
		 data: str,
		 success:function(data) {
			//
			if(data > 0) {
				$("#open_trades").replaceWith('<span class="new badge" id="open_trades">'+data+'</span>');
			}
			else {
				$("#open_trades").replaceWith('<span class="new badge" id="open_trades" style="display: none">0</span>');
			}
			
			$("#open_trades_card").replaceWith('<span id="open_trades_card">'+data+'</span>');
			
		 }	 
	})	
}

function change_settings(type, value) {
	
	var str = "";
		
	if(type == "subscribe") {
		str = "&type=subscribe&value=" + value;
		
	}
	if(type == "amount") {
		if(value == "other") {
			str = "&type=amount&value=" + $("#amount").val();
		}
		else {
			str = "&type=amount&value=" + value;
		}
	}
	$.ajax({
		 type: "GET",
		 url: "php/change_settings.php",
		 data: str,
		 success:function(data) {
			//
			 location.reload(); 
		 }	 
	})	
}