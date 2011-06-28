<?php session_start();
$_SESSION['role'] = 6;
$_SESSION['language'] = "en_EN";
$_SESSION["valid_user"] = TRUE;

// ** set configuration
include('../config/config.general.php');
// ** connect to database
include('../web/classes/connect.db.php');
// ** database functions
include('../web/classes/database.class.php');
// ** localization functions
include('../web/classes/local.class.php');
// translate to selected language
translateSite(substr($_SESSION['language'],0,2),'../web/');
// ** business functions
include('../web/classes/business.class.php');
// ** all database queries
include('../web/classes/db_queries.db.php');
// ** get superglobal variables
	include('../web/includes/get_variables.inc.php');

// id of property
if (!$_SESSION['propertyID']) {
	if ($_GET['propertyID']) {
		$_SESSION['propertyID'] = ($_GET['propertyID']) ? (int)$_GET['propertyID'] : 0;
	}else if ($_POST['propertyID']) {
		$_SESSION['propertyID'] = ($_POST['propertyID']) ? (int)$_POST['propertyID'] : 0;
	}else {
		$_SESSION['propertyID'] = 0;
	}
}
$_SESSION['property'] =	$_SESSION['propertyID'];
// id of outlet
if (!$_SESSION['outletID']) {
	$_SESSION['outletID'] = ($_GET['outletID']) ? (int)$_GET['outletID'] : querySQL('standard_outlet');
}else if ($_GET['outletID']) {
	$_SESSION['outletID'] = (int)$_GET['outletID'];
}
if ($_POST['reservation_outlet_id']) {
	$_SESSION['outletID'] = $_POST['reservation_outlet_id'];
}

// +++ memorize selected outlet details; maybe moved reservation +++
$rows = querySQL('db_outlet_info');

if($rows){
	foreach ($rows as $key => $value) {
		$_SESSION['selOutlet'][$key] = $value;
	}
}

// Outlet name
$outlet_name = $_SESSION['selOutlet']['outlet_name'];
//$outlet_name = querySQL('db_outlet');

// ** set configuration
include('../config/config.inc.php');

?>

<style type="text/css">

 #myseat_widget {
   border:1px solid #aaa;
   background: <?php echo $general['contactform_background'];?>;
   width:235px;
   padding: 5px;
   text-align:left;
   font-family:helvetica, arial;
   margin:0 auto;
   position: relative;
   line-height:1em;
 	-moz-border-radius: 10px 10px 10px 10px;
	-webkit-border-radius: 10px 10px 10px 10px;
 }
 
 #myseat_widget table, #myseat_widget tr, #myseat_widget td, #myseat_widget form {
   margin: 0;
   padding: 0;
   vertical-align: top;
 }
 
 #myseat_widget .w_buttons {
   white-space: nowrap;
   
 }
 
 #myseat_widget .w_header, #myseat_widget .w_header a, #myseat_widget .w_header a:visited {
   font-size:18px;
   line-height:18px;
   font-weight:bold;
   margin:3px 1px 0px 1px ;
   color:#000;
   
 }
 
 #myseat_widget a.w_button, #myseat_widget a.w_button:visited {
   display: inline-block;
   text-align: center;
   line-height: 1;
   border:1px solid #dd7200;
   background: #f0a21f  repeat-x; 
   font-size:15px;
   font-weight:bold;
   width:68px;
   height:16px;
   padding:2px 0px 2px 0px;
   margin:3px 3px 3px 0px;
   color:white;
   font-family:helvetica, arial;
   -moz-border-radius: 5px;
   -webkit-border-radius: 5px;
   text-decoration:none;
 }

 #myseat_widget .w_instructions {
   font-size:12px;
font-weight: bold;
   color:#000;
   margin:7px 2px 3px 2px;
   
 }

 #w_reclam { color:#000; }
	
#myseat_widget .w_button {
  display:inline-block;
   text-align:center;
   -moz-border-radius: 5px;
   -webkit-border-radius: 5px;
 	padding:0px 6px 3px 6px !important;
 	background: #F1F2F2;
   border:1px solid #ccc;
   height:24px !important;
   vertical-align:middle;
   font-size:15px;
   font-weight:bold;
   color: #000;
}

#myseat_widget input.w_button {
     height: 20px;
     padding: 0 3px;
     background: #F1F2F2;
   }

   html > body #myseat_widget input.w_button {
     padding:0 4px;
     margin:-2px 0 10px;
   }
   
#myseat_widget .myseat_table td {
	padding: 0 8px 2px 0;
	vertical-align: bottom;
}

dl.myseat_table {
  display:block;
  padding:0;
  margin:0;
  clear:both;
}
dl.myseat_table dt,
dl.myseat_table dd {
  margin:0;
  padding:0;
  display:block;
  float:left;
  font-size:12px;
}

dl.myseat_table dd select { font-size:12px; }

dl.day,
dl.time,
dl.gobutton {
  display:block;
  margin-right:2px;
  margin-bottom:5px;
  float:left;
  clear:none;  
}
html > body dl.day,
html > body dl.time,
html > body dl.gobutton {margin-right:5px;}

