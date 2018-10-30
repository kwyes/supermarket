<?php
	/**
	 * Giftcard API
	 * 
	 * @author Jeong, Munchang
	 * @since Create: 2012. 06. 28 / Update: 2012. 09. 26
	 */

	include_once("./include_setup.php");
	
	// Input variable
	$tel = JMC_GetInput("tel", METHOD);
	$session = JMC_GetInput("session", METHOD);
	$gift = JMC_GetInput("gift", METHOD);
	
	// Check variable
	if(/*$session && */$tel && $gift) {
		try {
			/*
			// Select DB table for session ID
			mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
			$query = "SELECT session_id, cardNo FROM tbl_app_member WHERE phoneNumber = '$tel'";
			$dbraw = mssql_query($query);
			$result = mssql_fetch_array($dbraw);
			if(DEBUG) $data['session'] = $result['session_id'];
			
			// Authorize session ID
			if($result['session_id'] == $session) {
			*/
				// Select DB table for search giftcard
				mssql_select_db(BBY_DB_NAME, $conn_bby);
				$query = "select top 1 Balance from tblGiftCertificate where SerialNo = '$gift' order by RegDate desc";
				$dbraw = mssql_query($query, $conn_bby);
				$result = mssql_fetch_array($dbraw);
				$result_num_row = mssql_num_rows($dbraw);
					
				$balancelen = strlen($result['Balance']);
					
				if($result['Balance'] <= 0) {
					$balance = 0;
				} else if($balancelen > 6) {
					$balance = substr($result['Balance'], 0, 4);
				} else if($result['Balance'] < 100) {
					$balance = substr($result['Balance'], 0, 5);
				} else if($result['Balance'] < 10) 	{
					$balance = substr($result['Balance'], 0, 4);
				} else {
					$balance = substr($result['Balance'], 0, 7);
				}
		
				$data['process'] = true;
				$data['message'] = "Check result";
				$data['balance'] = $balance;
				$data['giftcard'] = $gift;
			/*
			} else {
				$data['process'] = false;
				$data['message'] = "Session failed";
			}
			*/
		} catch(Exception $e) {
			$data['process'] = false;
			$data['message'] = $e;
		}
	} else {
		$data['process'] = false;
		$data['message'] = "Empty parameter";
	}
	JMC_PrintJson('giftcard', $data);
	 
	include_once("./include_db_disconnect.php");
?>