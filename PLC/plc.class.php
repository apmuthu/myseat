<?php
/**
* PHP Class to user access (login, register, logout, etc)
* 
* <code><?php
* include('plc.class.php');
* $user = new flexibleAccess();
* ? ></code>
* 
*
* ==============================================================================
* 
* modified class by:
* @version $Id: plc.class.php,v 0.93 2008/05/02 10:54:32 $
* @copyright Copyright (c) 2010 Bernd Orttenburger (http://www.myseat.us)
* @author Bernd Orttenburger <beo@myseat.us>
*
* original class by:
* @copyright Copyright (c) 2007 Nick Papanotas (http://www.webdigity.com)
* @author Nick Papanotas <nikolas@webdigity.com>
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License (GPL)
* 
* dxAuth class by:
* @author Original Author: FreakAuth_light 1.1
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License (GPL)
* ==============================================================================
*/

/**
* Flexible Access - The main class
* 
* @param string $dbName
* @param string $dbHost 
* @param string $dbUser
* @param string $dbPass
* @param string $dbTable
*/

class flexibleAccess{
 /*Settings*/
 /**
  * The database that we will use
  * var string
  */
 var $dbName = 'myseat';
 /**
  * The database host
  * mostly 'localhost' fits
  * var string
  */
 var $dbHost = 'localhost';
 /**
  * The database port
  * var int
  */
 var $dbPort = '3306';
 /**
  * The database user
  * var string
  */
 var $dbUser = 'root';
 /**
  * The database password
  * var string
  */
 var $dbPass = 'root';
 /**
  * The session variable ($_SESSION[$sessionVariable]) which will hold the data while the user is logged on
  * var string
  */
 var $sessionVariable = 'XTSess';
 /**
  * The database table that holds all the information
  * var string
  */
 var $dbTable  = 'plc_users';
 /**
  * The database table that holds all sessions
  * var string
  */
 var $dbSession  = 'plc_autologin';
 /**
  * The database table that holds all sessions
  * var string
  */
 var $dbAttempts  = 'plc_sessions';
 /**
  * Those are the fields that our user table uses in order to fetch the needed data. 
  * The structure is 'fieldType' => 'fieldName'
  * var array
  */
 var $tbFields = array(
 	'userID' => 'userID',
	'group' => 'group_id',
 	'login'  => 'username',
 	'pass'   => 'password',
 	'email'  => 'email',
	'last_ip'  => 'last_ip',
	'last_login'  => 'last_login',
 	'active' => 'active',
  	'forgot' => 'forgotten_password_code',
  	'lang' => 'lang_id',
	'role' => 'role',
	'hotel' => 'hotel_id'
 );
 /**
  * Those are the fields that our cookie session table uses in order to fetch the needed data. 
  * The structure is 'fieldType' => 'fieldName'
  * var array
  */
var $tbSessions = array(
	'key_id' => 'key_id',
  	'user_id' => 'user_id',
  	'user_agent' => 'user_agent',
  	'last_ip' => 'last_ip',
  	'last_login' => 'last_login'
);
 /**
  * Those are the fields that our login session table uses in order to fetch the needed data. 
  * The structure is 'fieldType' => 'fieldName'
  * var array
  */
var $tbLogin = array(
	'id' => 'session_id',
  	'login' => 'login_attempts',
  	'activity' => 'last_activity'
);
 /**
  * The database table that holds all sessions
  * var string
  */
var $dbTrans  = 'plc_trans';
var $tbTrans = array(
	'id' => 'id',
  	'field' => 'type',
  	'app' => 'app',
  	'old_id' => 'old_id',
  	'new_id' => 'new_id'
);
/**
  * When user wants the system to remember him/her, how much time to keep the cookie? (seconds)
  * cookie time in seconds: 60 seconds * 60 minutes * 24 hours * 365 days = 1 year
  * var int
  */
// var $remTime = 3600; // 1 hours
// var $remTime = 10800; // 3 hours
 var $remTime = 86400; // 24 hours
 /**
  * The name of the cookie which we will use if user wants to be remembered by the system
  * var string
  */
 var $remCookieName = 'ckPLC';
 /**
  * The method used to encrypt the password. It can be: sha1, dxauth, reduxauth or nothing (no encryption)
  * var string
  */
 var $passMethod = 'reduxauth';
 /**
  * You can add major salt to be hashed with password. -> DXAUTH
  * For example, you can get salt from here: https://www.grc.com/passwords.htm
  */
 var $majorsalt = 'N;8m0(PSOZqzlg9?cLt5]:ZT7|0Fo6w$A*pAQ`O8<.^vZr6@!EY~lI{lX|Ty)-+';
 /**
  * Salt Length -> REDUXAUTH
  **/
 var $salt_length = 10;
 /**
  * Display errors? Set this to true if you are going to seek for help, or have troubles with the script
  * var bool
  */
 var $displayErrors = true;
 /**
  * Login attempts. How many false login attempts would you like accept?
  * This should prevent 'Brutforce' attacks
  * var int
  */
 var $loginAttempts = 6;
 /**
  * Time intervall between false login attempts
  * This should prevent 'Brutforce' attacks
  * var int
  */
 var $loginTime = 5;
 /**
  * The definition of the roles / permissions
  * The higher the number the less it's worth
  * => superadmin (has permissions on everything and can also create other admin)
  * => admin	  (has permissions on everything but not be able to create users)
  * => user		  (it is a registered user, and you can decide to give in rights)
  * => guest		 (registered user, but only allowed to see data, not allowed to edit)  
  * var array
  */
 var $roles = array(
 	'1' => 'superadmin',
	'2' => 'admin',
 	'3'  => 'manager',
 	'4'  => 'supervisor',
 	'5'   => 'user',
 	'6'   => 'guest'
 );

