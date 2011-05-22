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
				<h2 class="header"><?php lang("cxl_intro");?></h2>
				<p>
					<br/>
					<span id="result">
				  	<?php
	                 if($_POST['action'] == 'cncl_book'){
				      if($cancel>=1){
						echo "<div class='alert_success'><p><img src='../web/images/icons/icon_accept.png' alt='success' class='middle'/>&nbsp;&nbsp;";
						echo $lang['cxl_form_success']."<br>";
						echo "</p></div>";
				      }else{
						echo "<div class='alert_error'><p><img src='../web/images/icon_error.png' alt='error' class='middle'/>&nbsp;&nbsp;";
						echo $lang['contact_form_fail']."<br>";
						echo "</p></div>";
				      }
	                 }
				  	?>
	            	</span>
				</p>
			</div>
			<br class="clear"/>
			<hr/>
			<div class="inner">
			 <form method="post" action="cancel.php" name="contactForm" id="contactForm">
                    <br/>
                        <p>
                        <label><?php lang("book_num"); ?></label><br/>
                                <input type="text" name="reservation_bookingnumber" id="reservation_bookingnumber" class="form required" value=""/>
                        </p>
                        <br/>
                        <p>
                        <label><?php lang("contact_form_email"); ?></label><br/>
                                <input type="text" name="reservation_guest_email" class="form required email" id="reservation_guest_email" value="" />
                        </p>
                    <br/>
                <p class="tc">
                  <input type="hidden" name="reservation_timestamp" value="<?= date('Y-m-d H:i:s');?>">
                  <input type="hidden" name="reservation_ip" value="<?= $_SERVER['REMOTE_ADDR'];?>">
                  <input type="hidden" name="action" value="cncl_book">
                  <input type='submit' class='button_dark' value="<?php lang("contact_form_cxl");?>"/>
                </p>
                <div class="error"></div>
              </form>
			
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
			


</body>
</html>