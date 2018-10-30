<?php
	/**
	 * Flyer API
	 * 
	 * @author Jeong, Munchang
	 * @since Create: 2012. 06. 28 / Update: 2012. 09. 26
	 */

	include_once("./include_setup.php");

	// Input variable
	$tel = JMC_GetInput("tel", METHOD);
	$session = JMC_GetInput("session", METHOD);
	
	// Check variable
	if(/*$session && */$tel) {
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
				// Select DB table for flyer data
				mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
				$query = "SELECT TOP 1 seq, image_name, subject, convert(char(10), start_date+1, 112) as start_date, convert(char(10), start_date+7, 112) as end_date ".
					     "FROM new_regularUpdate ".
					     "WHERE type = 1 AND start_date <= GETDATE() ".
					     "ORDER BY start_date DESC";
				$dbraw = mssql_query($query, $conn_hannam);
				$result = mssql_fetch_array($dbraw);
				$result_num_row = mssql_num_rows($dbraw);

				$data['process'] = true;
				$data['message'] = "Check result";
				if($result['seq'] && iconv('euc-kr', 'utf-8', $result['image_name']))
				{
					$data['subject'] = @iconv('euc-kr', 'utf-8', $result['subject']);
					$data['startDate'] = @iconv('euc-kr', 'utf-8', $result['start_date']);
					$data['endDate'] = @iconv('euc-kr', 'utf-8', $result['end_date']);
					$data['imageUrl']=ADDRESS."/upload/weekly_flyer/Korean/".@iconv('euc-kr', 'utf-8', $result['image_name']);
				}
				/*
				// Select DB table for flyer data
				mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
				$query = "select top 1 ilId, imageFile1, ilName, convert(char(10),ilStartDate,112) as startDate, convert(char(10),ilEndDate,112) as endDate from imagelibrary where ilId > '0' and ilFacilities = '22' and ilActive = '1' and ilStartDate <= '$today' and ilEndDate >= '$today' order by ilRank desc, ilId desc";
				$dbraw = mssql_query($query, $conn_hannam);
				$result = mssql_fetch_array($dbraw);
				$result_num_row = mssql_num_rows($dbraw);
		
				$data['process'] = true;
				$data['message'] = "Check result";
				if($result['ilId'] && iconv('euc-kr', 'utf-8', $result['imageFile1']))
				{
					$data['subject'] = @iconv('euc-kr', 'utf-8', $result['ilName']);
					$data['startDate'] = @iconv('euc-kr', 'utf-8', $result['startDate']);
					$data['endDate'] = @iconv('euc-kr', 'utf-8', $result['endDate']);
					$data['imageUrl']=ADDRESS."/File_images_library/".@iconv('euc-kr', 'utf-8', $result['imageFile1']);
				}
				*/
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
	JMC_PrintJson('flyer', $data);
	
	include_once("./include_db_disconnect.php");
?>
