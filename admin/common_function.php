<?
function send_subscribe_verification_email($member_seq, $email, $verification_key, $type) {
	$fromName = "Hannam Supermarket";
	$fromEmail = "prteam@hannamsm.com";
	$toName = "Hannam Customer";
	$toEmail = $email;
	$subject = "[Hannam Supermaket] E-mail Verification";
	$verify_link = ABSOLUTE_PATH."/subscribe_confirm.php?mem=".base64_encode($toEmail)."&id=".md5($member_seq)."&key=".md5($verification_key)."&type=".$type;
	$content = "<p>안녕하세요.</p>";
	$content .= "<p>매주 새로워지는 알뜰장보기 TIP  [한남주간세일광고]를 신청해 주셔서 감사합니다. 아래 링크를 클릭하여 이메일을 인증하여 주십시요.</p>";
	$content .= "<p><a href='".$verify_link."' target='_blank'>이메일 인증하기</a></p>";
	$content .= "<p>문제가 있으시면 prteam@hannamsm.com 으로 이메일 주시기 바랍니다.</p>";
	$content .= "<br>";
	$content .= "<p>Dear,</p>";
	$content .= "<p>Thank you for your registration. Please click on the link below to confirm your registration.</p>";
	$content .= "<p><a href='".$verify_link."' target='_blank'>Verify E-mail</a></p>";
	$content .= "<p>If you experience any problem, please contact us at prteam@hannamsm.com</p>";

	sendMail($fromName, $fromEmail, $toName, $toEmail, $subject, $content, $isDebug=0);
}

function send_subscribe_verification_email2($member_seq_flyer, $member_seq_event, $email, $verification_key_flyer, $verification_key_event) {
	$fromName = "Hannam Supermarket";
	$fromEmail = "prteam@hannamsm.com";
	$toName = "Hannam Customer";
	$toEmail = $email;
	$subject = "[Hannam Supermaket] E-mail Verification";
	$verify_link = ABSOLUTE_PATH."/subscribe_confirm.php?mem=".base64_encode($toEmail)."&flyerid=".md5($member_seq_flyer)."&eventid=".md5($member_seq_event)."&flyerkey=".md5($verification_key_flyer)."&eventkey=".md5($verification_key_event);
	$content = "<p>안녕하세요.</p>";
	$content .= "<p>매주 새로워지는 알뜰장보기 TIP  [한남주간세일광고]를 신청해 주셔서 감사합니다. 아래 링크를 클릭하여 이메일을 인증하여 주십시요.</p>";
	$content .= "<p><a href='".$verify_link."' target='_blank'>이메일 인증하기</a></p>";
	$content .= "<p>문제가 있으시면 prteam@hannamsm.com 으로 이메일 주시기 바랍니다.</p>";
	$content .= "<br>";
	$content .= "<p>Dear,</p>";
	$content .= "<p>Thank you for your registration. Please click on the link below to confirm your registration.</p>";
	$content .= "<p><a href='".$verify_link."' target='_blank'>Verify E-mail</a></p>";
	$content .= "<p>If you experience any problem, please contact us at prteam@hannamsm.com</p>";

	sendMail($fromName, $fromEmail, $toName, $toEmail, $subject, $content, $isDebug=0);
}

/*
function check_subscribe_verification_email($email, $member_seq, $key) {
	mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
	$verifyEmail_query = "SELECT seq, verification_key FROM new_subscribe_member WHERE email = '$email'";
	$verifyEmail_query_result = mssql_query($verifyEmail_query, $conn_hannam);
	$verifyEmail_row = mssql_fetch_array($verifyEmail_query_result);

	if((md5($verifyEmail_row['seq']) == $member_seq) && ($verifyEmail_row['verification_key'] == $key)) {
		$subscribeUpdate_query = "UPDATE new_subscribe_member SET status = 1, status_date = GETDATE() WHERE email = '$email'";
		mssql_query($subscribeUpdate_query, $conn_hannam);

		return true;
	} else {
		return false;
	}
}
*/

function sendMail($fromName, $fromEmail, $toName, $toEmail, $subject, $contents, $isDebug=0) {
	$smtp_host = "mail.hannamsm.com";
	$port = 25;
	$type = "text/html";
	$charSet = "UTF-8";

	//Open Socket
	$fp = @fsockopen($smtp_host, $port, $errno, $errstr, 1);
	if($fp) {
		//Connection and Greetting
		$returnMessage = fgets($fp, 128);
		if($isDebug)
			print "CONNECTING MSG:".$returnMessage."\n";
		fputs($fp, "HELO YA\r\n");
		$returnMessage = fgets($fp, 128);
		if($isDebug)
			print "GREETING MSG:".$returnMessage."\n";

		fputs($fp, "MAIL FROM: <".$fromEmail.">\r\n");
		$returnvalue[0] = fgets($fp, 128);
		fputs($fp, "rcpt to: <".$toEmail.">\r\n");
		$returnvalue[1] = fgets($fp, 128);

		if($isDebug) {
			print "returnvalue:";
			print_r($returnvalue);
		}

		//Data
		fputs($fp, "data\r\n");
		$returnMessage = fgets($fp, 128);
		if($isDebug)
			print "data:".$returnMessage;
		fputs($fp, "Return-Path: ".$fromEmail."\r\n");

		$fromName = "=?".$charSet."?B?".base64_encode($fromName)."?=";
		fputs($fp, "From: ".$fromName." <".$fromEmail.">\r\n");
		fputs($fp, "To: <".$toEmail.">\r\n");
		$subject = "=?".$charSet."?B?".base64_encode($subject)."?=";

		fputs($fp, "Subject: ".$subject."\r\n");
		fputs($fp, "Content-Type: ".$type."; charset=\"".$charSet."\"\r\n");
		fputs($fp, "Content-Transfer-Encoding: base64\r\n");
		fputs($fp, "\r\n");
		$contents = chunk_split(base64_encode($contents));

		fputs($fp, $contents);
		fputs($fp, "\r\n");
		fputs($fp, "\r\n.\r\n");
		$returnvalue[2] = fgets($fp, 128);

		//Close Connection
		fputs($fp, "quit\r\n");
		fclose($fp);

		//Message
		if(ereg("^250", $returnvalue[0])&&ereg("^250", $returnvalue[1])&&ereg("^250", $returnvalue[2])) {
			$sendmail_flag = true;
		} else {
			$sendmail_flag = false;
			print "NO :".$errno.", STR : ".$errstr;
		}
  	}

	if(!$sendmail_flag) {
		echo "메일 보내기 실패";
	}
	return $sendmail_flag;
}

function Br_dconv($string) {
	$quote = array("\'", '\"');
	$replace_quote = array("''", '"');
	$string = str_replace($quote, $replace_quote, $string);

    if($string) {
        $string = iconv('utf-8', 'euc-kr', $string);
        return $string;
    } else {
        return false;
    }
}

function Br_iconv($string) {
    if($string == " ") {
        return "";
    } else if($string) {
        $string = iconv('euc-kr', 'utf-8', $string);
        return $string;
    } else {
        return false;
    }
}
?>