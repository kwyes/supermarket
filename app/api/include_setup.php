<?php
	/**
	 * Set include path
	 * 
	 * @author Jeong, Munchang
	 * @since Create: 2012. 06. 30 / Update: 2012. 08. 23
	 */

	define('_APP_PHPMODULE', true);
	
	// include basic variable and constant
	include_once("./include_system.php");
	
	// Include DB information
	include_once("./include_db.php");
	
	// Include common function
	include_once("./include_function.php");
?>