<?
include 'include_preset.php';

$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];

// Gift Card balance
if($mode == "giftcard") {
	$cardNum = ($_GET['cardNum']) ? $_GET['cardNum'] : $_POST['cardNum'];
	
	if(strlen($cardNum) != 8) {
		if($LANGUAGE == "korean") {
			echo "검색할 수 없는 상품권번호입니다.";
		} else {
			echo "Invalid Gift Card Number";
		}
	} else {
		mssql_select_db(BBY_DB_NAME, $conn_bby);
		$giftcard_query = "SELECT TOP 1 Balance FROM tblGiftCertificate WHERE SerialNo = '$cardNum' ORDER BY RegDate DESC";
		$giftcard_query_result = mssql_query($giftcard_query, $conn_bby);
		$giftcard_row = mssql_fetch_array($giftcard_query_result);

		$balancelen = strlen($giftcard_row['Balance']);

		if($giftcard_row['Balance'] <= 0) {
			$balance = 0;
		} else if($balancelen > 6) {
			$balance = substr($giftcard_row['Balance'], 0, 4);
		} else if($giftcard_row['Balance'] < 100) {
			$balance = substr($giftcard_row['Balance'], 0, 5);
		} else if($giftcard_row['Balance'] < 10) 	{
			$balance = substr($giftcard_row['Balance'], 0, 4);
		} else {
			$balance = substr($giftcard_row['Balance'], 0, 7);
		}

		if($LANGUAGE == "korean") {
			echo "<strong>잔액:&nbsp;&nbsp;</strong><span class='balance'>$".$balance."</span>";
		} else {
			echo "<strong>Balance:&nbsp;&nbsp;</strong><span class='balance'>$".$balance."</span>";
		}
	}
}

// Membership Point balance
if($mode == "membership") {
	$cardNum = ($_GET['cardNum']) ? $_GET['cardNum'] : $_POST['cardNum'];
	$cardNum = "882004".$cardNum;
	
	if(strlen($cardNum) != 12) {
		if($LANGUAGE == "korean") {
			echo "검색할 수 없는 멤버쉽 번호입니다.";
		} else {
			echo "Invalid Membership Number";
		}
	} else {
		mssql_select_db(BBY_DB_NAME, $conn_bby);
		$membership_query = "SELECT TOP 1 CollectedPoint FROM tblCustomer WHERE CardNo = '$cardNum' ";
		$membership_query_result = mssql_query($membership_query, $conn_bby);
		$membership_row = mssql_fetch_array($membership_query_result);

		if($membership_row['CollectedPoint']) {
			echo "<strong>Point:&nbsp;&nbsp;</strong><span class='balance'>".number_format($membership_row['CollectedPoint'])."</span>";
		} else {
			if($LANGUAGE == "korean") {
				echo "존재하지 않는 멤버쉽 번호입니다.";
			} else {
				echo "Invalid Membership Number";
			}
		}
	}
}

// Membership card duplication check
if($mode == "membership_cardCheck") {
	$cardNum = ($_GET['cardNum']) ? $_GET['cardNum'] : $_POST['cardNum'];

	if(strlen($cardNum) != 12) {
		echo "<span style='color:red'>사용 X</span>";
	} else {
		mssql_select_db(BBY_DB_NAME, $conn_bby);
		$memberDupCheck_query = "SELECT TOP 1 CardNo FROM tblCustomer WHERE CardNo = '$cardNum' ";
		$memberDupCheck_query_result = mssql_query($memberDupCheck_query, $conn_bby);
		$memberDupCheck_row = mssql_fetch_array($memberDupCheck_query_result);

		if($memberDupCheck_row['CardNo']) {
			echo "<span style='color:red'>사용 X</span>";
		} else {
			echo "<script>(document.getElementsByName('membership_card_confirm')[0].value = 1)</script>";
			echo "<span style='color:green'>사용 O</span>";
		}
	}
}

