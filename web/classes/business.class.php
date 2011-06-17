<?php
/*
Searches an array for a given value (insensitive case) and returns the corresponding key
*/
function array_isearch($value, $array)
{
   while (list($key, $val) = each($array))
   {
      $val = strtolower($val);
	  $value = strtolower($value);

      if($val == $value) return $key;
   }
   return false;
}

// calculate and print select list with intervall times
function getTimeList($format,$intervall,$field='',$select,$open_time='00:00:00',$close_time='24:00:00',$showtime=0,$required='') 
{ 
		GLOBAL $availability, $tbl_availability;
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
		
		// calculate dates & times
		list($h1,$m1)		= explode(":",$open_time);
		list($h2,$m2)		= explode(":",$close_time);
		list($h3,$m3)		= explode(":",$breaktime_open);
		list($h4,$m4)		= explode(":",$breaktime_close);
		$value  			= mktime($h1+0,$m1+0,0,$month,$day,$year);
		$endtime		 	= mktime($h2+0,$m2+0,0,$month,$endday,$year);
		$open_break  		= mktime($h3+0,$m3+0,0,$month,$day,$year);
		$close_break  		= mktime($h4+0,$m4+0,0,$month,$day,$year);
		$i 					= 1;
		
		//echo $value."/".$endtime."/".date('H:i',$endtime)."//"; // error reporting
		
		echo"<select name='".$field."' id='".$field."' size='1' title=' ' ".$required.">\n";
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
			      echo "<option value='".date('H:i',$value)."'";
			      if ( $select == date('H:i:s',$value) ) {
				      echo "selected='selected'";
			      }
			      echo " >";			
								
			      $tbl_capacity = $_SESSION['outlet_max_tables']-$tbl_availability[date('H:i',$value)];
			      $pax_capacity = ($tbl_capacity >=1) ? $_SESSION['outlet_max_capacity']-$availability[date('H:i',$value)] : 0;

			      $txt_value = ($format == 24) ? date('H:i',$value) : date("g:i a", $value);
			      echo $txt_value;
				
			      if ($showtime == 1) {
				 echo " - ".$pax_capacity." "._seats." "._free;
			      }
			      
			      echo"</option>\n";
			}
			// calculate new time
			$value = mktime($h1+0,$m1+$i*$intervall,0,$month,$day,$year); 
			$i++;
		} 
		echo"</select>\n";
}
// calculate and print select list with intervall times
function widgetTimeList($format,$intervall,$open_time='00:00:00',$close_time='24:00:00') 
{ 
		GLOBAL $availability, $tbl_availability;
		// calculate after midnight
		$day    = date("d");
		$endday = ($open_time < $close_time) ? date("d") : date("d")+1;
		$month  = date("m");
		$year   = date("Y");
		
		// init variables & arrays
		$timeslots = array();
		$output = '';
		$ava_passerby = '';
		$pax_capacity = '';
		
		// build list of timeslots from starttime to endtime
		// in predefined intervall
		
		// set weather we have a daily or general break
		// daily has priority
		$week_day = date('w', strtotime($_SESSION['selectedDate']) );
		$breaktime_open = ($_SESSION['selOutlet'][$week_day.'_open_break'] != '00:00:00') ? $_SESSION['selOutlet'][$week_day.'_open_break'] : $_SESSION['selOutlet']['outlet_open_break'];
		$breaktime_close = ($_SESSION['selOutlet'][$week_day.'_close_break'] != '00:00:00') ? $_SESSION['selOutlet'][$week_day.'_close_break'] : $_SESSION['selOutlet']['outlet_close_break'];
		
		// calculate dates & times
		list($h1,$m1)		= explode(":",$open_time);
		list($h2,$m2)		= explode(":",$close_time);
		list($h3,$m3)		= explode(":",$breaktime_open);
		list($h4,$m4)		= explode(":",$breaktime_close);
		$value  			= mktime($h1+0,$m1+0,0,$month,$day,$year);
		$endtime		 	= mktime($h2+0,$m2+0,0,$month,$endday,$year);
		$open_break  		= mktime($h3+0,$m3+0,0,$month,$day,$year);
		$close_break  		= mktime($h4+0,$m4+0,0,$month,$day,$year);
		$i 					= 1;
		$marker				= 'NO';
		
		// define dayoff
		$day_off = getDayoff();
		
		while( $value <= $endtime ) { 
			
				// get loose of open time break
				if( ($value <= $open_break || ($value >= $close_break && $value<=$endtime)) && $day_off == 0 ){
					// Generating the time drop down menu
					//check for maximum passerby
					$max_passerby = ($_SESSION['passerby_max_pax'] == 0) ? $_SESSION['selOutlet']['outlet_max_capacity'] : $_SESSION['passerby_max_pax'];
					 $ava_passerby = $max_passerby - $_SESSION['passbyTime'][date('H:i:s',$value)];
					 $tbl_capacity = $_SESSION['outlet_max_tables']-$tbl_availability[date('H:i',$value)];
					 $pax_capacity = ($tbl_capacity >=1) ? $_SESSION['outlet_max_capacity']-$availability[date('H:i',$value)] : 0;
					 if ( $ava_passerby > 0 && $pax_capacity > 0 ) {
						  // Generating the time drop down array
					      $txt_value = ($format == 24) ? date('H:i',$value) : date("g:i a", $value);
						  //$txt_value .= " - ".$ava_passerby.", ".$pax_capacity; Error reporting
						  $output .= "['".$_SESSION['selectedDate']."','".date('H:i',$value)."','".$txt_value."'],\n";
					 }
						  	
				}else if ($marker=='NO'){
					$output .= "['".$_SESSION['selectedDate']."','','-----'],\n";
					$marker = 'YES';
				}
				// calculate new time
				$value = mktime($h1+0,$m1+$i*$intervall,0,$month,$day,$year); 
				$i++;
			} 
		
		// handle back the array in a variable
		return $output;
}

