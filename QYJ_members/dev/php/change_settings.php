<?php

ini_set('display_errors', '0');

session_start();

include("../variables.php");
$db = new ezSQL_mysql($GLOBALS['database_username'],$GLOBALS['database_password'],$GLOBALS['database_database'],$GLOBALS['database_server']);

$email = $_SESSION['email'];

$user = $db->get_row("select * from users where email='$email'");

$id_user = $user->id;

$type = addslashes($_GET['type']);
$value = addslashes($_GET['value']);

if($type == "subscribe")
	$db->query("update users_settings set subscribed='$value' where id_user='$id_user'");
elseif($type == "amount")
	$db->query("update users_settings set amount='$value' where id_user='$id_user'");
?>