// Membership application
if($mode == "membership_apply") {
	$membership_nameEng = strtoupper($_POST['membership_nameEng']);
	$membership_nameKor = Br_dconv($_POST['membership_nameKor']);
	$membership_phone = $_POST['membership_phone'];
	$membership_email = $_POST['membership_email'];
	$membership_language = $_POST['membership_language'];
	$membership_street = $_POST['membership_street'];
	$membership_city = $_POST['membership_city'];
	$membership_postal = strtoupper(str_replace(" ", "", $_POST['membership_postal']));
	$membership_province = $_POST['membership_province'];
	$membership_bDay = $_POST['membership_bDay'];
	$membership_familyNum = $_POST['membership_familyNum'];
	$membership_agree = ($_POST['membership_agree'] ? 1 : 0);
	$membership_weeklyFlyer = ($_POST['membership_weeklyFlyer'] ? 1 : 0);
	$membership_eventFlyer = ($_POST['membership_eventFlyer'] ? 1 : 0);
	
	// checking application
	$checkPhone = str_replace("-", "", $membership_phone);
	mssql_select_db(BBY_DB_NAME, $conn_bby);

	$memCheck_query = "SELECT Phone, EnName, KrName FROM tblCustomer WHERE Active = 1 AND phone = '$checkPhone' ";
	$memCheck_query_result = mssql_query($memCheck_query, $conn_bby);
	$memCheck_num_row = mssql_num_rows($memCheck_query_result);
	$check_result = true;
	$flyer_verify_email = false;
	$event_verify_email = false;
	
	if($memCheck_num_row) {
		// phone # is already registered
		while($memCheck_row = mssql_fetch_array($memCheck_query_result)) {
			if((strtoupper($memCheck_row['EnName']) == $membership_nameEng) || (Br_iconv($memCheck_row['KrName']) == $membership_nameKor)) {
				// English OR Korean name is same
				$check_result = false;
				break;
			}
		}
	} 
	if($check_result) {
		// phone # is not registered
		// saving application
		mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
		$membershipGetSeq_query = "SELECT seq FROM new_membership ORDER BY seq DESC";
		$membershipGetSeq_query_result = mssql_query($membershipGetSeq_query, $conn_hannam);
		$membershipGetSeq_row = mssql_fetch_array($membershipGetSeq_query_result);

		if($membershipGetSeq_row['seq'])	$membership_seq = $membershipGetSeq_row['seq'] + 1;
		else								$membership_seq = 1;

		// Inserting mandatory field 
		$memApply_query = "INSERT INTO new_membership (seq, name_eng, phone, email, language_pref, postalCode, province, agree) ".
						  "VALUES ($membership_seq, '$membership_nameEng', '$membership_phone', '$membership_email', '$membership_language', '$membership_postal', '$membership_province', $membership_agree) ";
		mssql_query($memApply_query, $conn_hannam);

		// Inserting non-mandatory field
		$memApply_query2 = "UPDATE new_membership SET ".
						   (($membership_street) ? "street = '$membership_street', " : "" ).
						   (($membership_bDay) ? "birthDay = '$membership_bDay', " : "" ).
						   "name_kor = '$membership_nameKor', city = '$membership_city', ".
						   "family = $membership_familyNum, weeklyFlyer = $membership_weeklyFlyer, eventFlyer = $membership_eventFlyer ".
						   "WHERE seq = $membership_seq";
		mssql_query($memApply_query2, $conn_hannam);

		// Inserting INTO subscribe DB if signed up email subscribe
		if($membership_weeklyFlyer) {
			$item_type = 1;
			$subscribeCheckEmail_query = "SELECT seq, status, postal_code, verification_key FROM new_subscribe_member WHERE type = $item_type AND email = '$membership_email'";
			$subscribeCheckEmail_query_result = mssql_query($subscribeCheckEmail_query, $conn_hannam);
			$subscribeCheckEmail_row = mssql_fetch_array($subscribeCheckEmail_query_result);

			if($subscribeCheckEmail_row['seq']) {				// already exist
				if($subscribeCheckEmail_row['postal_code'] != $membership_postal) {
					$subscribeUpdate_query = "UPDATE new_subscribe_member SET postal_code = '$membership_postal' WHERE email = '$membership_email'";
					mssql_query($subscribeUpdate_query, $conn_hannam);
				}

				if($subscribeCheckEmail_row['status'] == 0) {	// not verified OR discontinue
					$flyer_verify_email = true;
					$member_seq_flyer = $subscribeCheckEmail_row['seq'];
					$verification_key_flyer = $subscribeCheckEmail_row['verification_key'];
					//send_subscribe_verification_email($subscribeCheckEmail_row['seq'], $membership_email, $subscribeCheckEmail_row['verification_key']);
				}
			} else {											// not exist
				$memberGetSeqFlyer_query = "SELECT TOP 1 seq FROM new_subscribe_member ORDER BY seq DESC";
				$memberGetSeqFlyer_query_result = mssql_query($memberGetSeqFlyer_query, $conn_hannam);
				$memberGetSeqFlyer_row = mssql_fetch_array($memberGetSeqFlyer_query_result);

				if($memberGetSeqFlyer_row['seq'])	$member_seq_flyer = $memberGetSeqFlyer_row['seq'] + 1;
				else								$member_seq_flyer = 1;

				$verification_key_flyer = rand(10000000, 99999999);
				$subscribeAdd_query = "INSERT INTO new_subscribe_member (seq, type, email, postal_code, verification_key) ".
									  "VALUES ($member_seq_flyer, $item_type, '$membership_email', '$membership_postal', '$verification_key_flyer') ";
				mssql_query($subscribeAdd_query, $conn_hannam);

				$flyer_verify_email = true;
				//send_subscribe_verification_email($member_seq_flyer, $membership_email, $verification_key);
			}
		}
		if($membership_eventFlyer) {
			$item_type = 2;
			$eventCheckEmail_query = "SELECT seq, status, postal_code, verification_key FROM new_subscribe_member WHERE type = $item_type AND email = '$membership_email'";
			$eventCheckEmail_query_result = mssql_query($eventCheckEmail_query, $conn_hannam);
			$eventCheckEmail_row = mssql_fetch_array($eventCheckEmail_query_result);

			if($eventCheckEmail_row['seq']) {				// already exist
				if($eventCheckEmail_row['postal_code'] != $membership_postal) {
					$eventUpdate_query = "UPDATE new_subscribe_member SET postal_code = '$membership_postal' WHERE email = '$membership_email'";
					mssql_query($eventUpdate_query, $conn_hannam);
				}

				if($eventCheckEmail_row['status'] == 0) {	// not verified OR discontinue
					$event_verify_email = true;
					$member_seq_event = $eventCheckEmail_row['seq'];
					$verification_key_event = $eventCheckEmail_row['verification_key'];
					//send_subscribe_verification_email($eventCheckEmail_row['seq'], $membership_email, $eventCheckEmail_row['verification_key']);
				}
			} else {											// not exist
				$memberGetSeqEvent_query = "SELECT TOP 1 seq FROM new_subscribe_member ORDER BY seq DESC";
				$memberGetSeqEvent_query_result = mssql_query($memberGetSeqEvent_query, $conn_hannam);
				$memberGetSeqEvent_row = mssql_fetch_array($memberGetSeqEvent_query_result);

				if($memberGetSeqEvent_row['seq'])	$member_seq_event = $memberGetSeqEvent_row['seq'] + 1;
				else								$member_seq_event = 1;

				$verification_key_event = rand(10000000, 99999999);
				$eventAdd_query = "INSERT INTO new_subscribe_member (seq, type, email, postal_code, verification_key) ".
								  "VALUES ($member_seq_event, $item_type, '$membership_email', '$membership_postal', '$verification_key_event') ";
				mssql_query($eventAdd_query, $conn_hannam);

				$event_verify_email = true;
				//send_subscribe_verification_email($member_seq_event, $membership_email, $verification_key);
			}
		}

		if($flyer_verify_email && $event_verify_email) {
			send_subscribe_verification_email2($member_seq_flyer, $member_seq_event, $membership_email, $verification_key_flyer, $verification_key_event);
		} else {
			if($flyer_verify_email) {
				send_subscribe_verification_email($member_seq_flyer, $membership_email, $verification_key_flyer, $item_type);
			} else if($event_verify_email) {
				send_subscribe_verification_email($member_seq_event, $membership_email, $verification_key_event, $item_type);
			}
		}
		echo "<script>location.replace('../membership_success.php');</script>";
	} else {
		echo "<script>location.replace('../membership_error.php');</script>";
	}
}

