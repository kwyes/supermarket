<?php include 'header.php'; ?>

<style>
<?php include 'css/career_form.css'; ?>
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
	} else {				
		tmp += str.substr(0, 3);
		tmp += '-';
		tmp += str.substr(3, 4);
		tmp += '-';
		tmp += str.substr(7);
		target.value = tmp;
		return;
	}
	target.value = str;
	return;
}

function allCheckNCancel(type) {
	if(type == "workHour") {
		var checkBoxAll = document.getElementById("all_workHour");
		var allCheckBox = document.getElementsByName('career_workHour[]');
	}
	if(type == "workField") {
		var checkBoxAll = document.getElementById("all_workField");
		var allCheckBox = document.getElementsByName('career_workField[]');
	}

	if(checkBoxAll == null || checkBoxAll == undefined || allCheckBox == null || allCheckBox == undefined)
		return;

	for(var i = 0; i < allCheckBox.length; i++) {
		if(checkBoxAll.checked)
			allCheckBox[i].checked = true; 
		else
			allCheckBox[i].checked = false; 
	}
}

function CheckNCancel(type) {
	if(type == "workHour") {
		var checkBoxAll = document.getElementById("all_workHour");
		var allCheckBox = document.getElementsByName('career_workHour[]');
	}
	if(type == "workField") {
		var checkBoxAll = document.getElementById("all_workField");
		var allCheckBox = document.getElementsByName('career_workField[]');
	}

	if(checkBoxAll == null || checkBoxAll == undefined || allCheckBox == null || allCheckBox == undefined)
		return;

	var allChecked = true;
	for(var i = 0; i < allCheckBox.length; i++) {
		if(!allCheckBox[i].checked)
			allChecked = false;
	}

	if(!checkBoxAll.checked && allChecked)
		checkBoxAll.checked = true;
	else
		checkBoxAll.checked = false;
}

function delete_file(type, file) {
	if(type == "coverLetter") {
		document.forms.form_career.delete_coverLetter.value = file;
		document.getElementById("coverLetter_wrapper").innerHTML = '<input name="career_coverLetter" type="file" name="fileField">';
	}
	if(type == "resume") {
		document.forms.form_career.delete_resume.value = file;
		document.getElementById("resume_wrapper").innerHTML = '<input name="career_resume" type="file" name="fileField">';
	}
}

function career_save_form() {
	var form = document.forms.form_career;
	var form_check = true;

	if(!form.career_nameE.value) {
		form_check = false;
		form.career_nameE.style.borderColor = "#dd4b39";
	}
	if(!form.career_phone1.value) {
		form_check = false;
		form.career_phone1.style.borderColor = "#dd4b39";
	}
	if(!form.career_email.value) {
		form_check = false;
		form.career_email.style.borderColor = "#dd4b39";
	}
	if(!form.career_city.value) {
		form_check = false;
		form.career_city.style.borderColor = "#dd4b39";
	}
	var work_hour = document.getElementsByName('career_workHour[]');
	var work_hour_check = false;
	for(var i = 0; i < work_hour.length; i++) {
		if(work_hour[i].checked) {
			var work_hour_check = true;
			break;
		}
	}
	if(!work_hour_check) {
		form_check = false;
	}

	if(!form_check) {
		alert("Please fill out all mandatory fields.");
		return false;
	} else {
		if(form.career_phone1.value.length != 12) {
			alert("Invalid Phone Number 1.");
			form.career_phone1.focus();
			form.career_phone1.style.borderColor = "#dd4b39";
			return false;
		}
		var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if(!filter.test(form.career_email.value)) {
			alert('Invalid Email.');
			form.career_email.focus();
			form.career_email.style.borderColor = "#dd4b39";
			return false;
		}
		if(!work_hour_check) {
			alert("Please check one of the options in Prefered work hours");
			return false;
		}

		document.getElementById("submit_btn_div").innerHTML = '<button class="btn2">&nbsp;<img src="img/ajax-loader.gif" style=""></button>';
		form.submit();
	}
}
</script>

<?
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
$career_workHour = array();
$career_workField = array();

