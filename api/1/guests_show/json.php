<?php session_start();
/*
**************************************************************************
                              RESTful API
**************************************************************************/ 

// PARAMETER
// outletID     : a valid numeric number, outlet ID
// startDate    : in form of YYYY-MM-DD
// endDate      : in form of YYYY-MM-DD
// status       : guest status: HG,PASS,WALK or ALL; default = ALL
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
$start_date                 = $_GET['startDate'];
$end_date                   = $_GET['endDate'];
$outlet_id                  = $_GET['outletID'];
$reservation_hotelguest_yn  = (empty($_GET['status'])) ? $_POST['status'] : 'ALL';
$api_token                  = ($_SESSION['reservation_guest_name']!='') ? $_GET['token'] : '';

// set wildcat for 'ALL'
$reservation_hotelguest_yn  = ($reservation_hotelguest_yn='ALL') ? '%' : $reservation_hotelguest_yn;


// *** DATABASE queries ***

// check token
if ($api_token!='') {

    //check the user's API
    $request_count = validate_requests($api_token);

    // check the parameters
    if ($start_date > $end_date) {
            // create some error code/message
            $status['code'] = 400;
            $status['status'] = getStatusCodeMessage($status['code']); 
    }

        // database request
        if ($status['code'] != 400 && $request_count < $max_requests) {
            // return database property results

            //DEFINE SQL QUERY
            $sql ="SELECT outlet_name, reservation_date, reservation_time, reservation_guest_name, reservation_pax, reservation_hotelguest_yn, reservation_notes, reservation_guest_adress, reservation_guest_city, reservation_guest_email,reservation_advertise, reservation_guest_phone 
                FROM reservations INNER JOIN outlets ON (reservation_outlet_id = outlet_id) 
                WHERE reservation_date >= '".$start_date."' 
                AND reservation_date <= '".$end_date."' 
                AND reservation_hotelguest_yn LIKE '".$reservation_hotelguest_yn."' 
                AND reservation_wait = 0 
                AND reservation_hidden = 0 
                AND reservation_guest_name != '' 
                AND reservation_outlet_id = '".$outlet_id."' 
                ORDER BY reservation_guest_name ASC";   

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
        $result['response'] = $data;
    
    // Toss back results as json encoded array.
    return_result_json($result);

?>
