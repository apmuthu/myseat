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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html lang="<?php echo $language; ?>">
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
<!-- Meta data for SEO -->
<meta id="htmlTagMetaDescription" name="Description" content="Make online reservationsfor lunch and dinners. mySeat is a OpenSource online reservation system for restaurants." />
<meta id="htmlTagMetaKeyword" name="Keyword" content="restaurant reservations, online restaurant reservations, restaurant management software, mySeat, free tables" />
<meta name="robots" content="all,follow" />
<meta name="author" lang="en" content="Bernd Orttenburger [www.myseat.us]" />
<meta name="copyright" lang="en" content="mySeat [www.myseat.us]" />
<meta name="keywords" content="mySeat, table reservation system, Bookings Diary, Reservation Diary, Restaurant Reservations, restaurant reservation system, open source, software, reservation management software, restaurant table management, table planner, restaurant table planner, table management, hotel" />
<!-- Website Title --> 
<title>Reservation</title>

<!-- Template stylesheet -->
<link rel="stylesheet" href="css/screen.css" type="text/css" media="all"/>
<link href="../contactform/style/datepicker.css" rel="stylesheet" type="text/css" />

<!-- jQuery Library-->
<script src="../contactform/js/jQuery.min.js"></script>
<script src="../contactform/js/jquery.easing.1.3.js"></script>
<script src="../contactform/js/jquery-ui.js" type="text/javascript" ></script> 
<script src="../contactform/js/functions.js"></script>

</head>
<body>
	<!-- Begin page wrapper -->
	<div id="wrapper">
		
		<div id="top_bar">
			<div class="inner">
				<h1>
					<a href="<? echo $website; ?>">
						<? echo $prp_info['name'];?>
					</a>
				</h1>
			</div>
			<div class="home">
				<a href="index.php">
					<img src="images/icon_home.png" alt="" width="40" height="40"/>
				</a>
			</div>
		</div>
		<br/><br/>
		<div id="content_wrapper">
			<div class="inner">
				<h2 class="header"><?= $lang["conf_title"];?></h2>
				<p>
					<?php
				    lang("conf_intro"); 
				    echo " ".$outlet_name." ".$lang["_at_"]." ".buildDate($general['dateformat'],$sd,$sm,$sy)." ".$lang["_at_"]." ".$bookingtime;
				  ?>
				</p>
			</div>
			<br class="clear"/>
			<hr/>	
			<div class="inner">
				<p>
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
						// Do booking!
						$waitlist = processBooking();
				      }
				      // CSRF - Secure forms with token
				      $barrier = md5(uniqid(rand(), true)); 
				      $_SESSION['barrier'] = $barrier;

				      if($waitlist == 2){
						echo "<div class='alert_success'><p>
								<img src='images/icon_accept.png' alt='success' class='middle'/>";
						echo $lang['contact_form_success']." ".$_SESSION['booking_number']."<br>";
						echo "</p></div>";
				      }else if ($waitlist == 1){
						echo "<div class='alert_error' style='margin-bottom:20px'><p>
								<img src='images/icon_error.png' alt='delete'' class='middle'/>";
						echo $lang['contact_form_full']."<br>";
						echo "</p></div>";
				      }else{
							echo "<div class='alert_error' style='margin-bottom:20px'><p>
									<img src='images/icon_error.png' alt='delete'' class='middle'/>";
						echo $lang['contact_form_fail']."<br>";
						echo "</p></div>";
				      }

						$_SESSION['messages'] = array();

				    }

				  ?>
				</p>
			<br/><br/>
			<div class="pagination"><small>
			&copy; 2010 by <a href="http://www.myseat.us" target="_blank">mySeat</a> 
			under the GPL license, designed for easy  & free restaurant reservations.
			<small></div>
			<br/><br/>
		</div>
	</div>
	<!-- End page wrapper -->
</body>
</html>