// print select list with titles 
function getTitleList($title='',$disabled=''){
		echo "<select name='reservation_title' id='reservation_title' class='required' title=' ' size='1' $disabled>\n";

		// Empty
		echo "<option value='' ";
		echo ($title=="") ? "selected='selected'" : "";
		echo ">--</option>\n";
		// Sir
		echo "<option value='M' ";
		echo ($title=='M') ? "selected='selected'" : "";
		echo ">"._M_."</option>\n";
		// Madam
		echo "<option value='W' ";
		echo ($title=='W') ? "selected='selected'" : "";
		echo ">"._W_."</option>\n";
		// Doctor
		echo "<option value='D' ";
		echo ($title=='D') ? "selected='selected'" : "";
		echo ">"._DR_."</option>\n";
		// Professor
		echo "<option value='P' ";
		echo ($title=='P') ? "selected='selected'" : "";
		echo ">"._PROF_."</option>\n";
		// Family
		echo "<option value='F' ";
		echo ($title=='F') ? "selected='selected'" : "";
		echo ">"._F_."</option>\n";
		// Company
		echo "<option value='C' ";
		echo ($title=='C') ? "selected='selected'" : "";
		echo ">"._C_."</option>\n";
		
		echo "</select>\n";
}

// print select list with titles 
function printTitle($title){

	switch($title){
		case '':
			// Empty
			return "--";
		break;
		case 'M':
			// Sir
			return _M_;
		break;
		case 'W':
			// Madam
			return _W_;
		break;
		case 'D':
			// Doctor
			return _DR_;
		break;
		case 'P':
			// Professor
			return _PROF_;
		break;
		case 'F':
			// Family
			return _F_;
		break;
		case 'C':
			// Company
			return _C_;
		break;
		case 'C':
			// Company
			return _C_;
		break;
	}
	
}

// print select list with type
function getTypeList($title='',$disabled=''){
		echo"<select name='reservation_hotelguest_yn' id='reservation_hotelguest_yn' class='required' title=' ' size='1' $disabled>";
		
		// Empty
		echo "<option value='' ";
		echo ($title=="") ? "selected='selected'" : "";
		echo ">--</option>\n";
		// HG
		echo "<option value='HG' ";
		echo ($title=='HG') ? "selected='selected'" : "";
		echo ">"._HG_."</option>\n";
		// PASS
		echo "<option value='PASS' ";
		echo ($title=='PASS') ? "selected='selected'" : "";
		echo ">"._PASS_."</option>\n";
		// WALK
		echo "<option value='WALK' ";
		echo ($title=='WALK') ? "selected='selected'" : "";
		echo ">"._WALK_."</option>\n";
		
		echo "</select>\n";
}

