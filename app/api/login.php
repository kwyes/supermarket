<?php
	/**
	 * Login APi
	 * 
	 * @author Jeong, Munchang
	 * @since Create: 2012. 08. 07 / Update: 2012. 10. 09
	 */

	include_once("./include_setup.php");
	
	// Input variable
	$mode = JMC_GetInput("mode", METHOD);
	$push_id = JMC_GetInput("push_id", METHOD);
	$tel = JMC_GetInput("tel", METHOD);
	$session = JMC_GetInput("session", METHOD);
	$name = JMC_GetInput("name", METHOD);
	$cardno = JMC_GetInput("cardno", METHOD);
	$type = JMC_GetInput("type", METHOD);
	$level = JMC_GetInput("level", METHOD);
	$birthday = JMC_GetInput("birthday", METHOD);
	$phonetype = JMC_GetInput("phonetype", METHOD);
	$pushtype = JMC_GetInput("pushtype", METHOD);
	$pushstatus = JMC_GetInput("pushstatus", METHOD);
	$membership_tel = JMC_GetInput("membership_tel", METHOD);
	
	// Select mode
	switch($mode) {
		default: {
			$data['process'] = false;
			$data['message'] = "Mode error";
			break;
		}
		case "init": {
			/*
			 * Input : push_id, tel
			 * Output : session, process, message
			 */
			if(DEBUG) $data['mode'] = $mode;
			if(DEBUG) $data['push_id'] = $push_id;
			if(DEBUG) $data['tel'] = $tel;
			if(DEBUG) $data['session'] = $session;
			if(DEBUG) $data['name'] = $name;
			if(DEBUG) $data['cardno'] = $cardno;
			if(DEBUG) $data['type'] = $type;
			if(DEBUG) $data['level'] = $level;
			if(DEBUG) $data['birthday'] = $birthday;
			
			// Check variable
			if($push_id && $tel && $phonetype) {
				try {
					// Select DB table for push ID
					$new_session = JMC_CreateSession();
					mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
					$query = "SELECT C2DM_id, session_id FROM tbl_app_member WHERE phoneNumber = '$tel'";
					$dbraw = mssql_query($query);
					$result = mssql_fetch_array($dbraw);
					
					if($result['C2DM_id'] == $push_id) {
						$data['process'] = true;
						$data['message'] = "Registered user";
					} elseif (isset($result['C2DM_id']) && $result['C2DM_id'] != $push_id) {
						mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
						$query = "UPDATE tbl_app_member SET C2DM_id = '$push_id', session_id = '$new_session', phoneType = '$phonetype' WHERE phoneNumber = '$tel'";
						$dbraw = mssql_query($query);
						$data['process'] = true;
						$data['message'] = "User's push_id updated";
					} else {
						mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
						$query = "INSERT INTO tbl_app_member (phoneNumber, C2DM_id, regDate, modDate, session_id, phoneType) VALUES ('$tel', '$push_id', '$today', '$today', '$new_session', $phonetype)";
						$dbraw = mssql_query($query);
						$data['process'] = true;
						$data['message'] = "User Inserted";
					}
					mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
					$query = "SELECT session_id FROM tbl_app_member WHERE phoneNumber = '$tel'";
					$dbraw = mssql_query($query);
					$result = mssql_fetch_array($dbraw);
					$data['session'] = $result['session_id'];
					 
				} catch(Exception $e) {
					$data['process'] = false;
					$data['message'] = $e;
				}
			} else {
				$data['process'] = false;
				$data['message'] = "Empty parameter";
			}
			break;
		}
		case "tel_search": {
			/*
			 * Input : session, tel
			 * Output : cardNo, cardCount, process, message
			 */
			if(DEBUG) $data['mode'] = $mode;
			if(DEBUG) $data['push_id'] = $push_id;
			if(DEBUG) $data['tel'] = $tel;
			if(DEBUG) $data['session'] = $session;
			if(DEBUG) $data['name'] = $name;
			if(DEBUG) $data['cardno'] = $cardno;
			if(DEBUG) $data['type'] = $type;
			if(DEBUG) $data['level'] = $level;
			if(DEBUG) $data['birthday'] = $birthday;
			
			// Check variable
			if($session && $tel) {
				try {
					// Select DB table for session ID
					mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
					$query = "SELECT session_id FROM tbl_app_member WHERE phoneNumber = '$tel'";
					$dbraw = mssql_query($query);
					$result = mssql_fetch_array($dbraw);
					if(DEBUG) $data['session'] = $result['session_id'];
					
					// Authorize session ID
					if($result['session_id'] == $session) {
						// Select DB table for search member's phone number
						mssql_select_db( BBY_DB_NAME, $conn_bby );
						$query = "SELECT a.cardNo FROM tblCustomer a LEFT OUTER JOIN tblCustomerHist b ON a.CardNo = b.CardNo ".
									"WHERE b.NewCardNo is null AND a.Active = 1 AND a.Phone = '$tel'";
						$dbraw = mssql_query($query, $conn_bby);
						$result = mssql_fetch_array($dbraw);
						$result_num_row = mssql_num_rows($dbraw);
						
						// Count member's cards
						if($result_num_row < 1) {
							$data['process'] = false;
							$data['message'] = "Not found phone number";
						} elseif($result_num_row == 1) {
							$data['process'] = true;
							$data['message'] = "Found phone number";
							$data['cardNo'] = $result['cardNo'];
						} elseif($result_num_row > 1) {
							$data['process'] = false;
							$data['message'] = "duplicated phone number";
							$data['cardCount'] = $result_num_row;
							
							// 중복되는 카드 목록 출력
							if(DEBUG) {
								$index_card = 1;
								$data['cardNo_'+$index_card] = $result['cardNo'];
								while($result = mssql_fetch_array($dbraw)) {
									$index_card++;
									$data['cardNo_'+$index_card] = $result['cardNo'];
								}
							}
						}
					}
					else {
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
			break;
		}
		case "membership_tel_search": {
			/*
			 * Input : session, tel
			* Output : cardNo, cardCount, process, message
			*/
			if(DEBUG) $data['mode'] = $mode;
			if(DEBUG) $data['push_id'] = $push_id;
			if(DEBUG) $data['tel'] = $tel;
			if(DEBUG) $data['session'] = $session;
			if(DEBUG) $data['name'] = $name;
			if(DEBUG) $data['cardno'] = $cardno;
			if(DEBUG) $data['type'] = $type;
			if(DEBUG) $data['level'] = $level;
			if(DEBUG) $data['birthday'] = $birthday;
			if(DEBUG) $data['membership_tel'] = $membership_tel;
				
			// Check variable
			if($session && $tel) {
				try {
					// Select DB table for session ID
					mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
					$query = "SELECT session_id FROM tbl_app_member WHERE phoneNumber = '$tel'";
					$dbraw = mssql_query($query);
					$result = mssql_fetch_array($dbraw);
					if(DEBUG) $data['session'] = $result['session_id'];
						
					// Authorize session ID
					if($result['session_id'] == $session) {
						// Select DB table for search member's phone number
						mssql_select_db( BBY_DB_NAME, $conn_bby );
						//$query = "SELECT cardNo FROM tblCustomer WHERE Phone = '$membership_tel'";
						$query = "SELECT a.cardNo FROM tblCustomer a LEFT OUTER JOIN tblCustomerHist b ON a.CardNo = b.CardNo ".
									"WHERE b.NewCardNo is null AND a.Active = 1 AND a.Phone = '$tel'";
						$dbraw = mssql_query($query, $conn_bby);
						$result = mssql_fetch_array($dbraw);
						$result_num_row = mssql_num_rows($dbraw);
		
						// Count member's cards
						if($result_num_row < 1) {
							$data['process'] = false;
							$data['message'] = "Not found phone number";
						} elseif($result_num_row == 1) {
							$data['process'] = true;
							$data['message'] = "Found phone number";
							$data['cardNo'] = $result['cardNo'];
						} elseif($result_num_row > 1) {
							$data['process'] = false;
							$data['message'] = "duplicated phone number";
							$data['cardCount'] = $result_num_row;
								
							// 중복되는 카드 목록 출력
							if(DEBUG) {
								$index_card = 1;
								$data['cardNo_'+$index_card] = $result['cardNo'];
								while($result = mssql_fetch_array($dbraw)) {
									$index_card++;
									$data['cardNo_'+$index_card] = $result['cardNo'];
								}
							}
						}
					}
					else {
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
			break;
		}
		case "name_search": {
			/*
			 * Input : session, tel, name
			 * Output : cardNo, cardCount, process, message
			 */
			if(DEBUG) $data['mode'] = $mode;
			if(DEBUG) $data['push_id'] = $push_id;
			if(DEBUG) $data['tel'] = $tel;
			if(DEBUG) $data['session'] = $session;
			if(DEBUG) $data['name'] = $name;
			if(DEBUG) $data['cardno'] = $cardno;
			if(DEBUG) $data['type'] = $type;
			if(DEBUG) $data['level'] = $level;
			if(DEBUG) $data['birthday'] = $birthday;
			
			// Check variable
			if($session && $name && $tel) {
				try {
					// Select DB table for session ID
					mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
					$query = "SELECT session_id FROM tbl_app_member WHERE phoneNumber = '$tel'";
					$dbraw = mssql_query($query);
					$result = mssql_fetch_array($dbraw);
					if(DEBUG) $data['session'] = $result['session_id'];
					
					// Authorize session ID
					if($result['session_id'] == $session) {
						// Select DB table for search member's Korean name
						mssql_select_db(BBY_DB_NAME, $conn_bby);
						$query = "SELECT cardNo FROM tblCustomer WHERE KrName = '$name' AND Phone = '$tel'";
						$query = iconv('utf-8', 'euc-kr',$query);
						$dbraw = mssql_query($query, $conn_bby);
						$result = mssql_fetch_array($dbraw);
						$result_num_row = mssql_num_rows($dbraw);
						
						// Count member's cards
						if($result_num_row < 1) {
							$data['process'] = false;
							$data['message'] = "Not found phone number";
						} elseif($result_num_row == 1) {
							$data['process'] = true;
							$data['message'] = "Found phone number";
							$data['cardNo'] = $result['cardNo'];
						} elseif($result_num_row > 1) {
							$data['process'] = false;
							$data['message'] = "duplicated phone number";
							$data['cardCount'] = $result_num_row;
							
							// 중복되는 카드 목록 출력
							if(DEBUG) {
								$index_card = 1;
								$data['cardNo_'+$index_card] = $result['cardNo'];
								while($result = mssql_fetch_array($dbraw)) {
									$index_card++;
									$data['cardNo_'+$index_card] = $result['cardNo'];
								}
							}
						}
					}
					else {
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
			break;
		}
		case "birth_search": {
			/*
			 * Input : session, tel, birthday
			 * Output : cardNo, cardCount, process, message
			 */
			if(DEBUG) $data['mode'] = $mode;
			if(DEBUG) $data['push_id'] = $push_id;
			if(DEBUG) $data['tel'] = $tel;
			if(DEBUG) $data['session'] = $session;
			if(DEBUG) $data['name'] = $name;
			if(DEBUG) $data['cardno'] = $cardno;
			if(DEBUG) $data['type'] = $type;
			if(DEBUG) $data['level'] = $level;
			if(DEBUG) $data['birthday'] = $birthday;
		
			// Check variable
			if($session && $birthday && $tel) {
				try {
					// Select DB table for session ID
					mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
					$query = "SELECT session_id FROM tbl_app_member WHERE phoneNumber = '$tel'";
					$dbraw = mssql_query($query);
					$result = mssql_fetch_array($dbraw);
					if(DEBUG) $data['session'] = $result['session_id'];
					
					// Authorize session ID
					if($result['session_id'] == $session) {
						// Select DB table for search member's birthday
						mssql_select_db(BBY_DB_NAME, $conn_bby);
						$query = "SELECT cardNo FROM tblCustomer WHERE birthDate = '$birthday' AND Phone = '$tel'";
						$dbraw = mssql_query($query, $conn_bby);
						$result = mssql_fetch_array($dbraw);
						$result_num_row = mssql_num_rows($dbraw);
						
						// Count member's cards
						if($result_num_row < 1) {
							$data['process'] = false;
							$data['message'] = "Not found phone number";
						} elseif($result_num_row == 1) {
							$data['process'] = true;
							$data['message'] = "Found phone number";
							$data['cardNo'] = $result['cardNo'];
						} elseif($result_num_row > 1) {
							$data['process'] = false;
							$data['message'] = "duplicated phone number";
							$data['cardCount'] = $result_num_row;
							
							// 중복되는 카드 목록 출력
							if(DEBUG) {
								$index_card = 1;
								$data['cardNo_'+$index_card] = $result['cardNo'];
								while($result = mssql_fetch_array($dbraw)) {
									$index_card++;
									$data['cardNo_'+$index_card] = $result['cardNo'];
								}
							}
						}
					}
					else {
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
			break;
		}
		case "card_search": {
			/*
			 * Input : session, tel, cardno
			 * Output : cardNo, process, message
			 */
			if(DEBUG) $data['mode'] = $mode;
			if(DEBUG) $data['push_id'] = $push_id;
			if(DEBUG) $data['tel'] = $tel;
			if(DEBUG) $data['session'] = $session;
			if(DEBUG) $data['name'] = $name;
			if(DEBUG) $data['cardno'] = $cardno;
			if(DEBUG) $data['type'] = $type;
			if(DEBUG) $data['level'] = $level;
			if(DEBUG) $data['birthday'] = $birthday;
			
			// Check variable
			if($session && $cardno && $tel) {
				try {
					// Select DB table for session ID
					mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
					$query = "SELECT session_id FROM tbl_app_member WHERE phoneNumber = '$tel'";
					$dbraw = mssql_query($query);
					$result = mssql_fetch_array($dbraw);
					if(DEBUG) $data['session'] = $result['session_id'];
					
					// Authorize session ID
					if($result['session_id'] == $session) {
						mssql_select_db(BBY_DB_NAME, $conn_bby);
						$query = "SELECT cardNo FROM tblCustomer WHERE cardNo = '$cardno'";
						$dbraw = mssql_query($query, $conn_bby);
						$result = mssql_fetch_array($dbraw);
						$result_num_row = mssql_num_rows($dbraw);
						if($result_num_row < 1) {
							$data['process'] = false;
							$data['message'] = "Not found card number";
						} elseif($result_num_row == 1) {
							$data['process'] = true;
							$data['message'] = "Found card number";
							$data['cardNo'] = $result['cardNo'];
						}
					}
					else {
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
			break;
		}
		case "update": {
			/*
			 * Input : session, cardno, tel, level, type
			 * Output : process, message
			 */
			if(DEBUG) $data['mode'] = $mode;
			if(DEBUG) $data['push_id'] = $push_id;
			if(DEBUG) $data['tel'] = $tel;
			if(DEBUG) $data['session'] = $session;
			if(DEBUG) $data['name'] = $name;
			if(DEBUG) $data['cardno'] = $cardno;
			if(DEBUG) $data['type'] = $type;
			if(DEBUG) $data['level'] = $level;
			if(DEBUG) $data['birthday'] = $birthday;
			
			// Check variable
			if($session && $tel && $type) {
				try {
					// Select DB table for session ID
					mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
					$query = "SELECT session_id, cardNo FROM tbl_app_member WHERE phoneNumber = '$tel'";
					$dbraw = mssql_query($query);
					$result = mssql_fetch_array($dbraw);
					if(DEBUG) $data['session'] = $result['session_id'];
					
					// Authorize session ID
					if($result['session_id'] == $session) {
						switch ($type) {
							// Update DB table 'tbl_app_member' for member's card number
							case "card": {
								try {
									if($cardno == "" || $cardno == " ") {
										$data['process'] = false;
										$data['message'] = "Empty parameter";
									} else {
										mssql_select_db( HANNAM_DB_NAME, $conn_hannam );
										$query = "UPDATE tbl_app_member SET cardNo = '$cardno' WHERE phoneNumber = '$tel'";
										$dbraw = mssql_query($query);
										$data['process'] = true;
										$data['message'] = "Card number updated";
									}
								} catch(Exception $e) {
									$data['process'] = false;
									$data['message'] = $e;
								}
								continue;
							}
							// Update DB table 'tbl_app_member' for member's level
							case "level": {
								if($level == "" || $level == " ") {
									$data['process'] = false;
									$data['message'] = "Empty parameter";
								} else {
									try {
										mssql_select_db( HANNAM_DB_NAME, $conn_hannam );
										$query = "UPDATE tbl_app_member SET level = '$level' WHERE phoneNumber = '$tel'";
										$dbraw = mssql_query($query);
										$data['process'] = true;
										$data['message'] = "Level updated";
									} catch(Exception $e) {
										$data['process'] = false;
										$data['message'] = $e;
									}
								}
								continue;
							}
							// Update DB table 'tblCustomer' for member's phone number
							case "change_phone": {
								try {
									mssql_select_db(BBY_DB_NAME, $conn_bby);
									$query = "UPDATE tblCustomer SET Phone = '$tel' WHERE cardNo = '$cardno'";
									//$dbraw = mssql_query($query, $conn_bby); //고객 DB업데이트
									$data['process'] = true;
									$data['message'] = "Phone number updated";
								} catch(Exception $e) {
									$data['process'] = false;
									$data['message'] = $e;
								}
								continue;
							}
						}
					}
					else {
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
			break;
		}
		case "login": {
			/* 
			 * Input : tel, push_id
			 * Output : session, process, message
			 */
			if(DEBUG) $data['mode'] = $mode;
			if(DEBUG) $data['push_id'] = $push_id;
			if(DEBUG) $data['tel'] = $tel;
			if(DEBUG) $data['session'] = $session;
			if(DEBUG) $data['name'] = $name;
			if(DEBUG) $data['cardno'] = $cardno;
			if(DEBUG) $data['type'] = $type;
			if(DEBUG) $data['level'] = $level;
			if(DEBUG) $data['birthday'] = $birthday;
			
			// Check variable
			if($tel && $push_id) {
				try {
					// update 2015-11-27
					$new_session = JMC_CreateSession();
					mssql_select_db( HANNAM_DB_NAME, $conn_hannam );
					$query = "SELECT C2DM_id FROM tbl_app_member WHERE phoneNumber = '$tel' AND phoneType = '$phonetype'";
					$dbraw = mssql_query($query);
					$result = mssql_fetch_array($dbraw);
					if($result['C2DM_id'] == $push_id) {
						$query = "UPDATE tbl_app_member SET session_id = '$new_session' WHERE phoneNumber = '$tel'";
					} else {
						$query = "UPDATE tbl_app_member SET C2DM_id = '$push_id', session_id = '$new_session' WHERE phoneNumber = '$tel'";
					}
					$dbraw = mssql_query($query);

					sleep(1); //DB 업데이트 되는 시간
					$query = "SELECT C2DM_id, session_id, level FROM tbl_app_member WHERE phoneNumber = '$tel'";
					$dbraw = mssql_query($query);
					$result = mssql_fetch_array($dbraw);
					if($new_session == $result['session_id']) {
						$data['session'] = $result['session_id'];
						$data['level'] = $result['level'];
						$data['process'] = true;
						$data['message'] = "Created new session ID";
					} else {
						$data['process'] = false;
						$data['message'] = "Not created new session ID";
					}

					/* c2dm
					// Select DB table for authorize session ID and telephone number
					$new_session = JMC_CreateSession();
					mssql_select_db( HANNAM_DB_NAME, $conn_hannam );
					$query = "SELECT C2DM_id FROM tbl_app_member WHERE phoneNumber = '$tel' and C2DM_id = '$push_id'";
					$dbraw = mssql_query($query);
					$result = mssql_fetch_array($dbraw);
					if(isset($result['C2DM_id'])) {
						$query = "UPDATE tbl_app_member SET session_id = '$new_session' WHERE phoneNumber = '$tel'";
						$dbraw = mssql_query($query);
						sleep(1); //DB 업데이트 되는 시간
						$query = "SELECT session_id, level FROM tbl_app_member WHERE phoneNumber = '$tel'";
						$dbraw = mssql_query($query);
						$result = mssql_fetch_array($dbraw);
						if($new_session == $result['session_id']) {
							$data['session'] = $result['session_id'];
							$data['level'] = $result['level'];
							$data['process'] = true;
							$data['message'] = "Created new session ID";
						} else {
							$data['process'] = false;
							$data['message'] = "Not created new session ID";
						}
					} else {
						$data['process'] = false;
						$data['message'] = "Not found member data";
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
			break;
		}
		case "setting": {
			/*
			 * Input : tel, push_id
			* Output : process, message
			*/
			if(DEBUG) $data['mode'] = $mode;
			if(DEBUG) $data['push_id'] = $push_id;
			if(DEBUG) $data['tel'] = $tel;
			if(DEBUG) $data['session'] = $session;
			if(DEBUG) $data['name'] = $name;
			if(DEBUG) $data['cardno'] = $cardno;
			if(DEBUG) $data['type'] = $type;
			if(DEBUG) $data['level'] = $level;
			if(DEBUG) $data['birthday'] = $birthday;
			if(DEBUG) $data['pushtype'] = $pushtype;
			if(DEBUG) $data['pushstatus'] = $pushstatus;
				
			// Check variable
			if($tel && $push_id) {
				try {
					// Select DB table
					$new_session = JMC_CreateSession();
					mssql_select_db( HANNAM_DB_NAME, $conn_hannam );
					$query = "SELECT push_event, push_flyer, push_qna, level FROM tbl_app_member WHERE phoneNumber = '$tel' and C2DM_id = '$push_id'";
					$dbraw = mssql_query($query);
					$result = mssql_fetch_array($dbraw);
					$data['level'] = $result['level'];
					$data['push_event'] = $result['push_event'];
					$data['push_flyer'] = $result['push_flyer'];
					$data['push_qna'] = $result['push_qna'];
					$data['process'] = true;
					$data['message'] = "Found member data";
				} catch(Exception $e) {
					$data['process'] = false;
					$data['message'] = $e;
				}
			} else {
				$data['process'] = false;
				$data['message'] = "Empty parameter";
			}
			break;
		}
		
		case "push": {
			/*
			 * Input : tel, push_id, pushtype, pushstatus
			* Output : process, message
			*/
			if(DEBUG) $data['mode'] = $mode;
			if(DEBUG) $data['push_id'] = $push_id;
			if(DEBUG) $data['tel'] = $tel;
			if(DEBUG) $data['session'] = $session;
			if(DEBUG) $data['name'] = $name;
			if(DEBUG) $data['cardno'] = $cardno;
			if(DEBUG) $data['type'] = $type;
			if(DEBUG) $data['level'] = $level;
			if(DEBUG) $data['birthday'] = $birthday;
		
			// Check variable
			if($tel && $push_id && $pushtype && $pushstatus) {
				try {
					// Select DB table
					$new_session = JMC_CreateSession();
					mssql_select_db( HANNAM_DB_NAME, $conn_hannam );
					$query = "SELECT cardNo FROM tbl_app_member WHERE phoneNumber = '$tel' and C2DM_id = '$push_id'";
					$dbraw = mssql_query($query);
					$result = mssql_fetch_array($dbraw);
					if(isset($result['cardNo'])) {
						mssql_select_db( HANNAM_DB_NAME, $conn_hannam );
						if($pushtype == "event") {
							$query = "UPDATE tbl_app_member SET push_event = '$pushstatus' WHERE phoneNumber = '$tel'";
						} elseif($pushtype == "flyer") {
							$query = "UPDATE tbl_app_member SET push_flyer = '$pushstatus' WHERE phoneNumber = '$tel'";
						} elseif($pushtype == "qna") {
							$query = "UPDATE tbl_app_member SET push_qna = '$pushstatus' WHERE phoneNumber = '$tel'";
						}
						$dbraw = mssql_query($query);
						$data['process'] = true;
						$data['message'] = "Updated member data";
					} else {
						$data['process'] = false;
						$data['message'] = "Not found member data";
					}
				} catch(Exception $e) {
					$data['process'] = false;
					$data['message'] = $e;
				}
			} else {
				$data['process'] = false;
				$data['message'] = "Empty parameter";
			}
			break;
		}

		case "membership_info_search": {
			/*
			 * Input : session, tel, name
			* Output : cardNo, cardCount, process, message
			*/
			if(DEBUG) $data['mode'] = $mode;
			if(DEBUG) $data['push_id'] = $push_id;
			if(DEBUG) $data['tel'] = $tel;
			if(DEBUG) $data['session'] = $session;
			if(DEBUG) $data['name'] = $name;
			if(DEBUG) $data['cardno'] = $cardno;
			if(DEBUG) $data['type'] = $type;
			if(DEBUG) $data['level'] = $level;
			if(DEBUG) $data['birthday'] = $birthday;
			if(DEBUG) $data['membership_tel'] = $membership_tel;
				
			// Check variable
			if($session && $tel) {
				try {
					// Select DB table for session ID
					mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
					$query = "SELECT session_id FROM tbl_app_member WHERE phoneNumber = '$tel'";
					$dbraw = mssql_query($query);
					$result = mssql_fetch_array($dbraw);
					if(DEBUG) $data['session'] = $result['session_id'];
						
					// Authorize session ID
					if($result['session_id'] == $session) {
						// Select DB table for search member's phone number
						mssql_select_db( BBY_DB_NAME, $conn_bby );
						if(preg_match("/[\xA1-\xFE][\xA1-\xFE]/", $name)) {
							$test = 1;
							$query = "SELECT cardNo FROM tblCustomer WHERE Phone = '$membership_tel' AND KrName = '$name'";
							$query = iconv('utf-8', 'euc-kr',$query);
						} else {
							$test = 2;
							$query = "SELECT cardNo FROM tblCustomer WHERE Phone = '$membership_tel' AND EnName = '$name'";
						}
						$dbraw = mssql_query($query, $conn_bby);
						$result = mssql_fetch_array($dbraw);
						$result_num_row = mssql_num_rows($dbraw);
		
						// Count member's cards
						if($result_num_row < 1) {
							$data['process'] = false;
							$data['message'] = "1 - "."Not found phone number";
						} elseif($result_num_row == 1) {
							$data['process'] = true;
							$data['message'] = "1 - "."Found phone number";
							$data['cardNo'] = $result['cardNo'];
						} elseif($result_num_row > 1) {
							$query2 = "SELECT a.cardNo FROM tblCustomer a LEFT OUTER JOIN tblCustomerHist b ON a.CardNo = b.CardNo ".
									  "WHERE b.NewCardNo is null AND a.Active = 1 AND a.Phone = '$membership_tel'";
							$dbraw2 = mssql_query($query2, $conn_bby);
							$result2 = mssql_fetch_array($dbraw2);
							$result_num_row2 = mssql_num_rows($dbraw2);

							if($result_num_row2 < 1) {
								$data['process'] = false;
								$data['message'] = "2 - "."Not found phone number";
							} elseif($result_num_row2 == 1) {
								$data['process'] = true;
								$data['message'] = "2 - "."Found phone number";
								$data['cardNo'] = $result2['cardNo'];
							} elseif($result_num_row2 > 1) {
								$data['process'] = false;
								$data['message'] = "2 - "."duplicated phone number";
								$data['cardCount'] = $result_num_row2;
								
								// 중복되는 카드 목록 출력
								if(DEBUG) {
									$index_card = 1;
									$data['cardNo_'+$index_card] = $result2['cardNo'];
									while($result2 = mssql_fetch_array($dbraw2)) {
										$index_card++;
										$data['cardNo_'+$index_card] = $result2['cardNo'];
									}
								}
							}
						}
					}
					else {
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
			break;
		}

		case "new_update": {
			/*
			 * Input : tel, session, cardno, level, push_id, pushstatus
			 * Output : process, message
			 */
			if(DEBUG) $data['mode'] = $mode;
			if(DEBUG) $data['tel'] = $tel;
			if(DEBUG) $data['session'] = $session;
			if(DEBUG) $data['cardno'] = $cardno;
			if(DEBUG) $data['level'] = $level;
			if(DEBUG) $data['push_id'] = $push_id;
			if(DEBUG) $data['pushstatus'] = $pushstatus;
			
			// Check variable
			if($session && $tel) {
				try {
					// Select DB table for session ID
					mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
					$query = "SELECT session_id, cardNo FROM tbl_app_member WHERE phoneNumber = '$tel'";
					$dbraw = mssql_query($query);
					$result = mssql_fetch_array($dbraw);
					if(DEBUG) $data['session'] = $result['session_id'];
					
					// Authorize session ID
					if($result['session_id'] == $session) {
						mssql_select_db( HANNAM_DB_NAME, $conn_hannam );
						$query = "UPDATE tbl_app_member SET ".
								 (($cardno) ? "cardNo = '$cardno', " : "").
								 "level = '$level', push_event = '$pushstatus', push_flyer = '$pushstatus', push_qna = '$pushstatus' ".
								 "WHERE phoneNumber = '$tel'";
						$dbraw = mssql_query($query);
						$data['process'] = true;
						$data['message'] = "Card number, Level, member data updated";
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
			break;
		}
	}
	JMC_PrintJson('login', $data);
	
	//finish DB connection.
	include_once("./include_db_disconnect.php");
?>