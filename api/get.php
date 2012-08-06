<?php session_start();
/*
**************************************************************************
                              RESTful API
**************************************************************************/ 

// PARAMETER
// outletID     : a valid numeric number  > outlet ID
// selectedDate : in form of YYYY-MM-DD
// token        : your personal API token

// ** set configuration
    include('../../config/config.general.php');
// ** database functions
    include('../../web/classes/database.class.php');
// ** connect to database
    include('../../web/classes/connect.db.php');
// ** all database queries
    include('../../web/classes/db_queries.db.php');
// ** set configuration
    include('../../config/config.inc.php');
// ** set configuration
    include('../rest.class.php');

// prevent dangerous GET input
secureSuperGlobals();

// we'll store our data here
$data						= array();
$status						= array();
$result						= array();

// basic settings 
$_SESSION['storno']			= 0;
$_SESSION['wait']			= 0;
$max_requests               = max_requests();

// Fetch GET data 
$_SESSION['outletID'] 		= $_GET['outletID'];
$_SESSION['selectedDate'] 	= $_GET['selectedDate'];
$api_token            		= $_GET['token'];


// *** DATABASE queries ***

// check token
$sql = "SELECT request_count, last_login FROM `$dbTables->api_users` WHERE `token` = '".$api_token."'";
$result = query($sql);
$login = getRowList($result);

if (!empty($login)) {
    $now = date('Y-m-d');
    // count API requests per day
    if ($login->last_login == $now ) {
        $sql = "UPDATE `$dbTables->api_users` SET 
            `request_count` = request_count+1,
            WHERE `token` = '".$api_token."'";
    } else {
        $sql = "UPDATE `$dbTables->api_users` SET 
            `request_count` = 1,  
            `last_login` = CURDATE()
            WHERE `token` = '".$api_token."'";
    }
    $result = query($sql);

    // database request
    if ($login->request_count < $max_requests) {
        // return database reservations results
        $data   = querySQL('reservations');

        // create some status code/message
        if (!empty($data)) {
            $status['code'] = 200;
            $status['status'] = getStatusCodeMessage($status['code']);
        }else{
            $status['code'] = 204;
            $status['status'] = getStatusCodeMessage($status['code']);  
        }
    }

} else {
        // create some status code/message
        $status['code'] = 400;
        $status['status'] = getStatusCodeMessage($status['code']); 
}




    $result['status'] = $status;
    $result['response']['confirmed'] = $data;

    // return database waitlist results
    $_SESSION['wait'] = 1;
    $data	= querySQL('reservations');
    $result['response']['waitlist'] = $data;


    $return_obj = json_encode($result);

    //output JSON
    echo header('Content-type: text/plain');
    echo "result:\n";
    echo prettifyJson($return_obj);

    //return results in JSON
    return $return_obj;

?>
