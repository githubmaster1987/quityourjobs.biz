<?php

 session_start();
 require_once('../variables.php');

 $first_name=$_POST['fname'];
 $last_name=$_POST['lname'];
 $email = $_POST['email'];
 $password = $_POST['password'];
 $country = $_POST['country'];
 $phone = $_POST['phone'];


 echo $first_name.' '.$last_name;

?>