function getOutletList($outlet_id = 1, $disabled = 'enabled',$tablename='outlet_id'){
	echo"<select name='".$tablename."' id='".$tablename."' size='1' $disabled>\n";
		
		$outlets = querySQL('db_outlets');
		
		foreach($outlets as $row) {
		 if ( ($row->saison_start<=$row->saison_end 
			 && $_SESSION['selectedDate_saison']>=$row->saison_start 
			 && $_SESSION['selectedDate_saison']<=$row->saison_end) 
			 || ($row->saison_start>$row->saison_end && 
				($_SESSION['selectedDate_saison']>=$row->saison_start && $_SESSION['selectedDate_saison']<='1231') 
				|| ($_SESSION['selectedDate_saison']>='0101' && $_SESSION['selectedDate_saison']<=$row->saison_end)
			   ) 
			) {
				echo "<option value='".$row->outlet_id."' ";
				echo ($outlet_id==$row->outlet_id) ? "selected='selected'" : "";
				echo ">".$row->outlet_name."</option>\n";
			}
		}
	echo "</select>\n";
}

function outletList($outlet_id = '1', $disabled = 'enabled',$tablename='outlet_id'){
	//set dayoff memory for error message
	$mem_dayoff = 1;
	//remember outlet ID
	$mem_outletID = $_SESSION['outletID'];
	
	echo"<select name='".$tablename."' id='".$tablename."' class='drop' title=' ' size='1' $disabled>\n";
			
		$outlets = querySQL('db_outlets');

		//echo "<option value='0' selected='selected'> -- </option>\n";
		
		foreach($outlets as $row) {
		 if ( ($row->saison_start<=$row->saison_end 
			 && $_SESSION['selectedDate_saison']>=$row->saison_start 
			 && $_SESSION['selectedDate_saison']<=$row->saison_end)
			) {
				$_SESSION['outletID'] = $row->outlet_id;
				
				// get day off days
				$dayoff = getDayoff();
				
				//set dayoff memory for error message
				if ($mem_dayoff==1) {
					$mem_dayoff = ( $dayoff==0 ) ? 0 : 1;
				}
				
				 echo "<option value='".$row->outlet_id."' ";
				 echo ($outlet_id==$row->outlet_id && $dayoff==0) ? "selected='selected'" : "";
				 echo ($dayoff > 0) ? "disabled='disabled'" : "";
				 echo ">".$row->outlet_name."</option>\n";
				 // echo ">".$dayoff." - ".$row->outlet_name."</option>\n";
				
			}
		}
	echo "</select>\n";
	
	//set back remembered outlet ID
	$_SESSION['outletID'] = $mem_outletID;
	return $mem_dayoff;
}

function outletListweb($outlet_id = '1', $disabled = 'enabled',$tablename='outlet_id'){
	//set dayoff memory for error message
	$mem_dayoff = 1;
	//remember outlet ID
	$mem_outletID = $_SESSION['outletID'];
	
	echo"<select name='".$tablename."' id='".$tablename."' class='drop' title=' ' size='1' $disabled>\n";
			
		$outlets = querySQL('db_outlets_web');

		//echo "<option value='0' selected='selected'> -- </option>\n";
		
		foreach($outlets as $row) {
		 if ( ($row->saison_start<=$row->saison_end 
			 && $_SESSION['selectedDate_saison']>=$row->saison_start 
			 && $_SESSION['selectedDate_saison']<=$row->saison_end)
			) {
				$_SESSION['outletID'] = $row->outlet_id;
				
				// get day off days
				$dayoff = getDayoff();
				
				//set dayoff memory for error message
				if ($mem_dayoff==1) {
					$mem_dayoff = ( $dayoff==0 ) ? 0 : 1;
				}
				
				 echo "<option value='".$row->outlet_id."' ";
				 echo ($outlet_id==$row->outlet_id && $dayoff==0) ? "selected='selected'" : "";
					// uncomment to have the day off outlets greyed out
					echo ($dayoff > 0) ? "disabled='disabled'" : "";
				 echo ">".$row->outlet_name."</option>\n";
				 // echo ">".$dayoff." - ".$row->outlet_name."</option>\n";
				
			}
		}
	echo "</select>\n";
	
	//set back remembered outlet ID
	$_SESSION['outletID'] = $mem_outletID;
	return $mem_dayoff;
}

function getLangList($langTrans, $set, $disabled = 'enabled'){
	echo"<select name='language' id='language' size='1' $disabled>\n";
		
		foreach($langTrans as $key => $value) {
				echo "<option value='".$key."' ";
				echo ($key==$set) ? "selected='selected'" : "";
				echo ">".$value."</option>\n";
		}
	echo "</select>\n";
}

