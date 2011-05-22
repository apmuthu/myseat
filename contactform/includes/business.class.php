<?php
function getHost($Address) {
   $parseUrl = parse_url(trim($Address));
   return trim($parseUrl[host] ? $parseUrl[host] : array_shift(explode('/', $parseUrl[path], 2)));
}

function checkMobile(){
// Mobile Browser 
// Device Detection
// (c) by Andy Moore
 
	$mobile_browser = '0';
 
	if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
	    $mobile_browser++;
	}
 
	if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
	    $mobile_browser++;
	}    
 
	$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
	$mobile_agents = array(
	    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
	    'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
	    'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
	    'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
	    'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
	    'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
	    'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
	    'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
	    'wapr','webc','winw','winw','xda ','xda-');
 
	if (in_array($mobile_ua,$mobile_agents)) {
	    $mobile_browser++;
	}
 
	if (strpos(strtolower($_SERVER['ALL_HTTP']),'OperaMini') > 0) {
	    $mobile_browser++;
	}
 
	if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'windows') > 0) {
	    $mobile_browser = 0;
	}

	// return result
	return $mobile_browser;
}

// calculate and print select list with interval times
function timeList($format,$intervall,$field='',$select,$open_time='00:00:00',$close_time='24:00:00',$showtime=0) 
{ 
		GLOBAL $general,$availability, $tbl_availability;
		// calculate after midnight
		$day    = date("d");
		$endday = ($open_time < $close_time) ? date("d") : date("d")+1;
		$month  = date("m");
		$year   = date("Y");
		
		// init timeslots array
		$timeslots = array();
		// build list of timeslots from starttime to endtime
		// in predefined intervall
		
		// set weather we have a daily or general break
		// daily has priority
		$week_day = date('w', strtotime($_SESSION['selectedDate']) );
		$breaktime_open = ($_SESSION['selOutlet'][$week_day.'_open_break'] != '00:00:00') ? $_SESSION['selOutlet'][$week_day.'_open_break'] : $_SESSION['selOutlet']['outlet_open_break'];
		$breaktime_close = ($_SESSION['selOutlet'][$week_day.'_close_break'] != '00:00:00') ? $_SESSION['selOutlet'][$week_day.'_close_break'] : $_SESSION['selOutlet']['outlet_close_break'];
		
		// limit booking time at actual day
		// not to book past times
		if ($_SESSION['selectedDate'] == date('Y-m-d') && date('H:i:s') > $open_time) {
				//Set opentime to rounded actual time
				$minutes = ceil(date('i')/$general['timeintervall'])*$general['timeintervall'];
				$open_time = date('H').":".$minutes;
		}
		// floor($min/60*4)/4*60

		// calculate dates & times
		list($h1,$m1)		= explode(":",$open_time);
		list($h2,$m2)		= explode(":",$close_time);
		list($h3,$m3)		= explode(":",$breaktime_open);
		list($h4,$m4)		= explode(":",$breaktime_close);
		$value  		= mktime($h1+0,$m1+0,0,$month,$day,$year);
		$endtime		= mktime($h2+0,$m2+0,0,$month,$endday,$year);
		$open_break  		= mktime($h3+0,$m3+0,0,$month,$day,$year);
		$close_break  		= mktime($h4+0,$m4+0,0,$month,$day,$year);
		$i 			= 1;
		
		echo"<select name='$field' id='$field' size='1' class='drop required' title=' ' >\n";
		echo "<option value='' ";
		if ($select=='') {
			echo "selected='selected'";
		}
		echo ">--</option>\n";
		while( $value <= $endtime )
		{ 
			// get loose of break
			if( $value <= $open_break || ($value >= $close_break && $value<=$endtime) ){
			// Generating the time drop down menu
			//check for maximum passerby
			$max_passerby = ($_SESSION['passerby_max_pax'] == 0) ? $_SESSION['selOutlet']['outlet_max_capacity'] : $_SESSION['passerby_max_pax'];
			$ava_passerby = $max_passerby - $_SESSION['passbyTime'][date('H:i:s',$value)];
				if($ava_passerby>0){
					echo "<option value='".date('H:i',$value)."'";
					if ( $select == date('H:i:s',$value) ) {
						echo " selected='selected' ";
					}
				
					 $tbl_capacity = $_SESSION['outlet_max_tables']-$tbl_availability[date('H:i',$value)];
					 $pax_capacity = ($tbl_capacity >=1) ? $_SESSION['outlet_max_capacity']-$availability[date('H:i',$value)] : 0;
					 if ( $pax_capacity == 0 ) {
						echo " disabled='disabled' ";
					 }
				
					echo " >";
				
					$txt_value = ($format == 24) ? date('H:i',$value) : date("g:i a", $value);
					echo $txt_value;
					if ($showtime == 1) {
						echo " - ".$ava_passerby." Seats free";
					}
					echo"</option>\n";
				}
			}
			// calculate new time
			$value = mktime($h1+0,$m1+$i*$intervall,0,$month,$day,$year); 
			$i++;
		} 
		echo"</select>\n";
}

