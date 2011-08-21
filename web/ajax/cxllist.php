<?php session_start();
 
// ** set configuration
include('../../config/config.general.php');
// ** connect to database
include('../classes/connect.db.php');
// ** database functions
include('../classes/database.class.php');
// ** localization functions
include('../classes/local.class.php');
// ** database functions
include('../classes/business.class.php');
// translate to selected language
translateSite(substr($_SESSION['language'],0,2),'../');
// ** all database queries
include('../classes/db_queries.db.php');
// ** set configuration
include('../../config/config.inc.php');

// check for new reservations
$new = querySQL('cxl_list');

if($new!=''){
	$i = 1;
	$message = "<div style='width:400px; padding-left:23px;'>";
	$message .= "<h2>CXL "._overview."</h2>";
	$message .= "<p>"._cxl_text."</p><br/>";
	foreach ($new as $row) {
		$message .= $i.". ".printTitle($row->reservation_title)." <strong>".$row->reservation_guest_name."</strong> "._canceled_." ".$row->count."x<br/><br/>";
		$i++;
	}
	$message .= "</div>";
	echo $message;
}

?>