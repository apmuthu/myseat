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
	
	// Awesome Facebook Application
	// Create our Application instance (replace this with your appId and secret).
	// Get appID & secret from: https://www.facebook.com/developers/
	require '../facebook-php-sdk/src/facebook.php';
	
	$facebook = new Facebook(array(
	  'appId'  => '159369430744822',
	  'secret' => 'ce5d7ec20ade21d6d587c65fbef85f68',
	  'cookie' => true,
	));
	
// ** END SETTINGS **

	// We may or may not have this data based on a $_GET or $_COOKIE based session.
	//
	// If we get a session here, it means we found a correctly signed session using
	// the Application Secret only Facebook and the Application know. We dont know
	// if it is still valid until we make an API call using the session. A session
	// can become invalid if it has already expired (should not be getting the
	// session back in this case) or if the user logged out of Facebook.
	$session = $facebook->getSession();

	$me = null;
	// Session based API call.
	if ($session) {
	  try {
	    $uid = $facebook->getUser();
	    $me = $facebook->api('/me');
	  } catch (FacebookApiException $e) {
	    error_log($e);
	  }
	}

	// login or logout url will be needed depending on current user state.
	if ($me) {
	  $logoutUrl = $facebook->getLogoutUrl();
	} else {
	  $loginUrl = $facebook->getLoginUrl(array('req_perms' => 'email'));
	}	
	
	// The relative path to the lang folder
	$lang_folder = "lang";	
	
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
	
	function language_navigation_original()
	{
		global $lang;
		$languages = $lang["available_language"];
		foreach( $languages as $single_language )
		{
			echo '<li><a href="' . $_SERVER['PHP_SELF'] . '?lang=' . key($languages) . '">'. $single_language . '</a></li>';
			next($languages);
		}
	}
	
	function language_navigation($language)
	{
			echo '<li><a href="'.$_SERVER['PHP_SELF'].'?lang=en"><img src="img/flag_en.png"/></a></li>';
			if($language!='en'){
				echo '<li><a href="'.$_SERVER['PHP_SELF'].'?lang='.$language.'"><img src="img/flag_'.$language.'.png"/></a></li>';
			}
	}
	
	
?>