dl.day { width:100px; }
dl.time { width:75px;}
dl.gobutton {
  width:38px;
  margin-right:0;
}
html > body dl.gobutton {margin-left:5px;}

dl.reclam dt {display:none;}
dl.reclam dd{display:block;width:100%;float:left;}
dl.empty, dl.empty dt, dl.empty dd {height:0;line-height:0;}

</style>


<script type="text/javascript" charset="utf-8">
var arItems = new Array();
<?php
// unix timestamp for now
$stamp 				= time();

// begin time dropdown array
$time_array = " arItems = [\n";

for($j = 0; $j < 14; $j++) {
	
	 $_SESSION['selectedDate'] = date($settings['dbdate'],$stamp);

	// +++ Availability +++
	// get outlet maximum capacity
	 $maxC = maxCapacity();

	// get Pax by timeslot
	 $resbyTime = reservationsByTime('pax');
	 $tblbyTime = reservationsByTime('tbl');
	 $_SESSION['passbyTime'] = reservationsByTime('pass');

	// get availability by timeslot
	 $availability = getAvailability($resbyTime,$general['timeintervall']);
	 $tbl_availability = getAvailability($tblbyTime,$general['timeintervall']);
	
	// the time dropdown array
	$output = widgetTimeList($general['timeformat'], $general['timeintervall'], $_SESSION['selOutlet']['outlet_open_time'], $_SESSION['selOutlet']['outlet_close_time']);

	if ( $j < 13 && strlen($output) > 1){
			$time_array .= $output;
	}else{
		$time_array .= "['".$_SESSION['selectedDate']."','','-----'], \n";
	}
	// Now increase timestamp of one day
	// (hours*minutes*seconds)
	$stamp += (24*60*60);
}		
	// cut last comma
	$time_array = substr($time_array,0,-3);
	// close array
	$time_array .= "\n]\n";

// output the javascript array
echo "var ".$time_array;

?>

function fillTimes(intStart) {
    var fTypes = document.booking.selectedDate;
    var fItems = document.booking.times;
    var a = arItems;
    var b, c, d, intItem, intType

    if ( intStart > 0 ) {
        for ( b = 0; b < a.length; b++ ) {
            if ( a[b][1] == intStart )
                intType = a[b][0];
        }

        for ( c = 0; c < fTypes.length; c++ ) {
            if ( fTypes.options[ c ].value == intType )
                fTypes.selectedIndex = c;
        }
    }

    if ( intType == null )
        intType = fTypes.options[ fTypes.selectedIndex ].value;

    fItems.options.length = 0;

    for ( d = 0; d < a.length; d++ ) {
        if ( a[d][0] == intType )
            fItems.options[ fItems.options.length ] = new Option( a[d][2], a[d][1] );

        if ( a[d][1] == intStart )
            fItems.selectedIndex = fItems.options.length - 1;
    }
}


window.onload=function()
{
	/* initial set time dropdown */
 fillTimes(0);

 var l = arItems.length;
  if(l > 1){
	var p = "&copy; 2011 by mySeat. Online.";
  }else{
	var p = "&copy; 2011 by mySeat. Offline.";
  }
 var reclame = document.getElementById('w_reclam');
 if (reclame){reclame.innerHTML = p;}
}

</script>

<div id='myseat_widget'>
<form name="booking" id="booking" target="_top" method="get" action="<?php echo $global_basedir."contactform/index.php?so=ON&prp=".$row->property_id."&outletID=".$row->outlet_id;?>">
	<input type="hidden" value="<?php echo $_SESSION['outletID']; ?>" name="outletID" id="outletID">
	<input type="hidden" value="<?php echo $_SESSION['propertyID']; ?>" name="propertyID" id="propertyID">
<dt><?php echo $outlet_name; ?> </dt>
<dl class="myseat_table day">
    <dt class="w_instructions"><?php echo _date;?></dt>
    <dd>
      	<select name="selectedDate" id="selectedDate" onChange="fillTimes(0)">
				<?php
				// unix timestamp for now
				$stamp = time();
				
				for($i = 0; $i < 14; $i++) {
				    // Substitue this with your dropdown code
					if ($i == 0) {
						$datetext = _today;
					}else{
						$datetext = strftime('%a %e %b', $stamp);	
					}
					
				    echo "<option value='".date($settings['dbdate'],$stamp)."' ";
					if ($i == 0) {
						echo "selected='selected' ";
					}
					echo ">".$datetext."</option>\n";
				    // Now increase timestamp of one day
					// (hours*minutes*seconds)
				    $stamp += (24*60*60);
				} 
				?>
		</select>
    </dd>
  </dl>
  <dl class="myseat_table time">
    <dt class="w_instructions"><?php echo _time;?></dt>
    <dd>
		<select name="times" id="times"></select>
    </dd>
  </dl>
  <dl class="myseat_table gobutton">
    <dt class="w_instructions">&nbsp;</dt>
    <dd><input type="submit" value="<?php echo _ok_;?>" name="commit" class="w_button"></dd>
  </dl>
  <dl class="myseat_table reclam">
    <dt></dt>
    <dd id="w_reclam"></dd>
  </dl>
  <dl class="myseat_table empty"></dl>
</form>
</div>