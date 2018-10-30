<?php
	/**
	 * Event API
	 * 
	 * @author Jeong, Munchang
	 * @since Create: 2012. 08. 21 / Update: 2012. 10. 05
	 */
	
	include_once("./include_setup.php");
	
	// Input variable
	$tel = JMC_GetInput("tel", METHOD);
	$session = JMC_GetInput("session", METHOD);
	$mode = JMC_GetInput("mode", METHOD);
	$view_id = JMC_GetInput("view_id", METHOD);
	
	// Check variable
	if($session && $tel) {
		try {
			// Select DB table for session ID
			mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
			$query = "SELECT session_id, cardNo FROM tbl_app_member WHERE phoneNumber = '$tel'";
			$dbraw = mssql_query($query);
			$result = mssql_fetch_array($dbraw);
				
			// Authorize session ID
			if($result['session_id'] == $session) {
	
				// Select mode
				switch($mode) {
					default: {
						$data['process'] = false;
						$data['message'] = "Mode error";
						break;
					}
					case "list": {
						mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
						$query2 = "select count(*) as row from tbl_app_event where display = 1 and [delete] = 0 and startDate <= '$today' and endDate >= '$today_date'";
						$dbraw2 = mssql_query($query2, $conn_hannam);
						$result2 = mssql_fetch_array($dbraw2);
						if($result2['row'] > 0) {
							$i = 0;
							// Select DB table for event data
							mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
							$query3 = "select event_id, convert(char(10),regDate,102) as regDate, subject, convert(char(10),startDate,102) as startDate, convert(char(10),endDate,102) as endDate, contents, image from tbl_app_event where display = 1 and [delete] = 0 and startDate <= '$today' and endDate >= '$today_date' order by event_id DESC";
							$dbraw3 = mssql_query($query3, $conn_hannam);
							while($result3 = mssql_fetch_array($dbraw3)) {
								unset($sub_data);
								$sub_data['event_id'] = @iconv('euc-kr', 'utf-8', $result3['event_id']);
								$sub_data['regDate'] = @iconv('euc-kr', 'utf-8', $result3['regDate']);
								$sub_data['subject'] = @iconv('euc-kr', 'utf-8', $result3['subject']) or "Detected illegal character in this data";
								$sub_data['startDate'] = @iconv('euc-kr', 'utf-8', $result3['startDate']);
								$sub_data['endDate'] = @iconv('euc-kr', 'utf-8', $result3['endDate']);
								$sub_data['contents'] = @iconv('euc-kr', 'utf-8', $result3['contents']) or "Detected illegal character in this data";
								$sub_data['image'] = @iconv('euc-kr', 'utf-8', $result3['image']);
								if(DEBUG) $sub_data['session'] = $result['session_id'];
								
								//$data['item'.$i] = $sub_data;
								//JMC_PrintLIstJson('event', $sub_data);
								//$data['process'] = true;
								//$data['message'] = "Select event data";
								$data[$i] = $sub_data;
								$i++;
							}
							JMC_PrintLIstJson('event', $data);
							exit();
						} else {
							$data['process'] = false;
							$data['message'] = "Not found event data";
						}
						break;
					}
					case "view": {
						// Check variable
						if($view_id) {
							// Select DB table for evnent data
							mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
							$query = "select event_id, convert(char(10),regDate,102) as regDate, subject, convert(char(10),startDate,102) as startDate, convert(char(10),endDate,102) as endDate, contents, image from tbl_app_event where event_id = '$view_id'";
							$dbraw = mssql_query($query, $conn_hannam);
							$result = mssql_fetch_array($dbraw);
							$data['event_id'] = @iconv('euc-kr', 'utf-8', $result['event_id']);
							$data['regDate'] = @iconv('euc-kr', 'utf-8', $result['regDate']);
							$data['subject'] = @iconv('euc-kr', 'utf-8', $result['subject']) or "Detected illegal character in this data";
							$data['startDate'] = @iconv('euc-kr', 'utf-8', $result['startDate']);
							$data['endDate'] = @iconv('euc-kr', 'utf-8', $result['endDate']);
							$data['contents'] = @iconv('euc-kr', 'utf-8', $result['contents']) or "Detected illegal character in this data";
							$data['image'] = @iconv('euc-kr', 'utf-8', $result['image']);
							
							$data['process'] = true;
							$data['message'] = "Select event data";
						} else {
							$data['process'] = false;
							$data['message'] = "Empty parameter";
						}
						break;
					}
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
	JMC_PrintJson('event', $data);
	 
	include_once("./include_db_disconnect.php");
?>