if($mode == "modify") {
	$upload_path = "upload/career/temp/";

	$career_nameE = $_POST['career_nameE'];
	$career_nameK = $_POST['career_nameK'];
	//$career_gender = $_POST['career_gender'];
	$career_phone1 = $_POST['career_phone1'];
	$career_phone2 = $_POST['career_phone2'];
	$career_email = $_POST['career_email'];
	//$career_address = $_POST['career_address'];
	$career_city = $_POST['career_city'];
	//$career_postal = $_POST['career_postal'];
	$career_province = $_POST['career_province'];
	$career_workHour = (($_POST['career_workHour']) ? $_POST['career_workHour'] : array());
	$career_workField = (($_POST['career_workField']) ? $_POST['career_workField'] : array());
	//$career_prevWork = $_POST['career_prevWork'];
	//$career_prevWorkMonth = $_POST['career_prevWorkMonth'];
	$career_workPastYear = $_POST['career_workPastYear'];
	$old_coverLetter = $_POST['old_coverLetter'];
	$temp_coverLetter = $_POST['temp_coverLetter'];
	$old_resume = $_POST['old_resume'];
	$temp_resume = $_POST['temp_resume'];
	$delete_files = $_POST['delete_files'];

	/*
	echo "career_nameE - ".$career_nameE."<br>";
	echo "career_nameK - ".$career_nameK."<br>";
	echo "career_gender - ".$career_gender."<br>";
	echo "career_phone1 - ".$career_phone1."<br>";
	echo "career_phone2 - ".$career_phone2."<br>";
	echo "career_email - ".$career_email."<br>";
	echo "career_address - ".$career_address."<br>";
	echo "career_city - ".$career_city."<br>";
	echo "career_postal - ".$career_postal."<br>";
	echo "career_province - ".$career_province."<br>";
	echo "career_workHour - ".var_dump($career_workHour)."<br>";
	echo "career_workField - ".var_dump($career_workField)."<br>";
	echo "career_prevWork - ".$career_prevWork."<br>";
	echo "career_prevWorkMonth - ".$career_prevWorkMonth."<br>";
	echo "career_workPastYear - ".$career_workPastYear."<br>";
	echo "old_coverLetter - ".$old_coverLetter."<br>";
	echo "temp_coverLetter - ".$temp_coverLetter."<br>";
	echo "old_resume - ".$old_resume."<br>";
	echo "temp_resume - ".$temp_resume."<br>";
	*/
}
?>

