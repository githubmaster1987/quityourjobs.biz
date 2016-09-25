<!DOCTYPE html>
<html lang="en">

<!--================================================================================
	Item Name: Materialize - Material Design Admin Template
	Version: 1.0
	Author: GeeksLabs
	Author URL: http://www.themeforest.net/user/geekslabs
================================================================================ -->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Register | Self-made Millionaire System</title>

    <!-- Favicons-->
    <link rel="icon" href="images/favicon/favicon-32x32.png" sizes="32x32">
    <!-- Favicons-->
    <link rel="apple-touch-icon-precomposed" href="images/favicon/apple-touch-icon-152x152.png">
    <!-- For iPhone -->
    <meta name="msapplication-TileColor" content="#00bcd4">
    <meta name="msapplication-TileImage" content="images/favicon/mstile-144x144.png">
    <!-- For Windows Phone -->
    <!-- jQuery Library -->
    <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>


    <!--materialize js-->
    <!--<script type="text/javascript" src="js/materialize.js"></script>-->
    <!--prism-->
    <script type="text/javascript" src="js/prism.js"></script>
    <!--scrollbar-->
    <script type="text/javascript" src="js/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>

    <!--plugins.js - Some Specific JS codes for Plugin Settings-->
  <!--  <script type="text/javascript" src="js/plugins.js"></script>-->

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
          integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
            integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
            crossorigin="anonymous"></script>

    <!-- CORE CSS-->

    <!--<link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">-->

    <!--  <link href="css/page-center.css" type="text/css" rel="stylesheet" media="screen,projection">-->

    <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
    <link href="css/prism.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="js/plugins/perfect-scrollbar/perfect-scrollbar.css" type="text/css" rel="stylesheet"
          media="screen,projection">

    <style>

        #login-page {
            width: 360px;
            height: 385px;
            /*margin-top: -0.5%;*/
        }

        #country_code {
            width: 20%;
            float: left;
        }

        #phone {
            float: right;
            width: 80%;
            margin-left: 0px;
        }

        .phonelabel {
            padding-left: 22%;
        }

        /*.login-form input[type=text], input[type=password], input[type=email] {
            margin: 0 0 3px 0;
            margin-left: 3rem;
            border-bottom: 1px solid #FFF;
        }*/

        .r {
            border: 2px solid yellow;
            height: 3em;
            font-weight: bolder;
            -webkit-border-radius:0;
            -moz-border-radius:0;
            border-radius:0;
        }




        .card-panel {
            padding: 10px 20px;
        }

        .input-field .prefix {
            margin-top: 8px;
        }

        .input-field {
            position: relative;
            margin-top: 0rem;
        }

        .login-form .input-field:not(:first-child){
            margin-top: 15px;
        }

        .login-form .btn-success {
            font-weight: bolder;
            font-size: 20px;

        }

        .input-field label.active {

            top: 0.8rem;
            left: 0.75rem;
            font-size: 1rem;
            -webkit-transform: translateY(-140%);
            -moz-transform: translateY(-140%);
            -ms-transform: translateY(-140%);
            -o-transform: translateY(-140%);
            transform: translateY(-140%);
            opacity: 0;
        }

        .input-field label {
            color: #9e9e9e;
            position: absolute;
            font-size: 1rem;
            cursor: text;
            -webkit-transition: 0.2s ease-out;
            -moz-transition: 0.2s ease-out;
            -o-transition: 0.2s ease-out;
            -ms-transition: 0.2s ease-out;
            transition: 0.2s ease-out;
        }

        .noticemessage {
            text-align: center;
            margin-top: 5px;
            font-weight: bold;
        }

        html i {
            color: #FFF;
        }

        .input-field label {
            color: #FFF;
        }

        [type="checkbox"] + label:before {
            border: 2px solid #FFF;
        }

       /* input[type=text], input[type=password], input[type=email], input[type=url], input[type=time], input[type=date], input[type=datetime-local], input[type=tel], input[type=number], input[type=search], textarea.materialize-textarea {
            color: #FFF;
        }*/

        .btn.focus, .btn:focus, .btn:hover {
            color: #FFF;
        }

    </style>
