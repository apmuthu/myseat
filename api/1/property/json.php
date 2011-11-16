<?php session_start();
/*
**************************************************************************
                              RESTful API
**************************************************************************/ 

// PARAMETER
// propertyID   : a valid numeric number, outlet ID; default = 1;
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
$status                     = array();

// basic settings 
$max_requests               = max_requests();

// Fetch GET data
$_SESSION['property']       = ($_GET['propertyID']) ? $_GET['propertyID'] : '1';
$api_token                  = $_GET['token'];


// *** DATABASE queries ***

// check token
if ($api_token!='') {

    //check the user's API
    $request_count = validate_requests($api_token);

        // database request
        if ($status['code'] != 400 && $request_count < $max_requests) {
            // return database property results
                $data   = querySQL('property_info');

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
    $result['response']['property'] = $data;


    // Toss back results as json encoded array.
    return_result_json($result);

?>
