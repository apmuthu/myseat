<?php
/*
**************************************************************************
          AutoSuggest for new reservation build a database query
**************************************************************************/
include_once('../../config/config.general.php');

$return_arr = array();
	
mysql_connect($settings['dbHost'], $settings['dbUser'], $settings['dbPass']);
mysql_select_db($settings['dbName']) or die ("No Database");
mysql_query("SET NAMES 'utf8'");

$sql = "SELECT * FROM $dbTables->reservations WHERE reservation_guest_name LIKE '".$_GET['term']."%' GROUP BY reservation_guest_name ";
$fetch = mysql_query($sql);

	/* Retrieve and store in array the results of the query.*/
if($fetch){
	while ($row = mysql_fetch_array($fetch, MYSQL_ASSOC)) {
		$row_array['value'] = $row['reservation_guest_name'];
		$row_array['reservation_guest_adress'] = $row['reservation_guest_adress'];
		$row_array['reservation_guest_city'] = $row['reservation_guest_city'];
		$row_array['reservation_guest_email'] = $row['reservation_guest_email'];
		$row_array['reservation_guest_phone'] = $row['reservation_guest_phone'];
		$row_array['reservation_advertise'] = $row['reservation_advertise'];

	    array_push($return_arr,$row_array);
	}
}	
	/* Free connection resources. */	
	mysql_close();
	
	/* Toss back results as json encoded array. */
	echo json_encode($return_arr);
?>