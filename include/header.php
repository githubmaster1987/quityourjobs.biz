<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title><?php if(!empty($title)){ echo $title; }else{ echo 'Quit Your Jobs'; } ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="description" />
    <meta content="Mosaddek" name="author" />

    <!-- Favicons-->
    <link rel="icon" href="images/favicon/favicon-32x32.png" sizes="32x32">
    <!-- Favicons-->
    <link rel="apple-touch-icon-precomposed" href="images/favicon/apple-touch-icon-152x152.png">
    <!-- For iPhone -->
    <meta name="msapplication-TileColor" content="#00bcd4">
    <meta name="msapplication-TileImage" content="images/favicon/mstile-144x144.png">
    <!-- For Windows Phone -->

    <link href="assets/bootstrap/css/bootstrap-fileupload.css" rel="stylesheet" />
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/font-linea/basic/font-linea-basic.css" rel="stylesheet" />
    <link href="assets/font-linea/software/font-linea-software.css" rel="stylesheet" />
    <link href="assets/font-linea/arrows/font-linea-arrows.css" rel="stylesheet" />
    <link href="assets/themify/themify-icons.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
    <link href="css/style-responsive.css" rel="stylesheet" />
    <link href="assets/fullcalendar/fullcalendar/bootstrap-fullcalendar.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/sample.css" />
    <link rel="stylesheet" href="css/elite/animate.css" />
    <link rel="stylesheet" href="css/elite/megna.css" />
    <link rel="stylesheet" href="css/elite/style.css" />
    
    <script src="js/jquery-1.9.0.min.js"></script>
    <!-- <msdropdown> -->
    <link rel="stylesheet" type="text/css" href="css/dd.css" />
    <script src="js/jquery.dd.js"></script>
    <!-- </msdropdown> -->
    <link rel="stylesheet" type="text/css" href="css/skin2.css" />


    <?php if($getuserrow['deposit_status']=='DEMO'){ echo '<script type="text/javascript" async="async" defer="defer" data-cfasync="false" src="https://mylivechat.com/chatinline.aspx?hccid=71364593"></script>'; } ?>


    <link href="js/plugins/perfect-scrollbar/perfect-scrollbar.css" type="text/css" rel="stylesheet" media="screen,projection">
    <script type="text/javascript" src="assets/jquery-slimscroll/jquery-ui-1.9.2.custom.min.js"></script>
    <script type="text/javascript" src="assets/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/fullcalendar/fullcalendar/fullcalendar.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.ddslick.min.js"></script>
        <script src="js/jquery.sparkline.min.js"></script>
    <script src="js/jquery.charts-sparkline.js"></script>

    <!-- ie8 fixes -->
    <!--[if lt IE 9]>
    <script src="js/excanvas.js"></script>
    <script src="js/respond.js"></script>
    <![endif]-->


    <script>
        $(document).ready(function(e) {
            $("#payments").msDropdown({visibleRows:4});

            $( "#payments" ).change(function() {
                $( "#changebroker" ).submit();
            });
        });

    </script>



    <style>

        .supportnum {
            color: #ffb400;
            font-weight: bold;
            margin-bottom: -12px;
            padding: 10px;
        }
        .supportnumber {
            color: #fff;
            font-size: 15px;
            margin-left: 25px;
        }
        .leftpopup {
            background: #c56767 none repeat scroll 0 0;
            border-radius: 5px;
            bottom: 5px;
            box-shadow: 0 25px 10px -15px rgba(0, 0, 0, 0.05);
            color: #fff;
            font-size: 15px;
            height: 130px;
            left: -400px;
            position: fixed;
            text-align: center;
            transition: all 1s ease 0s;
            width: 350px;
            z-index: 200;
        }
        .rightpopup {
            background: #4a8bc2 none repeat scroll 0 0;
            border-radius: 5px;
            box-shadow: 0 25px 10px -15px rgba(0, 0, 0, 0.05);
            color: #fff;
            font-size: 15px;
            height: 38px;
            position: fixed;
            right: -400px;
            text-align: center;
            top: 125px;
            transition: all 1s ease 0s;
            width: 320px;
            z-index: 200;
        }
        .signalshow {
            left: 0;
        }
        .tradeshow {
            right: 0;
        }
        .button-0 {
            background-color: #74b749;
            border-bottom: 3px solid #74b749;
            border-radius: 10px;
            color: #fff;
            font-family: "Helvetica",cursive;
            font-size: 14px;
            margin-top: 2px;
            padding: 5px 10px;
            position: relative;
            text-decoration: none;
            text-shadow: 0 -2px #74b749;
            transition: all 0.1s ease 0s;
        }
        .button-0:hover, .button-0:focus {
            color: #fff;
            text-decoration: none;
        }
        .button-0:active {
            border-bottom: 1px solid;
            transform: translate(0px, 5px);
        }
        .headershow {
            background: #74b749 none repeat scroll 0 0;
            font-weight: bold;
            margin-bottom: 5px;
            padding: 7px;
        }
        #main-content {
            margin-bottom: 5px;
        }
        .tradeboxtext {
            font-weight: bold;
        }
        .showtext {
            margin-bottom: 5px;
        }
        .helpershow {
            background: #fcfbe3 none repeat scroll 0 0;
            color: #468847;
            display: none;
            font-size: 15px;
            line-height: 20px;
            padding: 10px;
            position: fixed;
            text-align: left;
            text-transform: capitalize;
            width: 270px;
            z-index: 100;
        }
        .helpershow::before {
            border-color: transparent transparent #fcfbe3;
            border-style: solid;
            border-width: 10px;
            bottom: 100%;
            content: " ";
            height: 0;
            margin-right: -9px;
            position: absolute;
            width: 0;
        }
        .laststep {
            background: #fcfbe3 none repeat scroll 0 0;
            bottom: 60px;
            color: #468847;
            display: none;
            font-size: 15px;
            line-height: 20px;
            padding: 10px;
            position: fixed;
            right: 45px;
            text-align: left;
            text-transform: capitalize;
            width: 270px;
            z-index: 100;
        }
        .laststep::after {
            border-color: #fcfbe3 transparent transparent;
            border-style: solid;
            border-width: 10px;
            content: " ";
            height: 0;
            position: absolute;
            right: 30px;
            top: 100%;
            width: 0;
        }
        .helperbtn {
            background: rgb(6, 156, 90) none repeat scroll 0 0;
            color: #fff;
            cursor: pointer;
            float: right;
            font-family: monospace;
            font-size: 15px;
            font-weight: bold;
            line-height: 30px;
            margin-left: 5px;
            margin-right: 5px;
            margin-top: 10px;
            padding: 0 8px;
            text-align: center;
        }
        .helperbtn:hover {
            background: rgba(6, 156, 90, 0.71) none repeat scroll 0 0;
        }
        .margin50 {
            margin-left: 50px;
        }
        #sidebar > ul > li {
            border-bottom: 1px solid #fff;
        }
        #sidebar > ul > li:first-of-type {
            border-bottom: 1px solid #fff;
            border-top: 1px solid #fff;
        }
                .videoiframe {
            min-height: 540px;
            width: 100%;
        }
        .modal .modal-body {
            overflow: hidden;
        }
        @media screen and (min-width: 767px) and (max-width: 90000px) {
            .modal {
                left: 31%;
                width: 1050px;
            }
            .modal.fade.in {
                bottom: 10%;
                top: 5%;
            }
            .modal .modal-body {
                max-height: 800px;
                padding: 0;
            }
        }
        #myModal {
            text-align: right;
        }
        .buttonclose {
            background: #00bcd4 none repeat scroll 0 0;
            color: #fff;
            font-size: 11px;
            margin-right: -11px;
            margin-top: -22px;
            padding: 0 7px;
        }
        .header-balance {
	   font-weight: bold;
           margin-right: 43px;
           margin-top: 18px;
        }
    </style>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="">
