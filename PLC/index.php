<?php
session_start();
//error_reporting(E_ALL & ~E_NOTICE);
//ini_set("display_errors", 1);

// ** clear all old session variables
$_SESSION = array();
$username = "";

// ** Set redirect page
$forwardPage = "../web/main_page.php?p=1";

// ** set configuration
	include('../config/config.general.php');

// ** init plc login class	
	require_once '../PLC/plc.class.php';
	$dbAccess = array(
	  'dbHost' => $settings['dbHost'],
	  'dbName' => $settings['dbName'],
	  'dbUser' => $settings['dbUser'],
	  'dbPass' => $settings['dbPass'],
	  'dbPort' => $settings['dbPort']
	 );

	$user = new flexibleAccess('',$dbAccess);
	
// ** auto checkout when going to loginpage
	$user->logout();

// ** User LOGIN **

    if( isset($_POST['submit']) ){
		
		// ** init variables
		$validate = true;
		$username = $_POST['user'];
		
		// ** Validate username and password
		if( strlen($username) <4 ) {
			$message = "Username is required.";
			$validate = false;
			
		}else if( strlen($_POST['token']) <4 ) {
			$message = "Password is required.";
			$validate = false;
		}
		
		// ** Check if user wants to change the password
		$newpassword = "";
		if ( isset($_POST['nPass1']) && isset($_POST['nPass2']) ) {
			if ( $_POST['nPass1'] == $_POST['nPass2'] ) {
				$newpassword = substr($_POST['nPass1'],0,12);
			}else{
				$user->login_matchFalse();	
				exit; //To ensure security
			}
		}

		// ** User LOGIN process if $validate is true
		if($validate){
			$loginAttempt = $user->login(substr($_POST['user'],0,30),substr($_POST['token'],0,12),$newpassword);
	        if ( $loginAttempt == 1 ){
				$message = "Login Success.";
				header("Location: ".$forwardPage);
				exit; //To ensure security
	        }else if ( $loginAttempt == 0 ){
				$l = 1 + $user->loginAttempts - $user->fAtmp;
				$message = "Login Failed : Login is not valid. ".$l." attempts left.";
			}else if ( $loginAttempt == 2 ){
				$message = "Login blocked for ".$user->loginTime." minutes: Too many false login attempts.";
				$username = "";
	    	}else if ( $loginAttempt == 3 ){
				$message = "Password successfully changed.";
				$username = "";
			}else if ( $loginAttempt == 4 ){
				$message = "Password unsave! It's not allowed.";
			}else{
				$message = "";
				$username = "";
			}
		}
	}else{
			$message = "";
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

<!-- CSS Stylesheets -->
<link rel="stylesheet" href="../web/css/screen.css" type="text/css" media="all"/>

<!--[if IE 7]>
	<link href="../web/css/ie7.css" rel="stylesheet" type="text/css" media="all">
<![endif]-->

<!--[if IE]>
	<script type="text/javascript" src="../web/js/excanvas.js"></script>
<![endif]-->

<!-- Custom CSS -->
<style type="text/css"> 
.most-recent {
			  -webkit-box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.075);
			  -moz-box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.075);
			  box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.075);
			}
.user    	{
			  font-weight: bold;
     		  font-size: 14px;
			  padding-left: 25px;
	          background: url('img/user.png') no-repeat 5px;
	       	}
.pass    	{
	  	font-weight: bold;
	  font-size: 14px;
			  padding-left: 25px;
              background: url('img/key.png') no-repeat 5px;
          	}	
</style>


<body class="login">
	
	<!-- Begin control panel wrapper -->
	<div id="wrapper">

		<div id="login_top">
			<img src="../web/images/logo_big.png" alt=""/>
		</div>
		
		<br class="clear"/><br/>
				
		<br/><br/>
		<!-- Begin one column box -->
		<div class="onecolumn most-recent" id="most_recent">

			<div class="header">
				<h2><?php echo strtoupper('Admin Backend Log in');?></h2>
			</div>

			<div class="content">
				
				<?php
					if ($message!='') {
						echo'<div id="login_info" class="alert_error" style="margin:auto;padding:auto;">
								<p>
									<img src="../web/images/icon_error.png" alt="success" class="middle"/>
									'.$message.'
								</p>
							</div>';
					}else{
						echo '<br/><p>We are happy to see you return! Please log in to continue.</p>';
					}
					?>
					<br/>
					<form name="loginform" id="loginform" style="margin-left:20px;" action="index.php" method="post">
						<p>
						<label>User</label><br/>
							<input type="text" name="user" class="user" maxlength="20" style="width:300px" value="<?php echo $username;?>"/>
						</p>
						<br/>
						<p>
						<label>Password</label><br/>
							<input type="password" name="token" class="pass" maxlength="12" style="width:300px" value=""/>
						</p>
						<br/>
						<div class="center">
						<input name="submit" type="submit" class="button_dark" value="LOG ME IN">
						<div>
		            	<br/><br/>
					</form>
					<p style="text-align:right;">
					<small style="vertical-align:middle;">256-bit SSL security&nbsp;</small>
						<?
						if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
						    || $_SERVER['SERVER_PORT'] == 443) {
								echo'<img src="img/lock.png" alt="SSL secure" style="vertical-align:middle;"/>';
						}else{
								echo'<img src="img/lock-unlock.png" alt="unsecure" style="vertical-align:middle;"/>';
						}
						?>
					</p>
					
			</div><!-- End content -->
	</div> <!-- End onecolumn -->
	
</body>
</html>