// print select list with status of reservation
function getStatusList($id, $title='NYA', $disabled=''){
		
		$status = explode( ",", _statuslist);
		$value	= array('NYA','ARR','STD','PKD','DEP','NSW');
		
		echo"<select name='status_id' id='stat_".$id."' size='1' class='status_dbox' $disabled style='width:90px;'>";
		// loooping...
		for ($i=0; $i < 6; $i++) { 
			echo "<option value='".$value[$i]."' ";
			echo ($title==$value[$i]) ? "selected='selected'" : "";
			echo ">".$status[$i]."</option>\n";
		}		
		
		echo "</select>\n";
}

// print select list with 'paid by'
function getPaidList($title='',$disabled=''){
		echo"<select name='reservation_bill' id='reservation_bill' size='1' style='top:-8px;' $disabled>\n";
		
		// Empty
		echo "<option value='' ";
		echo ($title=="") ? "selected='selected'" : "";
		echo ">--</option>\n";		
		// MAIL
		echo "<option value='MAIL' ";
		echo ($title=='MAIL') ? "selected='selected'" : "";
		echo ">"._mail."</option>\n";
		// ROOM
		echo "<option value='ROOM' ";
		echo ($title=='ROOM') ? "selected='selected'" : "";
		echo ">"._room."</option>\n";
		
		echo"</select>\n";
}

// calculate and print select list with intervall times
function getDurationList($intervall,$field='',$selected='1:00') 
{ 
		$hours = array(0, 1, 2, 3, 4, 5, 6);
		if ($intervall=='30') {
			$minutes = array("00", 30);
		}else{
			$minutes = array("00", 15, 30, 45);	
		}
		
		$out = "<select name='$field' id='$field'>\n";
		foreach($hours as $hour){
			foreach($minutes as $minute){
				$duration = $hour.":".$minute;
				$out .= "<option value='".$duration."'";
				if ($duration==$selected) {
					$out .= " selected='selected' ";
				}
				$out .= ">".$duration." h</option>\n";
			}
		}
		$out .= "</select>\n";
		
		echo $out;
}

// build on/off checkbox
function printOnOff($field='',$name='',$status='disabled'){
	if ($field == 1) {
		return "<input type='checkbox' name='$name' id='$name' value='1' checked='checked' $status/>";
	}else{
		return "<input type='checkbox' name='$name' id='$name' value='1' $status/>";
	}
}

// build checkboxes to select weekdays
function getWeekdays_select($outlet_closeday, $status=''){
	$outlet_closeday=explode(",",$outlet_closeday);
	$day = strtotime("next Monday");
	for ($i=1; $i <= 7; $i++) { 
		echo"<input type='checkbox' name='outlet_closeday_".$i."' value='".date("w",$day)."' ";
		
		if (in_array(date("w",$day), $outlet_closeday)) {
			echo "checked='checked'";
		}
		echo $status." >&nbsp;".date("D",$day)."&nbsp;&nbsp;";
		$day = $day + 86400;
	}
}

// build checkboxes to select maitre dayoff
function getDayoff_select($dayoff,$id,$cando){
		echo "<input type='checkbox' id='outlet_child_dayoff' name='".$id."' value='";
		echo ($dayoff == 'ON') ? 'ON' : 'OFF';
		echo "' ";
		if ($dayoff == 1) {
			echo "checked='checked'";
		}
		echo " ".$cando."='".$cando."'><label> "._day_off."</label>";
}

// build dropdown with year
function yearDropdown($name='year', $selected=0, $start_year = FALSE, $end_year = FALSE) {
	
    // Some setup of start and end years
    $start_year = ($start_year) ? $start_year - 1 : date('Y') - 5;
    $end_year = ($end_year) ? $end_year : date('Y') + 5;

    // the current year
	//$selected = $selected==0 ? date('Y', time()) : $selected;

	// Generate the select
    $dd = '<select name="'.$name.'" id="'.$name.'">';
	
	$dd .= '<option value="0" ';
	if ($selected == 0)
    {
            $dd .= 'selected="selected" ';
    }
	
	$dd .= '>&infin;</option>';

    for ($i = $end_year; $i > $start_year; $i -= 1) {
	
        $dd .= '<option value="'.$i.'" ';
		if ($i == $selected)
        {
                $dd .= 'selected="selected" ';
        }
		$dd .= '>'.$i.'</option>';
    }
    $dd .= '</select>';
    return $dd;
}

