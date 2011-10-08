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

$_SESSION['resID'] = $_GET['id'];

// check for reservation/guest details
$new = querySQL('reservation_info');

if($new!=''){
	foreach ($new as $row) {
		$bookingnumber = ($row->reservation_bookingnumber == '') ? '--' : $row->reservation_bookingnumber;
		$_SESSION['reservation_guest_name'] = $row->reservation_guest_name;
		$date = date($general['dateformat_short'],strtotime($row->reservation_date));
		$time = formatTime($row->reservation_time,$general['timeformat']);
		// count total visits
		$visits = querySQL('reservation_visits');
		// collect history infos about guest
		$history = 	querySQL('reservation_history');
		
		echo "<h3 style='width:500px;'>".printTitle($row->reservation_title)." ".$_SESSION['reservation_guest_name']."</h3>";
		echo "<h5>".$date." ".$time." | ".$row->outlet_name."</h5><br/>";
		echo "<table class='detailbig'>";
		echo "<tr><td style='width:160px;'>"._booknum."</td><td><strong>".$bookingnumber."</strong></td></tr>";
		echo "<tr><td>"._phone_room."</td><td><strong>".$row->reservation_guest_phone."</strong></td></tr>";
		echo "<tr><td>"._email."</td><td><strong>".$row->reservation_guest_email."</strong>";
		if ( $row->reservation_advertise =='YES' ) {
			echo"<img src='images/icons/mail_yes.png' class='mail-icon' title='Advertise allowed'/>";
		}else{
			echo"<img src='images/icons/mail_no.png'  class='mail-icon' title='No advertise'/>";	
		}
		echo "</td></tr>";
		echo "<tr><td>"._adress."</td><td><strong>".$row->reservation_guest_adress."</td></tr>";
		echo "<tr><td></td><td>".$row->reservation_guest_city."</strong></td></tr>";
		echo "<tr><td>"._visits."</td><td><strong>".$visits."</strong></td></tr>";
		echo "<tr><td>"._history."</td><td>";
		
		if(count($history)>1){
			echo "<ul class='global'>";
			foreach ($history as $row) {
				if (trim($row->reservation_notes)!=''){
					echo "<li>".$row->reservation_notes."</li>";
				}
			}
			echo "</ul>";
		}else{
			echo "--";
		}
		
		echo "</td></tr>";
		echo "</table><br/>";
	}
}

?>