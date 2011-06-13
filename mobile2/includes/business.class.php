<?php

function DateDropdown($field='reservation_date', $disabled = 'enabled'){
	GLOBAL $settings;
	//set dayoff memory for error message
	$mem_date = $_SESSION['selectedDate'];
	// unix timestamp for now
	$stamp = time();
	
	echo"<select name='".$field."' id='".$field."' size='1' $disabled>\n";
	
	for($i = 0; $i < 18; $i++) {
		
		$_SESSION['selectedDate'] = date($settings['dbdate'],$stamp);
		// get day off days
		$dayoff = getDayoff();
		
	    // Substitue this with your dropdown code
		if ($i == 0) {
			$datetext = _today;
		}else{
			$datetext = strftime('%a %e %b', $stamp);	
		}

	    echo "<option value='".date($settings['dbdate'],$stamp)."' ";
		// select today
		echo ($i == 0 || $mem_date == $_SESSION['selectedDate']) ? "selected='selected' " : "";
		// outlets' day off greyed out
		echo ($dayoff > 0) ? "disabled='disabled' " : "";
		
		echo ">".$datetext."</option>\n";
	    // Now increase timestamp of one day
		// (hours*minutes*seconds)
	    $stamp += (24*60*60);
	}
	echo "</select>\n";
	
	//set back remembered date
	$_SESSION['selectedDate'] = $mem_date;
}

?>