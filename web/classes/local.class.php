<?php

// ** build and format date from today
// * format see php date()
// * optionally add or subtract days
// * special: monthfirst,monthlast,yearfirst,yearlast
// * $d = day
// * $m = month e.g. 02 = febrary
// * $y = year e.g. 2015

function buildDate($format='',$d='',$m='',$y='',$days=0,$special=''){
	$d = (!$d) ? date('j') : $d;
	$m = (!$m) ? date('n') : $m;
	$y = (!$y) ? date('Y') : $y;

	if (!$special) {
		if(!$format) {
			// return unix time if no format is set
			return time()+($days*86400);		
		}else {
			if(!date('$format')) {
			$format = 'Y-m-d h:i:s';
			}	
			return date($format,mktime(0,0,0,$m,$d+$days,$y));
		}	
	}else if($special){
			if(!date('$format')) {
			$format = 'Y-m-d h:i:s';
			}
			if($special=='monthfirst') {
				return date('$format',mktime(0,0,0,$m,1,$y));	
			}else if($special=='monthlast') {
				return date('$format',mktime(0,0,0,$m+1,0,$y));	
			}else if($special=='yearfirst') {
				return date('$format',mktime(0,0,0,01,01,$y));
			}else if($special=='yearlast') {
				return date('$format',mktime(0,0,0,12,31,$y));
			}
	}
	return FALSE;
}

// make database date human readable
function humanize($date) {
	GLOBAL $general;
	$datef =  $general['dateformat_short'];
	$timef = ($general['timeformat'] == 24) ? "H:i" : "g:i a";
	$datetime = $datef." ".$timef;
	$pattern = (strlen($date) == 10) ? $datef : $datetime;
	return date($pattern, strtotime($date));
}

// format time 12/24h
function formatTime($tm, $format){
	$tm 			= substr($tm,0,5);
	list($h1,$m1)		= explode(":",$tm);
	$mktime  		= mktime($h1+0,$m1+0,0,date("m"),date("d"),date("Y"));
	$time_new = ($format == 24) ? date('H:i',$mktime) : date("g:i a",$mktime);
	return $time_new;
}

// read translation text from database
function translateSite($lang='en', $apx = ''){
	$lang=($lang=='') ? 'en' : $lang;
	include($apx.'lang/'.$lang.".php");
	/* old version with dtabase use
	$sql = "SELECT * FROM `l16n`";
	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result)){ 
		define ( $row['needle'],$row[$lang]);
	}
	*/
}

// calculate the difference between two dates
function timeDifference($ts, $te, $action='SUB',$dayshift=0){
	list($hh,$mm) = explode(":",$ts);
	list($h,$m) = explode(":",$te);
	if ($action=='SUB') {
		$newDate = date('H:i', mktime($hh-$h+0,$mm-$m+0,0,date("m"),date("d")+$dayshift,date("Y")) );
	}else if ($action=='ADD') {
		$newDate = date('H:i', mktime($hh+$h+0,$mm+$m+0,0,date("m"),date("d")+$dayshift,date("Y")) );
	}
	return $newDate;

}

// build a timezone menu for date_default_timezone_set function
function dateformatDropdown( $selected='d.m.Y', $kind = 0 ){

	/**
	 * Dateformat List
	**/	
	if ($kind == 1) {
		$date_formats = array(
			'd.m.y' => '31.12.2010',
			'd/m/y' => '31/12/2010',
			'y/m/d' => '2010/12/31',
			'm/d/y' => '12/31/2010'
			);
			$datefield = 'datepickerformat';
	}else if ($kind == 0){
		$date_formats = array(
			'd.m.Y' => '31.12.2010',
			'd/m/Y' => '31/12/2010',
			'Y/m/d' => '2010/12/31',
			'm/d/Y' => '12/31/2010'
			);
			$datefield = 'dateformat';
	}else if ($kind == 2){
		$date_formats = array(
			'd.m.' => '04.12.',
			'd/m' => '04/12',
			'm/d' => '12/04'
			);
			$datefield = 'dateformat_short';
	}
		
	echo"<select name='".$datefield."' id='".$datefield."' class='required' size='1' title=' ' >\n";
	foreach ($date_formats as $key => $value) {
		echo"<option value='".$key."' ";
		if ($key==$selected) {
			echo "selected='selected'";
		}
		echo">".$value."</option>\n";
	}
	echo"</select>\n";
		
}