<div id="gray_bg" style="background: #f5f5f5; height:100%; padding:10px 0px 0px 0px;">
	<div id="white_wrapper" >
		<article>
			<div class="h1_wrapper stripped">
				<h1> Career at Hannam : Application </h1>
			</div>

			<form name="form_career" action="career_review.php" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<input type="hidden" name="delete_coverLetter">
			<input type="hidden" name="delete_resume">
			<div id="form_wrapper" >
				<em style="font-size:13px;line-height:16px;">빨간색(*)표시는 필수 기입사항 입니다.<br>
				Red marks(<em>*</em>)are mandatory fields.</em>

				<p><strong>&#9632;&nbsp; Name / 성명</strong></p>
				<p>
					Legal Name / 영문 성명<em>*</em>
					<input name="career_nameE" class="textbox" type="text" placeholder="  ex. Gil Dong Hong" onkeyup="this.value=this.value.replace(/[^a-zA-Z0-9-, ]/g,'');" onFocus="input_focus(this)" onblur="input_blur(this)" value="<?=$career_nameE; ?>" required>
					Korean Name / 한글 성명
					<input name="career_nameK" class="textbox" type="text" placeholder="  ex. 홍길동" value="<?=$career_nameK; ?>">
				</p>
				<br><br>

				<p>
					<strong>&#9632;&nbsp; Phone Number 1 / 전화번호 1</strong><em>*</em>&nbsp;&nbsp;<br>
					<input name="career_phone1" class="textbox" type="text"  placeholder="  ex. 604-123-4567" onkeyup="autoHypenPhone(this);"  onFocus="input_focus(this)" onblur="input_blur(this)" maxlength=12 value="<?=$career_phone1; ?>" required>
				</p>
				<br><br>

				<p>
					<strong>&#9632;&nbsp; Phone Number 2 / 전화번호 2</strong> (optional)<br>
					<input name="career_phone2" class="textbox" type="text"  placeholder="  ex. 604-123-4567" onkeyup="autoHypenPhone(this);" onFocus="input_focus(this)" onblur="input_blur(this)" maxlength=12 value="<?=$career_phone2; ?>" required>
				</p>
				<br><br>

				<p>
					<strong>&#9632;&nbsp; Email / 이메일</strong><em>*</em>&nbsp;&nbsp;<br>
					<input name="career_email" class="textbox" type="text" placeholder="  example@hannamsm.com" onFocus="input_focus(this)" onblur="input_blur(this)" value="<?=$career_email; ?>" required>
				</p>
				<br><br>

				<p>
					<strong>&#9632;&nbsp; Current Address / 현재 거주 주소</strong><em>*</em><br>
					<!--<input name="career_address" class="textbox" type="text" placeholder=" Unit# - Street Address" value="<?=$career_address; ?>">-->
					<input name="career_city" class="textbox" type="text"  placeholder=" City*" style="width:160px;" onkeyup="this.value=this.value.replace(/[^a-zA-Z ]/g,'');" onFocus="input_focus(this)" onblur="input_blur(this)" value="<?=$career_city; ?>" required><em>*</em><br>
					<!--<input name="career_postal" class="textbox" type="text"  placeholder=" Postal Code(A0A0A0)*" style="width:140px;" onkeyup="this.value=this.value.replace(/[^a-zA-Z0-9]/g,'');" onFocus="input_focus(this)" onblur="input_blur(this)" maxlength=6 value="<?=$career_postal; ?>" required><em>*</em>-->
					<select name="career_province" class="textbox" style="width:200px;">
						<option value="BC" <?=(($career_province == "BC") ? "selected" : "" ); ?>>British Columbia</option>
						<option value="AB" <?=(($career_province == "AB") ? "selected" : "" ); ?>>Alberta</option>
						<option value="SK" <?=(($career_province == "SK") ? "selected" : "" ); ?>>Saskatchewan</option>
						<option value="MB" <?=(($career_province == "MB") ? "selected" : "" ); ?>>Manitoba</option>
						<option value="ON" <?=(($career_province == "ON") ? "selected" : "" ); ?>>Ontario</option>
						<option value="QC" <?=(($career_province == "QC") ? "selected" : "" ); ?>>Quebec</option>
						<option value="NB" <?=(($career_province == "NB") ? "selected" : "" ); ?>>New Brunswick</option>
						<option value="PE" <?=(($career_province == "NS") ? "selected" : "" ); ?>>Nova Scotia</option>
						<option value="NL" <?=(($career_province == "NL") ? "selected" : "" ); ?>>Newfoundland and Labrador</option>
						<option value="PE" <?=(($career_province == "PE") ? "selected" : "" ); ?>>Prince Edward Island</option>
						<option value="11" <?=(($career_province == "NT") ? "selected" : "" ); ?>>Northwest Territories</option>
						<option value="12" <?=(($career_province == "YT") ? "selected" : "" ); ?>>Yukon</option>
						<option value="13" <?=(($career_province == "NU") ? "selected" : "" ); ?>>Nunavut</option>
					</select>
				</p>
				<br><br>

				<p><strong>&#9632;&nbsp; Preferred work hours / 희망근무시간</strong><em>*</em><br></p>
				<input id="all_workHour" type="checkbox" style="vertical-align:middle;" onClick="allCheckNCancel('workHour')"> Anytime (Full & Part)<br><br>
				<input name="career_workHour[]" type="checkbox" style="vertical-align:middle;" value="Full" onClick="CheckNCancel('workHour')" <?=((in_array("Full", $career_workHour)) ? "checked" : "" ); ?>> Full time<br><br>
				<input name="career_workHour[]" type="checkbox" style="vertical-align:middle;" value="Part" onClick="CheckNCancel('workHour')" <?=((in_array("Part", $career_workHour)) ? "checked" : "" ); ?>> Part time
				<br><br><br>

				<p><strong>&#9632;&nbsp; Areas you want to work / 희망근무분야</strong><br></p>
				<input id="all_workField" type="checkbox" style="vertical-align:middle;" onClick="allCheckNCancel('workField')"> Any Department<br><br>
				<input name="career_workField[]" type="checkbox" style="vertical-align:middle;" value="Grocery" onClick="CheckNCancel('workField')" <?=((in_array("Grocery", $career_workField)) ? "checked" : "" ); ?>> Grocery / 그로서리<br><br>
				<input name="career_workField[]" type="checkbox" style="vertical-align:middle;" value="Meat" onClick="CheckNCancel('workField')" <?=((in_array("Meat", $career_workField)) ? "checked" : "" ); ?>> Meat / 정육<br><br>
				<input name="career_workField[]" type="checkbox" style="vertical-align:middle;" value="Seefood" onClick="CheckNCancel('workField')" <?=((in_array("Seefood", $career_workField)) ? "checked" : "" ); ?>> Seafood / 수산<br><br>
				<input name="career_workField[]" type="checkbox" style="vertical-align:middle;" value="Produce" onClick="CheckNCancel('workField')" <?=((in_array("Produce", $career_workField)) ? "checked" : "" ); ?>> Produce / 야채/과일<br><br>
				<input name="career_workField[]" type="checkbox" style="vertical-align:middle;" value="Hareware" onClick="CheckNCancel('workField')" <?=((in_array("Hareware", $career_workField)) ? "checked" : "" ); ?>> Hareware / 하드웨어<br><br>
				<input name="career_workField[]" type="checkbox" style="vertical-align:middle;" value="Deli" onClick="CheckNCancel('workField')" <?=((in_array("Deli", $career_workField)) ? "checked" : "" ); ?>> Deli / 반찬<br><br>
				<input name="career_workField[]" type="checkbox" style="vertical-align:middle;" value="Customer Service" onClick="CheckNCancel('workField')" <?=((in_array("Customer Service", $career_workField)) ? "checked" : "" ); ?>> Customer Service / 고객지원<br><br>
				<input name="career_workField[]" type="checkbox" style="vertical-align:middle;" value="Office" onClick="CheckNCancel('workField')" <?=((in_array("Office", $career_workField)) ? "checked" : "" ); ?>> Office 오피스 / 매장업무총괄지원<br><br>
				<input name="career_workField[]" type="checkbox" style="vertical-align:middle;" value="Maintenance" onClick="CheckNCancel('workField')" <?=((in_array("Maintenance", $career_workField)) ? "checked" : "" ); ?>> Maintenance / 시설(유지/관리/보수)<br><br>
				<input name="career_workField[]" type="checkbox" style="vertical-align:middle;" value="Others" onClick="CheckNCancel('workField')" <?=((in_array("Others", $career_workField)) ? "checked" : "" ); ?>> Others / 기타분야<br><br>
				<br>

				<p><strong>&#9632;&nbsp; Work Experience / 근무경력</strong><br></p>
				<!--
				Type of work / 관련분야<br>
				<input name="career_prevWork" class="textbox" type="text"  placeholder="  ex. Grocery Store" style="width:200px; margin-top:3px;" value="<?=$career_prevWork; ?>">
				<br><br>
				How long? (in months) / 근무기간(개월) <br>
				<input name="career_prevWorkMonth" class="textbox" type="text"  placeholder="  ex. 5" style="width:50px;margin-top:3px;margin-right:3px;" onkeyup="this.value=this.value.replace(/[^0-9-]/g,'');" onkeypress='return ((event.charCode >= 48 && event.charCode <= 57))' value="<?=$career_prevWorkMonth; ?>">months/ 개월<br><br>
				-->
				<input name="career_workPastYear" type="checkbox" style="vertical-align:middle;" <?=(($career_workPastYear) ? "checked" : "" ); ?>><span style="line-height:200%;"> Check if you have work experience in food industry, distribution, retail store in the past 3 years. / 나는 최근 3년이내에 식품, 유통, 매장 관련 업무 경력이 있습니다.</span><br><br>
				<br>

				<p><strong>&#9632;&nbsp; Upload Coverletter(1pg.) <br> 자기소개서 첨부(1페이지)<br></p></strong><br>
				<div id="coverLetter_wrapper">
					<? if($old_coverLetter) { ?>
						<a href="<?=$upload_path.Br_iconv($temp_coverLetter); ?>" target="_blank"><?=$old_coverLetter; ?></a>
						<img src="img/admin/bt_del.gif" style="cursor:pointer;" onClick="delete_file('coverLetter', '<?=$temp_coverLetter; ?>')">
						<input type="hidden" name="old_coverLetter" value="<?=$old_coverLetter; ?>">
						<input type="hidden" name="temp_coverLetter" value="<?=$temp_coverLetter; ?>">
					<? } else { ?>
						<input name="career_coverLetter" type="file" name="fileField">
					<? } ?>
				</div>
				<br><br><br>

				<p><strong>&#9632;&nbsp; Upload resume(1pg.) <br> 이력서 첨부(1페이지)<br></p></strong><br>
				<div id="resume_wrapper">
					<? if($old_resume) { ?>
						<a href="<?=$upload_path.Br_iconv($temp_resume); ?>" target="_blank"><?=$old_resume; ?></a>
						<img src="img/admin/bt_del.gif" style="cursor:pointer;" onClick="delete_file('resume', '<?=$temp_resume; ?>')">
						<input type="hidden" name="old_resume" value="<?=$old_resume; ?>">
						<input type="hidden" name="temp_resume" value="<?=$temp_resume; ?>">
					<? } else { ?>
						<input name="career_resume" type="file" name="fileField"><br>
					<? } ?>
				</div>
			</div>
			</form>

			<div id="submit_btn_div" style="padding-left:250px;"><button type="button" class="btn" style="cursor:pointer" onclick="career_save_form()">Next</button></div>
		</article>
	</div><!-- white_wrapper  -->
</div><!-- gray bg  -->

<?php include 'footer.php'; ?>