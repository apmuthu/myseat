<?php session_start();

// PHP part of page / business logic
// ** set configuration
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
// ** get superglobal variables
	include('../web/includes/get_variables.inc.php');	
// ** get property info for logo path
$prp_info = querySQL('property_info');

  if ($_POST['action']=='cncl_book'){

    // cancel reservation
    $result = query("UPDATE `$dbTables->reservations` SET `reservation_hidden` = '1' WHERE `reservation_hidden` = '0' AND `reservation_bookingnumber` = '%s' AND `reservation_guest_email` = '%s'", $_POST['reservation_bookingnumber'], $_POST['reservation_guest_email']);
    $cancel = $result;

    // get reservation id from booking number
    if($cancel>=1){
      $result = query("SELECT `reservation_id` FROM `$dbTables->reservations` WHERE `reservation_bookingnumber` = '%s' LIMIT 1",$_POST['reservation_booking_number']);
	if ($row = mysql_fetch_row($result)) {
		$reservation_id = $row[0];
	}
      // store changes in history
      $result = query("INSERT INTO `$dbTables->res_history` (reservation_id,author) VALUES ('%d','Online-Cancel')",$reservation_id);
    }
    
  }

 // translate to selected language
	$language = $general['language'];
	$set_lang = substr($language,0,2);
	$browser_lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
	if( isset($_GET['lang']) ){
		$language = $_GET['lang'];
		$_SESSION['lang'] = $language;
	}else if ( isset($browser_lang) && $_SESSION['lang'] == '' && ($browser_lang=="en" || $browser_lang == $set_lang) ){
		echo "HERE";
		$language = $browser_lang;
	}
	if( $_SESSION['lang'] == ''){
		echo "Here";
		$_SESSION['lang'] = $language;
	}
	$lang = substr($_SESSION['lang'],0,2);
	translateSite($lang,'../web/');
?>

<!DOCTYPE html>
<html lang="<?php echo $language; ?>">
<head>
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
	<link rel="shortcut icon" href="http://www.myseat.us/favicon.ico">

	<!-- CSS - Setup -->
	<link href="style/datepicker.css" rel="stylesheet" type="text/css" />
	<?php
	// Mobile Browser detection
		$mobile_browser = checkMobile();
		if ($mobile_browser > 0) {
			echo '<link href="style/mobile.css" rel="stylesheet" type="text/css" />';
		}else{
			echo '<link href="style/style.css" rel="stylesheet" type="text/css" />';
		}
	?>

    <!-- jQuery Library-->
    <script src="js/jQuery.min.js" type="text/javascript" ></script>
    <script src="js/jquery.easing.1.3.js" type="text/javascript" ></script>
    <script src="js/jquery-ui.js" type="text/javascript" ></script> 
    <script src="js/functions.js" type="text/javascript" ></script>

	<!-- Here you can define your own color scheme for the booking form -->
<!--	
		<style type="text/css">
			html {
				background:url(images/html-bg.jpg) left top repeat !important;
			}
			.data1, .data2, .data3, .register{
				background-color: #F6E6CC;			
			}
			h1, .data1 .number, .data2 .number, .data3 .number, .register .number{
				color: #AB245E;			
			}
			a, a:active, a:visited {
			color: #42032C;
			}
			a:hover {
				color:#7e4e7f;
				background-color: #F6E6CC;
			}
			.button:hover {
				color:#7e4e7f;
				background-color: #F6E6CC;
			}
				button, .button, .btn_pax {
				background-color: #561C40;
				color: #F6E6CC;
				border: 1px solid #B89394;
				text-shadow: none;
			}
		</style>
-->	
	<!-- color scheme for the booking form END -->

    <title><?php echo _edit_reservation;?></title>
</head>
<body>
<!-- page container -->
<div id="page-content">
<form method="post" action="cancel.php" name="contactForm" id="contactForm">
<?php language_navigation($set_lang);?>
<h1><?php echo _reservations;?> - <span><?php echo ucfirst(_cancelled);?></span></h1>
<div class='trenner'></div>
<span id="result">
<?php
 if($_POST['action'] == 'cncl_book'){
      if($cancel>=1){
		echo "<div class='alert_success'><p><img src='../web/images/icons/icon_accept.png' alt='success' class='middle'/>&nbsp;&nbsp;";
		echo _entry_deleted."<br>";
		echo "</p></div>";
      }else{
		echo "<div class='alert_error'><p><img src='../web/images/icon_error.png' alt='error' class='middle'/>&nbsp;&nbsp;";
		echo _nothing_deleted."<br>";
		echo "</p></div>";
      }
echo "<div class='trenner'></div>";
}
?>
</span>
<div class='data3'>	
	<div class='number'>1</div>
	<label><?php echo _booknum; ?></label><br/>	
		<input type="text" name="reservation_bookingnumber" id="reservation_bookingnumber" class="required" value=""/>
		<label><?php echo _email; ?></label><br/>
		<input type="text" name="reservation_guest_email" class="required email" id="reservation_guest_email" value="" />
</div> <!-- data3 close -->
                <br/>
                <p class="tc">
                  <input type="hidden" name="reservation_timestamp" value="<?php echo date('Y-m-d H:i:s');?>">
                  <input type="hidden" name="reservation_ip" value="<?php echo $_SERVER['REMOTE_ADDR'];?>">
                  <input type="hidden" name="action" value="cncl_book">
                  <button type="submit" class="button" id="submit"><?php echo _delete;?></button>
				<br/><br/>
				<a href="reserve.php">
					<button class="button <?php echo $default_color;?>" ><?php echo " "._back." ";?></button>
				</a>                
				</p>
                <div class="error"></div>
              </form>
			<br/>
	 <br class="cl" />

</div><!-- page-content close -->
  <!-- Javascript at the bottom for fast page loading --> 
<script>

 jQuery(document).ready(function($) {
 				// placeholder text for input fields 
		jQuery('.placeholder').each(function(i) {

		 var item = jQuery(this);
		 var text = item.attr('rel');
		 var form = item.parents('form:first');

		 if (item.val() === '') 
		 {
		 item.val(text);
		 item.css('color', '#888');
		 }

		 item.bind('focus.placeholder', function(event) {
		 if (item.val() === text)
		 item.val('');
		 item.css('color', '');
		 });

		 item.bind('blur.placeholder', function(event) {
		 if (item.val() === '')
		 {
		 item.val(text);
		 item.css('color', '#888');
		 }
		 });

		 form.bind("submit.placeholder", function(event) {
		 if (item.val() === text)
		 item.val("");
		 }); 

		 });
	
	
    });
</script>
</body>
</html>