// E-Flyer subscribe
if($mode == "subscribe_request") {
	$subscribe_email = $_POST['subscribe_email'];
	$subscribe_postal = strtoupper($_POST['subscribe_postal']);

	// check e-mail
	mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
	$item_type = 1;
	$subscribeCheckEmail_query = "SELECT seq, status, postal_code, verification_key FROM new_subscribe_member WHERE type = $item_type AND email = '$subscribe_email'";
	$subscribeCheckEmail_query_result = mssql_query($subscribeCheckEmail_query, $conn_hannam);
	$subscribeCheckEmail_row = mssql_fetch_array($subscribeCheckEmail_query_result);

	if($subscribeCheckEmail_row['seq']) {				// already exist
		if($subscribeCheckEmail_row['postal_code'] != $subscribe_postal) {
			$subscribeUpdate_query = "UPDATE new_subscribe_member SET postal_code = '$subscribe_postal', status_date = GETDATE() WHERE email = '$subscribe_email'";
			mssql_query($subscribeUpdate_query, $conn_hannam);
		}

		if($subscribeCheckEmail_row['status'] == 0) {	// not verified OR discontinue
			send_subscribe_verification_email($subscribeCheckEmail_row['seq'], $subscribe_email, $subscribeCheckEmail_row['verification_key'], $item_type);
		} else {										// already subscribing
			echo "<script>location.replace('../subscribe_confirm.php?');</script>";
		}
	} else {											// not exist
		$memberGetSeq_query = "SELECT TOP 1 seq FROM new_subscribe_member ORDER BY seq DESC";
		$memberGetSeq_query_result = mssql_query($memberGetSeq_query, $conn_hannam);
		$memberGetSeq_row = mssql_fetch_array($memberGetSeq_query_result);

		if($memberGetSeq_row['seq'])	$member_seq = $memberGetSeq_row['seq'] + 1;
		else							$member_seq = 1;

		$verification_key = rand(10000000, 99999999);
		$subscribeAdd_query = "INSERT INTO new_subscribe_member (seq, type, email, postal_code, verification_key) ".
							  "VALUES ($member_seq, $item_type, '$subscribe_email', '$subscribe_postal', '$verification_key') ";
		mssql_query($subscribeAdd_query, $conn_hannam);

		send_subscribe_verification_email($member_seq, $subscribe_email, $verification_key, $item_type);
	}

	echo "<script>location.replace('../subscribe_verify.php');</script>";
}

