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
		<a href="<? echo $website; ?>">mySeat</a>
		</h1>
	    <nav>
			<div class='langnav'>		
				<ul class="nav">
					<?php language_navigation(substr($general['language'],0,2)); ?>
				</ul>
			</div>
			<div class='langnav'>
				<?= strtoupper($prp_info['street']."<br>".$prp_info['zip']." ".$prp_info['city']."<br/>"."Tel. ".$prp_info['phone']);?>
				<br/><br/><strong><a href="index.php"><?= $lang["contact_form_back"];?></a> | <a href="cancel.php?p=2"><?= $lang["contact_form_cxl"];?></a></strong>
			</div>
	    </nav>

	    <br class="cl" />
	  </header>
	<!-- end header -->
	<!-- page container -->
	  <div id="page"> 
	    <!-- page title -->
	    <br class="cl" />
	    
	    <div id="page-content" class="container_12">
		
		<!-- page content goes here -->
			

			<h3>
			  <?php
			    lang("cxl_intro"); 
			  ?>
			</h3>
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
                             <form method="post" action="cancel.php" name="contactForm" id="contactForm"">
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
                                  <input type="hidden" name="reservation_timestamp" value="<?= date('Y-m-d H:i:s');?>">
                                  <input type="hidden" name="reservation_ip" value="<?= $_SERVER['REMOTE_ADDR'];?>">
                                  <input type="hidden" name="action" value="cncl_book">
                                  <button type="submit" class="button" id="submit"><?php lang("contact_form_cxl");?></button>
                                </p>
                                <div class="error"></div>
                              </form>
			<br/><br/><br/><br/><br/>
			<a href="index.php"><div class='button <?= $default_color;?>' style="line-height:32px;"><?php lang("contact_form_back");?></div></a>
			<br/>
			<div class="clear"></div>
			<br/><br/><br/>

	    <br class="cl" />
	    <br class="cl" />

		</div><!-- page content end -->
	</div><!-- page container end -->

  <!-- Footer Start -->
  <footer>

    <p><small>&copy; 2010 by <a href="http://www.myseat.us" target="_blank">mySeat</a> under the GPL license, designed for easy  & free restaurant reservations.</small></p>
    <br class="cl" />
  </footer>
  <!-- footer end -->
</div><!-- main close -->

</body>
</html>