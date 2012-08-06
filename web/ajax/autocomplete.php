<?php
/*
**************************************************************************
                  AutoSuggest build a database query
**************************************************************************/
include_once('../../config/config.general.php');

$return_arr = array();
	
mysql_connect($settings['dbHost'], $settings['dbUser'], $settings['dbPass']);
mysql_select_db($settings['dbName']) or die ("No Database");
mysql_query("SET NAMES 'utf8'");

$field = $_GET['field'];
$term = $_GET['term'];

// prevent SQL injection 
if ($field == 'reservation_guest_name' || $field == 'reservation_booker_name' ) {
	
	$sql = "SELECT DISTINCT ".$field." FROM $dbTables->reservations WHERE ".$field." LIKE '".$term."%' ORDER BY ".$field." ASC ";
	$fetch = mysql_query($sql);
		/* Retrieve and store in array the results of the query.*/
	if($fetch){
		while ($row = mysql_fetch_array($fetch, MYSQL_ASSOC)) {
			$row_array['value'] = $row[$field];

	        array_push($return_arr,$row_array);
	    }

	}

	/* Free connection resources. */
	mysql_close();

	/* Toss back results as json encoded array. */
	echo json_encode($return_arr);
}
?>