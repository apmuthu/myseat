<?php
session_start();

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
// ** get superglobal variables
	include('../web/includes/get_variables.inc.php');	
// ** get property info for logo path
$prp_info = querySQL('property_info');

  if ($_POST['action']=='cncl_book'){

    // cancel reservation
    $result = query("UPDATE `reservations` SET `reservation_hidden` = '1' WHERE `reservation_hidden` = '0' AND `reservation_bookingnumber` = '%s' AND `reservation_guest_email` = '%s'", $_POST['reservation_bookingnumber'], $_POST['reservation_guest_email']);
    $cancel = $result;

    // get reservation id from booking number
    if($cancel>=1){
      $result = query("SELECT `reservation_id` FROM `reservations` WHERE `reservation_bookingnumber` = '%s' LIMIT 1",$_POST['reservation_booking_number']);
	if ($row = mysql_fetch_row($result)) {
		$reservation_id = $row[0];
	}
      // store changes in history
      $result = query("INSERT INTO `res_history` (reservation_id,author) VALUES ('%d','Online-Cancel')",$reservation_id);
    }
    
  }
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
	<title>Cancel Reservation</title> 

	<!-- jQuery Library-->
	<link rel="stylesheet" href="jqmobile/jquery.mobile-1.0a4.1.min.css" />
	<script type="text/javascript" src="jqmobile/jquery-1.6.1.min.js"></script>
	<script type="text/javascript" src="jqmobile/jquery.mobile-1.0a4.1.min.js"></script>

</head>
<body>

	<div data-role="page" data-theme="b">

		<div data-role="header">
			<h1><? echo $prp_info['name'];?></h1>
			<a href="index.php" data-icon="grid" data-direction="reverse" class="ui-btn-right" data-iconpos="notext">Index</a>
		</div><!-- /header -->

		<div data-role="content">
			<p><?php lang("cxl_intro");?></p>
			
			<span id="result">
		  	<?php
             if($_POST['action'] == 'cncl_book'){
		      if($cancel>=1){
				echo"<a href='#' data-role='button' data-icon='check' data-theme='e'>".$lang['cxl_form_success']."</a>";
		      }else{
				echo"<a href='#' data-role='button' data-icon='alert' data-theme='e'>".$lang['contact_form_fail']."</a>";
		      }
             }
		  	?>
        	</span>
			<br/>
			<form method="post" action="cancel.php" name="contactForm" id="contactForm">
				<div data-role="fieldcontain">
				    <label for="reservation_bookingnumber"><?php lang("book_num"); ?></label>
				    <input type="text" name="reservation_bookingnumber" id="reservation_bookingnumber"/>
				</div>
				<div data-role="fieldcontain">
					<label  for="reservation_guest_email"><?php lang("contact_form_email"); ?></label>
                    <input type="text" name="reservation_guest_email" id="reservation_guest_email"/>
				</div>
				<input type="hidden" name="reservation_timestamp" value="<?= date('Y-m-d H:i:s');?>">
                  <input type="hidden" name="reservation_ip" value="<?= $_SERVER['REMOTE_ADDR'];?>">
                  <input type="hidden" name="action" value="cncl_book">
                  <input type='submit' data-theme="b" value="<?php lang("contact_form_cxl");?>"/>
			</form>		
		</div><!-- /content -->

		<div data-role="footer">
			<h6>&copy; 2010 by mySeat</h6>
		</div><!-- /footer -->
	</div><!-- /page -->
</body>
</html>