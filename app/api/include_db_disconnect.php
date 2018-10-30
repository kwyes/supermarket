<?
	/**
	 * DB disconnector
	 * 
	 * @author Jeong, Munchang
	 * @since Create: 2012. 06. 30 / Update: 2012. 08. 23
	 */

	// Close mssql connection
	mssql_close($conn_bby); 
	mssql_close($conn_sry);
	mssql_close($conn_hannam);
	mssql_close($conn_office);
?>