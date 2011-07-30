<?php
session_start();

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
	    <h2><?php echo $lang["cxl_title"];?><span></span> </h2>
		<br class="cl" />
	    
	    <div id="page-content" class="container_12">
		
		<!-- page content goes here -->
			

			<br/>
			  <?php
			    lang("cxl_intro"); 
			  ?>
			<br/>
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
                             <form method="post" action="cancel.php" name="contactForm" id="contactForm">
                                    <br/>
                                        <div>
                                        <label><?php lang("book_num"); ?></label><br/>
                                                <input type="text" name="reservation_bookingnumber" id="reservation_bookingnumber" class="form required" value=""/>
                                        </div>
                                        <br/>
                                        <div>
                                        <label><?php lang("contact_form_email"); ?></label><br/>
                                                <input type="text" name="reservation_guest_email" class="form required email" id="reservation_guest_email" value="" />
                                        </div>
                                    <br/>
                                <p class="tc">
                                  <input type="hidden" name="reservation_timestamp" value="<?php echo date('Y-m-d H:i:s');?>">
                                  <input type="hidden" name="reservation_ip" value="<?php echo $_SERVER['REMOTE_ADDR'];?>">
                                  <input type="hidden" name="action" value="cncl_book">
                                  <button type="submit" class="button" id="submit"><?php lang("contact_form_cxl");?></button>
                                </p>
                                <div class="error"></div>
                              </form>
			<br/>
			<div class="clear"></div>

	    <br class="cl" />

</div><!-- main close -->

</body>
</html>