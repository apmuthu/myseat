<?php
if (isset($_POST['action']) && $_POST['action'] =='save_res') {	
	// group reservation needs a common menu?
	if ( (int)$_POST['reservation_pax'] >= $general['max_menu']){ 
		$_SESSION['messages'][] = _sentence_8." ".$general['max_menu']." "._sentence_9 ;
	}
	
	// outlet has already opened?
	list($h,$i,$s) = explode(":",$_SESSION['selOutlet']['outlet_open_time']);
	list($d,$m,$y) = explode("-",$_SESSION['selectedDate']);
	
	if (date('H:i:s',time()) > $_SESSION['selOutlet']['outlet_open_time'] && $m == date('m') && $d == date('d') && $y == date('Y')){ 
		$_SESSION['messages'][] = _sentence_6 ;
	}		
	
}

?>