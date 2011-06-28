<?php session_start();
 
// ** set configuration
include('../../config/config.general.php');
// ** connect to database
include('../classes/connect.db.php');
// ** database functions
include('../classes/database.class.php');
// ** localization functions
include('../classes/local.class.php');
// translate to selected language
translateSite(substr($_SESSION['language'],0,2),'../');
// ** all database queries
include('../classes/db_queries.db.php');
// ** set configuration
include('../../config/config.inc.php');

// check for new reservations
$new = querySQL('notifications');

if($new!=''){
	$message = _recent_reservations.":<br/>";
	foreach ($new as $row) {
		$message .= substr($row->reservation_time,0,5)." ".$row->reservation_guest_name." "._for_." ".$row->outlet_name."<br/>";
	}
	echo $message;
}

?>