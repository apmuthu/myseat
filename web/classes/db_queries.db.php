<?php

function querySQL($statement){
	GLOBAL $storno,$wait,$author,$cellid,$repeatid,$id,$value,$field,$searchquery,$dbTables;
	$today 		  = date('Y-m-d');
	$yesterday	  = date('Y-m-d', time()-86400);
	$before_yesterday = date('Y-m-d', time()-172800);

	switch($statement){
		case 'availability':
			$result = query("SELECT reservation_time, SUM(reservation_pax) AS pax_total, COUNT(reservation_id) AS tbl_total
							FROM `$dbTables->reservations`
							WHERE `reservation_hidden` = '0' 
							AND `reservation_wait` = '0' 
							AND `reservation_outlet_id` = '%d' 
							AND `reservation_date` = '%s'
							AND ( NOT `reservation_status` = 'DEP' AND NOT `reservation_status` = 'NSW')
							AND `reservation_id` != '%d'
							GROUP BY `reservation_time`
							ORDER BY `reservation_time` ASC",
							$_SESSION['outletID'],$_SESSION['selectedDate'],(isset($_SESSION['resID']) ? $_SESSION['resID'] : 0)
							);
			return getRowList($result);
		break;
		case 'passerby_availability':
			$result = query("SELECT reservation_time, SUM(reservation_pax) AS passerby_total
							FROM `$dbTables->reservations`  
							WHERE `reservation_hidden` = '0' 
							AND `reservation_wait` = '0' 
							AND `reservation_outlet_id` = '%s' 
							AND `reservation_date` = '%s'
							AND ( NOT `reservation_status` = 'DEP' AND NOT `reservation_status` = 'NSW')
							AND `reservation_hotelguest_yn` = 'PASS'
							GROUP BY `reservation_time`
							ORDER BY `reservation_time` ASC",
							$_SESSION['outletID'],$_SESSION['selectedDate']
							);
			return getRowList($result);
		break;
		case 'maxcapacity':
			$out1 = array();
			$result = query("SELECT outlet_max_capacity, outlet_max_tables, passerby_max_pax FROM `$dbTables->outlets` 
							WHERE `outlet_id`='%d'",$_SESSION['outletID']);
			$out1 = getRowListarray($result);
			$result = query("SELECT outlet_child_tables, outlet_child_capacity, outlet_child_passer_max_pax FROM `$dbTables->maitre` 
							WHERE `maitre_outlet_id`='%d' 
							AND `maitre_date`='%s'",$_SESSION['outletID'],$_SESSION['selectedDate']);
			$out2 = getRowListarray($result);
			if (is_array($out1) && is_array($out2)) {
				return array_merge($out1,$out2);
			}else{
				return $out1;
			}
			
		break;
		case 'passerby_max_pax':
			$result = query("SELECT sum(reservation_pax) FROM $dbTables->reservations 
							WHERE `reservation_date`='%s' 
							AND `reservation_outlet_id`='%d' 
							AND `reservation_hidden`=0 
							AND `reservation_wait`=0 
							AND `reservation_hotelguest_yn`='PASS' 
							",$_SESSION['selectedDate'],$_SESSION['outletID']);
			return getResult($result);
		break;
		case 'max_id':
			$result = query("SELECT MAX(reservation_id) FROM $dbTables->reservations
							WHERE `reservation_date`='%s' 
							AND `reservation_outlet_id`='%d' 
							AND `reservation_hidden`=0 
							",$_SESSION['selectedDate'],$_SESSION['outletID']);
			return getResult($result);
		break;
		case 'standard_outlet':
			$result = query("SELECT outlet_id FROM `$dbTables->outlets` 
							WHERE `property_id` = '%d' 
							AND ( `saison_year` = 0 OR `saison_year` = YEAR(NOW()) )
							AND saison_start <= '%d' 
							AND saison_end >= '%d' 
							ORDER BY outlet_id LIMIT 1", 
							$_SESSION['property'],$_SESSION['selectedDate_saison'], $_SESSION['selectedDate_saison']);
			return getResult($result);
		break;
		case 'web_standard_outlet':
			$result = query("SELECT outlet_id FROM `$dbTables->outlets` 
							WHERE `property_id` = '%d' 
							AND ( `saison_year` = 0 OR `saison_year` = YEAR(NOW()) )
							AND `webform` ='1' 
							ORDER BY outlet_name DESC LIMIT 1",
							$_SESSION['property']);
			return getResult($result);
		break;
		case 'num_outlets':
			$result = query("SELECT COUNT(*) FROM `$dbTables->outlets` 
							WHERE ( `saison_year` = 0 OR `saison_year` = '%d' )
							AND `property_id` ='%d'
							AND `webform` = '1'",$_SESSION['selectedDate_year'],$_SESSION['property']);
			return getResult($result);
		break;
		case 'check_web_outlet':
			$result = query("SELECT COUNT(*) FROM `$dbTables->outlets` 
							WHERE ( `saison_year` = 0 OR `saison_year` = '%d' )
							AND `outlet_id` ='%d'
							AND `webform` = '1'",$_SESSION['selectedDate_year'],$_SESSION['outletID']);
			return getResult($result);
		break;
		case 'property_id_outlet':
			$result = query("SELECT `property_id` FROM `$dbTables->outlets` 
							WHERE `outlet_id` ='%d'",$_SESSION['outletID']);
			return getResult($result);
		break;
		case 'security_outlet':
			$result = query("SELECT COUNT(*) FROM `$dbTables->outlets` 
							WHERE ( `saison_year` = 0 OR `saison_year` = '%d' )
							AND `property_id` ='%d'
							AND `outlet_id` ='%d'
 ",$_SESSION['selectedDate_year'],$_SESSION['property'],$_SESSION['outletID']);
			return getResult($result);
		break;
		case 'db_outlet':
			$result = query("SELECT outlet_name FROM `$dbTables->outlets` WHERE `outlet_id` ='%d' AND `property_id` ='%d' LIMIT 1",$_SESSION['outletID'],$_SESSION['property']);
			return getResult($result);
		break;
		case 'db_prop_pic':
			$result = query("SELECT img_filename FROM `$dbTables->properties` WHERE `id` ='%d' LIMIT 1",$_SESSION['property']);
			return getResult($result);
		break;
		case 'db_property':
			$result = query("SELECT name FROM `$dbTables->properties` WHERE `id` ='%d' LIMIT 1",$_SESSION['property']);
			return getResult($result);
		break;
		case 'db_outlets':
			$result = query("SELECT * FROM `$dbTables->outlets` 
							WHERE ( `saison_year` = 0 OR `saison_year` = '%d' )
							AND `property_id` ='%d' 
							ORDER BY outlet_name",$_SESSION['selectedDate_year'],$_SESSION['property']);
			return getRowList($result);
		break;
		case 'db_outlets_web':
			$result = query("SELECT outlet_id, outlet_name, outlet_description, cuisine_style, saison_start, saison_end 
							FROM `$dbTables->outlets` 
							WHERE ( `saison_year` = 0 OR `saison_year` = '%d' )
							AND `property_id` ='%d'
							AND `webform` = '1'
							ORDER BY outlet_name",$_SESSION['selectedDate_year'],$_SESSION['property']);
			return getRowList($result);
		break;
		case 'db_all_outlets':
			$result = query("SELECT outlet_id, outlet_name, outlet_description, cuisine_style, 
							outlet_max_capacity, outlet_max_tables, outlet_open_time, outlet_close_time, 
							saison_start, saison_end, saison_year, webform, avg_duration 
							FROM `$dbTables->outlets` 
							WHERE `property_id` ='%d' 
							AND ( `saison_year` = 0 OR `saison_year` = '%d' )
							ORDER BY saison_year ASC, outlet_name ASC",$_SESSION['property'], $_SESSION['selectedDate_year']);
			return getRowList($result);
		break;
		case 'db_all_outlets_old':
			$result = query("SELECT  outlet_id, outlet_name, outlet_description, cuisine_style, 
							outlet_max_capacity, outlet_max_tables, outlet_open_time, outlet_close_time, 
							saison_start, saison_end, saison_year, webform, avg_duration 
							FROM `$dbTables->outlets` 
							WHERE `property_id` ='%d' 
							AND `saison_year` < '%d'
							AND `saison_year` != 0
							ORDER BY saison_year ASC, outlet_name ASC",$_SESSION['property'], $_SESSION['selectedDate_year']);
			return getRowList($result);
		break;
		case 'db_outlet_info':
			$result = query("SELECT outlet_id, outlet_name, property_id, outlet_description, outlet_description_en,	 
						cuisine_style, property_id, outlet_max_capacity, outlet_max_tables, outlet_open_time, 	 
						outlet_close_time, outlet_timestamp, outlet_closeday, saison_start, saison_end,  	  	 
						saison_year, webform, limit_password, confirmation_email, passerby_max_pax, avg_duration,	 
						1_open_time,1_close_time, 2_open_time,2_close_time, 	 
						3_open_time,3_close_time, 4_open_time,4_close_time, 	 
						5_open_time, 5_close_time, 6_open_time, 6_close_time, 	 
						0_open_time, 0_close_time, 1_open_break, 1_close_break, 	 
						2_open_break, 2_close_break, 3_open_break, 3_close_break, 	 
						4_open_break, 4_close_break, 5_open_break, 5_close_break, 	 
						6_open_break, 6_close_break, 0_open_break, 0_close_break
							FROM `$dbTables->outlets` 
							WHERE `outlet_id` ='%d' 
							AND `property_id` ='%d' ",$_SESSION['outletID'],$_SESSION['property']);
			return getRowListarray($result);
		break;
		case 'outlet_info':
			$result = query("SELECT * FROM `$dbTables->outlets` 
							LEFT JOIN `$dbTables->properties` on $dbTables->outlets.property_id = $dbTables->properties.id 
							WHERE `outlet_id` ='%d'
							AND `property_id` ='%d' 
							LIMIT 1",$_SESSION['outletID'],$_SESSION['property']);
			return getRowList($result);
		break;
		case 'db_propery_events':
			$result = query("SELECT id, outlet_id, subject,
			description, event_date, start_time, end_time,
			advertise_start, price 
							FROM `$dbTables->events` 
							WHERE `property_id` ='%d' 
							ORDER BY `event_date` DESC",$_SESSION['property']);
			return getRowList($result);
		break;
		case 'db_outlet_events':
			$result = query("SELECT id, outlet_id, subject,
			description, event_date, start_time, end_time,
			advertise_start, price 
							FROM `$dbTables->events` 
							WHERE `outlet_id` ='%d' 
							ORDER BY `event_date` DESC",$_SESSION['outletID']);
			return getRowList($result);
		break;
		case 'event_data_single':
			$result = query("SELECT id, outlet_id, property_id, subject,
			description, event_date, start_time, end_time,
			advertise_start, price 
							FROM `$dbTables->events` 
							WHERE `id` ='%d' 
							LIMIT 1",$_SESSION['eventID']);
			return getRowListarray($result);
		break;
		case 'event_advertise':
			$result = query("SELECT $dbTables->events.id, $dbTables->events.outlet_id, $dbTables->events.property_id, $dbTables->events.subject, 
			$dbTables->events.description, $dbTables->events.event_date, $dbTables->events.start_time, $dbTables->events.end_time,
			$dbTables->events.advertise_start, $dbTables->events.price, $dbTables->outlets.outlet_name FROM `$dbTables->events`
						LEFT JOIN `$dbTables->outlets` ON $dbTables->events.outlet_id = $dbTables->outlets.outlet_id
						WHERE DATE_SUB(`event_date`,INTERVAL `advertise_start` DAY) <= '%s'
						AND `event_date` > '%s'
						AND $dbTables->outlets.property_id ='%d' 
						ORDER BY advertise_start ASC", $_SESSION['selectedDate'],$_SESSION['selectedDate'],$_SESSION['property']);
			return getRowList($result);
		break;
		case 'event_advertise_web':
			$result = query("SELECT $dbTables->events.id, $dbTables->events.outlet_id, $dbTables->events.property_id, $dbTables->events.subject,
			$dbTables->events.description, $dbTables->events.event_date, $dbTables->events.start_time, $dbTables->events.end_time,
			$dbTables->events.advertise_start, $dbTables->events.price, $dbTables->outlets.outlet_name FROM `$dbTables->events`
						LEFT JOIN `$dbTables->outlets` ON $dbTables->events.outlet_id = $dbTables->outlets.outlet_id
						WHERE id >= (SELECT FLOOR( MAX(id) * RAND()) FROM `$dbTables->events` ) 
						AND DATE_SUB(`event_date`,INTERVAL `advertise_start` DAY) <= CURDATE()
						AND `event_date` >= CURDATE()
						AND `event_date` >= '%s'
						AND `webform` = '1'
						ORDER BY advertise_start,event_date ASC
						LIMIT 5",$_SESSION['selectedDate']);
			return getRowList($result);
		break;
		case 'event_data_day':
			$result = query("SELECT id, outlet_id, property_id, subject,
						description, event_date, start_time, end_time,
						advertise_start, price 
						FROM `$dbTables->events` 
						WHERE `event_date` ='%s' 
						AND `outlet_id` ='%d' 
						AND `property_id` ='%d'",
						$_SESSION['selectedDate'],$_SESSION['outletID'],$_SESSION['property']);
			return getRowList($result);
		break;
		case 'user_data':
			$result = query("SELECT userID,username,realname,password,email,role,
		  					property_id,active,confirm_code,last_ip,last_login,created,modified 
							FROM `$dbTables->plc_users` 
							WHERE `userID` ='%d' 
							LIMIT 1",$_SESSION['userID']);
			return getRowListarray($result);
		break;
		case 'user_confirm_code':
			$result = query("UPDATE `$dbTables->plc_users` SET confirm_code = '%s', active = '0' 
								WHERE `userID` ='%d' LIMIT 1",$_SESSION['confHash'],$id);
			return $result;
		break;
		case 'check_confirm_code':
			$result = query("SELECT active,confirm_code FROM `$dbTables->plc_users` WHERE confirm_code='%s'",$_SESSION['confHash']);
			return getRowListarray($result);
		break;
		case 'user_confirm_activate':
			$result = query("UPDATE `$dbTables->plc_users` SET confirm_code = '', active = '1' 
								WHERE confirm_code='%s'",$_SESSION['confHash']);
			return $result;
		break;
		case 'maitre_info':
			$result = query("SELECT maitre_id, maitre_outlet_id,
							maitre_date, maitre_comment_day,
							maitre_comment_day_timestamp, maitre_comment_day_name,
							maitre_timestamp, maitre_ip,
							maitre_author, outlet_child_tables,
							outlet_child_capacity, outlet_capacity_timestamp,
							outlet_child_passer_max_pax, outlet_child_dayoff 
							FROM `$dbTables->maitre` 
							WHERE `maitre_outlet_id` ='%d' 
							AND `maitre_date`='%s' 
							LIMIT 1",$_SESSION['outletID'],$_SESSION['selectedDate']);
			return getRowList($result);
		break;
		case 'maitre_dayoffs':
			$result = query("SELECT `maitre_date` FROM `$dbTables->maitre` 
							WHERE `outlet_child_dayoff` = 'ON'
							AND YEAR(maitre_date) = '%s'
							AND `maitre_outlet_id` ='%d' 
							ORDER BY `maitre_date` ASC",date('Y'),$_SESSION['outletID']);
			return getRowList($result);
		break;
		case 'outlet_closedays':
			$result = query("SELECT `outlet_closeday` FROM `$dbTables->outlets` 
							WHERE `outlet_id` ='%d' 
							",$_SESSION['outletID']);
			return getResult($result);
		break;
		case 'db_all_users':
			$result = query("SELECT userID,username,realname,password,email,role,
		  					property_id,active,confirm_code,last_ip,last_login,created,modified
							FROM `$dbTables->plc_users` ORDER BY `username`");
			return getRowList($result);
		break;
		case 'db_prp_users':
			$result = query("SELECT userID,username,realname,password,email,role,
		  					property_id,active,confirm_code,last_ip,last_login,created,modified 
							FROM `$dbTables->plc_users`
							WHERE `property_id` ='%d'
							ORDER BY `username`",$_SESSION['property']);
			return getRowList($result);
		break;
		case 'recent':
			$result = query("SELECT reservation_id, reservation_bookingnumber, reservation_outlet_id,
			reservation_date, reservation_time, reservation_title,
			reservation_guest_name, reservation_guest_adress, reservation_guest_city,
			reservation_guest_email, reservation_guest_phone, reservation_pax,
			reservation_hotelguest_yn, reservation_notes, reservation_booker_name,
			reservation_timestamp, reservation_ip, reservation_hidden,
			reservation_wait, repeat_id, reservation_bill,
			reservation_discount, reservation_bill_paid, reservation_billet_sent,
			reservation_parkticket, reservation_table, reservation_status,
			reservation_advertise,reservation_referer 
						FROM `$dbTables->reservations` 
						WHERE reservation_outlet_id='%d' 
						ORDER BY reservation_timestamp DESC LIMIT 0,4",$_SESSION['outletID']);
			return getRowList($result);
		break;
		case 'tautologous':
			$result = query("SELECT count(*) FROM `$dbTables->reservations` WHERE reservation_date='%s' AND reservation_hidden=0 AND reservation_wait=0 AND reservation_guest_name='%s' ",$_SESSION['selectedDate'],$_SESSION['reservation_guest_name']);
			return getResult($result);
		break;
		case 'capability':
			$result = query("SELECT `%d` FROM `$dbTables->capabilities` WHERE `capability`='%s'", $_SESSION['role'], $_SESSION['capability']);
			return getResult($result);
		break;
		case 'capabilities':
			$result = query("SELECT `capability`,`1`,`2`,`3`,`4`,`5`,`6` FROM `$dbTables->capabilities`");
			//return getRowListarray($result);
			return $result;
		break;
		case 'reservation_info':
			$result = query("SELECT reservation_id, reservation_bookingnumber, reservation_outlet_id,
			reservation_date, reservation_time, reservation_title,
			reservation_guest_name, reservation_guest_adress, reservation_guest_city,
			reservation_guest_email, reservation_guest_phone, reservation_pax,
			reservation_hotelguest_yn, reservation_notes, reservation_booker_name,
			reservation_timestamp, reservation_ip, reservation_hidden,
			reservation_wait, repeat_id, reservation_bill,
			reservation_discount, reservation_bill_paid, reservation_billet_sent,
			reservation_parkticket, reservation_table, reservation_status,
			reservation_advertise,reservation_referer,
			$dbTables->outlets.outlet_name,$dbTables->res_repeat.id,$dbTables->res_repeat.start_date,$dbTables->res_repeat.end_date 
					FROM `$dbTables->reservations`
					LEFT JOIN `$dbTables->outlets` ON outlet_id = reservation_outlet_id
					LEFT JOIN `$dbTables->res_repeat` ON $dbTables->res_repeat.id = $dbTables->reservations.repeat_id  
					WHERE $dbTables->reservations.reservation_id = '%d' LIMIT 1",$_SESSION['resID']);
			return getRowList($result);
		break;
		case 'reservations':
			$result = query("SELECT reservation_id, reservation_bookingnumber, reservation_outlet_id,
			reservation_date, reservation_time, reservation_title,
			reservation_guest_name, reservation_guest_adress, reservation_guest_city,
			reservation_guest_email, reservation_guest_phone, reservation_pax,
			reservation_hotelguest_yn, reservation_notes, reservation_booker_name,
			reservation_timestamp, reservation_ip, reservation_hidden,
			reservation_wait, repeat_id, reservation_bill,
			reservation_discount, reservation_bill_paid, reservation_billet_sent,
			reservation_parkticket, reservation_table, reservation_status,
			reservation_advertise,reservation_referer
							FROM `$dbTables->reservations` 
							INNER JOIN `$dbTables->outlets` ON `outlet_id` = `reservation_outlet_id` 
							WHERE `reservation_hidden` = '%d' 
							AND `reservation_wait` = '%d' 
							AND `reservation_outlet_id` = '%d' 
							AND `reservation_date` = '%s' 
							ORDER BY `reservation_time` ASC",
							$_SESSION['storno'],$_SESSION['wait'],$_SESSION['outletID'],$_SESSION['selectedDate']
							);
			return getRowList($result);
		break;
		case 'all_reservations':
			$result = query("SELECT reservation_id, reservation_bookingnumber, reservation_outlet_id,
			reservation_date, reservation_time, reservation_title,
			reservation_guest_name, reservation_guest_adress, reservation_guest_city,
			reservation_guest_email, reservation_guest_phone, reservation_pax,
			reservation_hotelguest_yn, reservation_notes, reservation_booker_name,
			reservation_timestamp, reservation_ip, reservation_hidden,
			reservation_wait, repeat_id, reservation_bill,
			reservation_discount, reservation_bill_paid, reservation_billet_sent,
			reservation_parkticket, reservation_table, reservation_status,
			reservation_advertise,reservation_referer,$dbTables->outlets.outlet_name
							FROM `$dbTables->reservations` 
							INNER JOIN `$dbTables->outlets` ON `outlet_id` = `reservation_outlet_id` 
							WHERE `reservation_hidden` = '0' 
							AND `reservation_wait` = '%d' 
							AND `property_id` = '%d' 
							AND `reservation_date` = '%s' 
							ORDER BY `reservation_time` ASC",
							$_SESSION['wait'],$_SESSION['propertyID'],$_SESSION['selectedDate']
							);
			return getRowList($result);
		break;
		case 'search':
			$result = query("SELECT reservation_id, reservation_bookingnumber, reservation_outlet_id,
			reservation_date, reservation_time, reservation_title,
			reservation_guest_name, reservation_guest_adress, reservation_guest_city,
			reservation_guest_email, reservation_guest_phone, reservation_pax,
			reservation_hotelguest_yn, reservation_notes, reservation_booker_name,
			reservation_timestamp, reservation_ip, reservation_hidden,
			reservation_wait, repeat_id, reservation_bill,
			reservation_discount, reservation_bill_paid, reservation_billet_sent,
			reservation_parkticket, reservation_table, reservation_status,
			reservation_advertise,reservation_referer, outlet_name 
				FROM `$dbTables->reservations`
				INNER JOIN `$dbTables->outlets` ON `outlet_id` = `reservation_outlet_id` 
				WHERE `property_id` = '%d' 
				AND (`reservation_guest_name` LIKE '%s' 
					OR `reservation_bookingnumber` LIKE '%s' 
					OR `reservation_guest_phone` LIKE '%s') 
				ORDER BY reservation_guest_name ASC",$_SESSION['propertyID'],$searchquery,$searchquery,$searchquery);
			return getRowList($result);
		break;
		case 'reservation_visits':		
			$result = query("SELECT COUNT(*) FROM `$dbTables->reservations` 
							WHERE `reservation_guest_name`='%s' 
							AND `reservation_hidden`=0", $_SESSION['reservation_guest_name']);
			return getResult($result);
		break;
		case 'reservation_last_visit':
			$result = query("SELECT `reservation_date` FROM `$dbTables->reservations` 
							WHERE `reservation_guest_name` = '%s' 
							AND `reservation_hidden` = 0 AND `reservation_date` <= now() 
							ORDER BY `reservation_timestamp` DESC", $_SESSION['reservation_guest_name']);
			return getResult($result);
		break;
		case 'reservation_history':
			$result = query("SELECT DISTINCT `reservation_notes` FROM `$dbTables->reservations` 
							WHERE `reservation_guest_name`='%s' 
							AND `reservation_hidden`=0 ORDER BY reservation_timestamp DESC", $_SESSION['reservation_guest_name']);
			return getRowList($result);
		break;
		case 'res_history':
			$result = query("SELECT id, reservation_id, author, timestamp
							FROM `$dbTables->res_history` 
							WHERE `reservation_id`='%d' 
							ORDER BY id DESC", $_SESSION['resID']);
			return getRowList($result);
		break;
		case 'settings_inc':
			$result = query("SELECT id, property_id, language,
			timezone, timeformat, timeintervall,
			dateformat, dateformat_short, datepickerformat,
			app_name, max_menu, old_days,
			manual_lines, contactform_color_scheme, contactform_background, 
			guest_type_text_HG, guest_type_text_PASS, guest_type_text_WALK
							FROM `$dbTables->settings` 
							WHERE `property_id` = '%d'", $_SESSION['property']);
			return getRowListarray($result);
		break;
		case 'timecontrol':
			$result = query("SELECT reservation_time, SUM(reservation_pax) AS paxsum FROM $dbTables->reservations 
						WHERE `reservation_wait`= '0' AND `reservation_hidden`= '0' AND `reservation_outlet_id`='%d' 
						AND `reservation_date`='%s' GROUP BY reservation_time 
						ORDER BY paxsum DESC",$_SESSION['outletID'],$_SESSION['selectedDate']);
			return getRowListarray($result);
		break;
		case 'del_res_single':
			$result = query("UPDATE `$dbTables->reservations` 
							SET `reservation_hidden`='1', `reservation_booker_name`='%s',	`reservation_timestamp` = now()
							WHERE `reservation_id`='%d'",$author,$cellid);
			return $result;
		break;
		case 'alw_res_single':
			$result = query("UPDATE `$dbTables->reservations` SET `reservation_wait`='0',`reservation_timestamp` = now()
							WHERE `reservation_id`='%d'",$cellid);
			return $result;
		break;
		case 'del_res_multi':
			$result = query("UPDATE `$dbTables->reservations` 
							SET `reservation_hidden`='1', `reservation_booker_name`='%s', `reservation_timestamp` = now() 
							WHERE `repeat_id`='%d'",$author,$repeatid);
			return $result;
		break;
		case 'del_user':
			$result = query("DELETE FROM `$dbTables->plc_users` WHERE `userID`='%d' LIMIT 1",$cellid);
			return $result;
		break;
		case 'del_event':
			$result = query("DELETE FROM `$dbTables->events` WHERE `id`='%d' LIMIT 1",$cellid);
			return $result;
		break;
		case 'del_outlet':
			$result = query("DELETE FROM `$dbTables->outlets` WHERE `outlet_id`='%d' LIMIT 1",$cellid);
			return $result;
		break;
		case 'update_status':
			$result = query("UPDATE `$dbTables->reservations` 
							SET `reservation_status`='%s' 
							WHERE `reservation_id`='%d'",$value,$id);
			return $result;	
		break;
		case 'update_maitre_dayoff':
			$result = query("INSERT INTO `$dbTables->maitre`
				 				(maitre_id,maitre_outlet_id,maitre_date,outlet_child_dayoff,maitre_ip,maitre_author) 
								VALUES ('%d','%d','%s','%s','%s','%s') 
								ON DUPLICATE KEY UPDATE 
								`outlet_child_dayoff`='%s',
								`maitre_ip`='%s',
								`maitre_author`='%s'",$id, $_SESSION['outletID'], $_SESSION['selectedDate'], $value,
					 			$_SERVER['REMOTE_ADDR'], $_SESSION['u_fullname'], $value,
					 			$_SERVER['REMOTE_ADDR'],$_SESSION['u_fullname']);
			return $result;
		break;
		case 'inline_edit':
			$result = query("UPDATE `$dbTables->reservations` SET `%s`='%s' WHERE `reservation_id`='%d'",$field,$value,$id);
			return $result;	
		break;
		case 'res_repeat':
			$result = query("INSERT INTO `$dbTables->res_repeat` (
				id, 
				start_date,
				end_date,
				create_by
				) VALUES (
				'%d',
				'%s',
				'%s',
				'%s')
				ON DUPLICATE KEY UPDATE 
				id='%d',
				start_date='%s',
				end_date='%s',
				create_by='%s'", $repeatid, $_SESSION['reservation_date'], $_SESSION['recurring_date'], $_SESSION['author'], $repeatid, $_SESSION['reservation_date'], $_SESSION['recurring_date'], $_SESSION['author']);
			return mysql_insert_id();	
		break;
		case 'statistic_month':
			$result = query("SELECT SUM(reservation_pax) AS paxsum FROM `$dbTables->reservations` 
							WHERE `reservation_wait`= '0' AND `reservation_hidden`= '0' 
							AND `reservation_outlet_id`='%d' 
							AND MONTH(reservation_date) = '%s'
							AND YEAR(reservation_date) = '%s'",
							$_SESSION['outletID'], $_SESSION['statistic_month'], $_SESSION['selectedDate_year']);
			return getRowList($result);
		break;
		case 'statistic_month_last':
			$result = query("SELECT SUM(reservation_pax) AS paxsum FROM `$dbTables->reservations` 
							WHERE `reservation_wait`= 0 AND `reservation_hidden`= 0 
							AND `reservation_outlet_id` = '%d' 
							AND MONTH(reservation_date) = '%s'
							AND YEAR(reservation_date) = '%s'",
							$_SESSION['outletID'], $_SESSION['statistic_month'], $_SESSION['selectedDate_year']-1);
			return getRowList($result);
		break;
		case 'statistic_week_def':
			$result = query("SELECT SUM(reservation_pax) AS paxsum FROM `$dbTables->reservations` 
							WHERE `reservation_wait` = 0 AND `reservation_hidden` = 0 
							AND `reservation_outlet_id` ='%d' 
							AND `reservation_date` = '%s'",
							$_SESSION['outletID'], $_SESSION['statistic_week']);
			return getRowList($result);
		break;
		case 'statistic_week_def_noon':
			$result = query("SELECT SUM(reservation_pax) AS paxsum FROM `$dbTables->reservations` 
							WHERE `reservation_wait` = 0 AND `reservation_hidden` = 0 
							AND `reservation_outlet_id` ='%d' 
							AND `reservation_date` = '%s'
							AND `reservation_time` < '%s'",
							$_SESSION['outletID'], $_SESSION['statistic_week'],$value);
			return getRowList($result);
		break;
		case 'statistic_week_def_evening':
			$result = query("SELECT SUM(reservation_pax) AS paxsum FROM `$dbTables->reservations` 
							WHERE `reservation_wait` = 0 AND `reservation_hidden` = 0 
							AND `reservation_outlet_id` ='%d' 
							AND `reservation_date` = '%s'
							AND `reservation_time` >= '%s'",
							$_SESSION['outletID'], $_SESSION['statistic_week'],$value);
			return getRowList($result);
		break;
		case 'statistic_type':
			$result = query("SELECT reservation_hotelguest_yn, SUM(reservation_pax) AS paxsum FROM `$dbTables->reservations`
							WHERE `reservation_wait`= 0 AND `reservation_hidden`= 0 
							AND `reservation_outlet_id` = '%d' 
							AND MONTH(reservation_date) = '%s'
							AND YEAR(reservation_date) = '%s'
							GROUP BY `reservation_hotelguest_yn`",
							$_SESSION['outletID'], $_SESSION['statistic_month'], $_SESSION['selectedDate_year']);
			return getRowList($result);
		break;
		case 'statistic_weekday':
			$result = query("SELECT SUM(reservation_pax) AS paxsum FROM `$dbTables->reservations` 
							WHERE `reservation_wait`= '0' AND `reservation_hidden`= '0' 
							AND `reservation_outlet_id`='%d' 
							AND MONTH(reservation_date) = '%s'
							AND YEAR(reservation_date) = '%s'
							GROUP BY WEEKDAY(reservation_date)",
							$_SESSION['outletID'], $_SESSION['statistic_month'], $_SESSION['selectedDate_year']);
			return getRowList($result);
		break;
		case 'statistic_referer':
			$result = query("SELECT reservation_referer, COUNT(*) AS total FROM `$dbTables->reservations` 
							WHERE `reservation_wait`= '0' AND `reservation_hidden`= '0' 
							AND `reservation_outlet_id`='%d' 
							AND MONTH(reservation_date) = '%s'
							AND YEAR(reservation_date) = '%s'
							GROUP BY reservation_referer
							ORDER BY total DESC
							LIMIT 0,7",
							$_SESSION['outletID'], $_SESSION['statistic_month'], $_SESSION['selectedDate_year']);
			return getRowList($result);
		break;
		case 'statistic_res_days':
			$result = query("SELECT ROUND(AVG(DATEDIFF(reservation_date,reservation_timestamp)),1)
							FROM `$dbTables->reservations`  
							WHERE `reservation_wait`= '0' AND `reservation_hidden`= '0'  
							AND `reservation_outlet_id`='%d'
							AND MONTH(reservation_date) = '%s' 
							AND YEAR(reservation_date) = '%s' 
							",$_SESSION['outletID'],$_SESSION['statistic_month'],$_SESSION['selectedDate_year']
							);
			return getResult($result);
		break;
		case 'statistic_guest_year':
			$result = query("SELECT SUM(reservation_pax) FROM `$dbTables->reservations`  
							WHERE `reservation_wait`= '0' AND `reservation_hidden`= '0'  
							AND `reservation_outlet_id`='%d' 
							AND YEAR(reservation_date) = '%s' 
							",$_SESSION['outletID'],$_SESSION['selectedDate_year']
							);
			return getResult($result);
		break;
		case 'statistic_all_guest_year':
			$result = query("SELECT SUM(reservation_pax) FROM `$dbTables->reservations` 
							INNER JOIN `$dbTables->outlets` ON `outlet_id` = `reservation_outlet_id` 
							WHERE `reservation_wait`= '0' AND `reservation_hidden`= '0'   
							AND `property_id` = '%d' 
							AND YEAR(reservation_date) = '%s' 
							",$_SESSION['propertyID'],$_SESSION['selectedDate_year']
							);
			return getResult($result);
		break;
		case 'statistic_cxl_year':
			$result = query("SELECT COUNT(*) FROM `$dbTables->reservations`  
							WHERE `reservation_wait`= '0' AND `reservation_hidden`= '0'  
							AND `reservation_outlet_id`='%d' 
							AND YEAR(reservation_date) = '%s' 
							",$_SESSION['outletID'],$_SESSION['selectedDate_year']
							);
			return getResult($result);
		break;
		case 'statistic_booker_year':
			$result = query("SELECT COUNT(*) FROM (SELECT `reservation_id` FROM `$dbTables->reservations`  
							WHERE `reservation_hidden`= '1'  
							AND `reservation_outlet_id`='%d' 
							AND YEAR(reservation_date) = '%s'
							GROUP BY `reservation_booker_name` ) groups 
							",$_SESSION['outletID'],$_SESSION['selectedDate_year']
							);
			return getResult($result);
		break;
		case 'statistic_wait_year':
			$result = query("SELECT COUNT(*) FROM `$dbTables->reservations`  
							WHERE `reservation_wait`= '1'  
							AND `reservation_outlet_id`='%d' 
							AND YEAR(reservation_date) = '%s' 
							",$_SESSION['outletID'],$_SESSION['selectedDate_year']
							);
			return getResult($result);
		break;
		case 'statistic_online_year':
			$result = query("SELECT COUNT(*) FROM `$dbTables->reservations`  
							WHERE `reservation_outlet_id`='%d' 
							AND YEAR(reservation_date) = '%s'
							AND `reservation_referer` != ''
							",$_SESSION['outletID'],$_SESSION['selectedDate_year']
							);
			return getResult($result);
		break;
		case 'statistic_top5_guest_year':
			$result = query("SELECT reservation_guest_name, COUNT(*) as total FROM `$dbTables->reservations`  
							WHERE `reservation_wait`= '0' AND `reservation_hidden`= '0'  
							AND `reservation_outlet_id`='%d' 
							AND YEAR(reservation_date) = '%s'
							GROUP BY `reservation_guest_name`
							ORDER BY total DESC
							LIMIT 0,7
							",$_SESSION['outletID'],$_SESSION['selectedDate_year']
							);
			return getRowList($result);
		break;
		case 'notifications':
			$result = query("SELECT outlet_name,reservation_guest_name,reservation_time FROM `$dbTables->reservations` 
							INNER JOIN `$dbTables->outlets` ON `outlet_id` = `reservation_outlet_id` 
							WHERE `reservation_hidden` = '0' 
							AND `property_id` ='%d'
							AND `reservation_date` = '%s'
							AND DATE_SUB(NOW(),INTERVAL 1 minute) <= `reservation_timestamp`
							ORDER BY `reservation_timestamp` ASC
							LIMIT 3",
							$_SESSION['property'],date('Y-m-d') );
			return getRowList($result);
		break;
		case 'all_properties':
			$result = query("SELECT id, name, street,
							zip, city, country,
							contactperson, phone, fax,
							email, website, created,
							img_filename, logo_filename, 
							status, social_fb, social_tw 
							FROM `$dbTables->properties` ORDER BY name ASC");
			return getRowList($result);
		break;
		case 'select_properties':
			$result = query("SELECT id, name, street,
					zip, city, country,
					contactperson, phone, fax,
					email, website, created,
					img_filename, logo_filename, 
					status, social_fb, social_tw 
					FROM `$dbTables->properties`
					WHERE `country` LIKE '%s'
					AND `city` LIKE '%s'
					ORDER BY name ASC",$_SESSION['countryID'],$_SESSION['city']);
			return getRowList($result);
		break;
		case 'num_admin':
			$result = query("SELECT COUNT(*) FROM `$dbTables->plc_users` WHERE `role` ='1' OR `role` ='2'");
			return getResult($result);
		break;
		case 'property_info':
			$result = query("SELECT id, name, street,
					zip, city, country,
					contactperson, phone, fax,
					email, website, created,
					img_filename, logo_filename, 
					status, social_fb, social_tw
					FROM `$dbTables->properties` 
					WHERE `id` ='%d'
                    LIMIT 1",$_SESSION['propertyID']);
			return getRowListarray($result);
		break;
		case 'property_countries':
			$result = query("SELECT DISTINCT country FROM `$dbTables->properties` 
					ORDER BY country ASC");
			return getRowList($result);
		break;
		case 'property_countries_num':
			$result = query("SELECT DISTINCT country FROM `$dbTables->properties` 
					ORDER BY country ASC");
			return mysql_num_rows($result);
		break;
		case 'property_cities':
			$result = query("SELECT DISTINCT city,country FROM `$dbTables->properties`
					WHERE `country` ='%s'
					ORDER BY city ASC",$_SESSION['countryID']);
			return getRowList($result);
		break;
		case 'property_cities_num':
			$result = query("SELECT DISTINCT city,country FROM `$dbTables->properties`
					WHERE `country` ='%s'
					ORDER BY city ASC",$_SESSION['countryID']);
			return mysql_num_rows($result);
		break;
		case 'view_img':
			$result = query("SELECT img_filename FROM `$dbTables->properties` 
					WHERE `id` ='%d'
                                        LIMIT 1",$_SESSION['property']);
			return getResult($result);
		break;
		case 'featured_outlet':
			$result = query("SELECT outlet_id, outlet_name, outlet_description, outlet_description_en,	 
						cuisine_style, property_id, outlet_max_capacity, outlet_max_tables, outlet_open_time, 	 
						outlet_close_time, outlet_timestamp, outlet_closeday, saison_start, saison_end,  	  	 
						saison_year, webform, confirmation_email, passerby_max_pax, avg_duration,	 
						1_open_time,1_close_time, 2_open_time,2_close_time, 	 
						3_open_time,3_close_time, 4_open_time,4_close_time, 	 
						5_open_time, 5_close_time, 6_open_time, 6_close_time, 	 
						0_open_time, 0_close_time, 1_open_break, 1_close_break, 	 
						2_open_break, 2_close_break, 3_open_break, 3_close_break, 	 
						4_open_break, 4_close_break, 5_open_break, 5_close_break, 	 
						6_open_break, 6_close_break, 0_open_break, 0_close_break 
					FROM `$dbTables->outlets`
					WHERE outlet_id >= (SELECT FLOOR( MAX(outlet_id) * RAND()) FROM `$dbTables->outlets` ) 
					AND ( `saison_year` = 0 OR `saison_year` = '%d' )
					AND `webform` = '1'
					ORDER BY outlet_id LIMIT 1",$_SESSION['selectedDate_year']);
			return getRowListarray($result);
		break;
		case 'del_properties':
			$result = query("DELETE FROM `$dbTables->properties` WHERE `id`='%d' LIMIT 1",$cellid);
			return $result;
		break;
		case 'check_username':
			$result = query("SELECT username FROM `$dbTables->plc_users` WHERE `username`='%s'",$value);
			return $result;
		break;
		case 'check_unique_id':
			$result = query("SELECT COUNT(*) FROM `$dbTables->reservations` WHERE `reservation_bookingnumber`='%s'",$_SESSION['PWD']);
			return getResult($result);
		break;
		case 'store_unique_id':
			$result = query("UPDATE `$dbTables->reservations` SET reservation_bookingnumber = '' WHERE `reservation_date`<'%s'",$today);
			return $result;
		break;
		case 'sanitize_unique_id':
			$result = query("UPDATE `$dbTables->reservations` SET reservation_bookingnumber = '' WHERE `reservation_date`<'%s'",$before_yesterday);
			return $result;
		break;
		case 'cxl_list':
			$result = query("SELECT reservation_title, reservation_guest_name, reservation_timestamp, COUNT(*) AS count 
							FROM `$dbTables->reservations`
							LEFT JOIN `$dbTables->outlets` ON outlet_id = reservation_outlet_id
							WHERE `reservation_hidden` = '1' 
							AND `property_id` = '%d'
							GROUP BY `reservation_guest_name`
							ORDER BY count DESC
							LIMIT 20",
							$_SESSION['propertyID']
							);
			return getRowList($result);
		break;
		case 'active_plugins':
			$plug = query("SELECT filename, action FROM `$dbTables->plugins` WHERE `action` = '1'");
			return getRowList($plug);
		break;
		case 'update_plugins':
			$result = query("UPDATE `$dbTables->plugins` SET `action` = '%d' WHERE `filename`='%s'",$value,$field);
			return $result;
		break;
		case 'count_plugins':
			$result = query("SELECT COUNT(*) FROM `$dbTables->plugins` WHERE `filename`='%s'",$field);
			return getResult($result);
		break;
		case 'get_plugins':
			$result = query("SELECT action FROM `$dbTables->plugins` WHERE `filename`='%s'",$field);
			return getResult($result);
		break;
		case 'insert_plugins':
			$result = query("INSERT INTO `$dbTables->plugins` (`filename`,`action`) VALUES ('%s','%d')",$field,$value);
			return $result;
		break;
		case 'user_activate':
			$result = query("UPDATE `$dbTables->plc_users` 
							SET `active`='%d' 
							WHERE `userID`='%d' LIMIT 1",$value,$id);
			return $result;	
		break;
	}
	
}
?>