<?php
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Passwords</title>
<meta name="robots" content="noindex,nofollow" />
</head>
<body>
	<h2>Passwords</h2>
	
	<?php
		for ($i=0; $i < 30; $i++) {
			for ($j=0; $j < 5; $j++) { 
				echo "<span style='margin-right:15px;'>".$user->ramdomPassword()."</span><br/>";
			}
			echo "<br/>";
		}
	?>	

</body>
</html>
