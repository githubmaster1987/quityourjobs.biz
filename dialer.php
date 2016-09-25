<?php
$country_code =$_REQUEST['code'];
$dial_code=json_decode(file_get_contents('https://restcountries.eu/rest/v1/alpha?codes=$country_code'));
echo $dial_code[0]->callingCodes[0];
?>