 /*Do not edit after this line*/
 var $userID;
 var $dbConn;
 var $fAtmp;
 var $userData=array();

/*
 |--------------------------------------------------------------------------
 | PHP Class to user access (login, register, logout, etc)
 | BASIC FUNCTIONS
 |--------------------------------------------------------------------------
 |
*/ 

/**
  * Class Constructure
  * 
  * @param string $dbConn
  * @param array $settings
  * @return void
  */
 function flexibleAccess($dbConn = '', $settings = '')
 {
    if ( is_array($settings) ){
	    foreach ( $settings as $k => $v ){
			    if ( !isset( $this->{$k} ) ) die('Property '.$k.' does not exists. Check your settings.');
			    $this->{$k} = $v;
		}
    }

    $this->remCookieDomain = $_SERVER['HTTP_HOST'];
    $this->dbConn = ($dbConn=='')? mysql_connect($this->dbHost.':'.$this->dbPort, $this->dbUser, $this->dbPass):$dbConn;
    if ( !$this->dbConn ) die(mysql_error($this->dbConn));
    mysql_select_db($this->dbName, $this->dbConn)or die(mysql_error($this->dbConn));
    if( !isset( $_SESSION ) ) session_start();
    if ( !empty($_SESSION[$this->sessionVariable]) )
    {
	    $this->loadUser( $_SESSION[$this->sessionVariable] );
    }
 /**
  * page to redirect after login
  * var string
  */
	if ($_SESSION['forwardPage'] =='') {
		$_SESSION['forwardPage'] = "../web/main_page.php?p=1";
	}

 }
 
