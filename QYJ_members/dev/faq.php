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
	$title='Self-made Millionaire System account details at '.$getbrandrow['name'];

        $getcbrand=mysql_query("SELECT * FROM `country` WHERE `iso`='GB'") or die(mysql_error());
	$getcbrandrow=mysql_fetch_array($getcbrand);


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

#accordion a, #accordion a:hover {
    text-shadow: none !important;
    color: #B94A48;
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
                     <h4 class="page-title">FAQ'S</h4>
                 </div>
                 <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                     <ol class="breadcrumb">
                         <li><a href="index.php">Home</a></li>
                         <li class="active breadcrumb-active">faq</li>
                     </ol>
                 </div>
                 <!-- /.col-lg-12 -->
             </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
		<div class="row-fluid">
                 <div class="span12">
                     <!-- BEGIN BLANK PAGE PORTLET-->
                     <div class="">                       
                         <div class="">
                             <div class="row-fluid">
                                 
                                 <div class="span12">
                                     
                                     <div id="accordion" class="accordion in collapse" style="height: auto;">

                                         <div class="accordion-group">
                                             <div class="accordion-heading">
                                                 <a href="#collapseOne" data-parent="#accordion" data-toggle="collapse" class="accordion-toggle">
                                                    <b>Question:</b> How can I get started?
                                                 </a>
                                             </div>
                                             <div class="accordion-body collapse" id="collapseOne" style="height: 0px;">
                                                 <div class="accordion-inner">
                                                   <b> Answer:</b> Follow the steps below to get start making profits using Self-Made Millionaire (SMM) System:<br>

							<br>- First, click on <b>'Deposit'</b> button in the left hand side menu and make a small deposit of $200 to $250 to activate the SMM System.<br>

							<br>- Then, click on <b>'Dashboard'</b> , you will see the deposited amount in the right hand side against <b>'Balance'</b>  under 'Account Summary' <b></b> .<br>

							<br>- Then logout the SMM System and login again. That's it, you are ready to make profits.<br>

							<br>- The simplest way to make profits hands free is <b>AutoTrading</b> . We have activated AutoTrading by default. If you see <b>'ON'</b>  button against <b>'ACTIVATE AUTO TRADING'</b>  in Blue color that means AutoTrading is active and $25 selected as the amount of trade. But you can change this amount if you wish. With AutoTrading ON, our system picks the signals automatically provided by our team of market analysts and places the trades on your behalf so that you have all the time to enjoy your life with your loved ones while the system is working for you and making profits for you without needing you to be in front of computer all the time.<br>

							<br>- If in any case, you do not prefer AutoTrading, you can trade manually following the detailed signals provided by our team of market analysts which will appear on bottom half of 'Dashboard' <b></b> . What you have to do is select the amount of trade from popup menu and click of the Orange color 'Trade'<b></b>  button under <b>'Investment'</b> .<br>

							<br>- In any case if you even do not want to follow our signals and want to trade independently directly on the Trading Platform, you can do so by clicking the <b>'Platform Trading'</b>  button in the left hand side menu.<br>

							<br>That’s it… it is very simple to make money using SMM system, even a newbie can do it without being an expert of financial trading and all. <br>
                                                 </div>
                                             </div>

                                         </div>
                                         <div class="accordion-group">
                                             <div class="accordion-heading">
                                                 <a href="#collapseTwo" data-parent="#accordion" data-toggle="collapse" class="accordion-toggle">
                                                     <b>Question:</b>  Is this opportunity available in my country?
                                                 </a>
                                             </div>
                                             <div class="accordion-body collapse" id="collapseTwo">
                                                 <div class="accordion-inner">
                                                  <b> Answer:</b> If you are inside SMM system and reading this FAQ, that confirms you are 100% eligible and this opportunity is available in your country. Individuals from the blocked countries are not even able to fill the sign up form.<br>
                                                 </div>
                                             </div>
                                         </div>
                                         <div class="accordion-group">
                                             <div class="accordion-heading">
                                                 <a href="#collapseThree" data-parent="#accordion" data-toggle="collapse" class="accordion-toggle">
                                                    <b>Question:</b>  How much does Self-Made Millionaire (SMM) System costs?
                                                 </a>
                                             </div>
                                             <div class="accordion-body collapse" id="collapseThree">
                                                 <div class="accordion-inner">
                                                    <b> Answer:</b> SMM system is absolutely free.<br>


                                                 </div>
                                             </div>
                                         </div>
                                         <div class="accordion-group">
                                             <div class="accordion-heading">
                                                 <a href="#collapseFour" data-parent="#accordion" data-toggle="collapse" class="accordion-toggle">
                                                     <b>Question:</b>  Do I need to have previous experience with binary options trading in order to use SMM?
                                                 </a>
                                             </div>
                                             <div class="accordion-body collapse" id="collapseFour">
                                                 <div class="accordion-inner">
                                                    <b> Answer:</b> No... No experience or knowledge is required to make money using SMM system. SMM system is designed keeping newbies in the mind. Whether you are a newbie or a seasoned/experienced trader, you can make profits alike. Just follow the simple steps explained above in the answer of a question <b>"How can I get started?"</b>and you are ready to make profits day in and day out.<br>
                                                 </div>
                                             </div>
                                         </div>
                                         <div class="accordion-group">
                                             <div class="accordion-heading">
                                                 <a href="#collapseFive" data-parent="#accordion" data-toggle="collapse" class="accordion-toggle">
                                                     <b>Question:</b>  Do I need to download any other software in order to make money with SMM?
                                                 </a>
                                             </div>
                                             <div class="accordion-body collapse" id="collapseFive">
                                                 <div class="accordion-inner">
                                                    <b> Answer:</b> No... You do not need to download anything. Everything has been included within the SMM system to make money and it is 100% web based so no need to download anything on your computer.<br>
                                                 </div>
                                             </div>
                                         </div>
                                         <div class="accordion-group">
                                             <div class="accordion-heading">
                                                 <a href="#collapseSix" data-parent="#accordion" data-toggle="collapse" class="accordion-toggle">
                                                    <b>Question:</b>  What is the minimum deposit amount?
                                                 </a>
                                             </div>
                                             <div class="accordion-body collapse" id="collapseSix">
                                                 <div class="accordion-inner">
                                                    <b> Answer:</b> The minimum deposit amount is $250 but with few trading platforms it is only $200, you need to check it out while making a deposit.<br>
