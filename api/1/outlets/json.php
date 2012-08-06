<?php session_start();
/*
**************************************************************************
                              RESTful API
**************************************************************************/ 

// PARAMETER
// outletID     : a valid numeric number, outlet ID
// propertyID   : a valid numeric number, outlet ID; default = 1;
// year         : in form of YYYY 4 digits; default = actual year;
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
$max_requests               = max_requests();

// Fetch GET data
$_SESSION['outletID']           = ($_GET['outletID']) ? $_GET['outletID'] : '';
$_SESSION['property']           = ($_GET['propertyID']) ? $_GET['propertyID'] : '1';
$_SESSION['selectedDate_year']  = ($_GET['year']) ? $_GET['year'] : date('Y');
$api_token                      = $_GET['token'];


// *** DATABASE queries ***

// check token
if ($api_token!='') {

    $sql = "SELECT request_count, last_login FROM `$dbTables->api_users` WHERE `token` = '".$api_token."'";
    $select = query($sql);
    $login = getRowList($select);

        $now = date('Y-m-d');
        

        //check the user's API
        $request_count = validate_requests($api_token);

            // database request
            if ($status['code'] != 400 && $request_count < $max_requests) {
                // return database property results
                // return database outlet results
                if ($_SESSION['outletID']!='') {
                    $data   = querySQL('db_outlet_info');
                } else {
                    $data   = querySQL('db_all_outlets');
                }

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
    $result['response']['outlet'] = $data;


    // Toss back results as json encoded array.
    return_result_json($result);

?>
