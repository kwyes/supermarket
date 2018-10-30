<?php
	/**
	 * Membership/Point API
	 * 
	 * @author Jeong, Munchang
	 * @since Create: 2012. 06. 28 / Update: 2012. 08. 31
	 */

	include_once("./include_setup.php");
	
	// Input variable
	$tel = JMC_GetInput("tel", METHOD);
	$session = JMC_GetInput("session", METHOD);
	
	// Check variable
	if($session && $tel) {
		try {
			// Select DB table for session ID
			mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
			$query = "SELECT session_id, cardNo FROM tbl_app_member WHERE phoneNumber = '$tel'";
			$dbraw = mssql_query($query);
			$result = mssql_fetch_array($dbraw);
			if(DEBUG) $data['session'] = $result['session_id'];
			if(DEBUG) $data['membershp_id'] = $membershp_id;
			if(DEBUG) $data['substr_membershp_id'] = substr($membershp_id, 0, 11);
			
			// Authorize session ID
			if($result['session_id'] == $session) {
				$membershp_id = $result['cardNo'];
				
				// Select DB table for member data
				mssql_select_db(BBY_DB_NAME, $conn_bby);
//				$query = "select a.CardNo, a.CollectedPoint, a.KrName, a.EnName from tblCustomer a LEFT OUTER JOIN tblCustomerHist b ON a.CardNo = b.CardNo ".
//						 " WHERE b.NewCardNo is null ".
//						 " AND a.Active = 1 ".
//						 " AND a.CardNo = '$membershp_id'";
				$query = "select CardNo, CollectedPoint, KrName, EnName from tblCustomer WHERE CardNo = '$membershp_id'";
				$dbraw = mssql_query($query, $conn_bby);
				$result = mssql_fetch_array($dbraw);
		
				$data['process'] = true;
				$data['message'] = "Check result";
				$data['barcode']=ADDRESS."/app/api/barcode/html/image.php?code=upca&o=1&dpi=72&t=30&r=2&rot=0&text=".substr($membershp_id, 0, 11)."&f1=Arial.ttf&f2=14&a1=&a2=&a3=";
				$data['customerName'] = (iconv('euc-kr', 'utf-8', $result['KrName'])) ? @iconv('euc-kr', 'utf-8', $result['KrName']): @iconv('euc-kr', 'utf-8', $result['EnName']);
				$data['cardNo']= @iconv('euc-kr', 'utf-8', $result['CardNo']) or "Detected illegal character in this data";
				$data['ablityPoint']=number_format(@iconv('euc-kr', 'utf-8', $result['CollectedPoint']));
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
	JMC_PrintJson('point', $data);
	    
    include_once("./include_db_disconnect.php");
?>
