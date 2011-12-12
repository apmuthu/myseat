<?php 
// ** set configuration
include('../../config/config.general.php');
// ** database functions
include('../classes/database.class.php');
// ** connect to database
include('../classes/connect.db.php');
// ** all database queries
include('../classes/db_queries.db.php');
// ** php hooks class
	include "../classes/phphooks.config.php";
	include "../classes/phphooks.class.php";
	//create instance of plugin class
	$plugin_path = '../../plugins/';
	include "../../config/plugins.init.php";

// prevent dangerous input
secureSuperGlobals();

	if (isset($_GET['action'])){$action=$_GET['action'];}
	elseif (isset($_POST['action'])){$action=$_POST['action'];}
	
	if (isset($_GET['author'])){$author=$_GET['author'];}
	elseif (isset($_POST['author'])){$author=$_POST['author'];}
	
	if (isset($_GET['cellid'])){$cellid=$_GET['cellid'];}
	elseif (isset($_POST['cellid'])){$cellid=$_POST['cellid'];}
	
	$repeatid=0;
	if (isset($_GET['repeatid'])){$repeatid=$_GET['repeatid'];}
	elseif (isset($_POST['repeatid'])){$repeatid=$_POST['repeatid'];}
	
	if (isset($_GET['button'])){$button=$_GET['button'];}
	elseif (isset($_POST['button'])){$button=$_POST['button'];}
	

if ($action=="DEL"){
	if ($button == 'all'){
		// ****** DELETE MULTI ******
		$cmd_delete = querySQL('del_res_multi');
		// ** plugin hook
		if ($hook->hook_exist('after_del_res')) {
			$hook->execute_hook('after_del_res');
		}
		$reservation_id=0;

		return $cmd_delete;
	}else if ($button == 'single'){	
		// ****** DELETE SINGLE ******
		$cmd_delete = querySQL('del_res_single');
		// ** plugin hook
		if ($hook->hook_exist('after_del_res')) {
			$hook->execute_hook('after_del_res');
		}
		return $cmd_delete;
	}
}else if ($action=="ALW"){	
		// ****** ALLOW SINGLE ******
		$cmd_allow = querySQL('alw_res_single');
		// ** plugin hook
		if ($hook->hook_exist('after_alw_res')) {
			$hook->execute_hook('after_alw_res');
		}
		return $cmd_allow;
}
?>