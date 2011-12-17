<?php
	echo ($c++%2==1) ? '' : '<tr>' ;
	echo "<td>";
?>

<ul class="sparklist">
  <li>
    <span class="sparkline">

<?php
// get Pax by timeslot
$resbyTime = reservationsByTime('pax');
$tblbyTime = reservationsByTime('tbl');

// get availability by timeslot
$availability = getAvailability($resbyTime,$general['timeintervall']);
$tbl_availability = getAvailability($tblbyTime,$general['timeintervall']);

// ERROR REPORTING
/*
echo $_SESSION['outlet_max_capacity']."->".$_SESSION['outlet_max_tables']."<br>";
echo"<pre>";
print_r($resbyTime)."<br><br>";
print_r($tblbyTime)."<br><br>";
print_r($availability)."<br><br>";
print_r($tbl_availability);
echo"</pre>";
*/

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

 	// Beginn sparkline loop
	while( $value <= $endtime )
	{ 
		// generate timeslot value in percentage
		$percentage = ($_SESSION['outlet_max_capacity']>0) ? 100/$_SESSION['outlet_max_capacity'] : 0;
		$tbl_percentage = ($_SESSION['outlet_max_tables']>0) ? 100/$_SESSION['outlet_max_tables'] : 0;
		
		$pax_by_time = ($availability[date('H:i',$value)]) ? $percentage*$availability[date('H:i',$value)] : 2.5;
		$pax_by_time = ( round($pax_by_time,1)>100 ) ? 100 : round($pax_by_time,0);
		
		$tbl_by_time = ($tbl_availability[date('H:i',$value)]) ? $tbl_percentage*$tbl_availability[date('H:i',$value)] : 2.5;
		$tbl_by_time = ( round($tbl_by_time,1)>100 ) ? 100 : round($tbl_by_time,0);
		
		$pax_capacity = $_SESSION['outlet_max_capacity']-$availability[date('H:i',$value)];
		$tbl_capacity = $_SESSION['outlet_max_tables']-$tbl_availability[date('H:i',$value)];

		if ($pax_by_time >= $tbl_by_time){
			$val_capacity = $pax_capacity;
			$val_by_time = $pax_by_time;
			$txt_capacity = $val_capacity;
		}else{
			$val_capacity = $tbl_capacity;
			$val_by_time = $tbl_by_time;
		}

		// Generating the sparkline graph
		if( $value <= $open_break || ($value >= $close_break && $value<=$endtime) ){

			echo "<span class='index'><span class='count";
			
			if($val_by_time >= 100){
				echo " full";
			}else if($val_by_time >= 60){
				echo " high";
			}else if($val_by_time >= 5){
				echo " low";
			}else{
				echo " free";
				$val_by_time = 5;
			}
			
			echo "' style='height: ".$val_by_time."% !important;'>".$val_capacity."</span>\n</span>\n";
		}	
		// increase time
		$value = mktime($h1+0,$m1+$i*$general['timeintervall'],0,date("m"),$day,date("Y")); 
		$i++;
	}
?>
  </span>
  <?php
  echo"<a href='main_page.php?p=2&outletID=".$_SESSION['selOutlet']['outlet_id']."'>".$_SESSION['selOutlet']['outlet_name']."</a>";
  ?>
 </li>
</ul>
<?php
	echo "</td>";
	echo ($c%2==1) ?  '' : '</tr>' ;
?>