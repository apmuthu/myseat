<?php
function querySQL($statement){
	GLOBAL $storno,$wait,$author,$cellid,$repeatid,$id,$value,$field,$searchquery;
	$today 		  = date('Y-m-d');
	$yesterday	  = date('Y-m-d', time()-86400);
	$before_yesterday = date('Y-m-d', time()-172800);
	
	switch($statement){
		case 'availability':
			$result = query("SELECT reservation_time, SUM(reservation_pax) AS pax_total, COUNT(reservation_id) AS tbl_total
							FROM `reservations`  
							WHERE `reservation_hidden` = '0' 
							AND `reservation_wait` = '0' 
							AND `reservation_outlet_id` = '%d' 
							AND `reservation_date` = '%s'
							AND `reservation_id` != '%d'
							GROUP BY `reservation_time`
							ORDER BY `reservation_time` ASC",
							$_SESSION['outletID'],$_SESSION['selectedDate'],$_SESSION['resID']
							);
			return getRowList($result);
		break;
		case 'passerby_availability':
			$result = query("SELECT reservation_time, SUM(reservation_pax) AS passerby_total
							FROM `reservations`  
							WHERE `reservation_hidden` = '0' 
							AND `reservation_wait` = '0' 
							AND `reservation_outlet_id` = '%s' 
							AND `reservation_date` = '%s'
							AND `reservation_status` != 'DEP'
							AND `reservation_hotelguest_yn` = 'PASS'
							GROUP BY `reservation_time`
							ORDER BY `reservation_time` ASC",
							$_SESSION['outletID'],$_SESSION['selectedDate']
							);
			return getRowList($result);
		break;
		case 'maxcapacity':
			$out1 = array();
			$result = query("SELECT outlet_max_capacity, outlet_max_tables, passerby_max_pax FROM `outlets` 
							WHERE `outlet_id`='%d'",$_SESSION['outletID']);
			$out1 = getRowListarray($result);
			$result = query("SELECT outlet_child_tables, outlet_child_capacity, outlet_child_passer_max_pax FROM `maitre` 
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
			$result = query("SELECT sum(reservation_pax) FROM reservations 
							WHERE `reservation_date`='%s' 
							AND `reservation_outlet_id`='%d' 
							AND `reservation_hidden`=0 
							AND `reservation_wait`=0 
							AND `reservation_hotelguest_yn`='PASS' 
							",$_SESSION['selectedDate'],$_SESSION['outletID']);
			return getResult($result);
		break;
		case 'standard_outlet':
			$result = query("SELECT outlet_id FROM `outlets` 
							WHERE `property_id` ='%d' 
							AND `saison_year` = 0
							ORDER BY outlet_id LIMIT 1",$_SESSION['property']);
			return getResult($result);
		break;
		case 'web_standard_outlet':
			$result = query("SELECT outlet_id FROM `outlets` 
							WHERE `property_id` ='%d' 
							AND `webform` ='1' 
							AND ( `saison_year` = 0 OR `saison_year` = YEAR(NOW()) )
							ORDER BY outlet_name LIMIT 1",$_SESSION['property']);
			return getResult($result);
		break;
		case 'num_outlets':
			$result = query("SELECT COUNT(*) FROM `outlets` 
							WHERE ( `saison_year` = 0 OR `saison_year` = '%d' )
							AND `property_id` ='%d'
							AND `webform` = '1'",$_SESSION['selectedDate_year'],$_SESSION['property']);
			return getResult($result);
		break;
		case 'security_outlet':
			$result = query("SELECT COUNT(*) FROM `outlets` 
							WHERE ( `saison_year` = 0 OR `saison_year` = '%d' )
							AND `property_id` ='%d'
							AND `outlet_id` ='%d'
 ",$_SESSION['selectedDate_year'],$_SESSION['property'],$_SESSION['outletID']);
			return getResult($result);
		break;
		case 'db_outlet':
			$result = query("SELECT outlet_name FROM `outlets` WHERE `outlet_id` ='%d' AND `property_id` ='%d' LIMIT 1",$_SESSION['outletID'],$_SESSION['property']);
			return getResult($result);
		break;
		case 'db_prop_pic':
			$result = query("SELECT img_filename FROM `properties` WHERE `id` ='%d' LIMIT 1",$_SESSION['property']);
			return getResult($result);
		break;
		case 'db_property':
			$result = query("SELECT name FROM `properties` WHERE `id` ='%d' LIMIT 1",$_SESSION['property']);
			return getResult($result);
		break; 
		case 'db_outlets':
			$result = query("SELECT * FROM `outlets` 
							WHERE ( `saison_year` = 0 OR `saison_year` = '%d' )
							AND `property_id` ='%d' 
							ORDER BY outlet_name",$_SESSION['selectedDate_year'],$_SESSION['property']);
			return getRowList($result);
		break;
		case 'db_outlets_web':
			$result = query("SELECT * FROM `outlets` 
							WHERE ( `saison_year` = 0 OR `saison_year` = '%d' )
							AND `property_id` ='%d'
							AND `webform` = '1'
							ORDER BY outlet_name",$_SESSION['selectedDate_year'],$_SESSION['property']);
			return getRowList($result);
		break;
		case 'db_all_outlets':
			$result = query("SELECT * FROM `outlets` 
							WHERE `property_id` ='%d' 
							AND ( `saison_year` = 0 OR `saison_year` = '%d' )
							ORDER BY saison_year ASC, outlet_name ASC",$_SESSION['property'], $_SESSION['selectedDate_year']);
			return getRowList($result);
		break;
		case 'db_all_outlets_old':
			$result = query("SELECT * FROM `outlets` 
							WHERE `property_id` ='%d' 
							AND `saison_year` < '%d'
							AND `saison_year` != 0
							ORDER BY saison_year ASC, outlet_name ASC",$_SESSION['property'], $_SESSION['selectedDate_year']);
			return getRowList($result);
		break;
		case 'db_outlet_info':
			$result = query("SELECT * FROM `outlets` 
							WHERE `outlet_id` ='%d' 
							AND `property_id` ='%d' ",$_SESSION['outletID'],$_SESSION['property']);
			return getRowListarray($result);
		break;
		case 'outlet_info':
			$result = query("SELECT * FROM `outlets` 
							LEFT JOIN `properties` on outlets.property_id = properties.id 
							WHERE `outlet_id` ='%d'
							AND `property_id` ='%d' 
							LIMIT 1",$_SESSION['outletID'],$_SESSION['property']);
			return getRowList($result);
		break;
		case 'db_propery_events':
			$result = query("SELECT * FROM `events` 
							WHERE `property_id` ='%d' 
							ORDER BY `event_date` DESC",$_SESSION['property']);
			return getRowList($result);
		break;
		case 'db_outlet_events':
			$result = query("SELECT * FROM `events` 
							WHERE `outlet_id` ='%d' 
							ORDER BY `event_date` DESC",$_SESSION['outletID']);
			return getRowList($result);
		break;
		case 'event_data_single':
			$result = query("SELECT * FROM `events` WHERE `id` ='%d' LIMIT 1",$_SESSION['eventID']);
			return getRowListarray($result);
		break;
		case 'event_advertise':
			$result = query("SELECT events.*,outlets.outlet_name FROM `events`
						LEFT JOIN `outlets` ON events.outlet_id = outlets.outlet_id
						WHERE DATE_SUB(`event_date`,INTERVAL `advertise_start` DAY) <= '%s'
						AND `event_date` > '%s'
						AND outlets.property_id ='%d' 
						ORDER BY advertise_start ASC", $_SESSION['selectedDate'],$_SESSION['selectedDate'],$_SESSION['property']);
			return getRowList($result);
		break;
		case 'event_advertise_web':
			$result = query("SELECT events.*,outlets.outlet_name FROM `events`
						LEFT JOIN `outlets` ON events.outlet_id = outlets.outlet_id
						WHERE id >= (SELECT FLOOR( MAX(id) * RAND()) FROM `events` ) 
						AND DATE_SUB(`event_date`,INTERVAL `advertise_start` DAY) <= CURDATE()
						AND `event_date` >= CURDATE()
						AND `event_date` >= '%s'
						AND `webform` = '1'
						ORDER BY advertise_start,event_date ASC
						LIMIT 5",$_SESSION['selectedDate']);
			return getRowList($result);
		break;
		case 'event_data_day':
			$result = query("SELECT * FROM `events` 
						WHERE `event_date` ='%s' 
						AND `outlet_id` ='%d' 
						AND `property_id` ='%d'",
						$_SESSION['selectedDate'],$_SESSION['outletID'],$_SESSION['property']);
			return getRowList($result);
		break;
		case 'user_data':
			$result = query("SELECT * FROM `plc_users` WHERE `userID` ='%d' LIMIT 1",$_SESSION['userID']);
			return getRowListarray($result);
		break;
		case 'user_confirm_code':
			$result = query("UPDATE `plc_users` SET confirm_code = '%s', active = '0' 
								WHERE `userID` ='%d' LIMIT 1",$_SESSION['confHash'],$id);
			return $result;
		break;
		case 'check_confirm_code':
			$result = query("SELECT active,confirm_code FROM `plc_users` WHERE confirm_code='%s'",$_SESSION['confHash']);
			return getRowListarray($result);
		break;
		case 'user_confirm_activate':
			$result = query("UPDATE `plc_users` SET confirm_code = '', active = '1' 
								WHERE confirm_code='%s'",$_SESSION['confHash']);
			return $result;
		break;
		case 'maitre_info':
			$result = query("SELECT * FROM `maitre` 
							WHERE `maitre_outlet_id` ='%d' 
							AND `maitre_date`='%s' 
							LIMIT 1",$_SESSION['outletID'],$_SESSION['selectedDate']);
			return getRowList($result);
		break;
		case 'maitre_dayoffs':
			$result = query("SELECT `maitre_date` FROM `maitre` 
							WHERE `outlet_child_dayoff` = 'ON'
							AND YEAR(maitre_date) = '%s' 
							ORDER BY `maitre_date` ASC",date('Y'));
			return getRowList($result);
		break;
		case 'outlet_closedays':
			$result = query("SELECT `outlet_closeday` FROM `outlets` 
							WHERE `outlet_id` ='%d' 
							",$_SESSION['outletID']);
			return getResult($result);
		break;
		case 'db_all_users':
			$result = query("SELECT * FROM `plc_users` ORDER BY username");
			return getRowList($result);
		break;
		case 'db_prp_users':
			$result = query("SELECT * FROM `plc_users`
				WHERE `property_id` ='%d'
				ORDER BY username
				",$_SESSION['property']);
			return getRowList($result);
		break;
		case 'recent':
			$result = query("SELECT * FROM `reservations` WHERE reservation_outlet_id='%d' ORDER BY reservation_timestamp DESC LIMIT 0,4",$_SESSION['outletID']);
			return getRowList($result);
		break;
		case 'tautologous':
			$result = query("SELECT count(*) FROM `reservations` WHERE reservation_date='%s' AND reservation_hidden=0 AND reservation_wait=0 AND reservation_guest_name='%s' ",$_SESSION['selectedDate'],$_SESSION['reservation_guest_name']);
			return getResult($result);
		break;
		case 'capability':
			$result = query("SELECT `%d` FROM `capabilities` WHERE `capability`='%s'", $_SESSION['role'], $_SESSION['capability']);
			return getResult($result);
		break;
		case 'capabilities':
			$result = query("SELECT `capability`,`1`,`2`,`3`,`4`,`5`,`6` FROM `capabilities`");
			//return getRowListarray($result);
			return $result;
		break;
		case 'reservation_info':
			$result = query("SELECT reservations.*, outlets.outlet_name,res_repeat.* FROM `reservations`
					LEFT JOIN `outlets` ON outlet_id = reservation_outlet_id
					LEFT JOIN `res_repeat` ON res_repeat.id = reservations.repeat_id  
					WHERE reservations.reservation_id = '%d' LIMIT 1",$_SESSION['resID']);
			return getRowList($result);
		break;
		case 'reservations':
			$result = query("SELECT * FROM `reservations` 
							INNER JOIN `outlets` ON `outlet_id` = `reservation_outlet_id` 
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
			$result = query("SELECT * FROM `reservations` 
							INNER JOIN `outlets` ON `outlet_id` = `reservation_outlet_id` 
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
			$result = query("SELECT * FROM `reservations` INNER JOIN `outlets` ON `outlet_id` = `reservation_outlet_id` 
				WHERE `reservation_hidden` = '0' 
				AND (`reservation_guest_name` LIKE '%s' 
					OR `reservation_bookingnumber` LIKE '%s' 
					OR `reservation_guest_phone` LIKE '%s') 
				ORDER BY reservation_guest_name ASC",$searchquery,$searchquery,$searchquery);
			return getRowList($result);
		break;
		case 'reservation_visits':		
			$result = query("SELECT COUNT(*) FROM `reservations` 
							WHERE `reservation_guest_name`='%s' 
							AND `reservation_hidden`=0", $_SESSION['reservation_guest_name']);
			return getResult($result);
		break;
		case 'reservation_last_visit':
			$result = query("SELECT `reservation_date` FROM `reservations` 
							WHERE `reservation_guest_name` = '%s' 
							AND `reservation_hidden` = 0 AND `reservation_date` <= now() 
							ORDER BY `reservation_timestamp` DESC", $_SESSION['reservation_guest_name']);
			return getResult($result);
		break;
		case 'reservation_history':
			$result = query("SELECT DISTINCT `reservation_notes` FROM `reservations` 
							WHERE `reservation_guest_name`='%s' 
							AND `reservation_hidden`=0 ORDER BY reservation_timestamp DESC", $_SESSION['reservation_guest_name']);
			return getRowList($result);
		break;
		case 'res_history':
			$result = query("SELECT * FROM `res_history` 
							WHERE `reservation_id`='%d' 
							ORDER BY id DESC", $_SESSION['resID']);
			return getRowList($result);
		break;
		case 'settings_inc':
			$result = query("SELECT * FROM `settings` WHERE `property_id` = '%d'", $_SESSION['property']);
			return getRowListarray($result);
		break;
		case 'timecontrol':
			$result = query("SELECT reservation_time, SUM(reservation_pax) AS paxsum FROM reservations 
						WHERE `reservation_wait`= '0' AND `reservation_hidden`= '0' AND `reservation_outlet_id`='%d' 
						AND `reservation_date`='%s' GROUP BY reservation_time 
						ORDER BY paxsum DESC",$_SESSION['outletID'],$_SESSION['selectedDate']);
			return getRowListarray($result);
		break;
		case 'del_res_single':
			$result = query("UPDATE `reservations` 
							SET `reservation_hidden`='1', `reservation_booker_name`='%s',	`reservation_timestamp` = now()
							WHERE `reservation_id`='%d'",$author,$cellid);
			return $result;
		break;
		case 'alw_res_single':
			$result = query("UPDATE `reservations` SET `reservation_wait`='0',`reservation_timestamp` = now()
							WHERE `reservation_id`='%d'",$cellid);
			return $result;
		break;
		case 'del_res_multi':
			$result = query("UPDATE `reservations` 
							SET `reservation_hidden`='1', `reservation_booker_name`='%s', `reservation_timestamp` = now() 
							WHERE `repeat_id`='%d'",$author,$repeatid);
			return $result;
		break;
		case 'del_user':
			$result = query("DELETE FROM `plc_users` WHERE `userID`='%d' LIMIT 1",$cellid);
			return $result;
		break;
		case 'del_event':
			$result = query("DELETE FROM `events` WHERE `id`='%d' LIMIT 1",$cellid);
			return $result;
		break;
		case 'del_outlet':
			$result = query("DELETE FROM `outlets` WHERE `outlet_id`='%d' LIMIT 1",$cellid);
			return $result;
		break;
		case 'update_status':
			$result = query("UPDATE `reservations` 
							SET `reservation_status`='%s' 
							WHERE `reservation_id`='%d'",$value,$id);
			return $result;	
		break;
		case 'update_maitre_dayoff':
			$result = query("INSERT INTO `maitre`
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
			$result = query("UPDATE `reservations` SET `%s`='%s' WHERE `reservation_id`='%d'",$field,$value,$id);
			return $result;	
		break;
		case 'res_repeat':
			$result = query("INSERT INTO `res_repeat` (
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
			$result = query("SELECT SUM(reservation_pax) AS paxsum FROM `reservations` 
							WHERE `reservation_wait`= '0' AND `reservation_hidden`= '0' 
							AND `reservation_outlet_id`='%d' 
							AND MONTH(reservation_date) = '%s'
							AND YEAR(reservation_date) = '%s'",
							$_SESSION['outletID'], $_SESSION['statistic_month'], $_SESSION['selectedDate_year']);
			return getRowList($result);
		break;
		case 'statistic_month_last':
			$result = query("SELECT SUM(reservation_pax) AS paxsum FROM `reservations` 
							WHERE `reservation_wait`= 0 AND `reservation_hidden`= 0 
							AND `reservation_outlet_id` = '%d' 
							AND MONTH(reservation_date) = '%s'
							AND YEAR(reservation_date) = '%s'",
							$_SESSION['outletID'], $_SESSION['statistic_month'], $_SESSION['selectedDate_year']-1);
			return getRowList($result);
		break;
		case 'statistic_week_def':
			$result = query("SELECT SUM(reservation_pax) AS paxsum FROM `reservations` 
							WHERE `reservation_wait` = 0 AND `reservation_hidden` = 0 
							AND `reservation_outlet_id` ='%d' 
							AND `reservation_date` = '%s'",
							$_SESSION['outletID'], $_SESSION['statistic_week']);
			return getRowList($result);
		break;
		case 'statistic_type':
			$result = query("SELECT reservation_hotelguest_yn, SUM(reservation_pax) AS paxsum FROM `reservations`
							WHERE `reservation_wait`= 0 AND `reservation_hidden`= 0 
							AND `reservation_outlet_id` = '%d' 
							AND MONTH(reservation_date) = '%s'
							AND YEAR(reservation_date) = '%s'
							GROUP BY `reservation_hotelguest_yn`",
							$_SESSION['outletID'], $_SESSION['statistic_month'], $_SESSION['selectedDate_year']);
			return getRowList($result);
		break;
		case 'statistic_weekday':
			$result = query("SELECT SUM(reservation_pax) AS paxsum FROM `reservations` 
							WHERE `reservation_wait`= '0' AND `reservation_hidden`= '0' 
							AND `reservation_outlet_id`='%d' 
							AND MONTH(reservation_date) = '%s'
							AND YEAR(reservation_date) = '%s'
							GROUP BY WEEKDAY(reservation_date)",
							$_SESSION['outletID'], $_SESSION['statistic_month'], $_SESSION['selectedDate_year']);
			return getRowList($result);
		break;
		case 'statistic_referer':
			$result = query("SELECT reservation_referer, COUNT(*) AS total FROM `reservations` 
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
							FROM `reservations`  
							WHERE `reservation_wait`= '0' AND `reservation_hidden`= '0'  
							AND `reservation_outlet_id`='%d'
							AND MONTH(reservation_date) = '%s' 
							AND YEAR(reservation_date) = '%s' 
							",$_SESSION['outletID'],$_SESSION['statistic_month'],$_SESSION['selectedDate_year']
							);
			return getResult($result);
		break;
		case 'statistic_guest_year':
			$result = query("SELECT SUM(reservation_pax) FROM `reservations`  
							WHERE `reservation_wait`= '0' AND `reservation_hidden`= '0'  
							AND `reservation_outlet_id`='%d' 
							AND YEAR(reservation_date) = '%s' 
							",$_SESSION['outletID'],$_SESSION['selectedDate_year']
							);
			return getResult($result);
		break;
		case 'statistic_all_guest_year':
			$result = query("SELECT SUM(reservation_pax) FROM `reservations` 
							INNER JOIN `outlets` ON `outlet_id` = `reservation_outlet_id` 
							WHERE `reservation_wait`= '0' AND `reservation_hidden`= '0'   
							AND `property_id` = '%d' 
							AND YEAR(reservation_date) = '%s' 
							",$_SESSION['propertyID'],$_SESSION['selectedDate_year']
							);
			return getResult($result);
		break;
		case 'statistic_cxl_year':
			$result = query("SELECT COUNT(*) FROM `reservations`  
							WHERE `reservation_wait`= '0' AND `reservation_hidden`= '0'  
							AND `reservation_outlet_id`='%d' 
							AND YEAR(reservation_date) = '%s' 
							",$_SESSION['outletID'],$_SESSION['selectedDate_year']
							);
			return getResult($result);
		break;
		case 'statistic_booker_year':
			$result = query("SELECT COUNT(*) FROM (SELECT `reservation_id` FROM `reservations`  
							WHERE `reservation_hidden`= '1'  
							AND `reservation_outlet_id`='%d' 
							AND YEAR(reservation_date) = '%s'
							GROUP BY `reservation_booker_name` ) groups 
							",$_SESSION['outletID'],$_SESSION['selectedDate_year']
							);
			return getResult($result);
		break;
		case 'statistic_wait_year':
			$result = query("SELECT COUNT(*) FROM `reservations`  
							WHERE `reservation_wait`= '1'  
							AND `reservation_outlet_id`='%d' 
							AND YEAR(reservation_date) = '%s' 
							",$_SESSION['outletID'],$_SESSION['selectedDate_year']
							);
			return getResult($result);
		break;
		case 'statistic_online_year':
			$result = query("SELECT COUNT(*) FROM `reservations`  
							WHERE `reservation_outlet_id`='%d' 
							AND `reservation_outlet_id` = '%s'
							AND `reservation_booker_name` = '%s'
							",$_SESSION['outletID'],$_SESSION['selectedDate_year'],'Contact Form'
							);
			return getResult($result);
		break;
		case 'statistic_top5_guest_year':
			$result = query("SELECT reservation_guest_name, COUNT(*) as total FROM `reservations`  
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
		case 'all_properties':
			$result = query("SELECT * FROM `properties` ORDER BY name ASC");
			return getRowList($result);
		break;
		case 'select_properties':
			$result = query("SELECT * FROM `properties`
					WHERE `country` LIKE '%s'
					AND `city` LIKE '%s'
					ORDER BY name ASC",$_SESSION['countryID'],$_SESSION['city']);
			return getRowList($result);
		break;
		case 'property_info':
			$result = query("SELECT * FROM `properties` 
					WHERE `id` ='%d'
                    LIMIT 1",$_SESSION['propertyID']);
			return getRowListarray($result);
		break;
		case 'property_countries':
			$result = query("SELECT DISTINCT country FROM `properties` 
					ORDER BY country ASC");
			return getRowList($result);
		break;
		case 'property_countries_num':
			$result = query("SELECT DISTINCT country FROM `properties` 
					ORDER BY country ASC");
			return mysql_num_rows($result);
		break;
		case 'property_cities':
			$result = query("SELECT DISTINCT city,country FROM `properties`
					WHERE `country` ='%s'
					ORDER BY city ASC",$_SESSION['countryID']);
			return getRowList($result);
		break;
		case 'property_cities_num':
			$result = query("SELECT DISTINCT city,country FROM `properties`
					WHERE `country` ='%s'
					ORDER BY city ASC",$_SESSION['countryID']);
			return mysql_num_rows($result);
		break;
		case 'view_img':
			$result = query("SELECT img_filename FROM `properties` 
					WHERE `id` ='%d'
                                        LIMIT 1",$_SESSION['property']);
			return getResult($result);
		break;
		case 'featured_outlet':
			$result = query("SELECT * FROM `outlets`
					WHERE outlet_id >= (SELECT FLOOR( MAX(outlet_id) * RAND()) FROM `outlets` ) 
					AND ( `saison_year` = 0 OR `saison_year` = '%d' )
					AND `webform` = '1'
					ORDER BY outlet_id LIMIT 1",$_SESSION['selectedDate_year']);
			return getRowListarray($result);
		break;
		case 'del_properties':
			$result = query("DELETE FROM `properties` WHERE `id`='%d' LIMIT 1",$cellid);
			return $result;
		break;
		case 'check_username':
			$result = query("SELECT username FROM `plc_users` WHERE `username`='%s'",$value);
			return $result;
		break;
		case 'check_unique_id':
			$result = query("SELECT COUNT(*) FROM `reservations` WHERE `reservation_bookingnumber`='%s'",$_SESSION['PWD']);
			return getResult($result);
		break;
		case 'store_unique_id':
			$result = query("UPDATE `reservations` SET reservation_bookingnumber = '' WHERE `reservation_date`<'%s'",$today);
			return $result;
		break;
		case 'sanitize_unique_id':
			$result = query("UPDATE `reservations` SET reservation_bookingnumber = '' WHERE `reservation_date`<'%s'",$before_yesterday);
			return $result;
		break;
	}
	
}
?>