<br>(Don't forget, the deposited amount is your own money in your trading account. It is not at all an investment of any kind within our system and this will only be used within our system to place trades. The deposited amount and the profits made by you are 100% your to keep which you can withdraw at any time by clicking the withdraw button.)<br>
                                                 </div>
                                             </div>
                                         </div>
                                         <div class="accordion-group">
                                             <div class="accordion-heading">
                                                 <a href="#collapseSeven" data-parent="#accordion" data-toggle="collapse" class="accordion-toggle">
                                                   <b>Question:</b>  Can I start without money?
                                                 </a>
                                             </div>
                                             <div class="accordion-body collapse" id="collapseSeven">
                                                 <div class="accordion-inner">
                                                  <b> Answer:</b> No, it is not possible. SMM is a top quality trading system which allows newbies and seasoned/experienced traders alike to make profits trading options, assets, currency pairs etc. We have already allowed you to use our system 100% free, just to prove this system works. But to make this system work for you, you need to have some money in your trading account to trade with and make profits out of it. That’s why it is required to at least make a minimum deposit in your trading account with the trading platform associated with SMM system.<br>
                                                 </div>
                                             </div>
                                         </div>
                                         <div class="accordion-group">
                                             <div class="accordion-heading">
                                                 <a href="#collapse8" data-parent="#accordion" data-toggle="collapse" class="accordion-toggle">
                                                    <b>Question:</b>  Can I start with $25?
                                                 </a>
                                             </div>
                                             <div class="accordion-body collapse" id="collapse8">
                                                 <div class="accordion-inner">
                                                    <b> Answer:</b> Yes, you can start trading with $25 which is the minimum amount you can trade with. But before that you should have funded your trading account with the minimum deposit amount of $200 to $250 with the trading platform associated with SMM system. <br>
                                                 </div>
                                             </div>
                                         </div>

                                         <div class="accordion-group">
                                             <div class="accordion-heading">
                                                 <a href="#collapse9" data-parent="#accordion" data-toggle="collapse" class="accordion-toggle">
                                                    <b>Question:</b>  If I'm a free member so why I have to deposit?
                                                 </a>
                                             </div>
                                             <div class="accordion-body collapse" id="collapse9">
                                                 <div class="accordion-inner">
                                                    <b> Answer:</b> We have allowed you to use our system 100% free, just to prove this system works. But to make this system work for you, you need to have some money in your trading account to trade with and make profits out of it. That’s why it is required to at least make a minimum deposit in your trading account with the trading platform associated with SMM system. Without having some money in your trading account to trade with and make profits, there is no use of SMM system and it will not work either.<br>
                                                 </div>
                                             </div>
                                         </div>

                                         <div class="accordion-group">
                                             <div class="accordion-heading">
                                                 <a href="#collapse10" data-parent="#accordion" data-toggle="collapse" class="accordion-toggle">
                                                   <b>Question:</b>  How can I make a deposit into my trading account?
                                                 </a>
                                             </div>
                                             <div class="accordion-body collapse" id="collapse10">
                                                 <div class="accordion-inner">
                                                   <b> Answer:</b> Just click on 'Deposit' button in the left hand side menu and make a small deposit of $200 to $250 to activate the SMM System.<br>
                                                 </div>
                                             </div>
                                         </div>

                                         <div class="accordion-group">
                                             <div class="accordion-heading">
                                                 <a href="#collapse11" data-parent="#accordion" data-toggle="collapse" class="accordion-toggle">
                                                    <b>Question:</b>  How do I withdraw my profits from my trading account?
                                                 </a>
                                             </div>
                                             <div class="accordion-body collapse" id="collapse11">
                                                 <div class="accordion-inner">
                                                    <b> Answer:</b> Just click on 'Withdraw' button in the left hand side menu and follow the simple instructions to withdraw your profits.<br>
                                                 </div>
                                             </div>
                                         </div>

                                         <div class="accordion-group">
                                             <div class="accordion-heading">
                                                 <a href="#collapse12" data-parent="#accordion" data-toggle="collapse" class="accordion-toggle">
                                                   <b>Question:</b>  How much I can make in starting?
                                                 </a>
                                             </div>
                                             <div class="accordion-body collapse" id="collapse12">
                                                 <div class="accordion-inner">
                                                   <b> Answer:</b> Good question... we can't predict how much you can earn at the start as it varies from person to person and depends on various factors but one thing is sure that you will be making 70% to 91% of the investment per trade as a profit with 70% to 90% success. And we are more that 100% confident that if you follow the simple steps explained above in the answer of a question "How can I get started?”, you will start making profits within half an hour from now.<br>
                                                 </div>
                                             </div>
                                         </div>

                                         <div class="accordion-group">
                                             <div class="accordion-heading">
                                                 <a href="#collapse13" data-parent="#accordion" data-toggle="collapse" class="accordion-toggle">
                                                   <b>Question:</b>  Is this program legit?
                                                 </a>
                                             </div>
                                             <div class="accordion-body collapse" id="collapse13">
                                                 <div class="accordion-inner">
                                                   <b> Answer:</b> Yes, definitely. SMM system is designed to make money online using Binary Options in an easy way… even a newbie can make money online using this system like 1-2-3.<br>
                                                 </div>
                                             </div>
                                         </div>


                              <div class="accordion-group">
                                             <div class="accordion-heading">
                                                 <a href="#collapse14" data-parent="#accordion" data-toggle="collapse" class="accordion-toggle">
                                                   <b>Question:</b>  What is the minimum investment amount per trade?
                                                 </a>
                                             </div>
                                             <div class="accordion-body collapse" id="collapse14">
                                                 <div class="accordion-inner">
                                                  <b> Answer:</b> It is $25<br>
                                                 </div>
                                             </div>
                                         </div>

                              <div class="accordion-group">
                                             <div class="accordion-heading">
                                                 <a href="#collapse15" data-parent="#accordion" data-toggle="collapse" class="accordion-toggle">
                                                  <b>Question:</b>  What is autotrading?
                                                 </a>
                                             </div>
                                             <div class="accordion-body collapse" id="collapse15">
                                                 <div class="accordion-inner">
                                                   <b> Answer:</b> This is the simplest way to make profits hands free using SMM system. With AutoTrading ON, our system picks the signals automatically provided by our team of market analysts and places the trades on your behalf so that you have all the time to enjoy your life with your loved ones while the system is working for you and making profits for you without needing you to be in front of computer all the time.<br>
                                                 </div>
                                             </div>
                                         </div>




                              <div class="accordion-group">
                                             <div class="accordion-heading">
                                                 <a href="#collapse16" data-parent="#accordion" data-toggle="collapse" class="accordion-toggle">
                                                   <b>Question:</b>  My question is not answered here, what do I do?
                                                 </a>
                                             </div>
                                             <div class="accordion-body collapse" id="collapse16">
                                                 <div class="accordion-inner">
                                                   <b> Answer:</b> No problem, we are always here to help you out.<br>

<br>- First thing you can do... contact our <b>LIVE Chat Support</b>. Even if, the chat support is not live, we can receive your query that you have submitted while contacting live support and we will reply to you by email.<br>

<br>- Next you can contact our <b>Telephonic Support</b>. This is the <b>Best</b> way to get your queries answered instantly. You can find the Support Number at the left hand side, below <b>'Logout'</b> button.<br>

<br>- You can contact our <b>email Support</b> at <b>“ support@selfmademillionaires.biz "</b> . But make sure, it may take upto 48 hours to get replies. So it is better to contact our LIVE Chat Support or Telephonic Support for instant response.<br>
                                                 </div>
                                             </div>
                                         </div>


                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <!-- END BLANK PAGE PORTLET-->
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


