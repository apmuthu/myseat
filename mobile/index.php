<?php session_start();
//error_reporting(E_ALL & ~E_NOTICE);
//ini_set("display_errors", 1);

$_SESSION['role'] = 6;
$_SESSION['language'] = 'en_EN';
$_SESSION['outletID'] = '';

// PHP part of page / business logic
// ** set configuration
	require("../contactform/config.php");

	include('../config/config.general.php');
// ** business functions
	require('../contactform/includes/business.class.php');
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

// get and define referer
	$ref = getHost($_SERVER['HTTP_REFERER']);
	$_SESSION['referer'] = ($_SESSION['referer']!='') ? $_SESSION['referer'] : $ref;

// property id
   if ($_GET['prp']) {
       $_SESSION['property'] = (int)$_GET['prp'];
   }elseif ($_POST['prp']) {
       $_SESSION['property'] = (int)$_POST['prp'];
   }elseif ($_SESSION['property']==''){
	$_SESSION['property'] = 1;
}
$_SESSION['propertyID'] = $_SESSION['property'];

//standard outlet for contact form
	if ($_GET['outletID']) {
		$_SESSION['outletID'] = (int)$_GET['outletID'];
	}else if ($_SESSION['outletID'] !='' ){
		$_SESSION['outletID'] = querySQL('web_standard_outlet');
	}

	if ($_GET['so']=='ON') {
		// set single outlet indicator
		$_SESSION['single_outlet'] = 'ON';	
	}else{
		// reset single outlet indicator
		$_SESSION['single_outlet'] = 'OFF';	
	}
	
	if ($_GET['times']) {
		// set selected time
		$time = $_GET['times'].":00";	
	}

	// Get POST data	
    // outlet id
    if (!$_SESSION['outletID']) {
		$_SESSION['outletID'] = ($_GET['outletID']) ? (int)$_GET['outletID'] : querySQL('standard_outlet');
    }
    
    if ($_GET['outletID']) {
		$_SESSION['outletID'] = (int)$_GET['outletID'];
    }else {
    	$_SESSION['outletID'] = querySQL('standard_outlet');
    }
	
	// get property info for logo path
	$prp_info = querySQL('property_info');
	
	if (strtolower(substr($prp_info['website'],0,4)) =="http") {
		$website = $prp_info['website'];
	}else{
		$website = "http://".$prp_info['website'];
	}

	// selected date
    if ($_GET['selectedDate']) {
        $_SESSION['selectedDate'] = $_GET['selectedDate'];
    }
	
	// +++ memorize selected outlet details; maybe moved reservation +++
	$rows = querySQL('db_outlet_info');
	if($rows){
		foreach ($rows as $key => $value) {
			$_SESSION['selOutlet'][$key] = $value;
		}
	}
	
	// ** get superglobal variables
		include('../web/includes/get_variables.inc.php');
	// CSRF - Secure forms with token
		$barrier = md5(uniqid(rand(), true)); 
		$_SESSION['barrier'] = $barrier;
	

	// ** set configuration
	include('../config/config.inc.php');
	
  	//prepare selected Date
    list($sy,$sm,$sd) = explode("-",$_SESSION['selectedDate']);
  
	// get outlet maximum capacity
	$maxC = maxCapacity();
	 
	// get Pax by timeslot
    $resbyTime = reservationsByTime('pax');
    $tblbyTime = reservationsByTime('tbl');
	$_SESSION['passbyTime'] = reservationsByTime('pass');

    // get availability by timeslot
    $availability = getAvailability($resbyTime,$general['timeintervall']);
    $tbl_availability = getAvailability($tblbyTime,$general['timeintervall']);

  // some constants
    $outlet_name = querySQL('db_outlet');

  // translate to selected language
	$_SESSION['language'] = $language;
	translateSite(substr($language,0,2),'../web/');
