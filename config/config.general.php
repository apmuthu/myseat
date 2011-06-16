<?php 
// ** Database Details Settings

 // ** the database that we will use
 $settings['dbName'] = 'myseatXT';
 // ** the database host
 // ** mostly 'localhost' fits
 $settings['dbHost'] = 'localhost';
 // ** the database user
 $settings['dbUser'] = 'root';
 // ** the database password
 $settings['dbPass'] = 'root';
 // ** the database port (standard: 3306)
 $settings['dbPort'] = 3306;


 // ** Google map API key
 // ** Sign up for your own at: http://code.google.com/intl/en-EN/apis/maps/signup.html
 // ** Do not use mine please !!
 // $settings['googlemap_key'] = "ABQIAAAA1-uY3igh_R_JiWHmwKK_UxT75Ut2Ph_t8aXAK0xXRJ_z6BkX6xTyGQK8WxAFbqP1c4QmI7AiZ-VjAQ";

 // ** Daylight settings
 // ** noon time
 $daylight_noon = '14:00';
 // ** evening time
 $daylight_evening = '18:00';
// **

// ********************************************************************
// Do not change anything under this line, except you know what you do.

// ** date & time format database
$settings['dbdate'] = "Y-m-d";
$settings['dbtime'] = "H:i:s";

// ** global currency
$settings['currency'] = "Euro";

// array consists of: PHP country code, language name
// http://www.all-acronyms.com/special/countries_acronyms_and_abbreviations
// Make sure you are using the ones listed in the coloumn with the name of TLD (Top Level Domain)
 $langTrans = array(
		'en_EN' => 'English',
		'de_DE' => 'Deutsch',
		'es_ES' => 'Español',
		'fr_FR' => 'Français',
		'nl_NL' => 'Nederlands',
		'se_SE' => 'Svenska',
		'it_IT' => 'Italia',
		'cn_CN' => 'Chinese',
		'dk_DK' => 'Dansk'
		);

// User roles
	$roles = array(
	'1' => 'Superadmin',
	'2' => 'Admin',
	'3'  => 'Manager',
	'4'  => 'Supervisor',
	'5'   => 'User',
	'6'   => 'Guest'
	);
	
// Advertise start ranges
// in days
$adv_range = array( 0,3,7,14,30,60,90);

?>