<?php
ob_start();

//ini_set('display_errors', 1);
//ini_set('error_reporting', E_ALL);
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//  Common database class
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

	// ** prevent attack, secure input
	function escapeInput($str) {
	  $str = (is_array($str)) ? $str : htmlentities($str, ENT_QUOTES, 'UTF-8');
	  $str = get_magic_quotes_gpc()?stripslashes($str):$str;
	  $str = (is_array($str)) ? $str : mysql_real_escape_string($str);
	  return $str;
	}
	// ** secure all POST and GET data
	function secureSuperGlobals() {
	        	// Escape POST data for SQL Injection
				foreach($_POST AS $key => $value) { 
					if ( $key != 'description' ) {
						$_POST[$key] = escapeInput($value);
					}else{
						$value = get_magic_quotes_gpc()?stripslashes($value):$value;
						$value = (is_array($value)) ? $value : mysql_real_escape_string($value);
						$_POST[$key] = $value;
					}
				}
				// Escape GET data for SQL Injection
				foreach($_GET AS $key => $value) { $_GET[$key] = escapeInput($value); }
	}
	// ** build query
	function setQuery($args) {
		call_user_func_array('query', $args);
	}
	// ** execute query
	function query($query) {
		$args = func_get_args();
		if (count($args) > 1) {
			/* array_shift($args);
			$args = array_map('escapeInput', $args);
			array_unshift($args, $query); */
			$query = call_user_func_array('sprintf', $args);
		}
		//DeBUGGING
		//echo "SEND QUERY: $query<br/>";
		$result = mysql_query($query) or die ("Error in query: $query. ".mysql_error());
		return $result;
	}
	// ** retrieve a single row in query resultset as object
	function getRow($result = '', $query = null) {
		if ($query) {
			$args = func_get_args();
			$result = setQuery($args);
		}
		if ($result) {
			if ($row = mysql_fetch_assoc($result)) {
				return (object) $row;
			}
		}
		return false;
	}
	// ** retrieve an array of objects from query resultset
	function getRowList($result = '', $query = null) {
		if ($query) {
			$args = func_get_args();
			$result = setQuery($args);
		}
		if ($result) {
			$rows = array();
			while($row = mysql_fetch_assoc($result)) {
				$rows[] = (object) $row;
			}
			return $rows;
		} else {
			return false;
		}
	}
	// ** retrieve an array from query resultset
	function getRowListarray($result = '', $query = null) {
		if ($query) {
			$args = func_get_args();
			$result = setQuery($args);
		}
		if ($result) {
			$rows = array();
			$rows = mysql_fetch_assoc($result);
			return $rows;
		} else {
			return false;
		}
	}
	// ** Retrieve a single result
	function getResult($result = '', $query = null) {
		if ($query) {
			$args = func_get_args();
			$result = setQuery($args);
		}
		if ($result) {
			if ($row = mysql_fetch_row($result)) {
				return $row[0];
			}
		}
		return false;
	}
	// ** retrieve number of rows affected in last insert|update query
	function getAffectedRows($result) {
		return mysql_affected_rows($result);
	}
	// ** get the auto-increment column of the last insert
	function getInsertId($result) {
		return mysql_insert_id($result);
	}
	// ** retrieve number of rows affected in last select
	function getNumRows($result) {
		return mysql_num_rows($result);
	}
	
	
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//     Store in database
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

