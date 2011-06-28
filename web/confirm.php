<?php
//$_SESSION['language'] = ($_SESSION['language']!='') ? $_SESSION['language'] : 'en_EN';

// Check for a unique username
// ** set configuration
    include('../config/config.general.php');
// ** database functions
    include('classes/database.class.php');
// ** connect to database
    include('classes/connect.db.php');
// ** all database queries
    include('classes/db_queries.db.php');
// ** localization functions
    include('classes/local.class.php');
// ** set configuration
    include('../config/config.inc.php');
// translate to selected language
    translateSite();

// prevent dangerous input
	secureSuperGlobals();

	$_SESSION['confHash'] = $_GET['c'];

	$errorMessage = "";
	$validCount = 0;

    //check confirmation hash with the database
    $result = querySQL('check_confirm_code');
	//print_r($result);
	//$validCount = mysql_num_rows($result);

	if($result['active'] == 1){ $errorMessage .= _errorMessage_all; }
	if(!$result){ $errorMessage .= _errorMessage_no; }

	if(empty($errorMessage))
	{    
		$result = querySQL('user_confirm_activate');

	 	$errorMessage .= _errorMessage_yes;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
 
<!-- Website Title --> 
<title>Account Activation</title>

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
								<h2>Account Activation</h2>
							</div>

							<div class="content">
								<div id="login_info" class="alert_info" style="margin:auto;padding:auto;">
									<p>
										<img src="images/icon_info.png" alt="success" class="middle"/>
										<?php echo $errorMessage;?>
									</p>
							</div>
									<br /><center>
									<input type="button" value="Back" onClick="location.href='../web/index.php'" 
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
