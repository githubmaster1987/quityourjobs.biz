<?php
$url= 'http://www.brokerofficial.com/back.php/affiliate/externalSorce/api?method=autoLoginClient&key=Kyr6zBFzmf&client_id=26860&redirect_url=/trade.php/binary/deposit/';
									echo $url;
                            $curl = curl_init($url);
                            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            $result = curl_exec($curl);
                            curl_close($curl);
							echo $result;exit;
                            $jsonResponse = json_decode($result);
                            $url = $jsonResponse->autoLoginClient->link;
?>