<?php
/**
 * @author eric.wzy@gmail.com
 * @version 1.1
 * @package phphooks
 * @category Plugins
 * 
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */

define ( 'PLUGINS_FOLDER', '../plugins/' );

class phphooks {
	/**
	 * plugins option data
	 * @var array
	 */
	var $plugins = array ();
	
	/**
	 * UNSET means load all plugins, which is stored in the plugin folder. ISSET just load the plugins in this array.
	 * @var array
	 */
	var $active_plugins = NULL;
	
	/**
	 * all plugins header information array.
	 * @var array
	 */
	var $plugins_header = array ();
	
	/**
	 * hooks data
	 * @var array
	 */
	var $hooks = array ();
	
	/**
	 * register hook name/tag, so plugin developers can attach functions to hooks
	 * @package phphooks
	 * @since 1.0
	 * 
	 * @param string $tag. The name of the hook.
	 */
	function set_hook($tag) {
		$this->hooks [$tag] = '';
	}
	
	/**
	 * register multiple hooks name/tag
	 * @package phphooks
	 * @since 1.0
	 * 
	 * @param array $tags. The name of the hooks.
	 */
	function set_hooks($tags) {
		foreach ( $tags as $tag ) {
			$this->set_hook ( $tag );
		}
	}
	
	/**
	 * write hook off
	 * @package phphooks
	 * @since 1.0
	 * 
	 * @param string $tag. The name of the hook.
	 */
	function unset_hook($tag) {
		unset ( $this->hooks [$tag] );
	}
	
	/**
	 * write multiple hooks off
	 * @package phphooks
	 * @since 1.0
	 * 
	 * @param array $tags. The name of the hooks.
	 */
	function unset_hooks($tags) {
		foreach ( $tags as $tag ) {
			$this->developer_unset_hook ( $tag );
		}
	}
	
	/**
	 * load plugins from specific folder, includes *.plugin.php files
	 * @package phphooks
	 * @since 1.0
	 * 
	 * @param string $from_folder optional. load plugins from folder, if no argument is supplied, a 'plugins/' constant will be used
	 */
	function load_plugins($from_folder = PLUGINS_FOLDER) {
		
		if ($handle = @opendir ( $from_folder )) {
			
			while ( $file = readdir ( $handle ) ) {
				if (is_file ( $from_folder . $file )) {
					if (($this->active_plugins == NULL || in_array ( $file, $this->active_plugins )) && strpos ( $from_folder . $file, '.plugin.php' )) {
						require_once $from_folder . $file;
						$this->plugins [$file] ['file'] = $file;
					}
				} else if ((is_dir ( $from_folder . $file )) && ($file != '.') && ($file != '..')) {
					$this->load_plugins ( $from_folder . $file . '/' );
				}
			}
			
			closedir ( $handle );
		}
	
	}
	
	/**
	 * return the all plugins ,which is stored in the plugin folder, header information.
	 * 
	 * @package phphooks
	 * @since 1.1
	 * @param string $from_folder optional. load plugins from folder, if no argument is supplied, a 'plugins/' constant will be used
	 * @return array. return the all plugins ,which is stored in the plugin folder, header information.
	 */
	function get_plugins_header($from_folder = PLUGINS_FOLDER) {
		
		if ($handle = @opendir ( $from_folder )) {
			
			while ( $file = readdir ( $handle ) ) {
				if (is_file ( $from_folder . $file )) {
					if (strpos ( $from_folder . $file, '.plugin.php' )) {
						$fp = fopen ( $from_folder . $file, 'r' );
						// Pull only the first 8kiB of the file in.
						$plugin_data = fread ( $fp, 8192 );
						fclose ( $fp );
						
						preg_match ( '|Plugin Name:(.*)$|mi', $plugin_data, $name );
						preg_match ( '|Plugin URI:(.*)$|mi', $plugin_data, $uri );
						preg_match ( '|Version:(.*)|i', $plugin_data, $version );
						preg_match ( '|Description:(.*)$|mi', $plugin_data, $description );
						preg_match ( '|Author:(.*)$|mi', $plugin_data, $author_name );
						preg_match ( '|Author URI:(.*)$|mi', $plugin_data, $author_uri );
						
						foreach ( array ('name', 'uri', 'version', 'description', 'author_name', 'author_uri' ) as $field ) {
							if (! empty ( ${$field} ))
								${$field} = trim ( ${$field} [1] );
							else
								${$field} = '';
						}
						$plugin_data = array ('filename' => $file, 'Name' => $name, 'Title' => $name, 'PluginURI' => $uri, 'Description' => $description, 'Author' => $author_name, 'AuthorURI' => $author_uri, 'Version' => $version );
						$this->plugins_header [] = $plugin_data;
					}
				} else if ((is_dir ( $from_folder . $file )) && ($file != '.') && ($file != '..')) {
					$this->get_plugins_header ( $from_folder . $file . '/' );
				}
			}
			
			closedir ( $handle );
		}
		return $this->plugins_header;
	}
	
