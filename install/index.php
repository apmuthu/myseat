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
// ** begin page content
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
 
<!-- Website Title --> 
<title>mySeatXT -  Install Process</title>

<!-- Meta data for SEO -->
<meta name="description" content="An easy to use Restaurant Reservation System"/>
<meta name="keywords" content="Restaurant Reservation System, Restaurant, Hotel, Reservation"/>
<meta name="author" content="Bernd Orttenburger"/>


<!-- Template stylesheet -->
<link rel="stylesheet" href="../web/css/screen.css" type="text/css" media="all"/>
<link rel="stylesheet" href="../web/css/print.css" type="text/css" media="print" >

<!--[if IE 7]>
	<link href="../web/css/ie7.css" rel="stylesheet" type="text/css" media="all">
<![endif]-->

<!--[if IE]>
	<script type="text/javascript" src="../web/js/excanvas.js"></script>
<![endif]-->


</head>
<body class="login">
	
	<!-- Begin control panel wrapper -->
	<div id="wrapper">
		<div id="login_top">
			<img src="../web/images/logo.png" alt=""/>
		</div>
		<br class="clear"/><br/>
                <div id="content_wrapper">
		<!-- Begin one column box -->
			<div class="onecolumn">
				<div class="header">
					<h2>Install process</h2>
				</div>
				
				<div class="content">
					<p style="font-size:1.2em;">
						mySeat is well known for its ease of installation. Under most circumstances installing mySeat is a very simple process and takes less than five minutes to complete.<br/>
						Before starting the automatic installer follow these intructions. 
					<ul style="margin-left:40px;" class="global">
						<li>Create a database for mySeat on your web server, as well as a MySQL user who has all privileges for accessing and modifying it.</li>
						<li>Open <tt>config/config.general.php</tt> in a text editor and fill in your database details.</li>
						<li>Be sure to set the right of the folder <tt>/uploads</tt> and all content in it to '777'.</li>
					</ul>
					</p>
					<form action="index.php" method="post" id="form_install" name="form_install">
						<input type="hidden" name="action" value="install"/>
						<p style="margin-top:30px; margin-right:auto; margin-left:auto; text-align:center;">
							<input type="submit" class="button_dark" value="Install"/>	
						</p>
					</form>
					<br class="clear"/><br>
					<p style="margin-left:40px; font-size:1.2em;">
						<?php
						if($_POST['action']=='install' || 1==1){
							include('install-process.php');
						}
						?>
					</p>
				</div>
			<br/><br/>
			</div>
		<br/><br/>
                </div>
                <!-- End content wrapper -->
	</div>
	<!-- End control panel wrapper -->
</body>
</html>
<?php
// close database connection
mysql_close();
?>