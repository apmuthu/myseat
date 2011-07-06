<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
// clear all old session variables
$_SESSION = array();

// ** set configuration
	include('../config/config.general.php');
	
	require_once '../PLC/plc.class.php';
	$dbAccess = array(
	  'dbName' => $settings['dbName'],
	  'dbUser' => $settings['dbUser'],
	  'dbPass' => $settings['dbPass'],
	  'dbPort' => '3306'
	 );

	$user = new flexibleAccess('',$dbAccess);

	if ( $_GET['logout'] == 1 ){
		$user->logout();
	}

if ( $_GET['form'] == 1 ){
    if ( $user->autologin() ){
		header("Location: ".$_SESSION['forwardPage']);
		exit; //To ensure security
	}

    if( isset($_POST['user']) && isset($_POST['token'])){

		$newpassword = "";
		if ( isset($_POST['nPass1']) && isset($_POST['nPass2']) ) {
			if ( $_POST['nPass1'] == $_POST['nPass2'] ) {
				$newpassword = substr($_POST['nPass1'],0,12);
			}else{
				$user->login_matchFalse();	
				exit; //To ensure security
			}
		}

		$loginAttempt = $user->login(substr($_POST['user'],0,30),substr($_POST['token'],0,12),$newpassword);
        if ( $loginAttempt == 1 ){
			$user->login_true();
        }else if ( $loginAttempt == 0 ){
			$user->login_false();
		}else if ( $loginAttempt == 2 ){
			$user->login_attemptFalse();
    	}else if ( $loginAttempt == 3 ){
			$user->login_newpass();
		}else if ( $loginAttempt == 4 ){
			$user->login_brutforce();
		}
	}else{
			$user->login_form();
    	}
      exit; //To ensure security
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
 
<!-- Website Title --> 
<title>Login</title>

<!-- Meta data for SEO -->
<meta name="description" content="An easy to use Restaurant Reservation System"/>
<meta name="keywords" content="Restaurant Reservation System, Restaurant, Hotel, Reservation"/>
<meta name="author" content="Bernd Orttenburger"/>
<meta name="robots" content="noindex,nofollow" />

<!-- Template stylesheet -->
<link rel="stylesheet" href="../web/css/screen.css" type="text/css" media="all"/>
<link href="css/style.css" rel="stylesheet" media="all" type="text/css" />
<link href="css/jquery.keypad.css" rel="stylesheet" media="all" type="text/css" />

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
<script language="javascript" type="text/javascript" src="js/jquery.keypad.min.js" charset="UTF-8"></script>
<script language="javascript" type="text/javascript">
$(document).ready(function()
{
//show keypad
$('#user').keypad({showOn: 'button', 
    			buttonImageOnly: true, 
				buttonImage: 'img/keyboard.png',
				keypadClass: 'darkKeypad', 
				keypadOnly: false, 
				layout: $.keypad.qwertyLayout, 
				prompt: ''});
$('#token').keypad({showOn: 'button', 
				buttonImageOnly: true, 
				buttonImage: 'img/keyboard.png',
				keypadClass: 'darkKeypad', 
				keypadOnly: false, 
				layout: $.keypad.qwertyLayout, 
				prompt: ''});
//Hide (Collapse) the toggle containers on load
$('#changePass').hide(); 

//Fade in on click
$("#nButton").click(function(){
	 if ($("#changePass").css("display") == "none") {
		$("#sbmt").html("Save"); 
		$("#changePass").show();
		return true;
	}else if ($("#changePass").css("display") == "block"){
		$("#changePass").hide();
		$("#sbmt").html("Log-In"); 
		return false;	
	}
});


});
</script>

<body class="login">
	
	<!-- Begin control panel wrapper -->
	<div id="wrapper">

		<div id="login_top">
			<img src="../web/images/logo_big.png" alt=""/>
		</div>
		
		<br class="clear"/><br/>
				
				<div id="err"></div><br>
				<div id="wrapper2"></div>
	</div>
	<!-- End control panel wrapper -->
	
</body>
</html>