<?php 
    $currentUri = $_SERVER['REQUEST_URI'];
    $page = basename($currentUri);
?>
<!-- Fixed left Box to show signals -->
<div id="signalbox" class="leftpopup">

</div>

<!-- Fixed left Box to show signals End -->

<!-- Fixed Right Box to show signals -->
<div id="tradebox" class="rightpopup">
    <h4 class="tradeboxtext"> Please Wait..</h4>
</div>

<!-- Fixed Right Box to show signals End -->


<div class="wrapper">
<!-- BEGIN HEADER -->
<nav class="navbar navbar-default navbar-static-top m-b-0">
    <div class="navbar-header active"> <a data-target=".navbar-collapse" data-toggle="collapse" href="javascript:void(0)" class="navbar-toggle hidden-sm hidden-md hidden-lg "><i class="ti-menu"></i></a>
        <div class="top-left-part">
            <a href="index.php" class="logo custom-logo">
                <b><img alt="home" src="img/logo.png" width="160"></b>
            </a>
        </div>
        
        <ul class="nav navbar-top-links navbar-right pull-right in">
            <li class="header-balance">Balance: $<?php echo number_format(floatval($_SESSION['balance']), 2); ?></li>
            <li class="dropdown active"> <a href="#" data-toggle="dropdown" class="dropdown-toggle profile-pic active"> <img width="36" class="img-circle" alt="user-img" src="http://quityourjobs.biz/members/images/avatar-login.png"><b class="hidden-xs"><?php echo $getuserrow['name']; ?></b> </a>
                <ul class="dropdown-menu dropdown-user animated flipInY in">
                    <li><a href="account.php" class="active"><i class="ti-user"></i> My Profile</a></li>
                    <li class="divider" role="separator"></li>
                    <li><a href="logout.php" class="active"><i class="fa fa-power-off"></i> Logout</a></li>
                </ul>
                <!-- /.dropdown-user -->
            </li>
            <li class=""> <a href="javascript:void(0)" style="cursor: auto;"></a></li>
            <!-- /.dropdown -->
        </ul>
    </div>
    <!-- /.navbar-header -->
    <!-- /.navbar-top-links -->
    <!-- /.navbar-static-side -->
    <div class="clear" style="height:0px !important;">&nbsp;</div>
