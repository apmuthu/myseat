<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
// ** set configuration
	include('../config/config.general.php');
// ** database functions
	include('../web/classes/database.class.php');
// ** localization functions
	include('../web/classes/local.class.php');
// ** connect to database
	include('../web/classes/connect.db.php');
// ** begin update page content

	// reset error variables
	$errorMessage = '';
	$i = 0;

// DEFINE TABLE UPDATES ----------------------------------------

/* Update the database from XT 0.178 to > XT 0.1782 */
/* ------------------------------------------------- */
$table_updates[$i]['table'] = "reservations";
$table_updates[$i]['field'] = "reservation_referer";
$table_updates[$i]['query'] = "ALTER TABLE `reservations` ADD `reservation_referer` TEXT NOT NULL";
$i++;

/* Update the database from XT 0.1782 to > XT 0.179 */
/* ------------------------------------------------- */
$table_updates[$i]['table'] = "properties";
$table_updates[$i]['field'] = "website";
$table_updates[$i]['query'] = "ALTER TABLE `properties` ADD `website` VARCHAR( 200 ) NOT NULL";
$i++;

/* Update the database from XT 0.179 to > XT 0.1795 */
/* ------------------------------------------------- */
$table_updates[$i]['table'] = "outlets";
$table_updates[$i]['field'] = "outlet_description_en";
$table_updates[$i]['query'] = "ALTER TABLE `outlets` ADD `outlet_description_en` TEXT NOT NULL AFTER `outlet_description`";
$i++;

/* Update the database from XT 0.1795 to > XT 0.1798 */
/* ------------------------------------------------- */
$table_updates[$i]['table'] = "properties";
$table_updates[$i]['field'] = "social_fb";
$table_updates[$i]['query'] = "ALTER TABLE `properties` ADD `social_fb` VARCHAR( 255 ) NOT NULL";
$i++;

$table_updates[$i]['table'] = "properties";
$table_updates[$i]['field'] = "social_tw";
$table_updates[$i]['query'] = "ALTER TABLE `properties` ADD `social_tw` VARCHAR( 255 ) NOT NULL";
$i++;

/* Update the database from XT 0.1790 to > XT 0.180 */
/* ------------------------------------------------- */
$table_updates[$i]['table'] = "outlets";
$table_updates[$i]['field'] = "limit_password";
$table_updates[$i]['query'] = "ALTER TABLE  `outlets` ADD `limit_password` VARCHAR( 255 ) NOT NULL AFTER `avg_duration`";
$i++;

/* Update the database from XT 0.180 to > XT 0.195 */
/* ------------------------------------------------- */
$table_updates[$i]['table'] = "plc_users";
$table_updates[$i]['field'] = "realname";
$table_updates[$i]['query'] = "ALTER TABLE `plc_users` ADD `realname` VARCHAR( 255 ) NOT NULL AFTER `username`";
$i++;

$table_updates[$i]['table'] = "plc_users";
$table_updates[$i]['field'] = "autofill";
$table_updates[$i]['query'] = "ALTER TABLE `plc_users` ADD `autofill` INT NOT NULL AFTER `last_login`";
$i++;

// ------------------------------------------------

//echo "<pre>";
//print_r($table_updates);
//echo "</pre>";


// BEGIN UPDATE ------------------------------------------------
foreach ($table_updates as $table_update) {
	// reset update variable
	$update = 'NO';
	
	$query = "SHOW COLUMNS FROM `".$table_update['table']."`;";
	$sql = query($query);
	$results = getRowList($sql);

	foreach ($results as $field) {
		//echo $field->Field." == ".$table_update['field']."<br>";
		if ($field->Field == $table_update['field']) {
			$update = 'YES';
		}
	}

	if ($update == 'NO') {
		$result = query($table_update['query']);
	}else{
		$errorMessage .= "The field '".$table_update['field']."' does already exist.<br/>";
	}
	if(!$result){ 
		$errorMessage .= mysql_error()."<br/>";
	}else{
		$errorMessage .= "New field '".$table_update['field']."' has been successfully created.<br/>"; 	
	}
	
}
		// update status
		$errorMessage .= "<br/>Update completed.<br/>";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
 
<!-- Website Title --> 
<title>Database Update</title>

<!-- Meta data for SEO -->
<meta name="description" content="An easy to use Restaurant Reservation System"/>
<meta name="keywords" content="Restaurant Reservation System, Restaurant, Hotel, Reservation"/>
<meta name="author" content="Bernd Orttenburger"/>
<meta name="robots" content="noindex,nofollow" />

<!-- Template stylesheet -->
<link rel="stylesheet" href="../web/css/screen.css" type="text/css" media="all"/>
<link href="css/style.css" rel="stylesheet" media="all" type="text/css" />

<!--[if IE 7]>
	<link href="../web/css/ie7.css" rel="stylesheet" type="text/css" media="all">
<![endif]-->

<!--[if IE]>
	<script type="text/javascript" src="../web/js/excanvas.js"></script>
<![endif]-->

<!-- Jquery and plugins -->
<script language="javascript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script language="javascript" type="text/javascript" src="js/form.js"></script>
<script language="javascript" type="text/javascript" src="js/login.php"></script>
<body class="login">
	
	<!-- Begin control panel wrapper -->
	<div id="wrapper">

		<div id="login_top">
			<img src="../web/images/logo.png" alt=""/>
		</div>
		
		<br class="clear"/><br/>
					
				<!-- Begin one column box -->
						<div class="onecolumn" style="width:540px;margin:auto">

							<div class="header">
								<h2>Database Update</h2>
							</div>

							<div class="content">
								<div id="login_info" class="alert_info" style="margin:auto;padding:auto;">
									<p>
										<?php echo $errorMessage;?>
									</p>
							</div>
									<br /><center>
									<input type="button" value="Continue" onClick="location.href='../web/index.php'" 
									class="button_dark">
									</center><br />
								
								<br class="clear"/>
							</div>
						</div>
				<div id="wrapper2">
				</div>
	</div>
	<!-- End control panel wrapper -->
	
</body>
</html>