 /**
   * Login function
   * @param string $uname
   * @param string $password
   * @param bool $loadUser
   * @return bool
 */
function login($uname,$password,$newpassword)
 {
// Login attempt check to prevent 'Bruteforce' attacks
 if($this->checkAttempt()){
	// encode uname and password
	$uname    = $this->escape($uname);
   	$password = $originalPassword = $this->escape($password);

	// Get password from database for redux auth encoding
	$sql = sprintf("SELECT `{$this->tbFields['pass']}` FROM `{$this->dbTable}` WHERE `{$this->tbFields['login']}` = '%s' LIMIT 1",$uname);
	$res = $this->query($sql,__LINE__);
	if ( @mysql_num_rows($res) == 0 ){
		// FALSE - store false attempt in session table
		$this->failedAttempt();
		return 0;
		}
		$this->userData = mysql_fetch_array($res);
		$this->userPass = $this->userData[$this->tbFields['pass']];

	switch(strtolower($this->passMethod)){
	  case 'sha1':
	  	$password = "SHA1('$password')"; break;
	  case 'dxauth' :
  		$password = "'".$this->pwEncode($password)."'"; break;
	  case 'reduxauth' :
  		$password = "'".$this->hash_password_db($this->userPass,$password)."'"; break;
	  case 'nothing':
	  	$password = "'$password'";
	}
	// check password
	$sql = sprintf("SELECT * FROM `{$this->dbTable}` WHERE `{$this->tbFields['login']}` = '%s' AND `{$this->tbFields['pass']}` = %s AND `{$this->tbFields['active']}` = '1' LIMIT 1",$uname,$password);

	$res = $this->query($sql,__LINE__);
	if ( @mysql_num_rows($res) == 0 ){
		// FALSE - store false attempt in session table
		$this->failedAttempt();
		return 0;
		}
		
		//TRUE - store logged in user data
			$this->userData = mysql_fetch_array($res);
			$this->userID = $this->userData[$this->tbFields['userID']];
			$this->loadUser($this->userID);
			
			//store IP & datetime in DB
			$time_now = date('Y-m-d H:i:s', time() );
	  		$sql = "UPDATE `{$this->dbTable}` 
					SET `{$this->tbFields['last_ip']}` = '".$_SERVER[REMOTE_ADDR]."', 
						`{$this->tbFields['last_login']}` = '".$time_now."' 
					WHERE `{$this->tbFields['userID']}` = '".$this->userID."'";
			$res = $this->query($sql,__LINE__);

			if ($newpassword!="") {
				// check password
				$sql = sprintf("SELECT * FROM `plc_brutforce` WHERE `text` = '%s'",$newpassword);
				$res = $this->query($sql,__LINE__);
				if ( @mysql_num_rows($res) > 0 ){
					return 4;
				}
				$this->newpassword($newpassword,$this->userID);
				return 3;
			}else{
				// store last login time
				$this->create_autologin($this->userID);
				$this->clearAttempt();
				return 1;
			}
 }
return 2;
}
/**
	* New Password function
	* param string $id
	* @return int
*/
function newpassword($newpass='',$id)
{
	if (!empty($newpass)) {
		$password =	$newpass;
		switch(strtolower($this->passMethod)){
		  case 'sha1':
		  	$password_db = "SHA1('$password')"; break;
		  case 'dxauth' :
	  		$password_db = "'".$this->pwEncode($password)."'"; break;
		  case 'reduxauth' :
	  		$password_db = "'".$this->hash_password($password)."'"; break;
		  case 'nothing':
		  	$password_db = "'$password'";
		}
		
  		$sql = "UPDATE `{$this->dbTable}` SET `{$this->tbFields['pass']}` = $password_db WHERE `{$this->tbFields['userID']}` = $id";  		
		$this->query($sql,__LINE__);
		$id = (int)mysql_insert_id($this->dbConn);	
	}
	return $id;
}

 /*
  * Creates a cookie session entry. The array should have the form 'database field' => 'value'
  * @param array $data
  * return int
  */  
function insertSession($data)
{
   if (!is_array($data)) $this->error('Data is not an array', __LINE__);
   foreach ($data as $k => $v ) $data[$k] = "'".$this->escape($v)."'";
   $this->query("INSERT INTO `{$this->dbSession}` (`".implode('`, `', array_keys($data))."`) VALUES (".implode(", ", $data).")",__LINE__);
   return (int)mysql_insert_id($this->dbConn);
 }

 /**
 	* Logout function
 	* param string $redirectTo
 	* @return bool
 */
 function logout($redirectTo = '')
 {
	if ( isset($_COOKIE[$this->remCookieName]) ){
     	$setCookie = unserialize(base64_decode($_COOKIE[$this->remCookieName]));
	 	foreach ($setCookie as $k => $v ) $setCookie[$k] = "'".$this->escape($v)."'";
		$sql = "DELETE FROM `{$this->dbSession}` WHERE `{$this->tbSessions['key_id']}` = ".$setCookie['key_id'];
   		$this->query($sql,__LINE__);
   		$_SESSION[$this->sessionVariable] = array();
   		setcookie($this->remCookieName, '', time()-60000);
   		$this->userData = '';
   		if ( $redirectTo != '' && !headers_sent()){
   			header('Location: '.$redirectTo );
   			exit;//To ensure security
   		}
	}
 }
 
