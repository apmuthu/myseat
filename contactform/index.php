<?php session_start();
//error_reporting(E_ALL & ~E_NOTICE);
//ini_set("display_errors", 1);

$_SESSION['role'] = 6;
$_SESSION['language'] = 'en_EN';
$_SESSION['outletID'] = '';

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

// get and define referer
	$ref = getHost($_SERVER['HTTP_REFERER']);
	$_SESSION['referer'] = ($_SESSION['referer']!='') ? $_SESSION['referer'] : $ref;

// property id
   if ($_GET['prp']) {
       $_SESSION['property'] = (int)$_GET['prp'];
   }elseif ($_POST['prp']) {
       $_SESSION['property'] = (int)$_POST['prp'];
   }elseif (!$_SESSION['property']){
	$_SESSION['property'] = '1';
}
$_SESSION['propertyID'] = $_SESSION['property'];

//standard outlet for contact form
	if ($_GET['outletID']) {
		$_SESSION['outletID'] = (int)$_GET['outletID'];
	}else if ($_SESSION['outletID'] !='' ){
		$_SESSION['outletID'] = querySQL('web_standard_outlet');
	}

	if ($_GET['so']) {
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
	<meta http-equiv=pragma content=no-cache/>
	<meta http-equiv=cache-control content=no-cache/> 
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
	<meta http-equiv="X-UA-Compatible" content="IE=8" />
	<!-- Meta data for SEO -->
	<meta id="htmlTagMetaDescription" name="Description" content="Make online reservationsfor lunch and dinners. mySeat is a OpenSource online reservation system for restaurants." />
	<meta id="htmlTagMetaKeyword" name="Keyword" content="restaurant reservations, online restaurant reservations, restaurant management software, mySeat, free tables" />
	<meta name="robots" content="all,follow" />
	<meta name="author" lang="en" content="Bernd Orttenburger [www.myseat.us]" />
	<meta name="copyright" lang="en" content="mySeat [www.myseat.us]" />
	<meta name="keywords" content="mySeat, table reservation system, Bookings Diary, Reservation Diary, Restaurant Reservations, restaurant reservation system, open source, software, reservation management software, restaurant table management, table planner, restaurant table planner, table management, hotel" />

	<!-- CSS - Setup -->
	<link href="style/style.css" rel="stylesheet" type="text/css" />
	<link href="style/base.css" rel="stylesheet" type="text/css" />
	<link href="style/grid.css" rel="stylesheet" type="text/css" />
	<!-- CSS - Theme -->
	<link id="theme" href="style/themes/<?= $default_style;?>.css" rel="stylesheet" type="text/css" />
	<link id="color" href="style/themes/<?= $general['contactform_color_scheme'];?>.css" rel="stylesheet" type="text/css" />
	<style type="text/css">
		body {
			background: <?= $general['contactform_background'];?>;
		}
	</style>
	
	<!-- CSS - Datepicker -->
	<link href="style/datepicker.css" rel="stylesheet" type="text/css" />

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
	<!-- start header -->
	<br/>
	<div id="wrapper"> 
	    <!-- logo -->
	    <h1 id="logo" style="background-image: url(../uploads/logo/<? echo ($prp_info['logo_filename']=='') ? 'logo.png' : $prp_info['logo_filename'];?>);">
		<a href="index.php?p=2">mySeat</a>
		</h1>
	    <nav>
			<div class='langnav'>
				<div><strong><?php lang("change_language"); ?></strong></div>		
				<ul class="nav">
					<?php language_navigation(); ?>
				</ul>
			</div>
	      <ul id="nav">
	        <li><a href="<?= $home_link;?>">Home</a></li>
	        <li <? if($p == 2){echo'class="current"';} ?> ><a href="cancel.php?p=2"><?= $lang["contact_form_cxl"];?></a>
	      </ul>
	      <br class="cl" />
	    </nav>
	    <br class="cl" />
	  </header>
	<!-- end header -->
	<!-- page container -->
	  <div id="page"> 
	    <!-- page title -->
	    <h2 class="ribbon full"><?= $lang["conf_title"];?><span></span> </h2>
	    <div class="triangle-ribbon"></div>
	    <br class="cl" />
	    
	    <div id="page-content" class="container_12">
		
		<!-- page content goes here -->	


			<?php lang("contact_form_intro"); ?>
			<br/>
			<h3><?= $outlet_name." - ".date($general['dateformat'],strtotime($_SESSION['selectedDate'])); ?></h3>
			
			<?php
				// Generate captcha fields
				$captchaField1 = rand(1, 10);
				$captchaField2 = rand(1, 20);
				$captchaField3 = rand(1, 10);
				$captchaField2 = ($captchaField2%2) ? "+" : "-";
			?>

		<form action="process_booking.php" method="post" id="contactForm">
		    
		    <!-- Datepicker -->
		    <label> </label><div id="bookingpicker"></div>
		    <input type="hidden" name="dbdate" id="dbdate" value="<?= $_SESSION['selectedDate']; ?>"/>
      
		    <input type="hidden" name="reservation_date" value="<?= $_SESSION['selectedDate'];?>">
		    <input type="hidden" name="recurring_dbdate" value="<?= $_SESSION['selectedDate']; ?>"/>
            
		    <!-- END datepicker -->
		    <div><br/>
			<?php
			
			// MESSAGES
			//Day off error message
			$day_off = getDayoff();
            if ($outlet_result == 1) {
				echo "<div class='alert_error'><p><img src='../web/images/icon_error.png' alt='error' class='middle'/>&nbsp;&nbsp;";
				echo lang('error_dayoff')."<br>";
				echo "</p></div>";
			}

			// Special event advertise
			$events_advertise = '';
			$events_advertise = querySQL('event_advertise');
			// Special event of the day and outlet
			$special_events = '';
			$special_events = querySQL('event_data_day');
			
			if ( $events_advertise || $special_events ) {
				if ( $special_events ) {
					echo "<div class='alert_info'>";
					$advertise = $special_events;
					// special events today at outlet
							//echo "<div class='ads'>"._ads."</div>";
								// special events
								foreach($advertise as $row) {
									echo "
									<img src='../web/images/icon_cutlery.png' alt='special' class='middle'/>
									<span class='bold'>
									<a href='".$_SERVER['SCRIPT_NAME']."?outletID=".$row->outlet_id."&selectedDate=".$row->event_date."'>".
									_today.": ".$row->subject."</a></span>
									<p>".$row->description."<br/><cite><span class='bold'>
									".date($general['dateformat'],strtotime($row->event_date)).
									"</span> ".formatTime($row->start_time,$general['timeformat']).
									" - ".formatTime($row->end_time,$general['timeformat'])." | ".
									_ticket_price.": ".number_format($row->price,2).
									"</cite></p>";
									if( key($row) != count($events_advertise)-1 ) {
										echo"<br/>";
									} 
								}
							echo "</div>";
				}else{
					echo "<div class='alert_ads'>";
					$advertise = $events_advertise;
					// special events advertisement
							echo "<div class='ads'>"._ads."</div>";
								// special events
								foreach($advertise as $row) {
									echo "
									<img src='../web/images/icon_cutlery.png' alt='special' class='middle'/>
									<span class='bold'>
									<a href='".$_SERVER['SCRIPT_NAME']."?outletID=".$row->outlet_id."&selectedDate=".$row->event_date."'>".
									_sp_events.": ".date($general['dateformat'],strtotime($row->event_date))." ".
									$row->subject."</a> | ".$row->outlet_name."</span>
									<p>".$row->description."<br/><cite><span class='bold'>
									".date($general['dateformat'],strtotime($row->event_date)).
									"</span> ".formatTime($row->start_time,$general['timeformat']).
									" - ".formatTime($row->end_time,$general['timeformat'])." | ".
									_ticket_price.": ".number_format($row->price,2).
									"</cite></p>";
									if( key($row) != count($events_advertise)-1 ) {
										echo"<br/>";
									} 
								}
							echo "</div>";
				}
					
			}
?>
			<br/><br/>	
<?			
			// Restaurant dropdown
			$num_outlets = 0;
			if ($_SESSION['single_outlet'] == 'OFF') {
				$num_outlets = querySQL('num_outlets');
			}
			
				if ($num_outlets>1) {
					echo"<label>RESTAURANT</label><br/><div class='txt-big'>";
					$outlet_result = outletListweb($_SESSION['outletID'],'enabled','reservation_outlet_id');
					echo "<input type='hidden' id='single_outlet' value='".$_SESSION['outletID']."'>";
				} else{
					echo "<input type='hidden' name='reservation_outlet_id' id='single_outlet' value='".$_SESSION['outletID']."'><br/><br/>";
				}
			
			?>
			
		    </div>
		    <div>
			<label><?php lang("contact_form_time"); ?>*</label><br/>
			<?php
			    timeList($general['timeformat'], $general['timeintervall'],'reservation_time',$time,$_SESSION['selOutlet']['outlet_open_time'],$_SESSION['selOutlet']['outlet_close_time'],0);
			?>
		    </div>
		  	<br/>
		    <div>
			<label><?php lang("contact_form_pax"); ?>*</label><br/>
                        <?php
							//personsList(max pax before menu , standard selected pax);
						    personsList($general['max_menu'],2);
						?>
                    </div>
			<br/><br/>
			
			<!-- facebook button-->		
		    <?php if ($me): ?>
			<img src="https://graph.facebook.com/<?php echo $uid; ?>/picture">
		    <a href="<?php echo $logoutUrl; ?>">
		      <img src="http://static.ak.fbcdn.net/rsrc.php/z2Y31/hash/cxrz4k7j.gif">
		    </a>
		    <?php else: ?>
		    <div>
		      <a href="<?php echo $loginUrl; ?>">
		        <img src="http://static.ak.fbcdn.net/rsrc.php/zB6N8/hash/4li2k73z.gif">
		      </a>
		    </div>
		    <?php endif ?>
			<!-- facebook button end -->
			
			<br/>
			<br/>
		    <div>
			<label><?php lang("contact_form_title"); ?></label><br/>
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
		    <br/>
		    <div>
			<label><?php lang("contact_form_name"); ?>*</label><br/>
               <input type="text" name="reservation_guest_name" class="form required" id="reservation_guest_name" value="<?php if($me['last_name']){echo $me['last_name'].", ".$me['first_name'];} ?>" />
                    </div>
		    <br/>
            <div>
			<label><?php lang("contact_form_email"); ?>*</label><br/>
              <input type="text" name="reservation_guest_email" class="form required email" id="reservation_guest_email" value="<?php echo $me['email']; ?>" />
            </div>
		    <br/>
		<div>
			<label></label>
			<input type="checkbox" name="reservation_advertise" class="small" id="reservation_advertise" value="YES"/>&nbsp;&nbsp;<?php lang("contact_form_advertise"); ?>
                    </div>
		    <br/>
		    <div>
			<label><?php lang("contact_form_phone"); ?></label><br/>
                        <input type="text" name="reservation_guest_phone" class="form" id="reservation_guest_phone" value="" />
                    </div>
		    <br/>
                    <div>
			<label><?php lang("contact_form_notes"); ?></label><br/>
                	<textarea cols="50" rows="10" name="reservation_notes" class="form" id="reservation_notes" ></textarea>
                    </div>
		    <br/>
			<?php lang("security_question"); ?>
		    <div class="side">
                	<div class="captchaContainer">
                		<label for="captcha">
		            		<span id="captchaField1" class="captchaField"><?php echo $captchaField1; ?></span>
		            		<input type="hidden" name="captchaField1" value="<?php echo $captchaField1; ?>"/>

		            		<span id="captchaField2" class="captchaField"><?php echo $captchaField2; ?></span>
		            		<input type="hidden" name="captchaField2" value="<?php echo $captchaField2; ?>"/>

							<span id="captchaField3" class="captchaField"><?php echo $captchaField3; ?></span>
							<input type="hidden" name="captchaField3" value="<?php echo $captchaField3; ?>"/>
							
							<span class="captchaField">=</span>
						</label>
                		<input type="text" name="captcha" class="form required captcha small" id="captcha" value="" />
                  		
	            		
                		<div class="clear"></div>
                	</div>
		    </div> 
                	<div class="oh">
				<input type="hidden" name="action" id="action" value="submit"/>
				<input type="hidden" name="barrier" value="<?php echo $barrier; ?>" />
				<input type="hidden" name="reservation_referer" value="<?php echo $_SESSION['referer']; ?>" />
				<input type="hidden" name="reservation_hotelguest_yn" id="reservation_hotelguest_yn" value="PASS"/>
				<input type="hidden" name="reservation_booker_name" id="reservation_booker_name" value="Contact Form"/>
				<input type="hidden" name="reservation_author" id="reservation_author" value="<?= querySQL('db_property');?> Team"/>
				<input type="hidden" name="email_type" id="email_type" value="<?php echo $language; ?>"/>
                <?php
				$day_off = getDayoff();
                if ($day_off == 0) {
                	echo"<div class='tc'><br/><br/><input class='button ".$default_color." large' type='submit' value='".$lang['contact_form_send']."' /></div>";
                }else{
					echo "<div id='messageBox'><div class='alert_error'>
					<p><img src='../web/images/icon_error.png' alt='error' class='middle' />&nbsp;&nbsp;"._day_off."</p></div></div>";
				}
                ?>	
                </div>
		</form>

	    <br class="cl" />
	    <br class="cl" />		
		</div></div><!-- page content end -->
			
  <!-- Footer Start -->
  <footer>

    <p><small>&copy; 2010 by <a href="http://www.myseat.us" target="_blank">mySeat</a> under the GPL license, designed for easy  & free restaurant reservations.</small></p>
    <br class="cl" />
  </footer>
  <!-- footer end -->
</div></div><!-- page container end -->
</div></div><!-- main close -->

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
	      numberOfMonths: 2,
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