</head>

<body class="">




<div id="login-page" class="">
    <div class="col s12">
        <form class="login-form" action="registermaster.php" method="post" name="register" id="register" target="_top">

            <div class="row margin">

                <div class="col-md-12 input-field">
                    <!--<i class="mdi-social-person-outline prefix"></i>-->
                    <input id="fname" type="text" name="fname" class="form-control r" required value="" placeholder="First Name" pattern="^[A-z]+$" oninvalid="setCustomValidity('Please enter your correct name')"
                           onchange="try{setCustomValidity('')}catch(e){}" />
                    <input id="param" type="hidden" name="param" value="">
                   <!-- <label for="fname" class="">First Name</label>-->
                </div>

                <div class="col-md-12 input-field">
                    <!--<i class="mdi-social-person-outline prefix"></i>-->
                    <input id="lname" type="text" name="lname" class="form-control r" required value="" placeholder="Last Name" pattern="^[A-z]+$" oninvalid="setCustomValidity('Please enter your correct name')"
                           onchange="try{setCustomValidity('')}catch(e){}" />
                    <!--<label for="lname" class="">Last Name</label>-->
                </div>

                <div class="col-md-12 input-field">
                    <!--<i class="mdi-communication-email prefix"></i>-->
                    <input id="email" type="email" name="email" required value="" class="form-control r" placeholder="Email" />
                    <!--<label for="email" class="">Email</label>-->
                </div>

                <div class="col-md-12 input-field ">
                    <!--<i class="mdi-action-lock-outline prefix"></i>-->
                    <input id="password" type="password" name="password" value="" required class="form-control r" placeholder="Password" />
                    <!--<label for="password" class="">Password</label>-->
                </div>

                <div class="col-md-12 input-field ">
                    <!--<i class="mdi-action-lock-outline prefix"></i>-->
                    <input id="confirm_password" type="password" name="confirm_password" value="" required class="form-control r" placeholder="Password again" />
                    <!--<label for="confirm_password" class="">Password again</label>-->
                </div>

                <div class="col-md-12 input-field ">
                   <!-- <i class="mdi-action-language prefix"></i>-->
                   <!-- <input type="text" name="country" id="country" value="" readonly class="form-control r"> -->


                    <select name="country" id="country" class="form-control r">
                        <option>Country</option>
                                                    <option
                                                                value="1">AFGHANISTAN</option>';
                                                        <option
                                                                value="2">ALBANIA</option>';
                                                        <option
                                                                value="3">ALGERIA</option>';
                                                        <option
                                                                value="4">AMERICAN SAMOA</option>';
                                                        <option
                                                                value="5">ANDORRA</option>';
                                                        <option
                                                                value="6">ANGOLA</option>';
                                                        <option
                                                                value="7">ANGUILLA</option>';
                                                        <option
                                                                value="8">ANTARCTICA</option>';
                                                        <option
                                                                value="9">ANTIGUA AND BARBUDA</option>';
                                                        <option
                                                                value="10">ARGENTINA</option>';
                                                        <option
                                                                value="11">ARMENIA</option>';
                                                        <option
                                                                value="12">ARUBA</option>';
                                                        <option
                                                                value="13">AUSTRALIA</option>';
                                                        <option
                                                                value="14">AUSTRIA</option>';
                                                        <option
                                                                value="15">AZERBAIJAN</option>';
                                                        <option
                                                                value="16">BAHAMAS</option>';
                                                        <option
                                                                value="17">BAHRAIN</option>';
                                                        <option
                                                                value="18">BANGLADESH</option>';
                                                        <option
                                                                value="19">BARBADOS</option>';
                                                        <option
                                                                value="20">BELARUS</option>';
                                                        <option
                                                                value="21">BELGIUM</option>';
                                                        <option
                                                                value="22">BELIZE</option>';
                                                        <option
                                                                value="23">BENIN</option>';
                                                        <option
                                                                value="24">BERMUDA</option>';
                                                        <option
                                                                value="25">BHUTAN</option>';
                                                        <option
                                                                value="26">BOLIVIA</option>';
                                                        <option
                                                                value="27">BOSNIA AND HERZEGOVINA</option>';
                                                        <option
                                                                value="28">BOTSWANA</option>';
                                                        <option
                                                                value="29">BOUVET ISLAND</option>';
                                                        <option
                                selected="selected"                                 value="30">BRAZIL</option>';
                                                        <option
                                                                value="31">BRITISH INDIAN OCEAN TERRITORY</option>';
                                                        <option
                                                                value="32">BRUNEI DARUSSALAM</option>';
                                                        <option
                                                                value="33">BULGARIA</option>';
                                                        <option
                                                                value="34">BURKINA FASO</option>';
                                                        <option
                                                                value="35">BURUNDI</option>';
                                                        <option
                                                                value="36">CAMBODIA</option>';
                                                        <option
                                                                value="37">CAMEROON</option>';
                                                        <option
                                                                value="38">CANADA</option>';
                                                        <option
                                                                value="39">CAPE VERDE</option>';
                                                        <option
                                                                value="40">CAYMAN ISLANDS</option>';
                                                        <option
                                                                value="41">CENTRAL AFRICAN REPUBLIC</option>';
                                                        <option
                                                                value="42">CHAD</option>';
                                                        <option
                                                                value="43">CHILE</option>';
                                                        <option
                                                                value="44">CHINA</option>';
                                                        <option
                                                                value="45">CHRISTMAS ISLAND</option>';
                                                        <option
                                                                value="46">COCOS (KEELING) ISLANDS</option>';
                                                        <option
                                                                value="47">COLOMBIA</option>';
                                                        <option
                                                                value="48">COMOROS</option>';
                                                        <option
                                                                value="49">CONGO</option>';
                                                        <option
                                                                value="50">CONGO, THE DEMOCRATIC REPUBLIC OF THE</option>';
                                                        <option
                                                                value="51">COOK ISLANDS</option>';
                                                        <option
                                                                value="52">COSTA RICA</option>';
                                                        <option
                                                                value="53">COTE D'IVOIRE</option>';
                                                        <option
                                                                value="54">CROATIA</option>';
                                                        <option
                                                                value="55">CUBA</option>';
                                                        <option
                                                                value="56">CYPRUS</option>';
                                                        <option
                                                                value="57">CZECH REPUBLIC</option>';
                                                        <option
                                                                value="58">DENMARK</option>';
                                                        <option
                                                                value="59">DJIBOUTI</option>';
                                                        <option
                                                                value="60">DOMINICA</option>';
                                                        <option
                                                                value="61">DOMINICAN REPUBLIC</option>';
                                                        <option
                                                                value="62">ECUADOR</option>';
                                                        <option
                                                                value="63">EGYPT</option>';
                                                        <option
                                                                value="64">EL SALVADOR</option>';
                                                        <option
                                                                value="65">EQUATORIAL GUINEA</option>';
                                                        <option
                                                                value="66">ERITREA</option>';
                                                        <option
                                                                value="67">ESTONIA</option>';
                                                        <option
                                                                value="68">ETHIOPIA</option>';
                                                        <option
                                                                value="69">FALKLAND ISLANDS (MALVINAS)</option>';
                                                        <option
                                                                value="70">FAROE ISLANDS</option>';
                                                        <option
                                                                value="71">FIJI</option>';
                                                        <option
                                                                value="72">FINLAND</option>';
                                                        <option
                                                                value="73">FRANCE</option>';
                                                        <option
                                                                value="74">FRENCH GUIANA</option>';
                                                        <option
                                                                value="75">FRENCH POLYNESIA</option>';
                                                        <option
                                                                value="76">FRENCH SOUTHERN TERRITORIES</option>';
                                                        <option
                                                                value="77">GABON</option>';
                                                        <option
                                                                value="78">GAMBIA</option>';
                                                        <option
                                                                value="79">GEORGIA</option>';
                                                        <option
                                                                value="80">GERMANY</option>';
                                                        <option
                                                                value="81">GHANA</option>';
                                                        <option
                                                                value="82">GIBRALTAR</option>';
                                                        <option
                                                                value="83">GREECE</option>';
                                                        <option
                                                                value="84">GREENLAND</option>';
                                                        <option
                                                                value="85">GRENADA</option>';
                                                        <option
                                                                value="86">GUADELOUPE</option>';
                                                        <option
                                                                value="87">GUAM</option>';
                                                        <option
                                                                value="88">GUATEMALA</option>';
                                                        <option
                                                                value="89">GUINEA</option>';
                                                        <option
                                                                value="90">GUINEA-BISSAU</option>';
                                                        <option
                                                                value="91">GUYANA</option>';
                                                        <option
                                                                value="92">HAITI</option>';
                                                        <option
                                                                value="93">HEARD ISLAND AND MCDONALD ISLANDS</option>';
                                                        <option
                                                                value="94">HOLY SEE (VATICAN CITY STATE)</option>';
                                                        <option
                                                                value="95">HONDURAS</option>';
                                                        <option
                                                                value="96">HONG KONG</option>';
                                                        <option
                                                                value="97">HUNGARY</option>';
                                                        <option
                                                                value="98">ICELAND</option>';
                                                        <option
                                                                value="99">INDIA</option>';
                                                        <option
                                                                value="100">INDONESIA</option>';
                                                        <option
                                                                value="101">IRAN, ISLAMIC REPUBLIC OF</option>';
                                                        <option
                                                                value="102">IRAQ</option>';
                                                        <option
                                                                value="103">IRELAND</option>';
                                                        <option
                                                                value="104">ISRAEL</option>';
                                                        <option
                                                                value="105">ITALY</option>';
                                                        <option
                                                                value="106">JAMAICA</option>';
                                                        <option
                                                                value="107">JAPAN</option>';
                                                        <option
                                                                value="108">JORDAN</option>';
                                                        <option
                                                                value="109">KAZAKHSTAN</option>';
                                                        <option
                                                                value="110">KENYA</option>';
                                                        <option
                                                                value="111">KIRIBATI</option>';
                                                        <option
                                                                value="112">KOREA, DEMOCRATIC PEOPLE'S REPUBLIC OF</option>';
                                                        <option
                                                                value="113">KOREA, REPUBLIC OF</option>';
                                                        <option
                                                                value="114">KUWAIT</option>';
                                                        <option
                                                                value="115">KYRGYZSTAN</option>';
                                                        <option
                                                                value="116">LAO PEOPLE'S DEMOCRATIC REPUBLIC</option>';
                                                        <option
                                                                value="117">LATVIA</option>';
                                                        <option
                                                                value="118">LEBANON</option>';
                                                        <option
                                                                value="119">LESOTHO</option>';
                                                        <option
                                                                value="120">LIBERIA</option>';
                                                        <option
                                                                value="121">LIBYAN ARAB JAMAHIRIYA</option>';
                                                        <option
                                                                value="122">LIECHTENSTEIN</option>';
                                                        <option
                                                                value="123">LITHUANIA</option>';
                                                        <option
                                                                value="124">LUXEMBOURG</option>';
                                                        <option
                                                                value="125">MACAO</option>';
                                                        <option
                                                                value="126">MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF</option>';
                                                        <option
                                                                value="127">MADAGASCAR</option>';
                                                        <option
                                                                value="128">MALAWI</option>';
                                                        <option
                                                                value="129">MALAYSIA</option>';
                                                        <option
                                                                value="130">MALDIVES</option>';
                                                        <option
                                                                value="131">MALI</option>';
                                                        <option
                                                                value="132">MALTA</option>';
                                                        <option
                                                                value="133">MARSHALL ISLANDS</option>';
                                                        <option
                                                                value="134">MARTINIQUE</option>';
                                                        <option
                                                                value="135">MAURITANIA</option>';
                                                        <option
                                                                value="136">MAURITIUS</option>';
                                                        <option
                                                                value="137">MAYOTTE</option>';
                                                        <option
                                                                value="138">MEXICO</option>';
                                                        <option
                                                                value="139">MICRONESIA, FEDERATED STATES OF</option>';
                                                        <option
                                                                value="140">MOLDOVA, REPUBLIC OF</option>';
                                                        <option
                                                                value="141">MONACO</option>';
                                                        <option
                                                                value="142">MONGOLIA</option>';
                                                        <option
                                                                value="143">MONTSERRAT</option>';
                                                        <option
                                                                value="144">MOROCCO</option>';
                                                        <option
                                                                value="145">MOZAMBIQUE</option>';
                                                        <option
                                                                value="146">MYANMAR</option>';
                                                        <option
                                                                value="147">NAMIBIA</option>';
                                                        <option
                                                                value="148">NAURU</option>';
                                                        <option
                                                                value="149">NEPAL</option>';
                                                        <option
                                                                value="150">NETHERLANDS</option>';
                                                        <option
                                                                value="151">NETHERLANDS ANTILLES</option>';
                                                        <option
                                                                value="152">NEW CALEDONIA</option>';
                                                        <option
                                                                value="153">NEW ZEALAND</option>';
                                                        <option
                                                                value="154">NICARAGUA</option>';
                                                        <option
                                                                value="155">NIGER</option>';
                                                        <option
                                                                value="156">NIGERIA</option>';
                                                        <option
                                                                value="157">NIUE</option>';
                                                        <option
                                                                value="158">NORFOLK ISLAND</option>';
                                                        <option
                                                                value="159">NORTHERN MARIANA ISLANDS</option>';
                                                        <option
                                                                value="160">NORWAY</option>';
                                                        <option
                                                                value="161">OMAN</option>';
                                                        <option
                                                                value="162">PAKISTAN</option>';
                                                        <option
                                                                value="163">PALAU</option>';
                                                        <option
                                                                value="164">PALESTINIAN TERRITORY, OCCUPIED</option>';
                                                        <option
                                                                value="165">PANAMA</option>';
                                                        <option
                                                                value="166">PAPUA NEW GUINEA</option>';
                                                        <option
                                                                value="167">PARAGUAY</option>';
                                                        <option
                                                                value="168">PERU</option>';
                                                        <option
                                                                value="169">PHILIPPINES</option>';
                                                        <option
                                                                value="170">PITCAIRN</option>';
                                                        <option
                                                                value="171">POLAND</option>';
                                                        <option
                                                                value="172">PORTUGAL</option>';
                                                        <option
                                                                value="173">PUERTO RICO</option>';
                                                        <option
                                                                value="174">QATAR</option>';
                                                        <option
                                                                value="175">REUNION</option>';
                                                        <option
                                                                value="176">ROMANIA</option>';
                                                        <option
                                                                value="177">RUSSIAN FEDERATION</option>';
                                                        <option
                                                                value="178">RWANDA</option>';
                                                        <option
                                                                value="179">SAINT HELENA</option>';
                                                        <option
                                                                value="180">SAINT KITTS AND NEVIS</option>';
                                                        <option
                                                                value="181">SAINT LUCIA</option>';
                                                        <option
                                                                value="182">SAINT PIERRE AND MIQUELON</option>';
                                                        <option
                                                                value="183">SAINT VINCENT AND THE GRENADINES</option>';
                                                        <option
                                                                value="184">SAMOA</option>';
                                                        <option
                                                                value="185">SAN MARINO</option>';
                                                        <option
                                                                value="186">SAO TOME AND PRINCIPE</option>';
                                                        <option
                                                                value="187">SAUDI ARABIA</option>';
                                                        <option
                                                                value="188">SENEGAL</option>';
                                                        <option
                                                                value="189">SERBIA AND MONTENEGRO</option>';
                                                        <option
                                                                value="190">SEYCHELLES</option>';
                                                        <option
                                                                value="191">SIERRA LEONE</option>';
                                                        <option
                                                                value="192">SINGAPORE</option>';
                                                        <option
                                                                value="193">SLOVAKIA</option>';
                                                        <option
                                                                value="194">SLOVENIA</option>';
                                                        <option
                                                                value="195">SOLOMON ISLANDS</option>';
                                                        <option
                                                                value="196">SOMALIA</option>';
                                                        <option
                                                                value="197">SOUTH AFRICA</option>';
                                                        <option
                                                                value="198">SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS</option>';
                                                        <option
                                                                value="199">SPAIN</option>';
                                                        <option
                                                                value="200">SRI LANKA</option>';
                                                        <option
                                                                value="201">SUDAN</option>';
                                                        <option
                                                                value="202">SURINAME</option>';
                                                        <option
                                                                value="203">SVALBARD AND JAN MAYEN</option>';
                                                        <option
                                                                value="204">SWAZILAND</option>';
                                                        <option
                                                                value="205">SWEDEN</option>';
                                                        <option
                                                                value="206">SWITZERLAND</option>';
                                                        <option
                                                                value="207">SYRIAN ARAB REPUBLIC</option>';
                                                        <option
                                                                value="208">TAIWAN, PROVINCE OF CHINA</option>';
                                                        <option
                                                                value="209">TAJIKISTAN</option>';
                                                        <option
                                                                value="210">TANZANIA, UNITED REPUBLIC OF</option>';
                                                        <option
                                                                value="211">THAILAND</option>';
                                                        <option
                                                                value="212">TIMOR-LESTE</option>';
                                                        <option
                                                                value="213">TOGO</option>';
                                                        <option
                                                                value="214">TOKELAU</option>';
                                                        <option
                                                                value="215">TONGA</option>';
                                                        <option
                                                                value="216">TRINIDAD AND TOBAGO</option>';
                                                        <option
                                                                value="217">TUNISIA</option>';
                                                        <option
                                                                value="218">TURKEY</option>';
                                                        <option
                                                                value="219">TURKMENISTAN</option>';
                                                        <option
                                                                value="220">TURKS AND CAICOS ISLANDS</option>';
                                                        <option
                                                                value="221">TUVALU</option>';
                                                        <option
                                                                value="222">UGANDA</option>';
                                                        <option
                                                                value="223">UKRAINE</option>';
                                                        <option
                                                                value="224">UNITED ARAB EMIRATES</option>';
                                                        <option
                                                                value="225">UNITED KINGDOM</option>';
                                                        <option
                                                                value="226">UNITED STATES</option>';
                                                        <option
                                                                value="227">UNITED STATES MINOR OUTLYING ISLANDS</option>';
                                                        <option
                                                                value="228">URUGUAY</option>';
                                                        <option
                                                                value="229">UZBEKISTAN</option>';
                                                        <option
                                                                value="230">VANUATU</option>';
                                                        <option
                                                                value="231">VENEZUELA</option>';
                                                        <option
                                                                value="232">VIET NAM</option>';
                                                        <option
                                                                value="233">VIRGIN ISLANDS, BRITISH</option>';
                                                        <option
                                                                value="234">VIRGIN ISLANDS, U.S.</option>';
                                                        <option
                                                                value="235">WALLIS AND FUTUNA</option>';
                                                        <option
                                                                value="236">WESTERN SAHARA</option>';
                                                        <option
                                                                value="237">YEMEN</option>';
                                                        <option
                                                                value="238">ZAMBIA</option>';
                                                        <option
                                                                value="239">ZIMBABWE</option>';
                            
                    </select>



                </div>

                <div class="col-md-12 input-field ">
                    <!--<i class="mdi-communication-call prefix"></i>-->
                    <input id="country_code" type="text" name="country_code" value="+55" readonly class="form-control r" />
                    <input id="phone" type="text" name="phone" value="" required onkeypress="return isNumber(event)" class="form-control r" />
                    <!--<label for="phone" class="phonelabel">Phone number</label>-->
                </div>

                <div class="col-md-12 input-field ">
                    <button type="submit" name="register" class="btn waves-effect waves-light btn-success btn-block" onClick="">Register
                        Now
                    </button>
                </div>

            </div>


            <!--<div class="row">
                <div class="col-xs-12" style="text-align: center;">
                    <button type="submit" name="register" class="btn waves-effect waves-light btn-success btn-block" onClick="">Register
                        Now
                    </button>
                </div>


            </div>-->
        </form>
    </div>
