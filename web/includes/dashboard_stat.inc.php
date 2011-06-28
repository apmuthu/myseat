<?php
// initialize
$count_slots = 0;
$val_by_time = 0;

// get Pax by timeslot
$resbyTime = reservationsByTime('pax');

// get availability by timeslot
$availability = getAvailability($resbyTime,$general['timeintervall']);

// actual time rounded half hour  
$round_numerator = 60 * $general['timeintervall']; // 60 seconds per minute * 15 minutes equals 900 seconds
$rounded_time = ( round ( time() / $round_numerator ) * $round_numerator );

//timeline open/close time
//prevent 'division by zero error'
$open_time = ($_SESSION['selOutlet']['outlet_open_time']!="") ? $_SESSION['selOutlet']['outlet_open_time'] : "11:00:00";
$close_time = ($_SESSION['selOutlet']['outlet_close_time']!="") ? $_SESSION['selOutlet']['outlet_close_time'] : "22:00:00";

// calculate after midnight
$day    = date("d");
$endday = ($open_time < $close_time) ? date("d") : date("d")+1;

// build time values
list($h1,$m1)		= explode(":",$open_time);
list($h2,$m2)		= explode(":",$close_time);
$value  			= mktime($h1+0,$m1+0,0,date("m"),$day,date("Y"));
$endtime		 	= mktime($h2+0,$m2+0,0,date("m"),$endday,date("Y"));
$i 					= 1;
// build break times UNIX time
list($h3,$m3)		= explode(":",$_SESSION['selOutlet']['outlet_open_break']);
list($h4,$m4)		= explode(":",$_SESSION['selOutlet']['outlet_close_break']);
$open_break  		= mktime($h3+0,$m3+0,0,date("m"),$day,date("Y"));
$close_break  		= mktime($h4+0,$m4+0,0,date("m"),$day,date("Y"));

	while( $value <= $endtime )
	{ 
		// generate timeslot value in percentage
		$percentage = ($_SESSION['outlet_max_capacity']>0) ? 100/$_SESSION['outlet_max_capacity'] : 0;
		
		$pax_by_time = ($availability[date('H:i',$value)]) ? $percentage*$availability[date('H:i',$value)] : 0;
		$pax_by_time = ( round($pax_by_time,1)>100 ) ? 100 : round($pax_by_time,0);
		
		$pax_capacity = $_SESSION['outlet_max_capacity']-$availability[date('H:i',$value)];

		$val_by_time += $pax_by_time;
		$count_slots ++;
		
		// increase time
		$value = mktime($h1+0,$m1+$i*$general['timeintervall'],0,date("m"),$day,date("Y")); 
		$i++;
	}
	
	$show_occupancy = ceil($val_by_time/$count_slots);
	
?>