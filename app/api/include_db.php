<?php
	/**
	 * DB connector
	 *
	 * @author Jeong, Munchang
	 * @since Create: 2012. 06. 30 / Update: 2012. 08. 23
	 */

	defined('_APP_PHPMODULE') or die('Access Error.');

	// Input burnaby information
	define("BBY_DB_SERVER", "Confidential");
	define("BBY_DB_USERID", "pos");
	define("BBY_PASSWORD", "Confidential");
	define("BBY_DB_NAME", "dbgal");

	// Input surrey information
	define("SRY_DB_SERVER", "Confidential");		// telus
	define("SRY_DB_USERID", "pos");
	define("SRY_DB_PASSWORD", "Confidential");
	define("SRY_DB_NAME", "dbgal");

	// Input hannam information
	define("HANNAM_DB_SERVER", "Confidential");
	define("HANNAM_DB_USERID", "hannam");
	define("HANNAM_DB_PASSWORD", "Confidential");
	define("HANNAM_DB_NAME", "hannam_db");

	// Input Hannam Office information
	define("OFFICE_DB_SERVER", "Confidential");
	define("OFFICE_DB_USERID", "hannam");
	define("OFFICE_DB_PASSWORD", "Confidential");
	define("OFFICE_DB_NAME", "dbhannam");

	// Generate connection variable
	$conn_bby = mssql_connect(BBY_DB_SERVER, BBY_DB_USERID, BBY_PASSWORD) or die("Database connection error.");
	$conn_sry = mssql_connect(SRY_DB_SERVER, SRY_DB_USERID, SRY_DB_PASSWORD) or die("Database connection error.");
	$conn_office = mssql_connect(OFFICE_DB_SERVER, OFFICE_DB_USERID, OFFICE_DB_PASSWORD) or die("Database connection error.");
	$conn_hannam = mssql_connect(HANNAM_DB_SERVER, HANNAM_DB_USERID, HANNAM_DB_PASSWORD) or die("Database connection error.");
?>
