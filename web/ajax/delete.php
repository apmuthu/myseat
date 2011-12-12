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
	
	if (isset($_GET['cellid'])){$cellid=$_GET['cellid'];}
	elseif (isset($_POST['cellid'])){$cellid=$_POST['cellid'];}
	
	if (isset($_GET['type'])){$type=$_GET['type'];}
	elseif (isset($_POST['type'])){$type=$_POST['type'];}
	
if ($action=="DEL"){
	if ($type == 'users'){
		// ****** DELETE USER ******
		$cmd_delete = querySQL('del_user');
		// ** plugin hook
		if ($hook->hook_exist('after_del_user')) {
			$hook->execute_hook('after_del_user');
		}
		return $cmd_delete;
	}else if ($type == 'outlets'){
		// ****** DELETE USER ******
		$cmd_delete = querySQL('del_outlet');
		// ** plugin hook
		if ($hook->hook_exist('after_del_outlet')) {
			$hook->execute_hook('after_del_outlet');
		}
		return $cmd_delete;
	}else if ($type == 'events'){
		// ****** DELETE EVENT ******
		$cmd_delete = querySQL('del_event');
		// ** plugin hook
		if ($hook->hook_exist('after_del_event')) {
			$hook->execute_hook('after_del_event');
		}
		return $cmd_delete;
	}else if ($type == 'properties'){
		// ****** DELETE PROPERTY ******
		$cmd_delete = querySQL('del_properties');
		return $cmd_delete;
	}
}
?>