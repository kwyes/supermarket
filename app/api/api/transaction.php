<?php
	include_once("./include_setup.php");
	
	// Input variable
	$tel = JMC_GetInput("tel", METHOD);
	$session = JMC_GetInput("session", METHOD);
	$periodType = JMC_GetInput("periodType", METHOD);
	
	// Check variable
	if($session && $tel) {
		try {
			// Select DB table for session ID
			mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
			$query = "SELECT session_id, cardNo FROM tbl_app_member WHERE phoneNumber = '$tel'";
			$dbraw = mssql_query($query, $conn_hannam);
			$result = mssql_fetch_array($dbraw);
			if(DEBUG) $data['session'] = $result['session_id'];
			
			// Authorize session ID
			if($result['session_id'] == $session) {
				$membershp_id = $result['cardNo'];
				
				// Select DB table for member data
				mssql_select_db(OFFICE_DB_NAME, $conn_office);

				if($periodType == "1") {
					$period = 30;
				} elseif($periodType == "2") {
					$period = 60;
				} elseif($periodType == "3") {
					$period = 182;
				}

				$query2 = "SELECT CardNo, CONVERT(char(10), TranDate, 120) AS TranDate, TranStore, TranAmount, PointThisSale ".
						  "FROM dt_tfMemberTran_com ".
						  "WHERE CardNo = '$membershp_id' AND (TranDate BETWEEN GETDATE() - $period AND GETDATE()) ".
						  "ORDER BY TranDate DESC";
				$dbraw2 = mssql_query($query2, $conn_office);
				$result2_row = mssql_num_rows($dbraw2);

				if($result2_row > 0) {
					$i = 0;
					while($result2 = mssql_fetch_array($dbraw2)) {
						unset($sub_data);

						$sub_data['tranDate'] = $result2['TranDate'];
						$sub_data['tranStore'] = (($result2['TranStore'] == "BBY") ? "Burnaby" : "Surrey");
						$sub_data['tranPrice'] = $result2['TranAmount'];
						$sub_data['tranPoint'] = $result2['PointThisSale'];

						$data[$i] = $sub_data;
						$i++;
					}
					JMC_PrintListJson('transaction', $data);
					exit();
				} else {
					$data['process'] = false;
					$data['message'] = "Not found product data";
				}
			} else {
				$data['process'] = false;
				$data['message'] = "Session failed";
			}
		} catch(Exception $e) {
			$data['process'] = false;
			$data['message'] = $e;
		}
	} else {
		$data['process'] = false;
		$data['message'] = "Empty parameter";
	}
	JMC_PrintJson('transaction', $data);
	    
    include_once("./include_db_disconnect.php");
?>