 /*
  * Creates a random password. You can use it to create a password for users
  * param int $length
  * param string $chrs
  * return string
  */
function ramdomPassword($pw_length = 6, $use_caps = false, $use_numeric = true, $use_specials = false) {
	$caps = array();
	$numbers = array();
	$num_specials = 0;
	$reg_length = $pw_length;
	$pws = array();
	$pwn = array();
	$rs_keys = array();
	for ($ch = 97; $ch <= 122; $ch++) $chars[] = $ch; // create a-z
	if ($use_caps) for ($ca = 65; $ca <= 90; $ca++) $caps[] = $ca; // create A-Z
	if ($use_numeric) for ($nu = 49; $nu <= 57; $nu++) $numbers[] = $nu; // create 1-9
	if ($use_specials) $signs = array(33,35,36,37,38,42,43,45,46); // create signs
	$all = array_merge($chars, $caps);
	if ($use_numeric) {
		$reg_length =  ceil($pw_length*0.75);
		$num_numeric = $pw_length - $reg_length;
		if ($num_numeric > 5) $num_numeric = 5;
		if ($num_numeric < 2) $num_numeric = 2;
		$rs_keys = array_rand($numbers, $num_numeric);
		foreach ($rs_keys as $rs) {
			$pwn[] = chr($numbers[$rs]);
		}
	}
	if ($use_specials) {
		$reg_length =  ceil($pw_length*0.75);
		$num_specials = $pw_length - $reg_length;
		if ($num_specials > 5) $num_specials = 5;
		if ($num_specials < 2) $num_specials = 2;
		$rs_keys = array_rand($signs, $num_specials);
		foreach ($rs_keys as $rs) {
			$pws[] = chr($signs[$rs]);
		}
	}
	$reg_length = $pw_length - $num_numeric - $num_specials;

	$rand_keys = array_rand($all, $reg_length);
	foreach ($rand_keys as $rand) {
		$pw[] = chr($all[$rand]);
	}	

	$compl = array_merge($pw, $pwn, $pws);	
	shuffle($compl);

	return implode('', $compl);
}

/*
 * compare a random password with the database to create a unique password
 * @param string $password
 * @return bool
 */
function uniquePassword($password){
	$res = $this->query("SELECT * FROM `{$this->dbTable}` WHERE `{$this->tbFields['pass']}` = '".$password."' ",__LINE__);
	if ( @mysql_num_rows($res) == 0){
		return true;
	}else{
		return false;
	}
}

/*
 |--------------------------------------------------------------------------
 | DX Auth
 |--------------------------------------------------------------------------
 |
 | Function: pwEncode
 | Modified for DX_Auth
 |Original Author: FreakAuth_light 1.1
*/

function pwEncode($password)
{
	/*
	* Password salt
	* You can add major salt to be hashed with password.
	* For example, you can get salt from here: https://www.grc.com/passwords.htm
	*/

	global $majorsalt;

	// if PHP5
	if (function_exists('str_split'))
	{
		$_pass = str_split($password);
	}
	// if PHP4
	else
	{
		$_pass = array();
		if (is_string($password))
		{
			for ($i = 0; $i < strlen($password); $i++)
			{
				array_push($_pass, $password[$i]);
			}
		}
	}

	// encrypts every single letter of the password
	foreach ($_pass as $_hashpass)
	{
		$majorsalt .= md5($_hashpass);
	}

	// encrypts the string combinations of every single encrypted letter
	// and finally returns the encrypted password
	return md5($majorsalt);
}

/*
 |--------------------------------------------------------------------------
 | REDUX Auth
 |--------------------------------------------------------------------------
 |
 | Function: hash_password
 | Modified for REDUX_Auth
 | Original Author: Mathew Davis
*/

/**
 * Hashes the password to be stored in the database.
 * Original by Redux_auth
 * @return string
 * @author Bernd 
 **/
function hash_password($password = FALSE)
{
    $salt_length = $this->salt_length;
    
    if ($password === FALSE)
    {
        return FALSE;
    }
    
	$salt = $this->salt();
	
	$password = $salt . substr(sha1($salt . $password), 0, -$salt_length);
	
	return $password;		
}
/**
 * This function takes a password and validates it
 * against an entry in the users table.
 * Original by Redux_auth
 * @return string
 * @author Bernd
 **/
function hash_password_db($password_db = FALSE, $password = FALSE)
{
	$salt_length = $this->salt_length;
    
    if ($password_db === FALSE || $password === FALSE)
    {
        return FALSE;
    }
    
	$salt = substr($password_db, 0, $salt_length);

	$password = $salt . substr(sha1($salt . $password), 0, -$salt_length);
    
	return $password;
}
/**
 * Generates a random salt value.
 * Original by Redux_auth
 * @return void
 * @author Bernd
 **/
function salt()
{
	return substr(md5(uniqid(rand(), TRUE)), 0, $this->salt_length);
}

/*
 |--------------------------------------------------------------------------
 | Autologin and Sessions related function
 |--------------------------------------------------------------------------
 |
*/

/**
* Autologin function
* @param array $data
*/
function autologin_weak()
{
	if ( isset($_COOKIE[$this->remCookieName]) ){
     $setCookie = unserialize(base64_decode($_COOKIE[$this->remCookieName]));
	//debugging 
	//foreach ($setCookie as $k => $v ) echo $k."=".$v."<br/>";
	 foreach ($setCookie as $k => $v ) $setCookie[$k] = "'".$this->escape($v)."'";

	$this->userID = $setCookie['user_id'];
		
	$sql = "SELECT * FROM `{$this->dbTable}` WHERE `{$this->tbFields['userID']}` = ".$setCookie['user_id']." LIMIT 1";
	$res = $this->query($sql,__LINE__);
	if ( mysql_num_rows($res) == 0)
		return false;
		
		//TRUE - store logged in user data
		$this->userData = mysql_fetch_array($res);
		$this->userID = $this->userData[$this->tbFields['userID']];	

		// extend cookie time
		//$this->create_autologin($this->userID);
	
		return true;
	}else{
		return false;
	}
}

/**
* Autologin function
* @param array $data
*/
function autologin()
{
	if ( isset($_COOKIE[$this->remCookieName]) ){
     $setCookie = unserialize(base64_decode($_COOKIE[$this->remCookieName]));
	 //foreach ($setCookie as $k => $v ) echo $k."=".$v."<br/>";
	 foreach ($setCookie as $k => $v ) $setCookie[$k] = "'".$this->escape($v)."'";
	$sql = "SELECT * FROM `{$this->dbSession}` 
		WHERE `{$this->tbSessions['key_id']}` = ".$setCookie['key_id']."
		 AND `{$this->tbSessions['user_id']}` = ".$setCookie['user_id']."
		 LIMIT 1";
	$res = $this->query($sql,__LINE__);
	if ( mysql_num_rows($res) == 0){
		return false;
	}
	$this->sessionData = mysql_fetch_array($res);
	$this->userID = $this->sessionData[$this->tbSessions['user_id']];
		
	$res = $this->query("SELECT * FROM `{$this->dbTable}` WHERE `{$this->tbFields['userID']}` = $this->userID LIMIT 1",__LINE__);
	if ( @mysql_num_rows($res) == 0)
		return false;
		
		//TRUE - store logged in user data
		$this->userData = mysql_fetch_array($res);
		$this->userID = $this->userData[$this->tbFields['userID']];	

		// extend cookie time
		//$this->create_autologin($this->userID);
	
		return true;
	}else{
		return false;
	}
}


/*
 * Creates autologin db entry and cookie.
 * param int $user_id
 * return bool
 */
function create_autologin($user_id)
{
	$data = array(
		'key_id' 		=> substr(uniqid(md5(rand().$this->remCookieName), true), 0, 23),
		'user_id' 		=> $user_id,
		'user_agent' 	=> substr($_SERVER['HTTP_USER_AGENT'], 0, 149),
		'last_ip' 		=> $_SERVER['REMOTE_ADDR']
	);

//$this->query("DELETE FROM `{$this->dbSession}` WHERE `{$this->tbSessions['user_id']}` = '".$user_id."'",__LINE__);
$sessionID = $this->insertSession($data);
$cookie = array(
	'name' 		=> $this->remCookieName,
	'value'		=> base64_encode(serialize($data)),
	'expire'	=> time()+$this->remTime,
);		  
$a = setcookie($cookie['name'], $cookie['value'], $cookie['expire'], '/');

}
/**
	* Get cookie data
	* @return array 
   */
function read_cookie()
{
	if ( isset($_COOKIE[$this->remCookieName]) ){
     $setCookie = unserialize(base64_decode($_COOKIE[$this->remCookieName]));
	 foreach ($setCookie as $k => $v ) $setCookie[$k] = "'".$this->escape($v)."'";
	
	return $setCookie;
	}
}
 /*
  * Creates an entry when login fails
  * @param int $id
  * return int
  */
function failedAttempt($id = 1)
{
	$res = $this->query("SELECT * FROM `{$this->dbAttempts}` WHERE `{$this->tbLogin['id']}` = ".$id." LIMIT 1",__LINE__);
	if ( @mysql_num_rows($res) == 0){
		// NEW SESSION
		$sql = "INSERT INTO `{$this->dbAttempts}` (`{$this->tbLogin['id']}`,`{$this->tbLogin['login']}`) VALUES (".$id.", 1)"; 		
		$this->query($sql,__LINE__);
		$this->fAtmp = 1;
	}else{
		// UPDATE SESSION
		$temp = mysql_fetch_array($res);
		$this->fAtmp = $temp[$this->tbLogin['login']] + 1;
		$sql = "UPDATE `{$this->dbAttempts}` SET `{$this->tbLogin['login']}` = `{$this->tbLogin['login']}` + 1 WHERE `{$this->tbLogin['id']}` = '".$id."'  LIMIT 1";		
		$this->query($sql,__LINE__);
	}
		$id = (int)mysql_insert_id($this->dbConn);
		return $id;
}
/*
 * Clears entry when login is successfull
 * @param int $id
 * return int
 */
function clearAttempt($id = 1)
{
		$sql = "UPDATE `{$this->dbAttempts}` SET `{$this->tbLogin['login']}` = 0 WHERE `{$this->tbLogin['id']}` = '".$id."' LIMIT 1";		
		$this->query($sql,__LINE__);
		$id = (int)mysql_insert_id($this->dbConn);
		return $id;
}
 /*
  * Checks failed login attempts
  * @param int $id
  * return bool
  */
function checkAttempt($id = 1)
{
	$sql = "SELECT * FROM `{$this->dbAttempts}` WHERE `{$this->tbLogin['id']}` = '".$id."' AND `{$this->tbLogin['login']}` >= '".$this->loginAttempts."' LIMIT 1";
	$res = $this->query($sql,__LINE__);
	if ( @mysql_num_rows($res) == 1){
		$sql = "SELECT TIMESTAMPDIFF(MINUTE, `{$this->tbLogin['activity']}`, now()) 
				FROM `{$this->dbAttempts}` WHERE `{$this->tbLogin['id']}` = '".$id."' 
				AND  TIMESTAMPDIFF(MINUTE, `{$this->tbLogin['activity']}`, now()) >= '".$this->loginTime."' LIMIT 1";
		$res = $this->query($sql,__LINE__);
		if ( @mysql_num_rows($res) == 1){
			$id = $this->clearAttempt($id);
			return true;
		}
		// too much login attempts and less time has flied
		return false;
	}else{
		return true;
	}
}

/*
 |--------------------------------------------------------------------------
 | AUTO PERSONS FUNCTIONS
 |--------------------------------------------------------------------------
 |
*/

/**
	* A function that is used to transform data
	* from login system to app
	* @param string $app,$field
	* @return int
*/
function transformData($app = 'Unknown', $field, $id)
{
	$sql = "SELECT `new_id` FROM `{$this->dbTrans}` WHERE `{$this->tbTrans['app']}` = '".$app."' AND `{$this->tbTrans['field']}` = '".$field."' AND `{$this->tbTrans['old_id']}` = '".$id."' LIMIT 1";
	$res = $this->query($sql);
  if ( mysql_num_rows($res) == 0 )
  	return 0;

  return mysql_result($res,0);
}

