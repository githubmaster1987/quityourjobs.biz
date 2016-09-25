<div class="headershow">New Depositor!</div>

<?php 
require_once("../variables.php");

$getuser = mysql_query("select * from show_user ORDER BY RAND() LIMIT 1");
$getuserrow=mysql_fetch_array($getuser);

$amount='250';
echo '<p class="showtext">'.$getuserrow['name']. ' Made a Deposit of $'.$amount.' and has started making money using SMM</p>';

?>

<button class="button-0" type="button" onClick="location.href='deposit.php'">Deposit Now!</button>
