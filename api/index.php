<?php
session_start();
//error_reporting(E_ALL & ~E_NOTICE);
//ini_set("display_errors", 1);

// ** set configuration
    include('../config/config.general.php');
// ** database functions
    include('../web/classes/database.class.php');
// ** connect to database
    include('../web/classes/connect.db.php');
// ** all database queries
    include('../web/classes/db_queries.db.php');
// ** set configuration
    include('../config/config.inc.php');
// ** set configuration
    include('1/rest.class.php');

// prevent dangerous input
secureSuperGlobals();

// Fetch form POST data 
$name 		 = $_POST['name'];
$url 	 	 = $_POST['website'];
$description = $_POST['description'];
$action 	 = $_POST['action'];
$message 	 = "";


// ** Process form **

    if( isset($_POST['submit']) ){
		
		// ** init variables
		$validate = true;
		
		// ** Validate username and password
		if( strlen($name) <4 ) {
			$message .= "Name is required.<br/>";
			$validate = false;	
		}
		if( strlen($_POST['description']) <4 ) {
			$message .= "Description is required.<br/>";
			$validate = false;
		}

		if(is_url($url)==FALSE){
			$message .= "'".$url."' is not a valid URL.";
			$validate = false;
		}

		// ** save API userdata
		if($validate && $action == "api"){

			$token 		= generate_key();
			$visitor_ip = $_SERVER[REMOTE_ADDR];
			$now		= date('Y-m-d H:i:s');

			$sql = "INSERT INTO `api_users` (
					`token`, `name`, `website`, `description`, `active`, `last_ip`, `created`)
					VALUES ( '".$token."' , '".$name."' , '".$url."' , '".$description."' , '1' , '".$visitor_ip."' , '".$now."')";
			$result = query($sql);
				
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
<title>Request API token</title>

<!-- Meta data for SEO -->
<meta name="description" content="An easy to use Restaurant Reservation System"/>
<meta name="keywords" content="Restaurant Reservation System, Restaurant, Hotel, Reservation"/>
<meta name="author" content="Bernd Orttenburger"/>
<meta name="robots" content="noindex,nofollow" />

<!-- Javascript -->

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
.alert_success {
	cursor: default !important;
}	
</style>


<body class="login">
	
	<!-- Begin control panel wrapper -->

		<div class="topbar" id="topbar">
		    <div class="container">
				<a class="brand" href="<?php echo dirname($_SERVER['PHP_SELF']);?>/main_page.php"><img src="../web/images/logo.png" alt=""/>
				</a>
		    </div>
		</div>
		
		<div id="wrapper">	
		
		<br/>
		<!-- Begin one column box -->
		<div class="onecolumn most-recent" id="most_recent">

			<div class="header">
				<h2><?php echo strtoupper('Request API Token');?></h2>
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
					}else if (isset($_POST['submit'])) {
						echo'<div id="login_info" class="alert_success" style="margin:auto;padding:auto;">
								<p>
									<label>API TOKEN:</label>
									<input name="token" style="width:340px" value="'.$token.'"/>
								</p>
							</div>';
					}else{
						echo '<br/><p>Get your API token.</p>';
					}
					?>
					<br/>
					<form name="apiform" id="apiform" style="margin-left:20px;" action="index.php" method="post">
						<p>
						<label>Name</label><br/>
							<input type="text" name="name" maxlength="40" style="width:300px" value="<?php echo $name; ?>" class="required" />
						</p>
						<br/>
						<p>
						<label>Website</label><br/>
							<input type="text" name="website" class="required" maxlength="60" style="width:300px" value="<?php echo ($url!='') ? $url : "http://www.";?>"/>
						</p>
						<br/>
						<p>
						<label>Describe your use of the API</label><br/>
							<input type="text" name="description" maxlength="200" style="width:300px" value="<?php echo $description; ?>"/>
						</p>
						<br/>
						<input type="hidden" name="action" value="api"/>
						<div class="center">
						<input name="submit" type="submit" class="button_dark" value="Request Token">
						<div>
		            	<br/><br/>
					</form>
					
			</div><!-- End content -->
	</div> <!-- End onecolumn -->

</body>
</html>