function personsList($max_pax = '12', $standard = '4',$tablename='reservation_pax'){
	echo"<select name='".$tablename."' id='".$tablename."' class='drop' size='1' $disabled>\n";	
		
		for ($i=1; $i <= $max_pax; $i++) { 
			 echo "<option value='".$i."'";
			echo ($i == $standard) ? "selected='selected'" : "";
			echo ">".$i."</option>\n";
		}

	echo "</select>\n";
}

function titleList($title='',$disabled=''){
	        // translation
		GLOBAL $lang;
   
		echo "<select name='reservation_title' id='reservation_title' class='drop' title=' ' size='1' $disabled>\n";

		// Empty
		/*
		echo "<option value='' ";
		echo ($title=="") ? "selected='selected'" : "";
		echo ">--</option>\n";
		*/
		// Sir
		echo "<option value='M' ";
		echo ($title=='M') ? "selected='selected'" : "";
		echo ">".$lang['_M_']."</option>\n";
		// Madam
		echo "<option value='W' ";
		echo ($title=='W') ? "selected='selected'" : "";
		echo ">".$lang['_W_']."</option>\n";
		// Dr.
		echo "<option value='D' ";
		echo ($title=='D') ? "selected='selected'" : "";
		echo ">".$lang["_DR_"]."</option>\n";
		// Prof.
		echo "<option value='P' ";
		echo ($title=='P') ? "selected='selected'" : "";
		echo ">".$lang["_PROF_"]."</option>\n";
		// Family
		echo "<option value='F' ";
		echo ($title=='F') ? "selected='selected'" : "";
		echo ">".$lang['_F_']."</option>\n";
		// Company
		echo "<option value='C' ";
		echo ($title=='C') ? "selected='selected'" : "";
		echo ">".$lang['_C_']."</option>\n";
		
		echo "</select>\n";
}

function defineOffDays(){
	
	$date_string = "";
	
	$dayoffs  =	querySQL('maitre_dayoffs');
	
	if($dayoffs){
		foreach ($dayoffs as $dayoff) {
			$date_string .= "'".$dayoff->maitre_date."',";
		}
	}
	
	$outlet_closedays   = querySQL('outlet_closedays');
	$outlet_closedays = "'".$outlet_closedays."'";
	
	$day		= mktime(0, 0, 0, date('m'), date('d'), date('y'));
	$enddate 	= mktime(0, 0, 0, date('m')+6, date('d'), date('y'));

	while ($day < $enddate) {
		if ( strpos($outlet_closedays, date("w",$day)) === false) {
			// do nothing ; '=== false' is manatory
		}else{
			$date_string .= "'".date('Y-m-d',$day)."',";
		}
		//add 1 day
		$day = $day + 86400;
	}
	
	$date_string = substr($date_string,0,-1);
	//print_r($dayoffs);
	//echo $outlet_closedays;
	echo $date_string;
}

