<?php
session_start();
$_SESSION['language'] = ($_SESSION['language']) ? $_SESSION['language'] : 'en';

// Check for a unique username
// ** set configuration
    include('../../config/config.general.php');
// ** database functions
    include('../classes/database.class.php');
// ** connect to database
    include('../classes/connect.db.php');
// ** all database queries
    include('../classes/db_queries.db.php');
// ** localization functions
    include('../classes/local.class.php');
// ** set configuration
    include('../../config/config.inc.php');
// translate to selected language
    translateSite(substr($_SESSION['language'],0,2),'../');

// prevent dangerous input
secureSuperGlobals();

	$id = $_REQUEST['lastid']; // last ID
	$newreservation = querySQL('realtime_res');

	if( $newreservation > 0 ){
		echo "<div class='alert_warning'><p style='margin-bottom:10px;'><img src='images/icon_info.png' alt='error' class='middle'/>";
		echo '<a href="selectedDate='.$_SESSION['selectedDate'].'">('.$newreservation.') '._new_entry.'</a>';
		echo "</p></div>";
	}
?>