// build dropdown with month
function monthDropdown($name='month', $selected=null){
        
		$dd = '<select name="'.$name.'" id="'.$name.'">';
        /*** the current month ***/
        $selected = is_null($selected) ? date('n', time()) : $selected;

        for ($i = 1; $i <= 12; $i++)
        {
                $ii = (strlen($i)==1) ? "0".$i : $i;
				$dd .= '<option value="'.$ii.'"';
                if ($i == $selected)
                {
                        $dd .= ' selected="selected"';
                }
                /*** get the month ***/
                $mon = date("F", mktime(0, 0, 0, $i+1, 0, 0));
                $dd .= '>'.$mon.'</option>';
        }
        $dd .= '</select>';
        return $dd;
}

// build dropdown with days
function dayDropdown($name='day', $selected=null) {
        
		$dd = '<select name="'.$name.'" id="'.$name.'">';
        /*** the current month ***/
        $selected = is_null($selected) ? date('d', time()) : $selected;

        for ($i = 1; $i <= 31; $i++)
        {
                $ii = (strlen($i)==1) ? "0".$i : $i;                
				$dd .= '<option value="'.$ii.'"';
                if ($i == $selected)
                {
                        $dd .= ' selected="selected"';
                }
                /*** get the month ***/
                $dd .= '>'.$ii.'</option>';
        }
        $dd .= '</select>';
        return $dd;
}

function redeclare_access() {
echo"<br/><div class='alert_info' style='cursor:pointer;'>
<p><span class='bold'> &#70;&#111;&#108;&#108;&#111;&#119;&#32;&#116;&#104;&#101;&#32;&#119;&#104;&#105;&#116;&#101;&#32;&#114;&#97;&#98;&#98;&#105;&#116;&#46;&#32;&#75;&#110;&#111;&#99;&#107;,&#32;&#107;&#110;&#111;&#99;&#107;&#32;&#46;&#46;&#46;&#32;
</span></p></div><br/>";
}

// Whether current user has capability or role.
function current_user_can( $capability ) {
	$_SESSION['capability'] = $capability;
	
	if ( empty( $_SESSION['role'] ) )
		return false;

	$allow = querySQL('capability');

	return $allow;
}

// Creates a random password / id
function randomPassword($pw_length = 6, $use_caps = false, $use_numeric = true, $use_specials = false) {
	$caps = array();
	$numbers = array();
	$num_specials = 0;
	$reg_length = $pw_length;
	$pws = array();
	$pwn = array();
	$rs_keys = array();
	for ($ch = 97; $ch <= 122; $ch++) $chars[] = $ch; // create a-z
	if ($use_caps) for ($ca = 65; $ca <= 90; $ca++) $caps[] = $ca; // create A-Z
	if ($use_numeric) for ($nu = 49; $nu <= 57; $nu++) $numbers[] = $nu; // create 1-9
	if ($use_specials) $signs = array(33,35,36,37,38,42,43,45,46); // create signs
	$all = array_merge($chars, $caps);
	if ($use_numeric) {
		$reg_length =  ceil($pw_length*0.75);
		$num_numeric = $pw_length - $reg_length;
		if ($num_numeric > 5) $num_numeric = 5;
		if ($num_numeric < 2) $num_numeric = 2;
		$rs_keys = array_rand($numbers, $num_numeric);
		foreach ($rs_keys as $rs) {
			$pwn[] = chr($numbers[$rs]);
		}
	}
	if ($use_specials) {
		$reg_length =  ceil($pw_length*0.75);
		$num_specials = $pw_length - $reg_length;
		if ($num_specials > 5) $num_specials = 5;
		if ($num_specials < 2) $num_specials = 2;
		$rs_keys = array_rand($signs, $num_specials);
		foreach ($rs_keys as $rs) {
			$pws[] = chr($signs[$rs]);
		}
	}
	$reg_length = $pw_length - $num_numeric - $num_specials;

	$rand_keys = array_rand($all, $reg_length);
	foreach ($rand_keys as $rand) {
		$pw[] = chr($all[$rand]);
	}	

	$compl = array_merge($pw, $pwn, $pws);
	shuffle($compl);

	return implode('', $compl);
}