function processBooking(){
// rather than recursively calling query, insert all rows with one query
	 GLOBAL $general;
	 // database table to store reservations
	 $table ='reservations';
	 // reservation date
	 $reservation_date = $_SESSION['selectedDate'];

	// prepare POST data for storage in database:
	// $keys
	// $values 
	if( $_POST['action'] == 'submit') {
		$keys = array();
		$values = array();
		$i=1;
		
		// prepare arrays for database query
		foreach($_POST as $key => $value) {
			if( $key != "action"
			      && $key != "dbdate"
			      && $key != "reservation_date"
			      && $key != "recurring_dbdate"
			      && $key != "captcha"
			      && $key != "barrier"
			      && $key != "reservation_author"
			      && $key != "email_type"
			      && $key != "captchaField1"
			      && $key != "captchaField2"
			      && $key != "captchaField3"){
			      	$keys[$i] = $key;
		     		$values[$i] = "'".$value."'";
			}
			// remember some values
			if( $key == "reservation_date" ){
			   $reservation_date = strtotime($value);
			}else if($key == 'reservation_booker_name'){	
			   $_SESSION['author'] = $value;
			}else if($key == 'reservation_time'){	
			   $_SESSION['reservation_time'] = "'".$value."'";
			}else if($key == 'reservation_pax'){	
			   $_SESSION['reservation_pax'] = "'".$value."'";
			}
			
			if( $key == "reservation_date" ){
			   $keys[$i] = $key;
		     	   $values[$i] = "'".$_SESSION['selectedDate']."'";
			}
			
			$i++;
		} // END foreach $_POST

		// =-=-=-=Store in database =-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			// clear old booking number
			$_SESSION['booking_number'] = '';
			// variables
			$res_pax = ($_POST['reservation_pax']) ? (int)$_POST['reservation_pax'] : 0;
			
			// sanitize old booking numbers
			$clr = querySQL('sanitize_unique_id');
			
			// create and store booking number
			if (!$_POST['reservation_id'] || $_POST['reservation_id']=='') {
			    $_SESSION['booking_number'] = uniqueBookingnumber();
			    //$_SESSION['messages'][] = _booknum.":&nbsp;&nbsp;' ".$_SESSION['booking_number']." '";
			    $keys[] = 'reservation_bookingnumber';
			    $values[] = "'".$_SESSION['booking_number']."'";
			}
			
		  // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		  // enter into database
		  // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
			// build new reservation date
			$index = array_search('reservation_date',$keys);
			// build for availability calculation

			$index = array_search('reservation_wait',$keys);
			if($index){
				$values[$index] = '1';
				$waitlist = '1';
			}
			
			
			//Check Availability
			// =-=-=-=-=-=-=-=-=
			
			// get Pax by timeslot
			$resbyTime = reservationsByTime('pax');
			$tblbyTime = reservationsByTime('tbl');
			// get availability by timeslot
			$occupancy = getAvailability($resbyTime,$general['timeintervall']);
			$tbl_occupancy = getAvailability($tblbyTime,$general['timeintervall']);
			
			//cut both " ' " from reservation_pax
			$res_pax = substr($_SESSION['reservation_pax'], 0, -1);
			$res_pax = substr($_SESSION['reservation_pax'], 1);
			
			$startvalue = $_SESSION['reservation_time'];
			//cut both " ' " from reservation_time
			$startvalue = substr($startvalue, 0, -1);
			$startvalue = substr($startvalue, 1);
			
			  $val_capacity = $_SESSION['outlet_max_capacity']-$occupancy[$startvalue];
			  $tbl_capacity = $_SESSION['outlet_max_tables']-$tbl_occupancy[$startvalue]; 

			if( (int)$res_pax > $val_capacity || $tbl_capacity < 1 ){
				//prevent double entry 	
				$index = array_search('reservation_wait',$keys);
				if($index>0){			
					  $values[$index] = '1'; // = waitlist
					  $waitlist = '1';
				}else{
					  // error on new entry
					  $keys[] = 'reservation_wait';
					  $values[] = '1'; // = waitlist
					  $waitlist = '1';
				}
			}
			// END Availability

		  if ($waitlist != 1){
			// number of database fields
			$max_keys = count($keys);
			// enter into database
			// -----
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
			$result = query($query);
			$_SESSION['result'] = $result;
			
			// Reservation ID
	 		$resID = mysql_insert_id();
		
			// *** send confirmation email
			if ( $_POST['email_type'] != 'no' ) {
				include('../web/classes/email.class.php');
			}
			
			// store new reservation in history
			$result = query("INSERT INTO `res_history` (reservation_id,author) VALUES ('%d','%s')",$resID,$_SESSION['author']);
			// Reservation was done
			$waitlist = 2;
		  }	
			// reservation done, handle back waitlist status
			return $waitlist;
	 }
}
?>