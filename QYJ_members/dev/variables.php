<?php
error_reporting(0);
//“smmnewbo_milliona_main” to the user “smmnewbo_user”.

/*
$username = "milliona_user";
$password = "admin@123";
$hostname = "localhost"; 

//connection to the database
$dbhandle = mysql_connect($hostname, $username, $password) or die("Unable to connect to MySQL");
//echo "Connected to MySQL<br>";

//select a database to work with
$selected = mysql_select_db("milliona_main") or die("Could not select Database");

*/


//“smmnewbo_milliona_main” to the user “smmnewbo_user”.

$username = "smmnewbo_user";
$password = "admin@123";
$hostname = "localhost"; 

//connection to the database
$dbhandle = mysql_connect($hostname, $username, $password) or die("Unable to connect to MySQL");
//echo "Connected to MySQL<br>";

//select a database to work with
$selected = mysql_select_db("smmnewbo_milliona_main") or die("Could not select Database");



$client_ip = $_SERVER['REMOTE_ADDR'];

$longip = ip2long($client_ip);


$getgeocountry = mysql_query("SELECT * FROM `geo_ip` WHERE '$longip' BETWEEN `iplong_start` AND `ip_long_end`");
$geocountryrow = mysql_fetch_array($getgeocountry);
$country = $geocountryrow['country'];
$countryiso = $geocountryrow['country_iso'];


$getcountrycode=mysql_query("SELECT * FROM `country` WHERE `iso`='$countryiso'");
$getcountrycoderow=mysql_fetch_array($getcountrycode);

$getAllCountry = mysql_query("SELECT phonecode, nicename FROM `country`");
$i = 0;
while($row = mysql_fetch_assoc($getAllCountry)) {
    $aCountry[$i]['nicename'] = $row['nicename'];
	$aCountry[$i]['phonecode'] = $row['phonecode'];
	$i++;
}
   
if(empty($country)){
  $ip = $_SERVER['REMOTE_ADDR'];
  $query = json_decode(file_get_contents('http://www.geoplugin.net/json.gp?ip=' . $ip));
  $countryiso=$query->geoplugin_countryCode;

   $getgeocountry = mysql_query("SELECT * FROM `country` WHERE `iso`='$countryiso'");
   $getcountrycoderow = mysql_fetch_array($getgeocountry); 
   $country=$getcountrycoderow['nicename'];
}





?>