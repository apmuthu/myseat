<?php session_start();

// reset single outlet indicator
$_SESSION['single_outlet'] = 'OFF';

$_SESSION['role'] = 6;
$_SESSION['language'] = 'en_EN';

// PHP part of page / business logic
// ** set configuration
	require("config.php");
	include('../config/config.general.php');
// ** business functions
	require('includes/business.class.php');
// ** database functions
	include('../web/classes/database.class.php');
// ** localization functions
	include('../web/classes/local.class.php');
// ** business functions
	include('../web/classes/business.class.php');
// ** connect to database
	include('../web/classes/connect.db.php');
// ** all database queries
	include('../web/classes/db_queries.db.php');
// ** set configuration
	include('../config/config.inc.php');
// translate to selected language
	translateSite($_POST['email_type'],'../web/');
// ** get superglobal variables
	include('../web/includes/get_variables.inc.php');
// ** get property info for logo path
$prp_info = querySQL('property_info');

// Get POST data	
   // outlet id
    if (!$_SESSION['outletID']) {
	$_SESSION['outletID'] = ($_GET['outletID']) ? (int)$_GET['outletID'] : querySQL('web_standard_outlet');
    }elseif ($_GET['id']) {
        $_SESSION['outletID'] = (int)$_GET['id'];
    }elseif ($_POST['id']) {
        $_SESSION['outletID'] = (int)$_POST['id'];
    }
    // property id
    if ($_GET['prp']) {
        $_SESSION['property'] = (int)$_GET['prp'];
    }elseif ($_POST['prp']) {
        $_SESSION['property'] = (int)$_POST['prp'];
    }
    // selected date
    if ($_GET['selectedDate']) {
        $_SESSION['selectedDate'] = $_GET['selectedDate'];
    }elseif ($_POST['selectedDate']) {
        $_SESSION['selectedDate'] = $_POST['selectedDate'];
    }elseif ($_POST['dbdate']) {
        $_SESSION['selectedDate'] = $_POST['dbdate'];
    }elseif (!$_SESSION['selectedDate']){
        //$_SESSION['selectedDate'] = date('Y-m-d');
    }

  //prepare selected Date
    list($sy,$sm,$sd) = explode("-",$_SESSION['selectedDate']);
  
  // get Pax by timeslot
    $resbyTime = reservationsByTime();
  // get availability by timeslot
    $availability = getAvailability($resbyTime,$general['timeintervall']);
  // some constants
    $bookingdate = date($general['dateformat'],strtotime($_POST['dbdate']));
    $bookingtime = formatTime($_POST['reservation_time'],$general['timeformat']);
    $outlet_name = querySQL('db_outlet');
    //$_SESSION['booking_number'] = '';
  
  //The subject of the confirmation email
  $subject = $lang["email_subject"]." ".$outlet_name;
  //Email address of the confirmation email
  $mailTo = $_POST['reservation_guest_email'];
?>

<!DOCTYPE html>
<html lang="<?php echo $language; ?>">
<head>
    <meta charset="utf-8"/>

	<!-- CSS - Setup -->
	<link href="style/style.css" rel="stylesheet" type="text/css" />
	<link href="style/base.css" rel="stylesheet" type="text/css" />
	<link href="style/grid.css" rel="stylesheet" type="text/css" />
	<!-- CSS - Theme -->
	<link id="theme" href="style/themes/<?php echo $default_style;?>.css" rel="stylesheet" type="text/css" />
	<link id="color" href="style/themes/<?php echo $general['contactform_color_scheme'];?>.css" rel="stylesheet" type="text/css" />
	<style type="text/css">
		body {
			background: #FFFFFF;
			color: #484747 !important;
		}
		h2 {
			font-family: Georgia,"Times New Roman",Times,serif !important;
			font-size: 21px !important;
		}
		h3 {
			font-family: Tahoma,Geneva,sans-serif !important;
			font-weight: normal !important;
			font-size: 21px !important;
		}
		p {
	    	font-family: Tahoma,Geneva,sans-serif !important;
	    	font-size: 13px !important;
	    	line-height: 1.3em !important;
		}
		form label {
			font-size: 13px !important;
	    	line-height: 1.3em !important;
		}
	</style>
	
    <!-- jQuery Library-->
    <script src="js/jQuery.min.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="js/jquery-ui.js"></script> 
    <script src="js/functions.js"></script>
    

	<!--[if IE 6]>
		<script src="js/DD_belatedPNG.js"></script>
		<script>
			DD_belatedPNG.fix('#togglePanel, .logo img, #mainBottom, #twitter, ul li, #searchForm, .footerLogo img, .social img');
		</script>
	<![endif]--> 

    <title>Reservation</title>
</head>
<body>
	<!-- page container -->
			<div class='langnav'>		
				<ul class="nav">
				<li>
					<a href="index.php"><?php lang("contact_form_back");?></a> | 
					<a href="cancel.php?p=2"><?php echo lang("contact_form_cxl");?></a>
				</li>
				</ul>
			</div>
	    <!-- page title -->
	    <h2><?php echo $lang["conf_title"];?><span></span> </h2>
		<br class="cl" />
	    
	    <div id="page-content" class="container_12">
		
		<!-- page content goes here -->

			<h4>
			  <?php
			    lang("conf_intro"); 
			    echo " ".$outlet_name.", ".$lang["_at_"]." ".buildDate($general['dateformat'],$sd,$sm,$sy)." ".$bookingtime;
			  ?>
			</h4>
			<br/>
			<span id="result">
			  <?php
			    // =-=-=-=-=-=-=-=-=-=-=
			    //  Process the Booking
			    // =-=-=-=-=-=-=-=-=-=-=
			    
			    // Check the captcha
			    $field1 = intval($_POST['captchaField1']);
			    $operator = $_POST['captchaField2'];
			    $field3 = intval($_POST['captchaField3']);
			    
			    $operator = ($operator == "+") ? true : false;
			    $correct = $operator ? $field1+$field3 : $field1-$field3; 
			    
			    if($_POST['captcha'] == $correct){
			      // CSRF - Secure forms with token
			      if ($_SESSION['barrier'] == $_POST['barrier']) {
					// <Do booking>
					$waitlist = processBooking();
			      }
			      // CSRF - Secure forms with token
			      $barrier = md5(uniqid(rand(), true)); 
			      $_SESSION['barrier'] = $barrier;
			      
			      if($waitlist == 2){
					echo "<div class='alert_success'><p><img src='../web/images/icons/icon_accept.png' alt='success' class='middle'/>&nbsp;&nbsp;";
					echo $lang['contact_form_success']." ".$_SESSION['booking_number']."<br>";
					echo "</p></div>";
			      }else if ($waitlist == 1){
					echo "<div class='alert_error'><p><img src='../web/images/icon_error.png' alt='error' class='middle'/>&nbsp;&nbsp;";
					echo $lang['contact_form_full']."<br>";
					echo "</p></div>";
			      }else{
					echo "<div class='alert_error'><p><img src='../web/images/icon_error.png' alt='error' class='middle'/>&nbsp;&nbsp;";
					echo $lang['contact_form_fail']."<br>";
					echo "</p></div>";
			      }
			
					$_SESSION['messages'] = array();
			    
			    }
			  
			  ?>
                	</span>
			<br/>
			<a href="index.php"><button class="button <?php echo $default_color;?>" ><?php echo $lang["contact_form_back"];?></button></a>
			<br/>
	    <br class="cl" />

		</div><!-- page content end -->
</div><!-- main close -->

</body>
</html>