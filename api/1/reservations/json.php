<?php session_start();
/*
**************************************************************************
                              RESTful API
**************************************************************************/ 

// PARAMETER
// outletID     : a valid numeric number, outlet ID
// selectedDate : in form of YYYY-MM-DD
// token        : your personal API token

// ** set configuration
    include('../../../config/config.general.php');
// ** database functions
    include('../../../web/classes/database.class.php');
// ** connect to database
    include('../../../web/classes/connect.db.php');
// ** all database queries
    include('../../../web/classes/db_queries.db.php');
// ** set configuration
    include('../../../config/config.inc.php');
// ** set configuration
    include('../rest.class.php');

// prevent dangerous GET input
secureSuperGlobals();

// we'll store our data here
$data                       = array();
$status                     = array();
$result                     = array();

// basic settings 
$_SESSION['storno']         = 0;
$_SESSION['wait']           = 0;
$max_requests               = max_requests();

// Fetch GET data 
$_SESSION['outletID']       = $_GET['outletID'];
$_SESSION['selectedDate']   = $_GET['selectedDate'];
$api_token                  = $_GET['token'];


// *** DATABASE queries ***

// check token
if ($api_token!='') {

    //check the user's API
    $request_count = validate_requests($api_token);

        // database request
        if ($status['code'] != 400 && $request_count < $max_requests) {
            // return database property results
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
            // create some error code/message
            $status['code'] = 401;
            $status['status'] = getStatusCodeMessage($status['code']); 
}

    $result['status'] = $status;
    $result['response']['confirmed'] = $data;

    // return database waitlist results
    if ($status['code'] != 400 && $request_count < $max_requests) {
        $_SESSION['wait'] = 1;
        $data   = querySQL('reservations');
    }
    
    $result['response']['waitlist'] = $data;


    // Toss back results as json encoded array.
    return_result_json($result);

?>
