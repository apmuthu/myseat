<?php session_start();
/*
**************************************************************************
                              RESTful API
**************************************************************************/ 

// PARAMETER
// propertyID   : a valid numeric number, outlet ID; default = 1;
// outletID     : a valid numeric number, outlet ID
// selectedDate : in form of YYYY-MM-DD
// pax          : a valid numeric number of guest to book
// time         : in form of hh:mm
// token        : your personal API token

 
// ** set configuration
include('../../../config/config.general.php');
// ** connect to database
include('../../../web/classes/connect.db.php');
// ** database functions
include('../../../web/classes/database.class.php');
// ** localization functions
include('../../../web/classes/local.class.php');
// ** business functions
include('../../../web/classes/business.class.php');
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
$_SESSION['property']           = ($_GET['propertyID']) ? $_GET['propertyID'] : '1';
$_SESSION['outletID']           = ($_GET['outletID']) ? $_GET['outletID'] : '';
$_SESSION['selectedDate']       = $_GET['selectedDate'];
$_SESSION['reservation_time']   = $_GET['time'];
$_SESSION['reservation_pax']    = $_GET['pax'];
$res_pax                        = $_GET['pax'];
$api_token                      = $_GET['token'];

// memorize selected outlet details
$rows = querySQL('db_outlet_info');
if($rows){
    foreach ($rows as $key => $value) {
        $_SESSION['selOutlet'][$key] = $value;
    }
}

// get outlet maximum capacity
$maxC = maxCapacity();

// get day off days
$dayoff = getDayoff();


// *** DATABASE queries ***

// check token
if ($api_token!='') {

    //check the user's API
    $request_count = validate_requests($api_token);

        // database request
        if ($status['code'] != 400 && $request_count < $max_requests) {
                
                //Check Availability
                // =-=-=-=-=-=-=-=-=

                // get Pax by timeslot
                $resbyTime = reservationsByTime('pax');
                $tblbyTime = reservationsByTime('tbl');
                // get availability by timeslot
                $occupancy = getAvailability($resbyTime,$general['timeintervall']);
                $tbl_occupancy = getAvailability($tblbyTime,$general['timeintervall']);               

                $val_capacity = $_SESSION['outlet_max_capacity']-$occupancy[$_SESSION['reservation_time']];
                $tbl_capacity = $_SESSION['outlet_max_tables']-$tbl_occupancy[$_SESSION['reservation_time']]; 

                if ($dayoff==0) {
                    if( $res_pax > $val_capacity || $tbl_capacity  < 1 ){
                                // NO more space available, reservation declined
                                $data['code'] = 406;
                                $data['status'] = getStatusCodeMessage($data['code']);
                    }else if( $res_pax <= $val_capacity || $tbl_capacity  >= 1 ){
                                // Free seats, reservation is ok
                                $data['code'] = 202;
                                $data['status'] = getStatusCodeMessage($data['code']);
                    }
                }else{
                                // Day off, reservation declined
                                $data['code'] = 403;
                                $data['status'] = getStatusCodeMessage($data['code']);
                }
                // END Availability

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
    $result['response']['availability'] = $data;

    // Toss back results as json encoded array.
    return_result_json($result);

?>