?>
<!DOCTYPE html> 
<html> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<!-- Meta data for SEO -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
	<meta http-equiv="X-UA-Compatible" content="IE=8" />
	<meta name="robots" content="follow,index,no-cache" />
	<meta name="author" lang="en" content="Bernd Orttenburger [www.myseat.us]" />
	<meta name="copyright" lang="en" content="mySeat [www.myseat.us]" />
	<meta name="keywords" content="mySeat, table reservation system, Bookings Diary, Reservation Diary, Restaurant Reservations, restaurant reservation system, open source, software, reservation management software, restaurant table management, table planner, restaurant table planner, table management, hotel" />
	<meta id="htmlTagMetaDescription" name="Description" content="Make online reservationsfor lunch and dinners. mySeat is a OpenSource online reservation system for restaurants." />
	<meta id="htmlTagMetaKeyword" name="Keyword" content="restaurant reservations, online restaurant reservations, restaurant management software, mySeat, free tables" />

	<!-- Meta data for all iDevices -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />

	<!-- Website Title --> 
	<title>Table Reservation</title> 

	<!-- Stylesheets -->
	<link rel="stylesheet" href="css/screen.css" type="text/css" media="all"/>
	<link rel="stylesheet" href="jqmobile/jquery.mobile-1.0a4.1.min.css" />
	<!-- jQuery Library-->
	<script type="text/javascript" src="jqmobile/jquery-1.6.1.min.js"></script>
	<script type="text/javascript" src="jqmobile/jquery.mobile-1.0a4.1.min.js"></script>
	<script type="text/javascript" src="../web/js/jquery.validate.min.js"></script>

