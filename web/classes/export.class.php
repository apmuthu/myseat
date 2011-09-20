<?php 
session_start();
// ** set configuration
include('../../config/config.general.php');
// ** database functions
include('../classes/database.class.php');
// ** connect to database
include('../classes/connect.db.php');
// ** all database queries
include('../classes/db_queries.db.php');
// ** set configuration
include('../../config/config.inc.php');
// ** localization functions
include('../classes/local.class.php');

// translate to selected language
translateSite(substr($_SESSION['language'],0,2),'../');

// prevent dangerous input
secureSuperGlobals();
$start_date = $_POST['s_dbdate'];
$end_date = $_POST['e_dbdate'];
$outlet_id = $_POST['outlet_id'];
$reservation_hotelguest_yn = $_POST['reservation_hotelguest_yn'];

//DEFINE SQL QUERY:
//you can use just about ANY kind of select statement you want - 
//edit this to suit your needs!
$sql ="SELECT outlet_name, reservation_date, reservation_time, reservation_guest_name, reservation_pax, reservation_hotelguest_yn, reservation_notes, reservation_guest_adress, reservation_guest_city, reservation_guest_email,reservation_advertise, reservation_guest_phone 
FROM reservations INNER JOIN outlets ON (reservation_outlet_id = outlet_id) 
WHERE reservation_date >= '".$start_date."' AND reservation_date <= '".$end_date."' ";
if($reservation_hotelguest_yn!=''){
$sql .="AND reservation_hotelguest_yn = '".$reservation_hotelguest_yn."' ";
}elseif($outlet_id == 0){
$sql .="AND reservation_wait = 0 AND reservation_hidden = 0 AND property_id = '".$_SESSION['propertyID']."' ORDER BY reservation_guest_name ASC";	
}else{
$sql .="AND reservation_wait = 0 AND reservation_hidden = 0 AND reservation_outlet_id = '".$outlet_id."' ORDER BY reservation_guest_name ASC";	
}


$result = query($sql);

//Optional: print out title to top of Excel or Word file with Timestamp
//for when file was generated:
//set $Use_Titel = 1 to generate title, 0 not to use title
$Use_Title = 1;
//define date for title: EDIT this to create the time-format you need
$now_date = date('m-d-Y H:i');
//define title for .doc or .xls file: EDIT this if you want
$title = $general['app_name']." "._export." "._at_." ".$now_date;
/*

Leave the connection info below as it is:
just edit the above.

(Editing of code past this point recommended only for advanced users.)
*/

//if this parameter is included ($w=1), file returned will be in word format ('.doc')
//if parameter is not included, file returned will be in excel format ('.xls')
if (isset($w) && ($w==1))
{
	$file_type = "msword";
	$file_ending = "doc";
}else {
	$file_type = "vnd.ms-excel";
	$file_ending = "xls";
}
//header info for browser: determines file type ('.doc' or '.xls')
header("Content-Type: application/$file_type");
header("Content-Disposition: attachment; filename=myseat_export.$file_ending");
header("Pragma: no-cache");
header("Expires: 0");

/*	Start of Formatting for Word or Excel	*/

if (isset($w) && ($w==1)) //check for $w again
{
	/*	FORMATTING FOR WORD DOCUMENTS ('.doc')   */
	//create title with timestamp:
	if ($Use_Title == 1)
	{
		echo("$title\n\n");
	}
	//define separator (defines columns in excel & tabs in word)
	$sep = "\n"; //new line character

	while($row = mysql_fetch_row($result))
	{
		//set_time_limit(60); // HaRa
		$schema_insert = "";
		for($j=0; $j<mysql_num_fields($result);$j++)
		{
		//define field names
		$field_name = mysql_field_name($result,$j);
		//will show name of fields
		$schema_insert .= "$field_name:\t";
			if(!isset($row[$j])) {
				$schema_insert .= "NULL".$sep;
				}
			elseif ($row[$j] != "") {
				$schema_insert .= "$row[$j]".$sep;
				}
			else {
				$schema_insert .= "".$sep;
				}
		}
		$schema_insert = str_replace($sep."$", "", $schema_insert);
		$schema_insert .= "\t";
		print(trim($schema_insert));
		//end of each mysql row
		//creates line to separate data from each MySQL table row
		print "\n----------------------------------------------------\n";
	}
}else{
	/*	FORMATTING FOR EXCEL DOCUMENTS ('.xls')   */
	//create title with timestamp:
	if ($Use_Title == 1)
	{
		echo("$title\n");
	}
	//define separator (defines columns in excel & tabs in word)
	$sep = "\t"; //tabbed character
echo $sql;
	//start of printing column names as names of MySQL fields
	for ($i = 0; $i < mysql_num_fields($result); $i++)
	{
		echo mysql_field_name($result,$i) . "\t";
	}
	print("\n");
	//end of printing column names

	//start while loop to get data
	while($row = mysql_fetch_row($result))
	{
		//set_time_limit(60); // HaRa
		$schema_insert = "";
		for($j=0; $j<mysql_num_fields($result);$j++)
		{
			if(!isset($row[$j]))
				$schema_insert .= "NULL".$sep;
			elseif ($row[$j] != "")
				$schema_insert .= "$row[$j]".$sep;
			else
				$schema_insert .= "".$sep;
		}
		$schema_insert = str_replace($sep."$", "", $schema_insert);
		//following fix suggested by Josue (thanks, Josue!)
		//this corrects output in excel when table fields contain \n or \r
		//these two characters are now replaced with a space
		$schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
		$schema_insert .= "\t";
		print(trim($schema_insert));
		print "\n";
	}
}

?>
