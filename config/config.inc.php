<?php
// Get global file path
function GetFileDir($php_self){
	$filename2 = '';
	$filename = explode("/", $php_self); // THIS WILL BREAK DOWN THE PATH INTO AN ARRAY
		for( $i = 0; $i < (count($filename) - 2); ++$i ) {
			$filename2 .= $filename[$i].'/';
		}
	return $filename2;
}

// General settings per resort from database
$general = array();
$general = querySQL('settings_inc');
if ($general) {
	if($_SESSION['valid_user']==TRUE){
		$_SESSION['language'] = $general['language'];
	}

	// Set default timezone in PHP 5.
	if ( function_exists( 'date_default_timezone_set' ) ){
		date_default_timezone_set( $general['timezone'] );
	}

	/* Set PHP local */
	setlocale(LC_TIME, $general['language']);
}

/* Set global base path */
$global_basedir = '';
if (isset($_SERVER['HTTPS'])) {
	$global_basedir = 'https://';
    $default_port = 443;
}else{
	$global_basedir = 'http://';
    $default_port = 80;
}

$global_basedir .= $_SERVER['SERVER_NAME'].(($_SERVER['SERVER_PORT'] != $default_port) ? ':'.$_SERVER['SERVER_PORT'] : '').GetFileDir($_SERVER['PHP_SELF']);

?>