</head>
<body>

	<div data-role="page" data-theme="b">

		<div data-role="header">
			<h1><?php echo $lang["conf_title"];?></h1>
			<a href="<?php echo $website; ?>" data-icon="home" class="ui-btn-left" data-direction="reverse"  data-iconpos="notext">Home</a>
			<a href="cancel.php" data-icon="delete" class="ui-btn-right" data-iconpos="notext"><?php echo $lang["contact_form_cxl"];?></a>
		</div><!-- /header -->

		<div data-role="content">
			<div style='float:right'>
				<?php language_navigation_mobile(substr($general['language'],0,2)); ?>
			</div>
			<p>
				<?php echo sprintf($lang["contact_form_intro"],$general['max_menu']); ?>
			</p>
			<h2>
				<?php echo date($general['dateformat'],strtotime($_SESSION['selectedDate'])); ?>
			</h2>
			<form action="process_booking.php" method="post" id="contactform" data-ajax="false">
				<legend><?php lang("contact_form_restaurant");?></legend><br/>
				<?php	
							// Restaurant dropdown
							$num_outlets = 0;
							if ($_SESSION['single_outlet'] == 'OFF') {
								$num_outlets = querySQL('num_outlets');
							}
								if ($num_outlets>1) {
									echo "<input type='hidden' id='single_outlet' value='".$_SESSION['outletID']."'>";
									$outlet_result = outletListweb($_SESSION['outletID'],'enabled','reservation_outlet_id');
								} else{
									echo "<input type='hidden' name='reservation_outlet_id' id='single_outlet' value='".$_SESSION['outletID']."'>".$outlet_name;
								}

						    // Outlet description
							if ($language == 'en') {
								echo "<br/><br/>".$_SESSION['selOutlet']['outlet_description_en']."<br/>";
							}else{
								echo "<br/><br/>".$_SESSION['selOutlet']['outlet_description']."<br/>";
							}
				?>
				<br/>
				<div data-role="fieldcontain">
				 <!-- Datepicker -->
					<legend for="date">Date Input</legend><br/>
					<?php echo DateDropdown();?>
				 <!-- END datepicker -->
				</div>
				<div data-role="fieldcontain">
				<legend><?php lang("contact_form_time"); ?>*</legend><br/>
				<?php
				    timeList($general['timeformat'], $general['timeintervall'],'reservation_time',$time,$_SESSION['selOutlet']['outlet_open_time'],$_SESSION['selOutlet']['outlet_close_time'],0);
				?>
				</div>
				<div data-role="fieldcontain">
					<legend><?php lang("contact_form_pax"); ?>*</legend><br/>
                    <?php
						//personsList(max pax before menu , standard selected pax);
					    personsList($general['max_menu'],2);
					?>
				</div>
				<div data-role="fieldcontain">
					<legend><?php lang("contact_form_title"); ?></legend><br/>
					<?php
						$title = '';
						 if ($me) {
						 	if ( $me['gender']=='male' ) {
								$title = 'M';
						 	}else if ( $me['gender']=='female' ) {
								$title = 'F';
						 	}
						 }
					    titleList($title);
					?>
				</div>
				<div data-role="fieldcontain">
					<legend><?php lang("contact_form_name"); ?>*</legend><br/>
		              	<input type="text" name="reservation_guest_name" class="form required" id="reservation_guest_name" value="<?php if($me['last_name']){echo $me['last_name'].", ".$me['first_name'];} ?>" />
				</div>
				<div data-role="fieldcontain">
					<legend><?php lang("contact_form_email"); ?>*</legend><br/>
			          	<input type="text" name="reservation_guest_email" class="form required email" id="reservation_guest_email" value="<?php echo $me['email']; ?>" /><br/>
				</div>
				<div data-role="fieldcontain">
					<legend><?php lang("contact_form_advertise"); ?></legend><br/>
					<fieldset data-role="controlgroup">
						<input type="checkbox" name="reservation_advertise" class="custom" id="reservation_advertise" value="YES"/>
						<label for="reservation_advertise">OK</label>
				    </fieldset>
				</div>
				<div data-role="fieldcontain">
					<legend><?php lang("contact_form_phone"); ?></legend><br/>
			            <input type="text" name="reservation_guest_phone" class="form required" id="reservation_guest_phone" value="" />
				</div>
				<div data-role="fieldcontain">
					<legend><?php lang("contact_form_notes"); ?></legend><br/>
				    <textarea name="reservation_notes" class="form" id="reservation_notes" style="width:85%" rows="5" cols="2"></textarea>
				</div>
				<div data-role="fieldcontain">
					<input type="hidden" name="action" id="action" value="submit"/>
					<input type="hidden" name="barrier" value="<?php echo $barrier; ?>" />
					<input type="hidden" name="reservation_referer" value="<?php echo $_SESSION['referer']; ?>" />
					<input type="hidden" name="reservation_hotelguest_yn" id="reservation_hotelguest_yn" value="PASS"/>
					<input type="hidden" name="reservation_booker_name" id="reservation_booker_name" value="Contact Form"/>
					<input type="hidden" name="reservation_author" id="reservation_author" value="<?php echo $prp_info['name'];?> Team"/>
					<input type="hidden" name="email_type" id="email_type" value="<?php echo $language; ?>"/>
				</div>
				<div data-role="fieldcontain">
				    <?php
					$day_off = getDayoff();
	                if ($day_off == 0) {
	                	echo"<input type='submit' data-theme='b' value='".$lang['contact_form_send']."'/>";
	                }else{
						echo"<a href='#' data-role='button' data-icon='alert' data-theme='e'>"._day_off."</a>";
					}
	                ?>
				</div>
				<h4 style='text-align:center;'>
					<?php echo sprintf($lang["footer_one"],$prp_info['phone'],$prp_info['email'],$prp_info['email']); ?>
				</h4>
				<div class="error" style="visibility:hidden;"></div>
		</form>
		</div>
		<div data-role="footer">
			<h6>&copy; 2010 by mySeat</h6>
		</div><!-- /footer -->
	</div><!-- /page -->

	<!-- Javascript at the bottom for fast page loading --> 
	<script>
	    jQuery(document).ready(function($) {
	      
			$("#reservation_outlet_id").live("change" , function(){
			    window.location.href='index.php?outletID=' + this.value;
			  });
			$("#reservation_date").live("change" , function(){
			    window.location.href='index.php?selectedDate=' + $('#reservation_date option:selected').val() + "&outletID=" + $("#reservation_outlet_id").val();
			  });

			// Start validation with customer reservation form
			$("#contactform").validate({
				errorLabelContainer: $("#contactform div.error"),
				highlight: function(element, errorClass) {
					$(element).addClass(errorClass);
				}
			});
			
		 });
	</script>

</body>
</html>