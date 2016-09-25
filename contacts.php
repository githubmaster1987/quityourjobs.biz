<? 

session_start();

if(isset($_SESSION['userid'])){
	
	require_once("variables.php");
		
	$getuser = mysql_query("select * from users where `id`='".$_SESSION['userid']."'");
        $getuserrow=mysql_fetch_array($getuser);
	
	$spotid = $getuserrow['spotid'];
        $email= $getuserrow['email'];
	$password = $getuserrow['password'];
        //echo $spotid.$password;
	
	$getbrand =mysql_query("select * from brands where ref='".$_SESSION['brand']."'");
        $getbrandrow=mysql_fetch_array($getbrand);
        //echo $getbrandrow['ref'];
	$title='Quit Your Jobs Contact Us at '.$getbrandrow['name'];

        $getcbrand=mysql_query("SELECT * FROM `country` WHERE `iso`='GB'") or die(mysql_error());
	$getcbrandrow=mysql_fetch_array($getcbrand);

if(isset($_POST['sendmessage'])){

// multiple recipients
	$to  = 'support@quityourjobs.biz';

	// subject
	$subject = $_POST['subject'];

	// message
	$message = $_POST['message'];

	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Additional headers
	$headers .= 'From: '.$_POST['name'].' <'.$_POST['email'].'>' . "\r\n";


	// Mail it
	mail($to, $subject, $message, $headers);



}

require_once('include/header.php'); ?>

<!-- BEGIN PAGE -->  
<style>
.collection{
 list-style-type: square;
 font-size: 13px;
}

.collection-item{
 margin-bottom: 7px;
}

.collection-item .badge{
float:right;
}

.howtotrade{
 text-align: center;
 text-transform: uppercase;
}

.howtotrade h3{
    font-weight: bold;
    color: #FFFFFF;
    letter-spacing: 2px;
}

.hometopwidget{
border-bottom: 2px solid #fff;
}

.hometopwidgetbody{
 background: #DE577B;
 color: #fff;
}

.alert-homes{
 background: #4090db;
 color: #fff;
}

.table-advance thead tr th {
    background-color: #4A8BC2;
    color: #FFF;
    line-height: 25px;
    text-align: center;
}

.table-advance tr td {
    border-left-width: 0px;
    vertical-align: middle;
    text-align: center;
}

.widget-title {
    background: #4A8BC2;
    height: 39px;
}

.msgsent{
    float: right;
    font-size: 20px;
    color: #74B749;
    margin-right: 25%;
}

</style>

<div id="main-content" style="margin-left: 0px; margin-bottom: 0px;">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
           <div class="row-fluid">
               <div class="row bg-title bredcrums-menu">
                 <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                     <h4 class="page-title">Contact Us</h4>
                 </div>
                 <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                     <ol class="breadcrumb">
                         <li><a href="index.php">Home</a></li>
                         <li class="active breadcrumb-active">Contact Us</li>
                     </ol>
                 </div>
                 <!-- /.col-lg-12 -->
             </div>
           </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
		<div class="row-fluid">
		<div class="section" style="min-height: 515px;">
                   <div class="row-fluid">
                     <div class="feedback">
                         <form class="form-inline" method="POST">
			<div class="control-group">
			<label class="control-label">Name:</label>
			<div class="controls">
			<input type="text" class="span6  popovers" data-trigger="hover" name="name" required data-content="Please Enter Your Full Name." data-original-title="Enter Name">
			</div>
			</div>

			<div class="control-group">
			<label class="control-label">Email:</label>
			<div class="controls">
			<input type="email" class="span6  popovers" data-trigger="hover" name="email" required data-content="Please Enter Your Email." data-original-title="Enter Email">
			</div>
			</div>

			<div class="control-group">
			<label class="control-label">Subject:</label>
			<div class="controls">
			<input type="text" class="span6  popovers" name="subject" required data-trigger="hover" data-content="Please Enter Subject." data-original-title="Enter Subject">
			</div><?php if(isset($_POST['sendmessage'])){ echo '<p class="msgsent"> Message sent! </p>'; } ?>
			</div>


 

                             <div class="control-group">
                                <label class="control-label">Message:</label>
                                <div class="controls">
                                    <textarea class="span6" name="Message:" required rows="5"></textarea>
                                </div>
                            </div>
                             <div class="text-center">
                                 <button class="btn btn-large btn-success" name="sendmessage" type="submit" style="margin-right: 120px;">Submit</button>
			      </div>
				
                         </form>
                     </div>
                 </div>
               </div>
             <!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>	
	
      <!-- END PAGE -->  

<?php require_once('include/footer.php'); ?>

<?php
if($_SESSION['is_demo'])
echo "<script type='text/javascript' async='async' defer='defer' data-cfasync='false' src='https://mylivechat.com/chatinline.aspx?hccid=71364593'></script>";

}
else {
	echo  "<meta http-equiv=\"Refresh\"content=\"0; URL=login.php\">";
 	exit;
}
?>