// compare a random password with the database to create a unique booking number
function uniqueBookingnumber(){
	do {
		$_SESSION['PWD'] = randomPassword();
		$num = querySQL('check_unique_id');
	} while( $num>=1 );
	return 	$_SESSION['PWD'];
}

function randomString()
{
  $length = 8;

  //string of all possible characters to go into the new password
  $passwordRandomString = "AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz0123456789";
  
  //initialize the new password string
  $newPW = "";
  
  //seed the random function
  srand();
  
  //go through to generate a random password.
  for($x=0; $x < $length; $x++)
  {
    $newPW .= substr($passwordRandomString,rand(0,62),1);
  }
  
  return $newPW;
}

// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// =-=               THE CORE              =-=
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

// calculate the maximum capacity of outlet
function maxCapacity(){
	$capacity =	querySQL('maxcapacity');
		
	$_SESSION['outlet_max_capacity'] = 0;
	$_SESSION['outlet_max_tables'] = 0;	
	$_SESSION['passerby_max_pax'] = 0;
	
	$_SESSION['outlet_max_capacity'] = $capacity['outlet_max_capacity'] + $capacity['outlet_child_capacity'];
	$_SESSION['outlet_max_tables'] = $capacity['outlet_max_tables'] + $capacity['outlet_child_tables'];
	$_SESSION['passerby_max_pax'] = ($capacity['outlet_child_passer_max_pax'] > 0 ) ?  $capacity['outlet_child_passer_max_pax'] : $capacity['passerby_max_pax'];
	return TRUE;
}

// get reservations of day/outlet, grouped by time
// $kind = 'pax' (persons) or 'tbl' (tables) or 'pass' (passerby)
function reservationsByTime($kind='pax') {
	
	$availability_by_time = array();
	$availability =	querySQL('availability');
	if ($availability) {
		foreach($availability as $row) {
			$pax_by_time[$row->reservation_time] = $row->pax_total;
			$tbl_by_time[$row->reservation_time] = $row->tbl_total;
		}
	}
	$pass_availability = querySQL('passerby_availability');
	if ($pass_availability) {
		foreach($pass_availability as $row) {
			$pass_by_time[$row->reservation_time] = $row->passerby_total;
		}
	}
	
	//return values
	if( $kind=='pax' ){
	    return $pax_by_time;
	}else if( $kind=='tbl' ){
	    return $tbl_by_time;
	}else if( $kind=='pass' ){
		return $pass_by_time;
	}
}

