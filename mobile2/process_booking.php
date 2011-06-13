<?php session_start();

// reset single outlet indicator
$_SESSION['single_outlet'] = 'OFF';

$_SESSION['role'] = 6;
$_SESSION['language'] = 'en_EN';

// PHP part of page / business logic
// ** set configuration
	require("../contactform/config.php");
	include('../config/config.general.php');
// ** business functions
	require('../contactform/includes/business.class.php');
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
	$_SESSION['outletID'] = ($_GET['outletID']) ? (int)$_GET['outletID'] : querySQL('standard_outlet');
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
<html> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
	<!-- Meta data for SEO -->
	<meta id="htmlTagMetaDescription" name="Description" content="Make online reservationsfor lunch and dinners. mySeat is a OpenSource online reservation system for restaurants." />
	<meta id="htmlTagMetaKeyword" name="Keyword" content="restaurant reservations, online restaurant reservations, restaurant management software, mySeat, free tables" />
	<meta name="robots" content="all,follow" />
	<meta name="author" lang="en" content="Bernd Orttenburger [www.myseat.us]" />
	<meta name="copyright" lang="en" content="mySeat [www.myseat.us]" />
	<meta name="keywords" content="mySeat, table reservation system, Bookings Diary, Reservation Diary, Restaurant Reservations, restaurant reservation system, open source, software, reservation management software, restaurant table management" />

	<!-- Website Title --> 
	<title>Table Reservation</title> 

	<!-- jQuery Library-->
	<link rel="stylesheet" href="jqmobile/jquery.mobile-1.0a4.1.min.css" />
	<script type="text/javascript" src="jqmobile/jquery-1.6.1.min.js"></script>
	<script type="text/javascript" src="jqmobile/jquery.mobile-1.0a4.1.min.js"></script>

</head>
<body>

<div data-role="page" data-theme="c">

	<div data-role="header" data-theme="e">
		<h1><? echo $prp_info['name'];?></h1>
		<a href='index.php' class='ui-btn-left ui-btn-back' data-direction="reverse" data-icon='arrow-l'>Back</a>
		<a href="<? echo $website; ?>" data-icon="home" data-iconpos="notext" data-direction="reverse" class="ui-btn-right home">Home</a>
	</div><!-- /header -->

	<div data-role="content" data-theme="c">
			
			<h2><?= $lang["conf_title"];?></h2>
			<p>
			  <?php
			    // =-=-=-=-=-=-=-=-=-=-=
			    //  Process the Booking
			    // =-=-=-=-=-=-=-=-=-=-= 

			      // CSRF - Secure forms with token
			      if ($_SESSION['barrier'] == $_POST['barrier']) {
					// Do booking!
					$waitlist = processBooking();
			      }
			      // CSRF - Secure forms with token
			      $barrier = md5(uniqid(rand(), true)); 
			      $_SESSION['barrier'] = $barrier;

			      if($waitlist == 2){
					echo "<h3>".$lang['contact_form_success']." ".$_SESSION['booking_number']."</h3>";
			      }else if ($waitlist == 1){
					echo "<h3>".$lang['contact_form_full']."</h3>";
			      }else{
					echo "<h3>".$lang['contact_form_fail']."</h3>";
			      }

					$_SESSION['messages'] = array();

			  ?>
			</p>
	
	</div><!-- /content -->
	<div data-role="footer">
		<h6>&copy; 2010 by mySeat</h6>
	</div><!-- /footer -->
</div><!-- /page -->

</body>
</html>