<?php
/* Connection to Database */
// ** set configuration
include('../../config/config.general.php');
// ** database functions
include('../classes/database.class.php');
// ** connect to database
include('../classes/connect.db.php');
// ** all database queries
include('../classes/db_queries.db.php');

if($_POST['cellid']){
	// prevent dangerous input
	secureSuperGlobals();
	
	$value = ($_POST['action']=='enable') ? '1' : '0';
	$id = (int)$_POST['cellid'];
	
	if (isset($id) ) {
		$sql = querySQL('user_activate');
		echo $sql;
	}else{
		echo "AJAX Error";
	}
}
?>