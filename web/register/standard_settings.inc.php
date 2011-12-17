<?php
// ### Set standard settings for a new property ###
$query = "INSERT INTO `settings` (`property_id`, `language`, `timezone`, `timeformat`, `timeintervall`, `dateformat`, `dateformat_short`, `datepickerformat`, `app_name`, `max_menu`, `old_days`, `manual_lines`) VALUES
	('".$new_id."', 'en_EN', 'Europe/Berlin', 24, 15, 'd.m.Y', 'd/m', 'd/m/y', 'mySeat XT', 8, 120, 5);";
  $result = query($query);


  $result = query("INSERT INTO `client_order` (`property_id`, `package_code`, `order_date`, `close_date`, `created_at`)
					VALUES ('%d', '%s', '%s', '0000-00-00', NOW())", $new_id, $_SESSION['pk_code'], date('Y-m-d'));
  
  $query = "INSERT INTO `outlets` (`outlet_name`, `outlet_description`, `cuisine_style`, `property_id`, `outlet_max_capacity`, `outlet_max_tables`, `outlet_open_time`, `outlet_close_time`, `outlet_timestamp`, `outlet_closeday`, `saison_start`, `saison_end`, `saison_year`, `webform`, `confirmation_email`, `passerby_max_pax`, `avg_duration`, `1_open_time`, `1_close_time`, `2_open_time`, `2_close_time`, `3_open_time`, `3_close_time`, `4_open_time`, `4_close_time`, `5_open_time`, `5_close_time`, `6_open_time`, `6_close_time`, `0_open_time`, `0_close_time`, `1_open_break`, `1_close_break`, `2_open_break`, `2_close_break`, `3_open_break`, `3_close_break`, `4_open_break`, `4_close_break`, `5_open_break`, `5_close_break`, `6_open_break`, `6_close_break`, `0_open_break`, `0_close_break`) VALUES
('Standard', 'The standard outlet when creating a new property. this is for demo purose only.', '47', '%d', 14, 5, '12:00:00', '23:00:00', NOW(), '1', '0101', '1231', 0, '1', 'info@myseat.us', 8, '1:30', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00')";
			
  $result = query($query, $new_id);

  //Clear errors after inserting
  $_SESSION['errors'] = array();
?>