// Career application
if($mode == "career") {
	$career_nameE = $_POST['career_nameE'];
	$career_nameK = Br_dconv($_POST['career_nameK']);
	//$career_gender = $_POST['career_gender'];
	$career_phone1 = $_POST['career_phone1'];
	$career_phone2 = $_POST['career_phone2'];
	$career_email = $_POST['career_email'];
	//$career_address = $_POST['career_address'];
	$career_city = $_POST['career_city'];
	//$career_postal = $_POST['career_postal'];
	$career_province = $_POST['career_province'];
	//$career_prevWork = Br_dconv($_POST['career_prevWork']);
	//$career_prevWorkMonth = $_POST['career_prevWorkMonth'];
	$career_workPastYear = (($_POST['career_workPastYear']) ? 1 : 0 );
	$old_coverLetter = $_POST['old_coverLetter'];
	$temp_coverLetter = $_POST['temp_coverLetter'];
	$old_resume = $_POST['old_resume'];
	$temp_resume = $_POST['temp_resume'];

	for($i = 0; $i < sizeof($_POST['career_workHour']); $i++) {
		if($_POST['career_workHour'][$i]) {
			if($i == 0)		$career_workHour = $_POST['career_workHour'][$i];
			else			$career_workHour .= "/".$_POST['career_workHour'][$i];
		}
	}
	for($i = 0; $i < sizeof($_POST['career_workField']); $i++) {
		if($_POST['career_workField'][$i]) {
			if($i == 0)		$career_workField = $_POST['career_workField'][$i];
			else			$career_workField .= "/".$_POST['career_workField'][$i];
		}
	}

	/*
	echo "career_nameE - ".$career_nameE."<br>";
	echo "career_nameK - ".$career_nameK."<br>";
	echo "career_gender - ".$career_gender."<br>";
	echo "career_phoneHome - ".$career_phoneHome."<br>";
	echo "career_phoneMobile - ".$career_phoneMobile."<br>";
	echo "career_email - ".$career_email."<br>";
	echo "career_address - ".$career_address."<br>";
	echo "career_city - ".$career_city."<br>";
	echo "career_postal - ".$career_postal."<br>";
	echo "career_province - ".$career_province."<br>";
	echo "career_workHour - ".$career_workHour."<br>";
	echo "career_workField - ".$career_workField."<br>";
	echo "career_prevWork - ".$career_prevWork."<br>";
	echo "career_prevWorkMonth - ".$career_prevWorkMonth."<br>";
	echo "career_workPastYear - ".$career_workPastYear."<br>";
	echo "old_coverLetter - ".$old_coverLetter."<br>";
	echo "temp_coverLetter - ".$temp_coverLetter."<br>";
	echo "old_resume - ".$old_resume."<br>";
	echo "temp_resume - ".$temp_resume."<br>";
	*/

	mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
	// Finding seq number
	$careerGetSeq_query = "SELECT TOP 1 seq FROM new_career ORDER BY seq DESC";
	$careerGetSeq_query_result = mssql_query($careerGetSeq_query, $conn_hannam);
	$careerGetSeq_row = mssql_fetch_array($careerGetSeq_query_result);

	if($careerGetSeq_row['seq'])	$career_seq = $careerGetSeq_row['seq'] + 1;
	else							$career_seq = 1;

	$careerAdd_query = "INSERT INTO new_career (seq, apply_date, name_eng, name_kor, phone_1, phone_2, email, city, province, pref_workHour, pref_department, exp_pastYear) ".
					   "VALUES ($career_seq, GETDATE(), '$career_nameE', '$career_nameK', '$career_phone1', '$career_phone2', '$career_email', '$career_city', '$career_province', '$career_workHour', '$career_workField', $career_workPastYear)";
	mssql_query($careerAdd_query, $conn_hannam);

	$temp_path = "../upload/career/temp/";
	$upload_path = "../upload/career/";

	// Uploading cover letter from temp folder to career folder
	if($temp_coverLetter) {
		$temp_fullpath = $temp_path.$temp_coverLetter;
		$upload_fullpath = $upload_path.$temp_coverLetter;
		if(file_exists($temp_fullpath)) {
			if(copy($temp_fullpath, $upload_fullpath)) {
				$careerAddCL_query = "UPDATE new_career SET file1 = '$temp_coverLetter' WHERE seq = $career_seq";
				mssql_query($careerAddCL_query, $conn_hannam);

				if(file_exists($upload_fullpath)) {
					unlink($temp_fullpath);
				}
			}
		}
	}

	// Uploading reusme from temp folder to career folder
	if($temp_resume) {
		$temp_fullpath = $temp_path.$temp_resume;
		$upload_fullpath = $upload_path.$temp_resume;
		if(file_exists($temp_fullpath)) {
			if(copy($temp_fullpath, $upload_fullpath)) {
				$careerAddResume_query = "UPDATE new_career SET file2 = '$temp_resume' WHERE seq = $career_seq";
				mssql_query($careerAddResume_query, $conn_hannam);

				if(file_exists($upload_fullpath)) {
					unlink($temp_fullpath);
				}
			}
		}
	}

	echo "<script>location.replace('../career_confirm.php');</script>";
}

