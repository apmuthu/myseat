<?
// Get global file path
function GetFileDir($php_self){
	$filename = explode("/", $php_self); // THIS WILL BREAK DOWN THE PATH INTO AN ARRAY
		for( $i = 0; $i < (count($filename) - 2); ++$i ) {
			$filename2 .= $filename[$i].'/';
		}
	return $filename2;
}

// General settings per resort from database
$general = array();
$general = querySQL('settings_inc');
	//print_r($general);

if($_SESSION['valid_user']==TRUE){
	$_SESSION['language'] = $general['language'];
}

// Set default timezone in PHP 5.
if ( function_exists( 'date_default_timezone_set' ) ){
	date_default_timezone_set( $general['timezone'] );
}

/* Set PHP local */
setlocale(LC_TIME, $general['language']);

/* Set global base path */
$global_basedir = '';
if ($_SERVER['HTTPS']) {
	$global_basedir = 'https://';
}else{
	$global_basedir = 'http://';
}

$global_basedir .= $_SERVER['SERVER_NAME'].GetFileDir($_SERVER['PHP_SELF']);

?>

