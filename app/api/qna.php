<?php
	/**
	 * Q&A API
	 * 
	 * @author Jeong, Munchang
	 * @since Create: 2012. 08. 20 / Update: 2012. 10. 05
	 */

	include_once("./include_setup.php");
	
	// Input variable
	$tel = JMC_GetInput("tel", METHOD);
	$session = JMC_GetInput("session", METHOD);
	$mode = JMC_GetInput("mode", METHOD);
	$subject = JMC_GetInput("subject", METHOD);
	$contents = JMC_GetInput("contents", METHOD);
	$view_id = JMC_GetInput("view_id", METHOD);
	
	// Check variable
	if($session && $tel) {
		try {
			// Select DB table for session ID
			mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
			$query = "SELECT member_id, session_id, cardNo FROM tbl_app_member WHERE phoneNumber = '$tel'";
			$dbraw = mssql_query($query);
			$result = mssql_fetch_array($dbraw);
			if(DEBUG) $data['session'] = $result['session_id'];
						
			// Authorize session ID
			if($result['session_id'] == $session) {
				
				$member_id = $result['member_id'];
				
				// Select mode
				switch($mode) {
					default: {
						$data['process'] = false;
						$data['message'] = "Mode error";
						break;
					}
					case "list": {
						mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
						$query2 = "select count(*) as row from tbl_app_qna where member_id = ".$result['member_id'];
						$dbraw2 = mssql_query($query2, $conn_hannam);
						$result2 = mssql_fetch_array($dbraw2);
						if($result2['row'] > 0) {
							$i = 0;
							// Select DB table for event data
							mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
							$query3 = "select * from tbl_app_qna where member_id = ".$result['member_id']." order by regDate desc";
							$dbraw3 = mssql_query($query3, $conn_hannam);
							while($result3 = mssql_fetch_array($dbraw3)) {
								unset($sub_data);
								$sub_data['qna_id'] = @iconv('euc-kr', 'utf-8', $result3['qna_id']);
								$sub_data['regDate'] = @iconv('euc-kr', 'utf-8', $result3['regDate']);
								$sub_data['answerDate'] = @iconv('euc-kr', 'utf-8', $result3['answerDate']);
								$sub_data['subject'] = @iconv('euc-kr', 'utf-8', $result3['subject']) or "Detected illegal character in this data";
								$sub_data['contents'] = @iconv('euc-kr', 'utf-8', $result3['contents']) or "Detected illegal character in this data";
								$sub_data['answer'] = @iconv('euc-kr', 'utf-8', $result3['answer']) or "Detected illegal character in this data";
		
								//$data['item'.$i] = $sub_data;
								//JMC_PrintJson('qna_item', $sub_data);
								//$data['process'] = true;
								//$data['message'] = "Select qna data";
								$data[$i] = $sub_data;
								$i++;
							}
							JMC_PrintLIstJson('qna', $data);
							exit();
						} else {
							$data['process'] = false;
							$data['message'] = "Not found qna data";
						}
						break;
					}
					case "view": {
						// Check variable
						if($view_id) {
							// Select DB table for evnent data
							mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
							$query = "select * from tbl_app_qna where qna_id = '$view_id'";
							$dbraw = mssql_query($query, $conn_hannam);
							$result = mssql_fetch_array($dbraw);
							$data['qna_id'] = @iconv('euc-kr', 'utf-8', $result['qna_id']);
							$data['regDate'] = @iconv('euc-kr', 'utf-8', $result['regDate']);
							$data['answerDate'] = @iconv('euc-kr', 'utf-8', $result['answerDate']);
							$data['subject'] = @iconv('euc-kr', 'utf-8', $result['subject']) or "Detected illegal character in this data";
							$data['contents'] = @iconv('euc-kr', 'utf-8', $result['contents']) or "Detected illegal character in this data";
							$data['answer'] = @iconv('euc-kr', 'utf-8', $result['answer']) or "Detected illegal character in this data";
							$data['process'] = true;
							$data['message'] = "Select qna data";
						} else {
							$data['process'] = false;
							$data['message'] = "Empty parameter";
						}
						break;
					}
					case "write": {
						if($subject && $contents) {
							// Select DB table for evnent data
							mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
							$query = "INSERT INTO tbl_app_qna (regDate, subject, contents, member_id) VALUES ('$today', '".iconv('utf-8', 'euc-kr', $subject)."', '".iconv('utf-8', 'euc-kr', $contents)."', $member_id)";
							$dbraw = mssql_query($query, $conn_hannam);
							$data['process'] = true;
							$data['message'] = "Insert qna";
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
	JMC_PrintJson('qna', $data);
	    
    include_once("./include_db_disconnect.php");
?>
