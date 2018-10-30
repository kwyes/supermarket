<?php
	

	defined('_APP_PHPMODULE') or die('Access Error.');

	// Setup Path
	define("ADDRESS","http://hannamsm.com");
	define("APP_PHPMODULE_PATH",".");

	// Define Method and Debug mode
	define("METHOD", "POST");
	define("DEBUG", false);

    // Set timezone
    putenv("TZ=America/Vancouver");
    $today = date("Y-m-d H:i:s");
    $today_date = date("Y-m-d");
?>
