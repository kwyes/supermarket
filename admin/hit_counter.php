<?
if($_SESSION['hit'] == "no") {
	mssql_select_db(HANNAM_DB_NAME, $conn_hannam);

	$today = date("Y-m-d");
	$checkTodayHit_query = "SELECT TOP 1 * FROM new_hit_counter WHERE hit_date = '$today'";
	$checkTodayHit_query_result = mssql_query($checkTodayHit_query, $conn_hannam);
	$checkTodayHit_row = mssql_fetch_array($checkTodayHit_query_result);

	if(!$checkTodayHit_row['hit_date']) {
		$addTodayHit_query = "INSERT INTO new_hit_counter (hit_date) VALUES ('$today')";
		mssql_query($addTodayHit_query, $conn_hannam);
	}

	if($_SESSION['browser'] == 'IPhone' || $_SESSION['browser'] == 'Android') {
		$where = "hit_mobile = (SELECT hit_mobile FROM new_hit_counter WHERE hit_date = '$today') + 1 ";
	} else {
		$where = "hit_pc = (SELECT hit_pc FROM new_hit_counter WHERE hit_date = '$today') + 1 ";
	}

	$updateTodayHit_query = "UPDATE new_hit_counter SET ".
							$where.
							"WHERE hit_date = '$today'";
	if(mssql_query($updateTodayHit_query, $conn_hannam))
		$_SESSION['hit'] = "yes";
}
?>