// Contact Us inserting into DB
if($mode == "contactus") {
	$quote = array("\'", '\"');
	$replace_quote = array("''", '"');
	$contactus_subject = Br_dconv(str_replace($quote, $replace_quote, $_POST['contactus_subject']));
	$contactus_name = Br_dconv(str_replace($quote, $replace_quote, $_POST['contactus_name']));
	$contactus_email = $_POST['contactus_email'];
	$contactus_phone = $_POST['contactus_phone'];
	$contactus_content = nl2br(Br_dconv(str_replace($quote, $replace_quote, $_POST['contactus_content'])));

	$contactusGetSeq_query = "SELECT TOP 1 seq FROM new_contactUs ORDER BY seq DESC";
	$contactusGetSeq_query_result = mssql_query($contactusGetSeq_query, $conn_hannam);
	$contactusGetSeq_row = mssql_fetch_array($contactusGetSeq_query_result);

	if($contactusGetSeq_row['seq'])	$contactus_seq = $contactusGetSeq_row['seq'] + 1;
	else							$contactus_seq = 1;

	$contactus_status = 2;		// 1 = 답변 완료, 2 = 답변 대기
	$contactus_query = "INSERT INTO new_contactus (seq, status, submit_date, name, email, phone, subject, content) ".
					   "VALUES ($contactus_seq, 2, GETDATE(), '$contactus_name', '$contactus_email', '$contactus_phone', '$contactus_subject', '$contactus_content')";
	mssql_query($contactus_query, $conn_hannam);

	// Send E-Mail
	$fromName = Br_iconv($contactus_name);
	$fromEmail = $contactus_email;
	$toName = "한남-홍보팀";
	$toEmail = "prteam@hannamsm.com";
	$subject = Br_iconv($contactus_subject);
	$content = Br_iconv($contactus_content);
	sendMail($fromName, $fromEmail, $toName, $toEmail, $subject, $content, $isDebug=0);

	echo "<script>location.replace('../contact_confirm.php');</script>";
}

