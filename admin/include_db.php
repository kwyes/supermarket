<?
// Input burnaby information
define("BBY_DB_SERVER", "confidential");
define("BBY_DB_USERID", "confidential");
define("BBY_PASSWORD", "confidential");
define("BBY_DB_NAME", "dbgal");

// Input surrey information
define("SRY_DB_SERVER", "confidential");		// telus
//define("SRY_DB_SERVER", "confidential");		// shaw
define("SRY_DB_USERID", "confidential");
define("SRY_PASSWORD", "confidential");
define("SRY_DB_NAME", "dbgal");

// Input hannam information
define("HANNAM_DB_SERVER", "confidential");
define("HANNAM_DB_USERID", "confidential");
define("HANNAM_DB_PASSWORD", "confidential#2006");
define("HANNAM_DB_NAME", "hannam_db");

// Generate connection variable
$conn_bby = mssql_connect(BBY_DB_SERVER, BBY_DB_USERID, BBY_PASSWORD);
$conn_sry = mssql_connect(SRY_DB_SERVER, SRY_DB_USERID, SRY_PASSWORD);
$conn_hannam = mssql_connect(HANNAM_DB_SERVER, HANNAM_DB_USERID, HANNAM_DB_PASSWORD);
?>