 /**
  * read persons function
  * @param array $data 
  * @return bool
 */
function buildPersons($needle)
 {
	$haystack = array();
	$res = $this->query("SELECT * FROM `{$this->dbTable}` ORDER BY `{$this->tbFields['empID']}`",__LINE__);
	if ( @mysql_num_rows($res) == 0)
		return false;

		while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
		    $haystack[$row[$this->tbFields['empID']]] = "'".$row[$this->tbFields['login']]."'";
		}

		$newpersons = array_diff_key($needle,$haystack);

	return $newpersons;
 }

 /**
  * write new persons function
  * return the passwords in decoded
  * @param array $data 
  * @return array $clear_passwords
 */
function writePersons($data)
 {
	$clear_passwords = array();
   	foreach ($data as $k => $v ){
		do {
		$password =	$this->ramdomPassword();
		switch(strtolower($this->passMethod)){
		  case 'sha1':
		  	$password_db = "SHA1('$password')"; break;
		  case 'dxauth' :
	  		$password_db = "'".$this->pwEncode($password)."'"; break;
		  case 'reduxauth' :
	  		$password_db = "'".$this->hash_password($password)."'"; break;
		  case 'nothing':
		  	$password_db = "'$password'";
		}
		$flag = $this->uniquePassword($password_db);
		} while($flag==FALSE);
		
   		$sql = "INSERT INTO `{$this->dbTable}` (`{$this->tbFields['empID']}`,`{$this->tbFields['login']}`,`{$this->tbFields['pass']}`) VALUES ('".$k."', ".$v.", '".$pwEncode."') ";  		
		$this->query($sql,__LINE__);
		$id = (int)mysql_insert_id($this->dbConn);
		$clear_passwords[$id] = $password;
	}
	return $clear_passwords;
 }

