<?php include 'header.php'; ?>

<style>
<?php include 'css/join_membership.css'; ?>
<?php include 'css/footer.css'; ?>
</style>

<link href='http://fonts.googleapis.com/css?family=Chango' rel='stylesheet' type='text/css'>

<script>
function input_focus(target) {
	target.style.borderColor = '#4d90fe';
}

function input_blur(target) {
	if(!target.value) {
		target.style.borderColor = '#dd4b39';
	} else {
		target.style.borderColor = '#999';
	}
}

function autoHypenPhone(target) {
	var str = target.value;
	str = str.replace(/[^0-9]/g, "");
	var tmp = '';
	if(str.length < 4) {
		target.value = str;
		return;
	} else if(str.length < 7) {
		tmp += str.substr(0, 3);
		tmp += '-';
		tmp += str.substr(3);
		target.value = tmp;
		return;
	} else if(str.length < 11) {
		tmp += str.substr(0, 3);
		tmp += '-';
		tmp += str.substr(3, 3);
		tmp += '-';
		tmp += str.substr(6);
		target.value = tmp;
		return;
	}
	target.value = str;
	return;
}

function autoHypenBday(target) {
	var str = target.value;
	str = str.replace(/[^0-9]/g, "");
	var tmp = '';
	if(str.length < 5) {
		target.value = str;
		return;
	} else if(str.length < 7) {
		tmp += str.substr(0, 4);
		tmp += '-';
		tmp += str.substr(4);
		target.value = tmp;
		return;
	} else if(str.length < 9) {
		tmp += str.substr(0, 4);
		tmp += '-';
		tmp += str.substr(4, 2);
		tmp += '-';
		tmp += str.substr(6);
		target.value = tmp;
		return;
	}
	target.value = str;
	return;
}

function membership_save_form() {
	var form = document.forms.form_membership;
	var form_check = true;

	if(!form.membership_nameEng.value) {
		form_check = false;
		form.membership_nameEng.style.borderColor = "#dd4b39";
	}
	if(!form.membership_phone.value) {
		form_check = false;
		form.membership_phone.style.borderColor = "#dd4b39";
	}
	if(!form.membership_email.value) {
		form_check = false;
		form.membership_email.style.borderColor = "#dd4b39";
	}
	if(!form.membership_city.value) {
		form_check = false;
		form.membership_city.style.borderColor = "#dd4b39";
	}
	if(!form.membership_postal.value) {
		form_check = false;
		form.membership_postal.style.borderColor = "#dd4b39";
	}

	if(!form_check) {
		alert("Please fill out all mandatory fields (*).\n빨간색(*) 표시된 필수기입사항을 입력해주세요.");
		return false;
	} else {
		if(form.membership_phone.value.length != 12) {
			alert("Invalid Mobile Number.\n전화번호 오류");
			form.membership_phone.focus();
			form.membership_phone.style.borderColor = "#dd4b39";
			return false;
		}
		var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if(!filter.test(form.membership_email.value)) {
			alert('Invalid Email.\n이메일 오류');
			form.membership_email.focus();
			form.membership_email.style.borderColor = "#dd4b39";
			return false;
		}
		if(form.membership_postal.value.length != 6) {
			alert("Invalid Postal Code.\n우편번호 오류");
			form.membership_postal.focus();
			form.membership_postal.style.borderColor = "#dd4b39";
			return false;
		}
		if(form.membership_agree.checked != true) {
			alert("멤버쉽에 가입하려면 회원약관에 동의해야 합니다. ");
			form.membership_agree.focus();
			form.membership_agree.style.border = "1px solid #dd4b39";
			return false;
		}

		form.submit();
	}
}
</script>

<?
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
if($mode == "modify") {
	$membership_nameEng = $_POST['membership_nameEng'];
	$membership_nameKor = $_POST['membership_nameKor'];
	$membership_phone = $_POST['membership_phone'];
	$membership_email = $_POST['membership_email'];
	$membership_language = $_POST['membership_language'];
	$membership_street = $_POST['membership_street'];
	$membership_city = $_POST['membership_city'];
	$membership_postal = $_POST['membership_postal'];
	$membership_province = $_POST['membership_province'];
	$membership_bDay = $_POST['membership_bDay'];
	$membership_familyNum = $_POST['membership_familyNum'];
	$membership_agree = $_POST['membership_agree'];
	$membership_weeklyFlyer = $_POST['membership_weeklyFlyer'];
	$membership_eventFlyer = $_POST['membership_eventFlyer'];
}
?>