	/**
	 * attach custom function to hook
	 * @package phphooks
	 * @since 1.0
	 * 
	 * @param string $tag. The name of the hook.
	 * @param string $function. The function you wish to be called.
	 * @param int $priority optional. Used to specify the order in which the functions associated with a particular action are executed.(range 0~20, 0 first call, 20 last call)
	 */
	function add_hook($tag, $function, $priority = 10) {
		if ( !isset($this->hooks[$tag]) ) {
			die ( "There is no such place ($tag) for hooks." );
		} else {
			$this->hooks [$tag] [$priority] [] = $function;
		}
	}
	
	/**
	 * check whether any function is attached to hook
	 * @package phphooks
	 * @since 1.0
	 * 
	 * @param string $tag The name of the hook.
	 */
	function hook_exist($tag) {
		return ( isset($this->hooks[$tag]) ) ? true : false;
	}

	/**
	 * print aut all hooks for debugging
	 * @package phphooks
	 * @since 1.0
	 * 
	 * @param string $tag The name of the hook.
	 */
	function show_hooks($tag) {
		print_r($this->hooks);
	}
	
	/**
	 * execute all functions which are attached to hook, you can provide argument (or arguments via array)
	 * @package phphooks
	 * @since 1.0
	 * 
	 * @param string $tag. The name of the hook.
	 * @param mix $args optional.The arguments the function accept (default none)
	 * @return optional.
	 */
	function execute_hook($tag, $args = '') {
		if (isset ( $this->hooks [$tag] )) {
			$these_hooks = $this->hooks [$tag];
			for($i = 0; $i <= 20; $i ++) {
				if (isset ( $these_hooks [$i] )) {
					foreach ( $these_hooks [$i] as $hook ) {
						//$args [] = $result;
						$result = call_user_func ( $hook, $args );
					}
				}
			}
			return $result;
		} else {
			die ( "There is no such place ($tag) for hooks." );
		}
	}
	
	/**
	 * filter $args and after modify, return it. (or arguments via array)
	 * @package phphooks
	 * @since 1.0
	 * 
	 * @param string $tag. The name of the hook.
	 * @param mix $args optional.The arguments the function accept to filter(default none)
	 * @return array. The $args filter result.
	 */
	function filter_hook($tag, $args = '') {
		$result = $args;
		if (isset ( $this->hooks [$tag] )) {
			$these_hooks = $this->hooks [$tag];
			for($i = 0; $i <= 20; $i ++) {
				if (isset ( $these_hooks [$i] )) {
					foreach ( $these_hooks [$i] as $hook ) {
						$args = $result;
						$result = call_user_func ( $hook, $args );
					}
				}
			}
			return $result;
		} else {
			die ( "There is no such place ($tag) for hooks." );
		}
	}
	
	/**
	 * register plugin data in $this->plugin
	 * @package phphooks
	 * @since 1.0
	 * 
	 * @param string $plugin_id. The name of the plugin.
	 * @param array $data optional.The data the plugin accessorial(default none)
	 */
	function register_plugin($plugin_id, $data = '') {
		foreach ( $data as $key => $value ) {
			$this->plugins [$plugin_id] [$key] = $value;
		}
	}
}
?>