</div>


<!-- ================================================
  Scripts
  ================================================ -->
<script>

    $(document).ready(function(){
        $("#country").change(function(){

            var country = $("#country").val();

            $.post("dialer.php", {country: country}, function(data){$("#country_code").val(data);});
        });

    });


    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

  /*  $("#fname").focusout(function () {
        var fname = $("#fname").val();
        if (this.value.length <= 1) {
            //alert("Please fill out your First Name");
        }
    });

    $("#lname").focusout(function () {
        var lname = $("#lname").val();
        if (this.value.length <= 1) {
            //alert("Please fill out your Last Name");
        }
    });

    $("#email").focusout(function () {
        if (!this.value.match(/^[-a-zA-Z0-9_+.]+@[A-Za-z0-9-]+?\.[A-Za-z_0-9.]{2,10}$/)) {
            //alert("Please enter valid email address");
            //$(this).focus();
        }
    });

    $("#password").focusout(function () {
        var text = new RegExp('[A-Za-z]');
        var numbers = new RegExp('[0-9]');

        if (!($(this).val().match(text) && $(this).val().match(numbers))) {
            //alert("Password should be Alphanumeric, e.g - abc123. No special characters e.g. - @/$%#^*!");
            //$("#password").focus();
            //$(this).focus();
        }
    });


    $("#confirm_password").focusout(function () {
        confirm_password = $("#confirm_password").val();
        password = $("#password").val();
        if (password != confirm_password) {
            //alert("Confirm password not matched.");
            console.log(0);
            $(this).val('');
        }
    });*/

    window.onload = function () {
        document.getElementById("password").onchange = validatePassword;
        document.getElementById("confirm_password").onchange = validatePassword;
    }
    function validatePassword(){
        var pass2=document.getElementById("confirm_password").value;
        var pass1=document.getElementById("password").value;
        var alpReg = /^[A-z]+$/;
        var regNum = /^[0-9]+$/;
        //console.log(regNum.test(pass1));
        if(regNum.test(pass1)){
            document.getElementById("password").setCustomValidity("Please Enter atleast one alphabets");
        } else if(alpReg.test(pass1)){
            document.getElementById("password").setCustomValidity("Please Enter atleast one Number");
        }else {
            document.getElementById("password").setCustomValidity("");
        }
        if(pass1!=pass2)
            document.getElementById("confirm_password").setCustomValidity("Passwords Don't Match");
        else
            document.getElementById("confirm_password").setCustomValidity('');
//empty string means no validation error
    }
    // Support: Safari, iOS Safari, default Android browser
    document.querySelector( "form" ).addEventListener( "submit", function( event ) {
        if ( !this.checkValidity() ) {
            event.preventDefault();
        }
    });


</script>


</body>

</html>
