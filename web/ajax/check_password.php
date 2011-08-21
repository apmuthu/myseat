<?php 
session_start();
$_SESSION['language'] = ($_SESSION['language']) ? $_SESSION['language'] : 'en';

// Check for a unique username
// ** set configuration
    include('../../config/config.general.php');
// ** database functions
    include('../classes/database.class.php');
// ** connect to database
    include('../classes/connect.db.php');
// ** all database queries
    include('../classes/db_queries.db.php');
// ** localization functions
    include('../classes/local.class.php');
// ** set configuration
    include('../../config/config.inc.php');
// translate to selected language
    translateSite(substr($_SESSION['language'],0,2),'../');

// prevent dangerous input
secureSuperGlobals();

if(isSet($_POST['password'])){
	
    if($_POST['password'] != $_SESSION['selOutlet']['limit_password']){
        echo '&nbsp;<span style="color: red;">'. _wrong_password .'</span>';
    }else{
        echo "OK";
    }

}
?>