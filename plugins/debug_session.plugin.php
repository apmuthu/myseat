<?php

/*
Plugin Name: Show Session
Plugin URI: http://www.myseat.us
Description: Debug console. Shows the content of the $_SESSION variable
Version: 1.0
Author: Bernd Orttenburger
Author URI: http://www.openmyseat.com/
*/

/**
 * Initialisation routines
 *
 */

//set plugin id as file name of plugin
$plugin_id = basename(__FILE__);

//the plugin data
$data['name'] = "Show Session";
$data['author'] = "Bernd Orttenburger";
$data['url'] = "http://www.openmyseat.com/";

//register plugin data
register_plugin($plugin_id, $data);

/**
 * Plugin function
 *
 */

// the main function
function print_session() {
	echo "<div class='alert_hint'>
		<h2 class='center'>Debug console</h2><p class='center'>
		<small><pre style='margin-left:30px'><h4>Content of SESSION variable:</h4>";
        //Session Variable
        print_r($_SESSION);
	echo "</pre></small></p></div>";
}

/**
* function add_hook($tag, $function, $priority)
* 
* @param string $tag. The name of the hook.
* @param string $function. The function you wish to be called.
* @param int $priority optional. Used to specify the order in which the functions are executed.
* Range 0~20, 0 first call, 20 last call, standard setting is 10
*/

//add hook, where to execute a function
add_hook('debug','print_session');

//code to execute when loading plugin
return TRUE;
// for debugging
//echo "<p>Plugin ".$data['name']." LOADED!</p>";
?>