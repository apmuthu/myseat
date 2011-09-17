<?php session_start();

// reset single outlet indicator
$_SESSION['single_outlet'] = 'OFF';

$_SESSION['role'] = 6;
$_SESSION['language'] = 'en_EN';

// PHP part of page / business logic
	include('../config/config.general.php');
// ** business functions
	require('business.class.php');
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
	
    <!-- jQuery Library-->
    <script src="js/jQuery.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui.js"></script> 

    <title><?php echo _reservations;?></title>
</head>
<body>
<div id="page-content" style="margin-left: 10px;">
	    <!-- page title -->
	    <h2><?php echo _reservations;?><span></span> </h2>
		<br class="cl" />
	    
	    <div id="page-content" class="container_12">
		
		<!-- page content goes here -->
			<br/>
			<span id="result">
			  <?php
			
			    // =-=-=-=-=-=-=-=-=-=-=
			    //  Process the Booking
			    // =-=-=-=-=-=-=-=-=-=-=

			      // CSRF - Secure forms with token
			      if ($_SESSION['barrier'] == $_POST['barrier']) {
					// check if no neccessary field is empty
					if (isset($_POST['dbdate']) &&
					isset($_POST['reservation_pax']) &&
					isset($_POST['reservation_time']) &&
					isset($_POST['reservation_guest_name']) &&
					isset($_POST['reservation_guest_email']) &&
					$_POST['terms'] == 'YES' ) {
						// <Do booking>
						$waitlist = processBooking();
					}else{
						$waitlist = 0;
					}
			      }
			      // CSRF - Secure forms with token
			      $barrier = md5(uniqid(rand(), true)); 
			      $_SESSION['barrier'] = $barrier;
			      
			      if($waitlist == 2){
					echo "<div class='alert_success'><p><img src='../web/images/icons/icon_accept.png' alt='success' class='middle'/>&nbsp;&nbsp;";
				    echo _email_subject." ".$outlet_name.", ".buildDate($general['dateformat'],$sd,$sm,$sy)." ".$bookingtime."<br/>";
					echo _contact_form_success." <strong>".$_SESSION['booking_number']."</strong><br/>";
					echo "</p></div>";
			      }else{
					echo "<div class='alert_error'><p><img src='../web/images/icon_error.png' alt='error' class='middle'/>&nbsp;&nbsp;";
					echo _sorry."<br/>";
					echo "</p></div>";
			      }
			
					$_SESSION['messages'] = array();
			  
			  ?>
                	</span>
	    <br class="cl" />

		</div><!-- page content end -->
</div><!-- main close -->

</body>
</html>