// Weekly Flyer view counter
if($mode == "flyer_view") {
	$db_type = $_GET['type'];
	$db_seq = $_GET['seq'];

	mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
	$flyerClick_query = "UPDATE new_regularUpdate SET ".
						"click_counter = (SELECT click_counter FROM new_regularUpdate WHERE type = $db_type AND seq = $db_seq) + 1 ".
						"WHERE type = $db_type AND seq = $db_seq";
	mssql_query($flyerClick_query, $conn_hannam);
}

// Weekly Flyer mobile view counter 
if($mode == "flyer_view_mobile") {
	$db_type = $_GET['type'];
	$db_seq = $_GET['seq'];

	mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
	$flyerClick_query = "UPDATE new_regularUpdate SET ".
						"click_counter_mobile = (SELECT click_counter_mobile FROM new_regularUpdate WHERE type = $db_type AND seq = $db_seq) + 1 ".
						"WHERE type = $db_type AND seq = $db_seq";
	mssql_query($flyerClick_query, $conn_hannam);
}

// Magazine view counter
if($mode == "magazine_view") {
	$db_type = 3;
	$db_seq = $_GET['seq'];

	mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
	$magazineClick_query = "UPDATE new_regularUpdate SET ".
						   "click_counter = (SELECT click_counter FROM new_regularUpdate WHERE type = $db_type AND seq = $db_seq) + 1 ".
						   "WHERE type = $db_type AND seq = $db_seq";
	mssql_query($magazineClick_query, $conn_hannam);
}

// Member Id check
if($mode == "member_idCheck") {
	$mId = ($_GET['id']) ? $_GET['id'] : $_POST['id'];

	if(strlen($mId) == 0 || trim($mId) == "") {
		echo "<span style='color:red'>사용 X</span>";
	} else {
		mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
		$memberDupIdCheck_query = "SELECT TOP 1 mId FROM new_member WHERE mId = '$mId' ";
		$memberDupIdCheck_query_result = mssql_query($memberDupIdCheck_query, $conn_hannam);
		$memberDupIdCheck_row = mssql_fetch_array($memberDupIdCheck_query_result);

		if($memberDupIdCheck_row['mId']) {
			echo "<span style='color:red'>사용 X</span>";
		} else {
			echo "<script>(document.getElementsByName('mId_check')[0].value = 1)</script>";
			echo "<span style='color:green'>사용 O</span>";
		}
	}
}

if($mode == "hit_count") {
	$date_start = ($_GET['date_start']) ? $_GET['date_start'] : $_POST['date_start'];
	$date_end = ($_GET['date_end']) ? $_GET['date_end'] : $_POST['date_end'];

	$hitCount_query = "SELECT SUM(hit_pc) AS hit_pc, SUM(hit_mobile) AS hit_mobile FROM new_hit_counter ".
					  "WHERE hit_date >= '$date_start' AND hit_date <= '$date_end ' ";
	$hitCount_query_result = mssql_query($hitCount_query, $conn_hannam);
	$hitCount_row = mssql_fetch_array($hitCount_query_result);

	$result = "<strong>[".$date_start." ~ ".$date_end."]</strong>".
			  "<div style='padding-top:5px;'>PC - ".$hitCount_row['hit_pc']." <br> "."Mobile - ".$hitCount_row['hit_mobile']."</div>";
	
	echo $result;
}
?>