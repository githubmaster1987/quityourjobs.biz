<?php

session_start();

//echo $_SESSION['userid'];

if(isset($_SESSION['userid'])){

	require_once("variables.php");
		
	$getuser = mysql_query("select * from users where `id`='".$_SESSION['userid']."'");
        $getuserrow=mysql_fetch_array($getuser);
	
	$spotid = $getuserrow['spotid'];
        $email=$getuserrow['email'];

      // echo $spotid;
	
	$getbrand =mysql_query("select * from brands where ref='".$_SESSION['brand']."'");
        $getbrandrow=mysql_fetch_array($getbrand);
       // echo $getbrandrow['ref'];

  //header('Location: index.php');
  echo "<meta http-equiv=\"Refresh\"content=\"0; URL=index.php\">";

}


if(isset($_POST['email'])){

  $getbrands=mysql_query("select * from users where `email`='".$_POST['email']."' AND `brand`='".$_POST['brand']."'");
  $getbrandsrow=mysql_fetch_array($getbrands);

  $_SESSION['userid'] = $getbrandsrow['id'];
  $_SESSION['brand'] = $_POST['brand'];

  $getbrand =mysql_query("select * from brands where ref='".$_POST['brand']."'");
  $getbrandrow=mysql_fetch_array($getbrand);

//  header('Location: index.php');
  echo "<meta http-equiv=\"Refresh\"content=\"0; URL=index.php\">";

}
?>

