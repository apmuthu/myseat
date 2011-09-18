<?php
session_start();
/*
**************************************************************************
                         Realtime lookup
**************************************************************************/

include_once('../../config/config.general.php');
	
mysql_connect($settings['dbHost'], $settings['dbUser'], $settings['dbPass']);
mysql_select_db($settings['dbName']) or die ("No Database");

	/* get last id fo page */
	$id = $_REQUEST['lastid']; // last ID
	/* build sql query */
	$sql = sprintf("SELECT count(*) as new FROM reservations 
						WHERE `reservation_id` > '%d'
						AND `reservation_date`='%s' 
						AND `reservation_outlet_id`='%d' 
						AND `reservation_hidden`=0 
						",$id,$_SESSION['selectedDate'],$_SESSION['outletID']);
	
	/* run sql query */
	$fetch = mysql_query($sql);
	/* Free connection resources. */
	mysql_close();
	/* return number of new reservations*/
	echo mysql_result($fetch, 0);
?>