/*
 |--------------------------------------------------------------------------
 | PRIVATE FUNCTIONS
 |--------------------------------------------------------------------------
 |
*/
  
  /**
  	* SQL query function
  	* @access private
  	* @param string $sql
  	* @return string
  */
  function query($sql, $line = 'Unknown')
  {
    //if (defined('DEVELOPMENT_MODE') ) echo '<b>Query to execute: </b>'.$sql.'<br /><b>Line: </b>'.$line.'<br />';
	$res = mysql_query($sql, $this->dbConn);
	if ( !$res )
		$this->error(mysql_error($this->dbConn), $line);
	return $res;
  }
  
  /**
  	* A function that is used to load the user's data
  	* @access private
  	* @param string $userID
  	* @return bool
  */
  function loadUser($userID)
  {
	$sql = "SELECT * FROM `{$this->dbTable}` WHERE `{$this->tbFields['userID']}` = '".$this->escape($userID)."' LIMIT 1";
	$res = $this->query($sql);
    if ( mysql_num_rows($res) == 0 )
    	return false;
    $this->userData = mysql_fetch_array($res);
    $this->userID = $userID;
    $_SESSION[$this->sessionVariable] = $this->userID;
    return true;
  }
  /**
  	* A function that is used to load the user's details
  	* @access private
  	* @param string $userID
  	* @return bool
  */
