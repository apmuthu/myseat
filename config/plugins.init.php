<?php
//create instance of class
$hook = new phphooks();

//fetch the active plugins name form db. store in array $plugins. 
$result_rows = querySQL('active_plugins');

if ($result_rows) {
	foreach ( $result_rows as $result_row ) {
		$plugins [] = $result_row->filename;
	}	
}

//unset means load all plugins in the plugin fold. set it, just load the plugins in this array.
//$hook->active_plugins = "";
$hook->active_plugins = $plugins;

//set multiple hooks to which plugin developers can assign functions
$hook->set_hooks ( array (
					'debug', 
					'after_booking', 
					'after_del_event', 
					'after_del_outlet', 
					'after_del_user',
					'after_del_res',
					'after_alw_res',
					) );

//load plugins from folder, if no argument is supplied, a 'plugins/' constant will be used
//trailing slash at the end is REQUIRED!
//this method will load all *.plugin.php files from given directory, INCLUDING subdirectories
$hook->load_plugins ($plugin_path);

//now, this is a workaround because plugins, when included, can't access $hook variable, so we
//as developers have to basically redefine functions which can be called from plugin files
function add_hook($tag, $function, $priority = 10) {
	global $hook;
	$hook->add_hook ( $tag, $function, $priority );
}

//same as above
function register_plugin($plugin_id, $data) {
	global $hook;
	$hook->register_plugin ( $plugin_id, $data );
}

?>