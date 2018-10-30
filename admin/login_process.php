<?
include "include_preset.php";

$loginId = $_POST['USERID'];
$loginPw = $_POST['USERPW'];

if($loginId && $loginPw) {
	mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
	//$login_query = "SELECT mPassword, memberType, mActive FROM member WHERE (memberType = 'admin' OR memberType = 'manager') AND mEmail = '$loginId'";
	$login_query = "SELECT mPassword, mName, mCode, mCompany, mLevel, mStatus FROM new_member WHERE mId = '$loginId' ";
	$login_query_result = mssql_query($login_query, $conn_hannam);
	$login_row = mssql_fetch_array($login_query_result);

	if($login_row['mPassword'] == $loginPw && $login_row['mStatus'] == 1) {
		$_SESSION['memberId'] = $loginId;
		$_SESSION['memberName'] = Br_iconv($login_row['mName']);
		$_SESSION['memberCode'] = $login_row['mCode'];
		$_SESSION['memberCompany'] = $login_row['mCompany'];
		$_SESSION['memberLevel'] = $login_row['mLevel'];
		unset($_SESSION['login_msg']);

		$loginTimeUpdate_query = "UPDATE new_member SET mLoginDate = GETDATE() WHERE mId = '$loginId' ";
		mssql_query($loginTimeUpdate_query, $conn_hannam);

	} else if($login_row['mStatus'] == 0) {
		$_SESSION['login_msg'] = "사용중지 아이디";
	} else {
		$_SESSION['login_msg'] = "아이디/비밀번호 오류";
	}

	echo '<script>location.href="admin.php";</script>';
}
?>