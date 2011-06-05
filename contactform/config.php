<?php session_start();

// ** SETTINGS **

	// default theme
	$default_style = "light";
	
	// The link to your startpage:
	//$base_link = $_SERVER['DOCUMENT_ROOT'];
	// or
	//$home_link = "/web/";
	// or
	//$home_link = "http://www.mysite.com";
	// best:
	$base_link = "http://".$_SERVER['HTTP_HOST'].substr(dirname($_SERVER['PHP_SELF']),0,-11);
	$home_link = $base_link;

	// The default language
	$default_lang = "en";	
	
	// The relative path to the lang folder
	$lang_folder = "lang";	
	if ( strpos($_SERVER['PHP_SELF'],'mobile') > 0 ){
		$lang_folder = "../contactform/lang";
	}
	
	//Get the language used by the browser
	$browser_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	$language = "";

	// Check if the language file exists
	function use_lang($language)
	{
		global $lang_folder;
		if( is_file($lang_folder."/".$language.".php") )
		{
			return true;
		}
	}	
	

	if( isset($_GET['lang']) && use_lang($_GET['lang']) )
	{
		$language = $_GET['lang'];
	}
	else if ( isset($browser_lang) && use_lang($browser_lang) && $_SESSION['lang'] == '' )
	{
		$language = $browser_lang;
	}
	else if( $_SESSION['lang'] == '' )
	{ 
		$language = $default_lang;
	}
	else if( $_SESSION['lang'] != '' )
	{ 
		$language = $_SESSION['lang'];
	}
	
	$language = substr($language,0,2);
	
	$_SESSION['lang'] = $language;
	
	// Include the right language file	
    include($lang_folder."/".$language.".php");
	
	// Helper function to echo the values of the $lang array
	function lang($key)
	{
		global $lang;
		echo $lang[$key];
	}
	
	function language_navigation_original() {
		global $lang;
		$languages = $lang["available_language"];
		foreach( $languages as $single_language )
		{
			echo '<li><a href="' . $_SERVER['PHP_SELF'] . '?lang=' . key($languages) . '">'. $single_language . '</a></li>';
			next($languages);
		}
	}
	
	function language_navigation($language) {
			echo '<li><a href="'.$_SERVER['PHP_SELF'].'?lang=en&so='.$_SESSION['single_outlet'].'&prp='.$_SESSION['property'].'&outletID='.$_SESSION['outletID'].'">
			<img src="img/flag_en.png"/></a></li>';
			if($language!='en'){
				echo '<li><a href="'.$_SERVER['PHP_SELF'].'?lang='.$language.'&so='.$_SESSION['single_outlet'].'&prp='.$_SESSION['property'].'&outletID='.$_SESSION['outletID'].'">
				<img src="img/flag_'.$language.'.png"/></a></li>';
			}
	}
	
	function language_navigation_mobile($language) {
			global $global_basedir;
			if($language!='en'){
				echo '<a href="'.$_SERVER['PHP_SELF'].'?lang='.$language.'" style="margin-right:9px;"><img src="'.$global_basedir.'/contactform/img/flag_'.$language.'.png"/></a>&nbsp;';
			}
		echo '<a href="'.$_SERVER['PHP_SELF'].'?lang=en" style="margin-right:13px;"><img src="'.$global_basedir.'/contactform/img/flag_en.png"/></a>';
	}
	
	
?>