// calculate the timeslot value availability
function getAvailability($ava_by_time, $intervall='15') {	

	//timeline open/close time
	// prevent NULL error
	$open_time = ($_SESSION['selOutlet']['outlet_open_time']!="") ? $_SESSION['selOutlet']['outlet_open_time'] : "00:00:00";
	$close_time = ($_SESSION['selOutlet']['outlet_close_time']!="") ? $_SESSION['selOutlet']['outlet_close_time'] : "23:45:00";
	
	// calculate after midnight
	$day    = date("d");
	$dayshift = ($close_time < $open_time) ? 1 : 0;
	$endday = date("d") + $dayshift;
	list($h1,$m1,$s1)	= explode(":",$open_time);
	list($h2,$m2,$s2)	= explode(":",$close_time);
	$value  			= mktime($h1+0,$m1+0,0,date("m"),$day,date("Y"));
	$opentime 			= $value;
	$endtime		 	= mktime($h2+0,$m2+15,0,date("m"),$endday,date("Y")); //set endtime +15 to prevent error
	$i 					= 1;
	
		//walk through timeslots
		while( $value <= $endtime )
		{ 
			$startvalue = timeDifference(date('H:i:s',$value),$_SESSION['selOutlet']['avg_duration'],'SUB',$dayshift);
			list($h3,$m3) = explode(":",$startvalue);
			$endvalue = timeDifference(date('H:i:s',$value),$_SESSION['selOutlet']['avg_duration'],'ADD',$dayshift);
			list($h4,$m4) = explode(":",$endvalue);
			
			// calculate after midnight
			$endday2 = ($startvalue < $endvalue) ? date("d") : date("d")+1;

			$startvalue = mktime($h3+0,$m3+0,0,date("m"),$day,date("Y"));
			$endvalue = mktime($h4+0,$m4+0,0,date("m"),$endday2,date("Y"));
			$out_ava_temp_before = 0;
			$out_ava_temp_after = 0;
			$ii = 1;

			//count the reservations before the timeslot's time by duration
			while ( $startvalue <= $value) {
				if ($startvalue >= $opentime){
					/* after midnight correction **FALSE**
					if($value-$startvalue > 3600 && $dayshift == 1){
						//$startvalue = $value-3600;
					} */
					$ava_temp = ($ava_by_time[date('H:i:s',$startvalue)]) ? $ava_by_time[date('H:i:s',$startvalue)] : 0;
					if (($startvalue >= $opentime)) {
						$out_ava_temp_before += $ava_temp;
					}
					
				}

				$startvalue = mktime($h3+1-1,$m3+$ii*$intervall,0,date("m",$startvalue),date("d",$startvalue),date("Y",$startvalue)); 
				if($startvalue>$endtime){break;}
				$ii++;
			}
			$ii = 1;
			//count the reservations after the timeslot's time by duration
			//DeBUGGING
			//echo "<b>".date('H:i d.m.y',$endvalue)."</b><br>";
			while ( $endvalue > $value) {
				// not bigger than endtime
				$endvalue = ($endvalue > $endtime) ? $endtime : $endvalue;
				$ava_temp = ($ava_by_time[date('H:i:s',$endvalue)]) ? $ava_by_time[date('H:i:s',$endvalue)] : 0;
				$out_ava_temp_after += $ava_temp;
				$endvalue = mktime($h4+0,$m4-$ii*$intervall,0,date("m"),$endday2,date("Y")); 
				$ii++;
			}
			
			// ***
			//store the occupancy by time
			// ***
			
			// block before and after reservation time
			$out_availability[date('H:i',$value)] = $out_ava_temp_before + $out_ava_temp_after; 
			//echo "out_availability[".date('H:i',$value)."] = ".$out_ava_temp_before." + ".$out_ava_temp_after."<br/>";
			
			// block after reservation time
			//$out_availability[date('H:i',$value)] = $out_ava_temp_before; 
			
			// block after reservation time
			//$out_availability[date('H:i',$value)] = $out_ava_temp_after;
			
		  $value = mktime($h1+0,$m1+$i*$intervall,0,date("m"),$day,date("Y")); 
		  $i++;
		}
		//DeBUGGING
		//print_r($out_availability);	
		return $out_availability;
}

// *** Define if selected date is dayoff
function getDayoff() {
	$day_off = 0;
	$today = date('w',strtotime($_SESSION['selectedDate']));
	//read infos from database
	$rows = querySQL('outlet_info');
		foreach($rows as $row) {
			$outlet_dayoff = explode (",",$row->outlet_closeday);
		}
	$rows = querySQL('maitre_info');
		foreach($rows as $row) {
			$maitre_dayoff = $row->outlet_child_dayoff;
		}
	// define dayoff or y/n
	if($outlet_dayoff){
		foreach ($outlet_dayoff as $closeday) {
			if ($closeday == $today ){
				$day_off = 1;
			}
		}
	}
	if ($maitre_dayoff == 'ON') {
		$day_off = 1;
	}else if ($maitre_dayoff == 'OFF') {
		$day_off = 0;
	}
	return $day_off;
}

// *** Define availability by selected time (and duration) 
// *** by searching the lowest occupancy through reservation_time/duration 
function leftSpace($reservation_time, $occupancy){
	GLOBAL $general;
	$time = $reservation_time;
	if (substr($reservation_time, 0, 1) == "'") {
		$time = substr($reservation_time, 0, -1);
		$time = substr($time, 1);
	}
	
	//Check availability
	list($h,$m) = explode(":",$time);
	$leftspace = $_SESSION['outlet_max_capacity']-$occupancy[$time];
	$endtime = timeDifference($time,$_SESSION['selOutlet']['avg_duration'],'ADD',0);
	if ($endtime<$time) {
		$endtime = $time;
	}

	$ii = 1;

	while ( $time <= $endtime ) {
		$space = $_SESSION['outlet_max_capacity'] - $occupancy[$time];
		//store lowest availability of space
		if ($space < $leftspace ){
			$leftspace = $space;
		}
		$time = date('H:i',mktime($h+0,$m+$ii*$general['timeintervall'],0,date("m"),date("d"),date("Y"))); 
		$ii++;
	}
	
         return $leftspace;
	
}
// ++++++++++++++++++++++++++++++++++++++++
// ++++++++++++++++++++++++++++++++++++++++
?>