function loadUserDetail($userID)
{
	$this->userDetail = array();
			
		$sql = "SELECT a.{$this->tbFields['userID']},a.{$this->tbFields['group']},a.{$this->tbFields['lang']},a.{$this->tbFields['hotel']} AS hotelID,
		CONCAT_WS(' ', b.firstname, b.name) AS loggeduser, b.*,c.*,f.*,g.*
		FROM `{$this->dbTable}` AS a
		STRAIGHT_JOIN employees AS b 
		STRAIGHT_JOIN users_employee_asoc AS ab
		STRAIGHT_JOIN hotel AS c
		STRAIGHT_JOIN jobs_specific AS d
		STRAIGHT_JOIN jobs_generic AS e
		STRAIGHT_JOIN department AS f
		STRAIGHT_JOIN accounts AS g
		STRAIGHT_JOIN account_user_assoc AS ag
		WHERE a.{$this->tbFields['userID']} = ab.user_id
		AND ab.employee_id = employeeid
		AND c.hotelid = d.hotel_id
		AND b.jobid = d.job_id
		AND d.gjob_id = e.gjob_id
		AND e.department_id = f.department_id
		AND a.{$this->tbFields['userID']} = ag.user_id
		AND ag.account_id = g.account_id
		AND a.{$this->tbFields['userID']} = ".$userID." 
		LIMIT 1";
		
	$res = $this->query($sql,__LINE__);
  if ( mysql_num_rows($res) == 0 )
  	return false;
  $this->userDetail = mysql_fetch_array($res);
  return true;
}

  /**
  	* A function that is used to load the user's details
  	* @access private
  	* @param string $userID
  	* @return bool
  */