<form name="form_membership" action="join_membership_review.php" method="post" accept-charset="utf-8">
<div id="gray_bg" style="background: #f5f5f5; height:100%; padding:10px 0px 0px 0px;">
	<div id="white_wrapper" >
		<article>

		<div class="h1_wrapper stripped">
			<h1> Join Hannam Membership : Application </h1>
		</div>
		<div style="text-align: center; padding:10px;">
			<img src="img/join/join2.jpg" class="center">
		</div>

		<div style="width: 80%; display: table;">
			<div id="form1_wrapper" >
				<p><strong>&#9632;&nbsp;  성명</strong></p>
				<p>
					 영문 성명<em>*</em><br>
					<input class="textbox" type="text" name="membership_nameEng" placeholder="  ex. Gil Dong Hong" onkeyup="this.value=this.value.replace(/[^a-zA-Z0-9-, ]/g,'');" onFocus="input_focus(this)" onblur="input_blur(this)" value="<?=$membership_nameEng; ?>"><br>
					 한글 성명
					<input class="textbox" type="text" name="membership_nameKor" placeholder="  ex. 홍길동" onkeyup="this.value=this.value.replace(/[a-zA-Z]/g,'');" value="<?=$membership_nameKor; ?>">
				</p>
				<p>
					<strong>&#9632;&nbsp; 전화번호<em>*</em>&nbsp;&nbsp;</strong><br>
					<input class="textbox" type="text" name="membership_phone" placeholder="  ex. 604-123-4567" onkeyup="autoHypenPhone(this);" onFocus="input_focus(this)" onblur="input_blur(this)" maxlength=12 value="<?=$membership_phone; ?>">
				</p>
				<p>
					<strong>&#9632;&nbsp;이메일<em>*</em>&nbsp;&nbsp;</strong><br>
					<input class="textbox" type="text" name="membership_email" placeholder="  example@hannamsm.com" onFocus="input_focus(this)" onblur="input_blur(this)" value="<?=$membership_email; ?>">
				</p>
				<p>
					<strong>&#9632;&nbsp; 언어<em>*</em>&nbsp;&nbsp;</strong><br>
					<select class="textbox2" name="membership_language">
						<option value="KOR" <?=(($membership_language == "KOR") ? "selected" : "" ); ?>>Korean</option>
						<option value="ENG" <?=(($membership_language == "ENG") ? "selected" : "" ); ?>>English</option>
						<option value="CHI" <?=(($membership_language == "CHI") ? "selected" : "" ); ?>>Chinese</option>
					</select>
				</p>
			</div>
			<div id="form2_wrapper" >
				<p>
					<strong>&#9632;&nbsp; 현재 거주 주소&nbsp;&nbsp;</strong><br>
					<input class="textbox" type="text" name="membership_street" placeholder=" Unit# - Street Address" value="<?=$membership_street; ?>">
					<?
					mssql_select_db(BBY_DB_NAME, $conn_bby);
					$city_query = "SELECT CityName FROM tblCity ORDER BY Code";
					$city_query_result = mssql_query($city_query, $conn_bby);
					?>
					<select class="textbox2" style="width:200px;" name="membership_city">
						<? while($city_row = mssql_fetch_array($city_query_result)) { ?>
							<option value="<?=$city_row['CityName'] ?>" <?=(($membership_province == $city_row['CityName']) ? "selected" : "" ); ?>><?=$city_row['CityName'] ?></option>
						<? } ?>
						<option value="ETC" <?=(($membership_province == "ETC") ? "selected" : "" ); ?>>ETC</option>
					</select>
					<!--
					<input class="textbox" type="text" name="membership_city" placeholder=" City" style="width:160px;" onkeyup="this.value=this.value.replace(/[^a-zA-Z ]/g,'');" onFocus="input_focus(this)" onblur="input_blur(this)" value="<?=$membership_city; ?>"><em>*</em><br>
					-->
					<input class="textbox" type="text" name="membership_postal" placeholder=" Postal Code (A0A0A0)" style="width:140px;" onkeyup="this.value=this.value.replace(/[^a-zA-Z0-9]/g,'');" onFocus="input_focus(this)" onblur="input_blur(this)" maxlength=6 value="<?=$membership_postal; ?>"><em>*</em>
					<input class="textbox" type="text" value="British Columbia" disabled>
					<input type="hidden" name="membership_province" value="BC">
					<!--
					<select class="textbox" style="width:200px;" name="membership_province">
						<option value="01" <?=(($membership_province == "01") ? "selected" : "" ); ?>>British Columbia</option>
						<option value="09" <?=(($membership_province == "09") ? "selected" : "" ); ?>>Alberta</option>
						<option value="08" <?=(($membership_province == "08") ? "selected" : "" ); ?>>Saskatchewan</option>
						<option value="05" <?=(($membership_province == "05") ? "selected" : "" ); ?>>Manitoba</option>
						<option value="06" <?=(($membership_province == "06") ? "selected" : "" ); ?>>Ontario</option>
						<option value="02" <?=(($membership_province == "02") ? "selected" : "" ); ?>>Quebec</option>
						<option value="04" <?=(($membership_province == "04") ? "selected" : "" ); ?>>New Brunswick</option>
						<option value="03" <?=(($membership_province == "03") ? "selected" : "" ); ?>>Nova Scotia</option>
						<option value="10" <?=(($membership_province == "10") ? "selected" : "" ); ?>>Newfoundland and Labrador</option>
						<option value="07" <?=(($membership_province == "07") ? "selected" : "" ); ?>>Prince Edward Island</option>
						<option value="11" <?=(($membership_province == "11") ? "selected" : "" ); ?>>Northwest Territories</option>
						<option value="12" <?=(($membership_province == "12") ? "selected" : "" ); ?>>Yukon</option>
						<option value="13" <?=(($membership_province == "13") ? "selected" : "" ); ?>>Nunavut</option>
					</select>
					-->
				</p>
				<p>
					<strong>&#9632;&nbsp;생년월일&nbsp;&nbsp;</strong><br>
					<input class="textbox" type="text" name="membership_bDay" placeholder="  YYYY-MM-DD" onkeyup="autoHypenBday(this);" maxlength=10 value="<?=$membership_bDay; ?>"><br>
					생일날 쇼핑시 포인트가 두배로 적립됩니다.
				</p> 

				<p>
					<strong>&#9632;&nbsp; 가족수&nbsp;&nbsp;</strong><br>
					<select class="textbox" name="membership_familyNum">
						<option value=1>1</option>
						<option value=2>2</option>
						<option value=3>3</option>
						<option value=4>4</option>
						<option value=5>5+</option>
					</select>
					<!--
					<input class="textbox" type="text" name="membership_familyNum" placeholder="  ex. 4" onkeyup="this.value=this.value.replace(/[^0-9-]/g,'');" onkeypress='return ((event.charCode >= 48 && event.charCode <= 57))' value="<?=$membership_familyNum; ?>"><br><br>
					-->
				</p>
				<p>
					<em>빨간색(*)표시는 필수 기입사항 입니다.</em><br>
					
				</p>
			</div>
		</div>

		<div id="form3_wrapper" style="margin:30px 70px;">
			<textarea name="mAgreeText" cols=90 rows=10 class="simpleform" style="padding:20px;width:590px; height:240px;">
				<?php include 'agreement.php'; ?>
			</textarea> 

			<p style="font-size:14px;">
				<input type="checkbox" name="membership_agree" <?=(($membership_agree) ? "checked" : "" ); ?>> 회원 약관 및 개인정보 수집과 이용에 대한 원칙을 다 읽어 보았으며, 위의 사항에 동의합니다.<em>*</em><br>
				<input type="checkbox" name="membership_weeklyFlyer" <?=(($membership_weeklyFlyer) ? "checked" : "" ); ?>> 이 이메일 주소로 매주 금요일 발행되는 [주간세일광고] 정보를 받는데에 동의합니다.(옵션)<br>
				<input type="checkbox" name="membership_eventFlyer" <?=(($membership_eventFlyer) ? "checked" : "" ); ?>> 이 이메일 주소로 다양한 행사 및 이벤트등의 쇼핑정보를 받는데에 동의합니다.(옵션)<br>
				<!--중복등록 방지<em>*</em>  <input class="textbox" type="text" style="width:30px;"> = 97 왼쪽에 보이는 숫자를 입력하세요.-->
			</p>
		</div>

		<button type="button" class="btn" style="margin:0 0 0 600px; cursor:pointer;" onClick="membership_save_form()">Next</button>
		</article>
	</div><!-- white_wrapper  -->
</div><!-- gray bg  -->
</form>

<?php include 'footer.php'; ?>