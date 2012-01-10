<?php
/**
 * @author eric.wzy@gmail.com
 * @version 1.0
 * @package config
 * 
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */

class config {

	private $IsFile;
	private $FileNew;
	private $Error;
	private $LastError;
	private $ArrayVars;
	public $prefix;
	
	/**
	 *  initialization constructor.  Called when class is created.
	 */
	function config() {
		$this->IsFile = '';
		$this->Error = null;
		$this->LastError = null;
		$this->ArrayVars = array ();
		$this->prefix = null;
	} 
	

	/**
	 * @param string $file. check the file is exists, or create a new one.
	 */
	function setFile($file) {
		$this->IsFile = $file;
	} 
	
	/**
	 * @return $this->Error
	 */
	function getError() {
		return $this->Error;
	} 
	

	/**
	 * @return $this->LastError
	 */
	function getLastError() {
		return $this->LastError;
	} 
	

	/**
	 * 
	 * @param $var. delete item.
	 */
	function delete($var) {
		if (isset ( $this->prefix ))
			$var = $this->prefix . $var;
		unset($this->ArrayVars[$var]);
		return true;
	} 
	

	/**
	 * add item.
	 * 
	 * @param sting $var
	 * @param sting $value
	 */
	function insert($var, $value) {
		if (isset ( $this->prefix ))
			$var = $this->prefix . $var;
		$this->ArrayVars[$var] = $value;
		return true;
	} 
	

	/**
	 * update item.
	 * 
	 * @param sting $var
	 * @param sting $value
	 */
	function update($var, $value) {
		if (isset ( $this->prefix ))
			$var = $this->prefix . $var;
		if (array_key_exists($var,$this->ArrayVars))$this->ArrayVars[$var]= $value;
		return true;
	} 
	

	/**
	 * openfile
	 */
	function openFile() {
		#get contents of a file into a string

		if (file_exists ( $this->IsFile )) {
			$handle = fopen ( $this->IsFile, "r" );
			$c = fread ( $handle, filesize ( $this->IsFile ) );
			fclose ( $handle );
			$c = str_replace ( "\n", "", str_replace ( "?>", "", str_replace ( "<?php", "", str_replace ( "'", "", str_replace ( ";", "", $c ) ) ) ) );
			$c = explode ( "$", $c );
			$c1 = null;
			$s = count ( $c );
			$s1 = 0;
			while ( $s1 < $s ) {
				if ($c [$s1] != null) {
					$exp = explode ( "=", $c [$s1] );
					$exp = array_map ( trim, $exp );
					$c1 [$exp [0]] = $exp [1];
				} //end if
				$s1 ++;
			} //end while
			$this->ArrayVars = $c1;
		}
		return true;
	} 
	
	/**
	 * closefile
	 */
	function closeFile() {
		//prepare string
		$string = null;
		//add init php file	
		$string = $string . "<?php\n";
		//add comments
		
		foreach ($this->ArrayVars as $field=>$value) {
			if (is_string($value)) $value ='"'.$value.'"'; 
			$string = $string."$".$field." = ".$value.";\n";
		} 	
		$string = $string . "?>\n";
		if (! $handle = fopen ( $this->IsFile, 'w' )) {
			return false;
		} //end if
		// Write $somecontent to our opened file.
		if (fwrite ( $handle, $string ) === FALSE) {
			return false;
		} //end if
		fclose ( $handle );
		return true;
	} 
} //end class
?>