function loadUserDetailNew($appID, $userID)
{
	$this->userDetail = array();
			
		$sql = "SELECT 
		a.{$this->tbFields['userID']},
		a.{$this->tbFields['group']},
		a.{$this->tbFields['lang']},
		b.hotel AS hotelID,
		b.role,
		b.department,
		d.*,
		CONCAT_WS(' ', c.firstname, c.name) AS loggeduser
		FROM `{$this->dbTable}` AS a
		STRAIGHT_JOIN ohc_application_user_role AS b
		STRAIGHT_JOIN users_employee_asoc AS ab
		STRAIGHT_JOIN employees AS c
		STRAIGHT_JOIN accounts AS d
		WHERE a.{$this->tbFields['userID']} = ab.user_id
		AND ab.employee_id = c.employeeid
		AND b.userid = a.{$this->tbFields['userID']}
		AND b.role = d.account_id
		AND b.application_id = ".$appID."
		AND a.{$this->tbFields['userID']} = ".$userID." 
		LIMIT 1";
		
	$res = $this->query($sql,__LINE__);
  if ( mysql_num_rows($res) == 0 )
  	return false;
  $this->userDetail = mysql_fetch_array($res);
  return true;
}

  /**
  	* Produces the result of addslashes() with more safety
  	* @access private
  	* @param string $str
  	* @return string
  */  
  function escape($str) {
	  $str = (is_array($str)) ? $str : htmlentities($str, ENT_QUOTES, 'UTF-8');
	  $str = get_magic_quotes_gpc()?stripslashes($str):$str;
	  $str = (is_array($str)) ? $str : mysql_real_escape_string($str);
	  return $str;
  }
  /**
  	* Produces a dropdown menu for the roles
  	* @access private
  	* @param string $str
  	* @return string
  */
  function roles_dropdown($roles,$selected = 4){
	$roles = str_replace(" ", " ", $roles); 

	echo '<SELECT name="role">'; 
	foreach ($roles as $key => $value) 
	{ 
	echo '<OPTION value='.$key;
	if($key == $selected){echo ' selected="selected" ';}
	echo '> '.$value.''; 
	} 
	echo '</SELECT>'; 
	
  } 

  /**
  	* Error holder for the class
  	* @access private
  	* @param string $error
  	* @param int $line
  	* @param bool $die
  	* @return bool
  */  
  function error($error, $line = '', $die = false) {
    if ( $this->displayErrors )
    	echo '<b>Error: </b>'.$error.'<br /><b>Line: </b>'.($line==''?'Unknown':$line).'<br />';
    if ($die) exit;
    return false;
  }
  /**
  	* Login form holder for the class
  	* @access private
	*
  */
function login_form(){
	  echo '<div id="stylized" class="myform">
			  <form name="ajaxform" id="ajaxform">
		    	<h1 style="font-size:1.5em;">Admin HEADQUARTERS</h1>
		    	<p>Please log in to proceed.</p>
				<label>User
			        <span class="small">Required</span>
			    </label>
            <input type="text" name="user" id="user" class="textfield user" maxlength="30"/>
			    <label>Password
			        <span class="small">Min. size 6 chars</span>
			    </label>
            <input type="password" name="token" id="token" class="textfield pass" maxlength="12"/>
			
            <button  type="submit" id="sbmt">Log-in</button><br/><br/>';
		if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
		    || $_SERVER['SERVER_PORT'] == 443) {
		echo'<p style="text-align:right;">
			<img src="img/lock.png" alt="SSL" style="vertical-align:middle;"/>
			<small style="vertical-align:middle;">&nbsp;256-bit SSL security</small></p>';
		}else{
			echo'<p style="text-align:right;">
				<img src="img/lock-unlock.png" alt="unsecure" style="vertical-align:middle;"/>
				</p>';
		}
	echo '</form></div>';
}

}
?>