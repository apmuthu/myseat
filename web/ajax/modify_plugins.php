<?php
$field = $_GET['filename'];

switch ($_GET['button']) {
	case "deactivate" :
		$value = 0;
		$cmd_update = querySQL('update_plugins');
		break;
	case "activate" :
		$value = 1;
		$count = querySQL('count_plugins');
		if ($count < 1) {
			$cmd_update = querySQL('insert_plugins');
		} else {
			$cmd_update = querySQL('update_plugins');
		}
		break;
}
?>