function writeForm($table =''){
	// rather than recursively calling query, insert all rows with one query
		GLOBAL $general,$global_basedir,$settings;
		$_SESSION['errors'] = array();
	// prepare POST data for storage in database:
	// $keys
	// $values 
	if($table) {
		$keys = array();
		$values = array();
		$i=1;

			// prepare day offs
			if ($table == 'outlets') {
				$dayoffs = '';
				for ($i=1; $i <= 7; $i++) {
					$field = "outlet_closeday_".$i;

					if ( isset($_POST[$field])){
						$dayoffs .= $_POST[$field].",";
					}

				}
				if($dayoffs !=''){
					$dayoffs = substr($dayoffs,0,-1);
				}
					$keys[$i] = 'outlet_closeday';
					$values[$i] = "'".$dayoffs."'";
					$i++;
				
			}
		
		// prepare arrays for database query
		foreach($_POST as $key => $value) {
			if ($key == 'saison_start_month' || $key == 'saison_start_day' || $key == 'saison_end_month' || $key == 'saison_end_day') {
				
				$saison_start = $_POST['saison_start_month'].$_POST['saison_start_day'];
				$saison_end = $_POST['saison_end_month'].$_POST['saison_end_day'];
			
			}else if($key == 'password'){
			
				if($value != "EdituseR"){
					$keys[$i] = $key;
					$dbAccess = array(
					  'dbName' => $settings['dbName'],
					  'dbUser' => $settings['dbUser'],
					  'dbPass' => $settings['dbPass'],
					  'dbPort' => '3306'
					 );
					$insert = new flexibleAccess('',$dbAccess);
					$password = $insert->hash_password($value);
					$values[$i] = "'".$password."'";
				}
				
			}else if($key == 'outlet_child_capacity'){	
				
				// make value always positive
				$keys[$i] = $key;
				$values[$i] = "'".abs($value)."'";
				
			}else if($key == 'outlet_child_capacity'){	

				// make value always positive
				$keys[$i] = $key;
				$values[$i] = "'".$value."'";

			}else if($key == 'role'){	
				if ($value<2) {
					$value = 6;
				}
				// add role field
				$keys[$i] = $key;
				$values[$i] = "'".$value."'";
				
			}else if( $key != "action"
				 && $key != "email_type"
				 && $key != "password2"
				 && $key != "eventID"
				 && $key != "e_datepicker"
				 && $key != "ev_datepicker"
				 && $key != "s_datepicker"
				 && $key != "MAX_FILE_SIZE"
				 && $key != "propertyID"
				 && $key != "token"
				 && $key != "verify"
				 && $key != "PHPSESSID"
				 && substr($key,0,15) != "outlet_closeday"){
					
					// all other 'normal fields'
					$keys[$i] = $key;
					$values[$i] = "'".$value."'";
					
			}
			
			$i++;
		} // END foreach $_POST
		
		// build webform field on outlets
		if($table == 'outlets') {
			$index = array_search('webform',$keys);
			if(!$index){
				$keys[] = 'webform';
				$values[] = 0;
			}
		}
		
		// img & logo upload
		// =-=-=-=-=-=
		
		// img upload
			if ($_FILES['img']['error'][0] > 0){
			  $_SESSION['errors'][] = _sorry;
			}else{
				if ( ($_FILES['img']["type"][0] == "image/gif"
				  || $_FILES['img']["type"][0] == "image/jpeg"
				  || $_FILES['img']["type"][0] == "image/png" )
				  && $_FILES['img']["size"][0] < 2000000 )
				  {
				  //$imgName 	  = $_FILES['img_logo']['name'];
				  $img_type   = substr($_FILES['img']["type"][0],6);
				  $imgName 	  = randomPassword(24, true, true, false).".".$img_type;
				  
				  $uploadpath = substr(dirname(__FILE__),0,-7);
				  $result     = move_uploaded_file($_FILES['img']["tmp_name"][0],"../uploads/img/".$imgName);
					$keys[] = 'img_filename';
					$values[] = "'".$imgName."'";
				  }
			}
		// logo upload
			if ($_FILES['img']['error'][1] > 0){
			  $_SESSION['errors'][] = _sorry;
			}else{
				if (($_FILES['img']["type"][1] == "image/gif"
				  || $_FILES['img']["type"][1] == "image/jpeg"
				  || $_FILES['img']["type"][1] == "image/png")
				  && $_FILES['img']["size"][1] < 2000000)
				  {
				  //$imgName 	  = $_FILES['img_logo']['name'];
				  $img_type   = substr($_FILES['img']["type"][1],6);
				  $imgName 	  = randomPassword(24, true, true, false).".".$img_type;
				
				  $uploadpath = substr(dirname(__FILE__),0,-7);
				  $result     = move_uploaded_file($_FILES['img']["tmp_name"][1],"../uploads/logo/".$imgName);
					$keys[] = 'logo_filename';
					$values[] = "'".$imgName."'";
				  }
			}
			
			$_SESSION['reservation_date'] = date('Y-m-d',$reservation_date);
			$_SESSION['recurring_date'] = date('Y-m-d',$recurring_date);
		
		// outlets build start and enddate
		if($saison_start!='' && $saison_end!=''){
			$keys[] = 'saison_start';
	    	$values[] = "'".$saison_start."'";
			$keys[] = 'saison_end';
	    	$values[] = "'".$saison_end."'";
		}

		// =-=-=-=Store in database =-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		  
		  // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		  // No reservation, everything else to store
		  // enter into database
		  // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

			// number of database fields
			$max_keys = count($keys);
			$query = "INSERT INTO `$table` (".implode(',', $keys).") VALUES (".implode(',', $values).") ON DUPLICATE KEY UPDATE ";
				// Build 'on duplicate' query
				for ($i=1; $i <= $max_keys; $i++) {
					if($keys[$i]!=''){
				 		$query .= $keys[$i]."=".$values[$i].",";
					}else{
						$max_keys++;
					}
				} 
			// run sql query 				
			$query = substr($query,0,-1);
			    //DEbugging
			    //echo $query;				   
			$result = query($query);
			$new_id = mysql_insert_id();
			
			// Set STANDARD settings for new property
			if ($table == 'properties' && $_POST['id'] == 0) {
			  include('register/standard_settings.inc.php');
			}

			// -----
			return $new_id;
			
 }
}

?>