// build a timezone menu for date_default_timezone_set function
function timezoneDropdown( $selected='GMT' ){
	
	/**
	 * Timezone List
	 * Array compiled from timezone_identifiers_list() 
	**/
	$tz = array(
		'Africa/Abidjan',
		'Africa/Accra',
		'Africa/Addis_Ababa',
		'Africa/Algiers',
		'Africa/Asmara',
		'Africa/Bamako',
		'Africa/Bangui',
		'Africa/Banjul',
		'Africa/Bissau',
		'Africa/Blantyre',
		'Africa/Brazzaville',
		'Africa/Bujumbura',
		'Africa/Cairo',
		'Africa/Casablanca',
		'Africa/Ceuta',
		'Africa/Conakry',
		'Africa/Dakar',
		'Africa/Dar_es_Salaam',
		'Africa/Djibouti',
		'Africa/Douala',
		'Africa/El_Aaiun',
		'Africa/Freetown',
		'Africa/Gaborone',
		'Africa/Harare',
		'Africa/Johannesburg',
		'Africa/Kampala',
		'Africa/Khartoum',
		'Africa/Kigali',
		'Africa/Kinshasa',
		'Africa/Lagos',
		'Africa/Libreville',
		'Africa/Lome',
		'Africa/Luanda',
		'Africa/Lubumbashi',
		'Africa/Lusaka',
		'Africa/Malabo',
		'Africa/Maputo',
		'Africa/Maseru',
		'Africa/Mbabane',
		'Africa/Mogadishu',
		'Africa/Monrovia',
		'Africa/Nairobi',
		'Africa/Ndjamena',
		'Africa/Niamey',
		'Africa/Nouakchott',
		'Africa/Ouagadougou',
		'Africa/Porto-Novo',
		'Africa/Sao_Tome',
		'Africa/Tripoli',
		'Africa/Tunis',
		'Africa/Windhoek',
		'America/Adak',
		'America/Anchorage',
		'America/Anguilla',
		'America/Antigua',
		'America/Araguaina',
		'America/Argentina/Buenos_Aires',
		'America/Argentina/Catamarca',
		'America/Argentina/Cordoba',
		'America/Argentina/Jujuy',
		'America/Argentina/La_Rioja',
		'America/Argentina/Mendoza',
		'America/Argentina/Rio_Gallegos',
		'America/Argentina/Salta',
		'America/Argentina/San_Juan',
		'America/Argentina/San_Luis',
		'America/Argentina/Tucuman',
		'America/Argentina/Ushuaia',
		'America/Aruba',
		'America/Asuncion',
		'America/Atikokan',
		'America/Bahia',
		'America/Barbados',
		'America/Belem',
		'America/Belize',
		'America/Blanc-Sablon',
		'America/Boa_Vista',
		'America/Bogota',
		'America/Boise',
		'America/Cambridge_Bay',
		'America/Campo_Grande',
		'America/Cancun',
		'America/Caracas',
		'America/Cayenne',
		'America/Cayman',
		'America/Chicago',
		'America/Chihuahua',
		'America/Costa_Rica',
		'America/Cuiaba',
		'America/Curacao',
		'America/Danmarkshavn',
		'America/Dawson',
		'America/Dawson_Creek',
		'America/Denver',
		'America/Detroit',
		'America/Dominica',
		'America/Edmonton',
		'America/Eirunepe',
		'America/El_Salvador',
		'America/Fortaleza',
		'America/Glace_Bay',
		'America/Godthab',
		'America/Goose_Bay',
		'America/Grand_Turk',
		'America/Grenada',
		'America/Guadeloupe',
		'America/Guatemala',
		'America/Guayaquil',
		'America/Guyana',
		'America/Halifax',
		'America/Havana',
		'America/Hermosillo',
		'America/Indiana/Indianapolis',
		'America/Indiana/Knox',
		'America/Indiana/Marengo',
		'America/Indiana/Petersburg',
		'America/Indiana/Tell_City',
		'America/Indiana/Vevay',
		'America/Indiana/Vincennes',
		'America/Indiana/Winamac',
		'America/Inuvik',
		'America/Iqaluit',
		'America/Jamaica',
		'America/Juneau',
		'America/Kentucky/Louisville',
		'America/Kentucky/Monticello',
		'America/La_Paz',
		'America/Lima',
		'America/Los_Angeles',
		'America/Maceio',
		'America/Managua',
		'America/Manaus',
		'America/Marigot',
		'America/Martinique',
		'America/Mazatlan',
		'America/Menominee',
		'America/Merida',
		'America/Mexico_City',
		'America/Miquelon',
		'America/Moncton',
		'America/Monterrey',
		'America/Montevideo',
		'America/Montreal',
		'America/Montserrat',
		'America/Nassau',
		'America/New_York',
		'America/Nipigon',
		'America/Nome',
		'America/Noronha',
		'America/North_Dakota/Center',
		'America/North_Dakota/New_Salem',
		'America/Panama',
		'America/Pangnirtung',
		'America/Paramaribo',
		'America/Phoenix',
		'America/Port-au-Prince',
		'America/Port_of_Spain',
		'America/Porto_Velho',
		'America/Puerto_Rico',
		'America/Rainy_River',
		'America/Rankin_Inlet',
		'America/Recife',
		'America/Regina',
		'America/Resolute',
		'America/Rio_Branco',
		'America/Santarem',
		'America/Santiago',
		'America/Santo_Domingo',
		'America/Sao_Paulo',
		'America/Scoresbysund',
		'America/Shiprock',
		'America/St_Barthelemy',
		'America/St_Johns',
		'America/St_Kitts',
		'America/St_Lucia',
		'America/St_Thomas',
		'America/St_Vincent',
		'America/Swift_Current',
		'America/Tegucigalpa',
		'America/Thule',
		'America/Thunder_Bay',
		'America/Tijuana',
		'America/Toronto',
		'America/Tortola',
		'America/Vancouver',
		'America/Whitehorse',
		'America/Winnipeg',
		'America/Yakutat',
		'America/Yellowknife',
		'Antarctica/Casey',
		'Antarctica/Davis',
		'Antarctica/DumontDUrville',
		'Antarctica/Mawson',
		'Antarctica/McMurdo',
		'Antarctica/Palmer',
		'Antarctica/Rothera',
		'Antarctica/South_Pole',
		'Antarctica/Syowa',
		'Antarctica/Vostok',
		'Arctic/Longyearbyen',
		'Asia/Aden',
		'Asia/Almaty',
		'Asia/Amman',
		'Asia/Anadyr',
		'Asia/Aqtau',
		'Asia/Aqtobe',
		'Asia/Ashgabat',
		'Asia/Baghdad',
		'Asia/Bahrain',
		'Asia/Baku',
		'Asia/Bangkok',
		'Asia/Beirut',
		'Asia/Bishkek',
		'Asia/Brunei',
		'Asia/Choibalsan',
		'Asia/Chongqing',
		'Asia/Colombo',
		'Asia/Damascus',
		'Asia/Dhaka',
		'Asia/Dili',
		'Asia/Dubai',
		'Asia/Dushanbe',
		'Asia/Gaza',
		'Asia/Harbin',
		'Asia/Ho_Chi_Minh',
		'Asia/Hong_Kong',
		'Asia/Hovd',
		'Asia/Irkutsk',
		'Asia/Jakarta',
		'Asia/Jayapura',
		'Asia/Jerusalem',
		'Asia/Kabul',
		'Asia/Kamchatka',
		'Asia/Karachi',
		'Asia/Kashgar',
		'Asia/Kathmandu',
		'Asia/Kolkata',
		'Asia/Krasnoyarsk',
		'Asia/Kuala_Lumpur',
		'Asia/Kuching',
		'Asia/Kuwait',
		'Asia/Macau',
		'Asia/Magadan',
		'Asia/Makassar',
		'Asia/Manila',
		'Asia/Muscat',
		'Asia/Nicosia',
		'Asia/Novosibirsk',
		'Asia/Omsk',
		'Asia/Oral',
		'Asia/Phnom_Penh',
		'Asia/Pontianak',
		'Asia/Pyongyang',
		'Asia/Qatar',
		'Asia/Qyzylorda',
		'Asia/Rangoon',
		'Asia/Riyadh',
		'Asia/Sakhalin',
		'Asia/Samarkand',
		'Asia/Seoul',
		'Asia/Shanghai',
		'Asia/Singapore',
		'Asia/Taipei',
		'Asia/Tashkent',
		'Asia/Tbilisi',
		'Asia/Tehran',
		'Asia/Thimphu',
		'Asia/Tokyo',
		'Asia/Ulaanbaatar',
		'Asia/Urumqi',
		'Asia/Vientiane',
		'Asia/Vladivostok',
		'Asia/Yakutsk',
		'Asia/Yekaterinburg',
		'Asia/Yerevan',
		'Atlantic/Azores',
		'Atlantic/Bermuda',
		'Atlantic/Canary',
		'Atlantic/Cape_Verde',
		'Atlantic/Faroe',
		'Atlantic/Madeira',
		'Atlantic/Reykjavik',
		'Atlantic/South_Georgia',
		'Atlantic/St_Helena',
		'Atlantic/Stanley',
		'Australia/Adelaide',
		'Australia/Brisbane',
		'Australia/Broken_Hill',
		'Australia/Currie',
		'Australia/Darwin',
		'Australia/Eucla',
		'Australia/Hobart',
		'Australia/Lindeman',
		'Australia/Lord_Howe',
		'Australia/Melbourne',
		'Australia/Perth',
		'Australia/Sydney',
		'Europe/Amsterdam',
		'Europe/Andorra',
		'Europe/Athens',
		'Europe/Belgrade',
		'Europe/Berlin',
		'Europe/Bratislava',
		'Europe/Brussels',
		'Europe/Bucharest',
		'Europe/Budapest',
		'Europe/Chisinau',
		'Europe/Copenhagen',
		'Europe/Dublin',
		'Europe/Gibraltar',
		'Europe/Guernsey',
		'Europe/Helsinki',
		'Europe/Isle_of_Man',
		'Europe/Istanbul',
		'Europe/Jersey',
		'Europe/Kaliningrad',
		'Europe/Kiev',
		'Europe/Lisbon',
		'Europe/Ljubljana',
		'Europe/London',
		'GMT',
		'Europe/Luxembourg',
		'Europe/Madrid',
		'Europe/Malta',
		'Europe/Mariehamn',
		'Europe/Minsk',
		'Europe/Monaco',
		'Europe/Moscow',
		'Europe/Oslo',
		'Europe/Paris',
		'Europe/Podgorica',
		'Europe/Prague',
		'Europe/Riga',
		'Europe/Rome',
		'Europe/Samara',
		'Europe/San_Marino',
		'Europe/Sarajevo',
		'Europe/Simferopol',
		'Europe/Skopje',
		'Europe/Sofia',
		'Europe/Stockholm',
		'Europe/Tallinn',
		'Europe/Tirane',
		'Europe/Uzhgorod',
		'Europe/Vaduz',
		'Europe/Vatican',
		'Europe/Vienna',
		'Europe/Vilnius',
		'Europe/Volgograd',
		'Europe/Warsaw',
		'Europe/Zagreb',
		'Europe/Zaporozhye',
		'Europe/Zurich',
		'Indian/Antananarivo',
		'Indian/Chagos',
		'Indian/Christmas',
		'Indian/Cocos',
		'Indian/Comoro',
		'Indian/Kerguelen',
		'Indian/Mahe',
		'Indian/Maldives',
		'Indian/Mauritius',
		'Indian/Mayotte',
		'Indian/Reunion',
		'Pacific/Apia',
		'Pacific/Auckland',
		'Pacific/Chatham',
		'Pacific/Easter',
		'Pacific/Efate',
		'Pacific/Enderbury',
		'Pacific/Fakaofo',
		'Pacific/Fiji',
		'Pacific/Funafuti',
		'Pacific/Galapagos',
		'Pacific/Gambier',
		'Pacific/Guadalcanal',
		'Pacific/Guam',
		'Pacific/Honolulu',
		'Pacific/Johnston',
		'Pacific/Kiritimati',
		'Pacific/Kosrae',
		'Pacific/Kwajalein',
		'Pacific/Majuro',
		'Pacific/Marquesas',
		'Pacific/Midway',
		'Pacific/Nauru',
		'Pacific/Niue',
		'Pacific/Norfolk',
		'Pacific/Noumea',
		'Pacific/Pago_Pago',
		'Pacific/Palau',
		'Pacific/Pitcairn',
		'Pacific/Ponape',
		'Pacific/Port_Moresby',
		'Pacific/Rarotonga',
		'Pacific/Saipan',
		'Pacific/Tahiti',
		'Pacific/Tarawa',
		'Pacific/Tongatapu',
		'Pacific/Truk',
		'Pacific/Wake',
		'Pacific/Wallis',
		'UTC'
		);

	echo"<select name='timezone' id='timezone' class='required' size='1' title=' ' >\n";
	foreach ($tz as $zone) {
		echo"<option value='".$zone."' ";
		if ($zone==$selected) {
			echo "selected='selected'";
		}
		echo">".$zone."</option>\n";
	}
	echo"</select>\n";
	
}

?>