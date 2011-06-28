<?php
// ** set configuration
	include('../config/config.general.php');
// ** database functions
	include('classes/database.class.php');
// ** connect to database
	include('classes/connect.db.php');
// ** all database queries
	include('classes/db_queries.db.php');
// ** set configuration
	include('../config/config.inc.php');
// ** prevent dangerous input
	secureSuperGlobals();
        
 
    $id = (int)$_GET['id'];
    
	//get image from database
        $row = querySQL('view_img');
	header( "Content-type: $row['img_filetype']");
	echo $row['img'];
	
?>