<!--
First, be sure that your bookingpage ends with .php instead of .htm oder .html.
Second, put a copy of the mobile_detect.php in the directory on your booking page.
Third, copy the following PHP code on top of your page.
-->
<?php
session_start();
	include("mobile_detect.php");
	$detect = new Mobile_Detect();
	if ($detect->isMobile()) {
	   header('Location: reserve.php');
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>mySeat Online Booking</title>
	<meta name="generator" content="TextMate http://macromates.com/">
	<meta name="author" content="mySeat">
	<!-- Date: 2011-05-29 -->
	<style type="text/css">
		body{
			background: #B3B7BA;
		}
		.frame{
			border:1px solid #aaa; 
			border-radius: 10px; 
			-moz-border-radius: 10px; 
			-webkit-border-radius: 10px;
		}
    </style>
	
</head>
<body>
	<center>
		<img src="../web/images/logo_mid.png" alt="Logo"/><br/>
		
	<!-- *
		 * The following 3 lines should be copied into your websites reservation page           *
		 * Adapt the page layout by setting the CSS styles directly in api/reserve.php line 250 * -->
		
		<iframe height="1300" width="560" src="reserve.php?propertyID=1" scrolling="no" class="frame"> 
			<p>Your browser does not support iframes.</p>
		</iframe>
		
		<!-- end copy code -->
		
	<center>
	<br/>
	<br/>
</body>
</html>