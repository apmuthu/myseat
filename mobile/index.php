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

// ** get superglobal variables
	include('../web/includes/get_variables.inc.php');
// CSRF - Secure forms with token
	$barrier = md5(uniqid(rand(), true)); 
	$_SESSION['barrier'] = $barrier;


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
<style type="text/css">
	.ui-datepicker {
		width: 295px;
	}
	.ui-datepicker table {
		width: 295px;
	}
	.ui-datepicker-calendar {
		width: 295px;
	}
</style>
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
				<?php language_navigation_mobile(substr($general['language'],0,2)); ?>
				<a href="cancel.php">
					<img src="images/icon_recycle.png" alt="" width="40" height="40"/>
				</a>
			</div>
		</div>
		<br/><br/>
		<div id="content_wrapper">
			<div class="inner">
				<h2 class="header"><?= $lang["conf_title"];?></h2>
				<p>
					<?php echo sprintf($lang["contact_form_intro"],$general['max_menu']); ?>
				</p>
			</div>
			<br class="clear"/>
			<hr/>
			
			
			<?php
				// Generate captcha fields
				$captchaField1 = rand(1, 10);
				$captchaField2 = rand(1, 20);
				$captchaField3 = rand(1, 10);
				$captchaField2 = ($captchaField2%2) ? "+" : "-";
			?>
			<div class="inner">
				<h2>
				<? echo
		lang("contact_form_restaurant")."<br/>".date($general['dateformat'],strtotime($_SESSION['selectedDate']))."<br/>";?>
				</h2>
				<form action="process_booking.php" method="post" id="contactForm">
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
					<br class="clear"/>
					<br/>
					<br/>
					 <!-- Datepicker -->
					    <div id="bookingpicker"></div>
					    <input type="hidden" name="dbdate" id="dbdate" value="<?= $_SESSION['selectedDate']; ?>"/>
					    <input type="hidden" name="reservation_date" value="<?= $_SESSION['selectedDate'];?>">
					    <input type="hidden" name="recurring_dbdate" value="<?= $_SESSION['selectedDate']; ?>"/>
					 <!-- END datepicker -->
					<br/>
					<p>
					<label><?php lang("contact_form_time"); ?>*</label>
					<?php
					    timeList($general['timeformat'], $general['timeintervall'],'reservation_time',$time,$_SESSION['selOutlet']['outlet_open_time'],$_SESSION['selOutlet']['outlet_close_time'],0);
					?>
				    </p>
					<p>
						<label><?php lang("contact_form_pax"); ?>*</label>
			                        <?php
										//personsList(max pax before menu , standard selected pax);
									    personsList($general['max_menu'],2);
									?>
					</p>
					<p>
						<label><?php lang("contact_form_title"); ?></label>
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
					</p>
					<label><?php lang("contact_form_name"); ?>*</label>
		              	<input type="text" name="reservation_guest_name" class="form required" id="reservation_guest_name" value="<?php if($me['last_name']){echo $me['last_name'].", ".$me['first_name'];} ?>" />
					<p>
					<label><?php lang("contact_form_email"); ?>*</label>
			          	<input type="text" name="reservation_guest_email" class="form required email" id="reservation_guest_email" value="<?php echo $me['email']; ?>" />
					</p>
					<p>
					<label></label>
						<input type="checkbox" name="reservation_advertise" class="small" id="reservation_advertise" value="YES"/>&nbsp;&nbsp;<?php lang("contact_form_advertise"); ?>
					</p>
					<p>
					<label><?php lang("contact_form_phone"); ?></label>
			            <input type="text" name="reservation_guest_phone" class="form required" id="reservation_guest_phone" value="" />
					</p>
					<p>
					    <label><?php lang("contact_form_notes"); ?></label>
					    <textarea name="reservation_notes" class="form" id="reservation_notes" style="width:85%" rows="5" cols="2"></textarea>
					</p>
					<p>
						<div class="captchaContainer">
	                		<label for="captcha">
								<?php lang("security_question"); ?>
								<br class="cl" />
			            		<span id="captchaField1" class="captchaField"><?php echo $captchaField1; ?></span>
			            		<input type="hidden" name="captchaField1" value="<?php echo $captchaField1; ?>"/>

			            		<span id="captchaField2" class="captchaField"><?php echo $captchaField2; ?></span>
			            		<input type="hidden" name="captchaField2" value="<?php echo $captchaField2; ?>"/>

								<span id="captchaField3" class="captchaField"><?php echo $captchaField3; ?></span>
								<input type="hidden" name="captchaField3" value="<?php echo $captchaField3; ?>"/>

								<span class="captchaField">=</span>
							</label>
	                		<input type="text" name="captcha" class="form required captcha" id="captcha" value="" style="width:25%"/>
	                	</div>
					</p>
					
					<p>
					</p>
					<p>
					</p>
					
					<br/>
					<p>
						<input type="hidden" name="action" id="action" value="submit"/>
						<input type="hidden" name="barrier" value="<?php echo $barrier; ?>" />
						<input type="hidden" name="reservation_referer" value="<?php echo $_SESSION['referer']; ?>" />
						<input type="hidden" name="reservation_hotelguest_yn" id="reservation_hotelguest_yn" value="PASS"/>
						<input type="hidden" name="reservation_booker_name" id="reservation_booker_name" value="Contact Form"/>
						<input type="hidden" name="reservation_author" id="reservation_author" value="<?= $prp_info['name'];?> Team"/>
						<input type="hidden" name="email_type" id="email_type" value="<?php echo $language; ?>"/>
					    
					
					    <?php
						$day_off = getDayoff();
		                if ($day_off == 0) {
		                	echo"<input type='submit' class='button_dark' value='".$lang['contact_form_send']."'/>";
		                }else{
							echo "<div class='alert_error' style='margin-bottom:20px'><p>
									<img src='images/icon_error.png' alt='delete'' class='middle'/> 
									&nbsp;&nbsp;"._day_off."</p></div>";
						}
		                ?>	
					</p>
					<br/><br/>
				</form>
				<div class="pagination">
				<?php echo sprintf($lang["footer_one"],$prp_info['phone'],$prp_info['email'],$prp_info['email']); ?>
				<br/><br/>
				<small>
					&copy; 2010 by <a href="http://www.myseat.us" target="_blank">mySeat</a>
					 under the GPL license, designed for easy  & free restaurant reservations.
				</small>
				</div>
				<br/>
			</div>
		</div>
	</div>
	<!-- End page wrapper -->

	<!-- Javascript at the bottom for fast page loading --> 
	<script>
		var disabledDays = [<?php defineOffDays(); ?>];

		/* utility functions */
		function offDays(date) {
		  var m = date.getMonth(), d = date.getDate(), y = date.getFullYear();
			m = m+1;
			/* add leading zero */
			if (d < 10) d = "0" + d;
			if (m < 10) m = "0" + m;
		  for (i = 0; i < disabledDays.length; i++) {
		    if ($.inArray( y + '-' + m + '-' + d, disabledDays) != -1 || new Date() > date) {
		      return [false];
		    }
		  }
		  return [true];
		}
		function noDayoffs(date) {
		  var noWeekend = jQuery.datepicker.noWeekends(date);
		  return noWeekend[0] = offDays(date);
		}

	    jQuery(document).ready(function($) {
	      // Setup datepicker input at customer reservation form
	      $("#bookingpicker").datepicker({
		      nextText: '&raquo;',
		      prevText: '&laquo;',
		      firstDay: 1,
		      numberOfMonths: 1,
			  minDate: 0,
			  maxDate: '+6M',
		      gotoCurrent: true,
		      altField: '#dbdate',
		      altFormat: 'yy-mm-dd',
		      defaultDate: 0,
			  beforeShowDay: noDayoffs,
		      dateFormat: '<?= $general['datepickerformat'];?>',
		      regional: '<?= substr($_SESSION['language'],0,2);?>',
		      onSelect: function(dateText, inst) { window.location.href="?selectedDate=" + $("#dbdate").val() + "&outletID=" + $("#single_outlet").val(); }
	      });
	      // month is 0 based, hence for Feb. we use 1
	      $("#bookingpicker").datepicker('setDate', new Date(<?= $sy.", ".($sm-1).", ".$sd; ?>));
	      $("#ui-datepicker-div").hide();
	      $("#reservation_outlet_id").change(function(){
		    window.location.href='?outletID=' + this.value;
		  });
	    });
	</script>
</body>
</html>