</nav>
<!-- END HEADER -->
<!-- BEGIN CONTAINER -->
<div id="container" class="row-fluid custom-container">
    <!-- BEGIN SIDEBAR -->
    <div role="navigation" class="navbar-default sidebar" style="overflow: inherit;">
        <div class="sidebar-nav navbar-collapse slimscrollsidebar active in" aria-expanded="true" style="">
            <ul id="side-menu" class="nav in">
                <li class="active">
                    <a class="waves-effect <?php echo ($page == 'index.php')?"active":""?>" href="index.php">
                        <i data-icon="v" class="linea-icon linea-basic fa-fw"></i>
                        <span class="hide-menu"> Dashboard <span class="fa arrow"></span> </span>
                    </a>
                </li>
                <li>
                    <a class="waves-effect <?php echo ($page == 'deposit.php')?"active":""?>" href="deposit.php">
                        <i class="linea-icon linea-basic fa-fw" data-icon=")"></i>
                        <span class="hide-menu">Deposit <span class="fa arrow"></span></span>
                    </a>
                </li>
                <li>
                    <a class="waves-effect <?php echo ($page == 'opentrades.php')?"active":""?>" href="opentrades.php">
                    <i class="linea-icon linea-basic fa-fw" data-icon="/"></i>
                    <span class="hide-menu">Open Trades<span class="fa arrow"></span></span>
                    </a>
                </li>
                <li> 
                    <a class="waves-effect <?php echo ($page == 'closetrades.php')?"active":""?>" href="closetrades.php">
                    <i class="linea-icon linea-basic fa-fw" data-icon=""></i>
                    <span class="hide-menu">Close Trades<span class="fa arrow"></span></span></a>
                </li>
                <li> 
                    <a class="waves-effect <?php echo ($page == 'account.php')?"active":""?>" href="account.php">
                    <i class="linea-icon linea-software linea-basic fa-fw" data-icon="O"></i>
                    <span class="hide-menu">Account Details<span class="fa arrow"></span></span>
                    </a>
                </li>
                <li>
                    <a class="waves-effect <?php echo ($page == 'withdraw.php')?"active":""?>" href="withdraw.php">
                    <i class="linea-icon linea-basic fa-fw" data-icon="&#xe008;"></i>
                    <span class="hide-menu">Withdraw<span class="fa arrow"></span></span>
                    </a>
                </li>
                <li> 
                    <a class="waves-effect <?php echo ($page == 'trading-area.php')?"active":""?>" href="trading-area.php">
                        <i class="linea-icon linea-software linea-basic fa-fw" data-icon="F"></i>
                        <span class="hide-menu">Trading Area<span class="fa arrow"></span></span>
                    </a>
                </li>
                <li>
                    <a class="waves-effect <?php echo ($page == 'contacts.php')?"active":""?>" href="contacts.php">
                        <i class="linea-icon linea-basic linea-aerrow fa-fw" data-icon="P"></i>
                        <span class="hide-menu">Contact Us</span>
                    </a>
                </li>
                <li>
                <a class="waves-effect intro" id="header-introduction" href="javascript:void(0)">
                        <i class="linea-icon linea-aerrow fa-fw" data-icon="&#xe027;"></i>
                        <span class="hide-menu">Introduction</span>
                    </a>
                </li>
                <li>
                    <a class="waves-effect" href="logout.php">
                        <i class="linea-icon linea-basic linea-aerrow fa-fw" data-icon="9"></i>
                        <span class="hide-menu">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- END SIDEBAR -->
    </div>

