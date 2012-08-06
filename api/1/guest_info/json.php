<?php session_start();
/*
**************************************************************************
                              RESTful API
**************************************************************************/ 

// PARAMETER
// name         : a full guest name; space must be encoded with '%20'
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
$_SESSION['reservation_guest_name']  = ($_GET['name']) ? $_GET['name'] : '';
// do not proceed when name GET is empty - cleat api token
$api_token                           = ($_SESSION['reservation_guest_name']!='') ? $_GET['token'] : '';


// *** DATABASE queries ***

// check token
if ($api_token!='') {

    //check the user's API
    $request_count = validate_requests($api_token);

        // database request
        if ($status['code'] != 400 && $request_count < $max_requests) {
            // return database property results

            $sql = "SELECT DISTINCT reservation_title,
                    reservation_guest_name, reservation_guest_adress, reservation_guest_city,
                    reservation_guest_email, reservation_guest_phone 
                    FROM $dbTables->reservations WHERE reservation_guest_name = '".$_SESSION['reservation_guest_name']."' 
                    GROUP BY reservation_guest_name";
            $data = getRowList(query($sql));

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
        $result['response']['details'] = $data;

        if ($status['code'] != 400 && $request_count < $max_requests) {
            $data   = querySQL('reservation_history');
        }
        $result['response']['history'] = $data;


        if ($status['code'] != 400 && $request_count < $max_requests) {
            // return database property results
            $data   = querySQL('reservation_last_visit');
        }
        $result['response']['last_visit'] = $data;

        if ($status['code'] != 400 && $request_count < $max_requests) {
            // return database property results
            $data   = querySQL('reservation_visits');
        }
        $result['response']['numberof_visits'] = $data;
    
    // Toss back results as json encoded array.
    return_result_json($result);

?>
