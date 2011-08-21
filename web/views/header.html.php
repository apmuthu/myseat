<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head> 
<!-- Website Title --> 
<title><?php echo $general['app_name'];?></title>

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

<!-- Icons -->
<link rel="shortcut icon" href="../favicon.ico">
<link rel="apple-touch-icon" href="../apple-touch-icon.png"/>

<!-- Template stylesheet -->
<link rel="stylesheet" href="css/screen.css" type="text/css" media="all"/>
<link rel="stylesheet" href="css/print.css" type="text/css" media="print" >
<link rel="stylesheet" href="css/datepicker.css" type="text/css" media="all"/>
<link rel="stylesheet" href="css/tipsy.css" type="text/css" media="all"/>
<link rel="stylesheet" href="css/jgrowl.css" type="text/css" media="all"/>
<link rel="stylesheet" href="js/visualize/visualize.css" type="text/css" media="all"/>
<link rel="stylesheet" href="js/jwysiwyg/jquery.wysiwyg.css" type="text/css" media="all"/>
<link rel="stylesheet" href="js/fancybox/jquery.fancybox-1.3.0.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="css/autocomplete.css" type="text/css" media="all"/>
<!--[if IE 7]>
	<link href="css/ie7.css" rel="stylesheet" type="text/css" media="all">
<![endif]-->

<!-- jQuery Javascript Framework -->
<script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.10.custom.min.js"></script>

<?php
list($y,$m,$d)	= explode("-",$_SESSION['selectedDate']);
$pickerDate = $d."/".$m."/".$y;
?>

</head>