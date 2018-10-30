<script>
function check_membership_card() {
	var membership_number = document.getElementsByName("membership_card")[0].value;

	if (!membership_number || membership_number == "" || membership_number.length < 12) {
		alert("멤버쉽 번호 오류");
		return;
	} else {
		document.getElementById("membership_check_result").innerHTML = '<img src="../img/ajax-loader.gif" height="14px">';

		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var parse_script = xmlhttp.responseText;
				if (match = parse_script.match(/<script[^>]*>(([^<]|\n|\r|<[^\/])+)<\/script>/)) {
					eval.call(window, match[1]);
				}
				document.getElementById("membership_check_result").innerHTML = xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET", "customer_handler.php?mode=membership_cardCheck&cardNum=" + membership_number, true);
		xmlhttp.send();
	}
}

function reset_membership_card() {
	document.getElementsByName("membership_card_confirm")[0].value = 0;

	document.getElementById("membership_check_result").innerHTML = '<input type="button" onClick="check_membership_card()" value="중복확인">';
}

function membershipCard_submit() {
	var form = document.forms.form_membershipCard;

	if(!form.membership_card || form.membership_card.value.length < 12 || form.membership_card_confirm.value == 0) {
		alert("멤버쉽 카드번호 오류");
		form.membership_card.focus();
		return false;
	}

	if(!form.membership_sex || form.membership_sex.value == "") {
		alert("멤버 성별 오류");
		form.membership_sex.focus();
		return false;
	}

	var answer = confirm("카드 번호를 등록하시겠습니까?");
	if(answer) {
		document.getElementById("submit_btn_div").innerHTML = '<img src="../img/ajax-loader.gif" height="20px" style="padding-right:30px;">';
		form.mode.value = "assign_card";
		form.submit();
	}
}
</script>

<?
$seq = ($_GET['seq']) ? $_GET['seq'] : $_POST['seq'];
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];

if($mode == "assign_card") {
	$membership_card = $_POST['membership_card'];
	$membership_sex = $_POST['membership_sex'];
	$membership_issuePerson = $_SESSION['memberId'];

	/*
	echo "membership_card - ".$membership_card."<br>";
	echo "membership_issuePerson - ".$membership_issuePerson."<br>";
	*/

	mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
	$membershipAddCard_query = "UPDATE new_membership SET ".
							   "status = 1, cardNo = '$membership_card', sex = '$membership_sex', cardIssuePerson = '$membership_issuePerson', cardIssueDate = GETDATE() ".
							   "WHERE seq = $seq";
	mssql_query($membershipAddCard_query, $conn_hannam);


	// Getting membership info
	$issuer_store = (($_SESSION['memberCompany'] == "B" || $_SESSION['memberCompany'] == "S") ? $_SESSION['memberCompany'] : "B" );	// Other 일 경우 bby로 설정
	$issuer_code = $_SESSION['memberCode'];
	$issuer_name = Br_dconv($_SESSION['memberName']);
	$active = 1;
	$today = date("Y-m-d");

	$memInfo_query = "SELECT *, CONVERT(char(10), birthDay, 120) AS birthDay, CONVERT(char(24), cardIssueDate, 121) AS cardIssueDate, CONVERT(char(10), cardIssueDate, 120) AS cardIssueDay, CONVERT(char(8), cardIssueDate, 108) AS cardIssueTime ".
					 "FROM new_membership WHERE seq = $seq";
	$memInfo_query_result = mssql_query($memInfo_query, $conn_hannam);
	$memInfo_row = mssql_fetch_array($memInfo_query_result);

	// Inserting into tblCustomer
	if($issuer_store == "S")	mssql_select_db(BBY_DB_NAME, $conn_sry);
	else						mssql_select_db(BBY_DB_NAME, $conn_bby);
	// mandatory field
	$membershipAddPOS_query = "INSERT INTO tblCustomer (CardNo, StoreCode, CustLanguage, Sex, EnName, Phone, ".(($memInfo_row['street']) ? "Address, " : "" )." City, province_cd, Province, country_cd, PostalCode, Email, ".(($memInfo_row['birthDay']) ? "BirthDate, " : "" )." Family, KrName, AppDate, CardIssueDate, CardIssueTime, CardIssueStore, CardIssuePassWord, CardIssuePerson, Active, LastModDate, LastModTime, LastModStore, LastModPassWord, LastModPerson, Agree, sync_ck, sync_dt, ip_dt) ".
							  "VALUES ('".$memInfo_row['cardNo']."', '$issuer_store', '".$memInfo_row['language_pref']."', '".$memInfo_row['sex']."', '".$memInfo_row['name_eng']."', '".str_replace("-", "", $memInfo_row['phone'])."', ".(($memInfo_row['street']) ? "'".$memInfo_row['street']."', " : "" )."'".$memInfo_row['city']."', 2, '01', 0, '".$memInfo_row['postalCode']."', '".$memInfo_row['email']."', ".(($memInfo_row['birthDay']) ? "'".$memInfo_row['birthDay']."', " : "" )."'".$memInfo_row['family']."', '".$memInfo_row['name_kor']."', '$today', '".$memInfo_row['cardIssueDay']."', '".$memInfo_row['cardIssueTime']."', '$issuer_store', '$issuer_code', '$issuer_name', $active, '".$memInfo_row['cardIssueDay']."', '".$memInfo_row['cardIssueTime']."', '$issuer_store', '$issuer_code', '$issuer_name', '".(($memInfo_row['weeklyFlyer']) ? "Y" : "N")."', 0, '".$memInfo_row['cardIssueDate']."', '".$memInfo_row['cardIssueDate']."') ";
	mssql_query($membershipAddPOS_query, (($issuer_store == "S") ? $conn_sry : $conn_bby ));
}

mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
$membershipDetail_query = "SELECT name_eng, name_kor, phone, email, language_pref, street, city, postalCode, province, CONVERT(char(10), birthDay, 120) AS birthDay, family, weeklyFlyer, eventFlyer, CONVERT(char(19), applyDate, 120) AS applyDate, status, cardNo, sex, cardIssuePerson, CONVERT(char(19), cardIssueDate, 120) AS cardIssueDate ".
						  "FROM new_membership WHERE seq = $seq";
$membershipDetail_query_result = mssql_query($membershipDetail_query, $conn_hannam);
$membershipDetail_row = mssql_fetch_array($membershipDetail_query_result);
?>

<div class="content_wrapper">
	<div class="h1_wrapper stripped">
		<h1>Customer Service - Membership New Application</h1>
	</div>

	<div style="text-align: left; padding:10px;">
		<table class="career_review" cellspacing=0 cellpadding=0>
			<tr>
				<td class="career_title" colspan=2><?=(($membershipDetail_row['status']) ? "Member Information" : "Membership Application" ); ?></td>
			</tr>
			<tr>
				<td class="career_subject"><strong>Legal Name <br> (영문 성명)</strong></td>
				<td class="career_content"><?=$membershipDetail_row['name_eng']; ?></td>
			</tr>
			<tr>
				<td class="career_subject"><strong>Korean Name <br> (한글 성명)</strong></td>
				<td class="career_content"><?=Br_iconv($membershipDetail_row['name_kor']); ?></td>
			</tr>
			<tr>
				<td class="career_subject"><strong>Phone Number <br> (전화번호)</strong></td>
				<td class="career_content"><?=$membershipDetail_row['phone']; ?></td>
			</tr>
			<tr>
				<td class="career_subject"><strong>Email <br> (이메일)</strong></td>
				<td class="career_content"><?=$membershipDetail_row['email']; ?></td>
			</tr>
			<tr>
				<td class="career_subject"><strong>Preferred Language <br> (언어)</strong></td>
				<td class="career_content"><?=$membershipDetail_row['language_pref']; ?></td>
			</tr>
			<tr>
				<td class="career_subject"><strong>Current Address <br> (현재 거주 주소)</strong></td>
				<td class="career_content">
					<?
					if($membershipDetail_row['street'])			echo $membershipDetail_row['street'].", ";
					if($membershipDetail_row['city'])			echo $membershipDetail_row['city'].", ";
					if($membershipDetail_row['postalCode'])		echo $membershipDetail_row['postalCode'].", ";
					if($membershipDetail_row['province'])		echo $membershipDetail_row['province'];
					?>
				</td>
			</tr>
			<tr>
				<td class="career_subject"><strong>Date of Birth <br> (생년월일)</strong></td>
				<td class="career_content"><?=$membershipDetail_row['birthDay']; ?></td>
			</tr>
			<tr>
				<td class="career_subject"><strong>Number of Family Members <br> (가족수)</strong></td>
				<td class="career_content"><?=$membershipDetail_row['family']; ?></td>
			</tr>
			<tr>
				<td class="career_subject"><strong>Subscribe to E-Flyer <br> (주간광고 이메일 수신)</strong></td>
				<td class="career_content"><?=(($membershipDetail_row['weeklyFlyer']) ? "Yes" : "No" ); ?></td>
			</tr>
			<tr>
				<td class="career_subject"><strong>Subscribe to Events <br> (이벤트 이메일 수신)</strong></td>
				<td class="career_content"><?=(($membershipDetail_row['eventFlyer']) ? "Yes" : "No" ); ?></td>
			</tr>

			<? if(!$membershipDetail_row['status']) { ?>
				<form name="form_membershipCard" action="?menu=menu3&list=membership_detail" method="post" accept-charset="utf-8" onsubmit="return false">
				<input type="hidden" name="mode">
				<input type="hidden" name="seq" value="<?=$seq; ?>">
				<tr>
					<td class="career_title" colspan=2>Assign Membership Card</td>
				</tr>
				<tr>
					<td class="career_subject"><strong>Card No <br> (카드번호)</strong></td>
					<td class="career_content">
						<input type="text" name="membership_card" size=25 onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" onkeypress='reset_membership_card(); ((event.charCode >= 48 && event.charCode <= 57)); if(event.keyCode==13) check_membership_card()' maxlength=12>
						<span id="membership_check_result" style="padding-left:10px;"><input type="button" onClick="check_membership_card()" value="중복확인"></span>
					</td>
					<input type="hidden" name="membership_card_confirm">
				</tr>
				<tr>
					<td class="career_subject"><strong>Sex <br> (성별)</strong></td>
					<td class="career_content">
						<select class="textbox2" name="membership_sex">
							<option value="">= 성별 선택 =</option>
							<option value="M">Male</option>
							<option value="F">Female</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="career_subject"><strong>Card Issue Person <br> (카드 발행인)</strong></td>
					<td class="career_content"><?=$_SESSION['memberId']; ?></td>
				</tr>
				<tr>
					<td class="career_subject"><strong>Card Issue Date <br> (카드 발행일)</strong></td>
					<td class="career_content"><?=date("Y-m-d"); ?></td>
				</tr>
				</form>

			<? } else { ?>
				<tr>
					<td class="career_title" colspan=2>Membership Card Information</td>
				</tr>
				<tr>
					<td class="career_subject"><strong>Card No <br> (카드번호)</strong></td>
					<td class="career_content"><?=$membershipDetail_row['cardNo']; ?></td>
					<input type="hidden" name="membership_card_confirm">
				</tr>
				<tr>
					<td class="career_subject"><strong>Sex <br> (성별)</strong></td>
					<td class="career_content"><?=(($membershipDetail_row['sex'] == "M") ? "Male" : "Female" ); ?></td>
				</tr>
				<tr>
					<td class="career_subject"><strong>Card Issue Person <br> (카드 발행인)</strong></td>
					<td class="career_content"><?=$membershipDetail_row['cardIssuePerson']; ?></td>
				</tr>
				<tr>
					<td class="career_subject"><strong>Card Issue Date <br> (카드 발행일)</strong></td>
					<td class="career_content"><?=$membershipDetail_row['cardIssueDate']; ?></td>
				</tr>
			<? } ?>
		</table>
	</div>

	<div style="margin-top:30px;">
		<div style="float:left;"><input type="button" class="btn" onClick="location.href='?menu=menu3&list=<?=((!$membershipDetail_row['status']) ? 'membership_new' : 'membership_all' ); ?>'" value="목록"></div>
		<? if(!$membershipDetail_row['status']) { ?>
			<div id="submit_btn_div" style="float:right;"><input type="button" id="submit_btn" class="btn" onClick="membershipCard_submit();" value="저장"></div>
		<? } ?>
	</div>
</div>