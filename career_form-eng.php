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
	if(!form.career_phoneHome.value) {
		form_check = false;
		form.career_phoneHome.style.borderColor = "#dd4b39";
	}
	if(!form.career_phoneMobile.value) {
		form_check = false;
		form.career_phoneMobile.style.borderColor = "#dd4b39";
	}
	if(!form.career_email.value) {
		form_check = false;
		form.career_email.style.borderColor = "#dd4b39";
	}
	if(!form.career_city.value) {
		form_check = false;
		form.career_city.style.borderColor = "#dd4b39";
	}
	if(!form.career_postal.value) {
		form_check = false;
		form.career_postal.style.borderColor = "#dd4b39";
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
		if(form.career_phoneHome.value.length != 12) {
			alert("Invalid Home Number.");
			form.career_phoneHome.focus();
			form.career_phoneHome.style.borderColor = "#dd4b39";
			return false;
		}
		if(form.career_phoneMobile.value.length != 12) {
			alert("Invalid Mobile Number.");
			form.career_phoneMobile.focus();
			form.career_phoneMobile.style.borderColor = "#dd4b39";
			return false;
		}
		var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if(!filter.test(form.career_email.value)) {
			alert('Invalid Email.');
			form.career_email.focus();
			form.career_email.style.borderColor = "#dd4b39";
			return false;
		}
		if(form.career_postal.value.length != 6) {
			alert("Invalid Postal Code.");
			form.career_postal.focus();
			form.career_postal.style.borderColor = "#dd4b39";
			return false;
		}
		if(!work_hour_check) {
			alert("Please check one of the options in Prefered work hours");
			return false;
		}

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
	$career_gender = $_POST['career_gender'];
	$career_phoneHome = $_POST['career_phoneHome'];
	$career_phoneMobile = $_POST['career_phoneMobile'];
	$career_email = $_POST['career_email'];
	$career_address = $_POST['career_address'];
	$career_city = $_POST['career_city'];
	$career_postal = $_POST['career_postal'];
	$career_province = $_POST['career_province'];
	$career_workHour = (($_POST['career_workHour']) ? $_POST['career_workHour'] : array());
	$career_workField = (($_POST['career_workField']) ? $_POST['career_workField'] : array());
	$career_prevWork = $_POST['career_prevWork'];
	$career_prevWorkMonth = $_POST['career_prevWorkMonth'];
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
	echo "career_phoneHome - ".$career_phoneHome."<br>";
	echo "career_phoneMobile - ".$career_phoneMobile."<br>";
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
				<em style="font-size:13px;line-height:16px;">
				Red marks(<em>*</em>)are mandatory fields.</em>

				<p><strong>&#9632;&nbsp; Name </strong></p>
				<p>
					Legal Name <em>*</em>
					<input name="career_nameE" class="textbox" type="text" placeholder="  ex. Gil Dong Hong" onkeyup="this.value=this.value.replace(/[^a-zA-Z0-9-, ]/g,'');" onFocus="input_focus(this)" onblur="input_blur(this)" value="<?=$career_nameE; ?>" required>
					Korean Name
					<input name="career_nameK" class="textbox" type="text" placeholder="  ex. 홍길동" onkeyup="this.value=this.value.replace(/[a-zA-Z]/g,'');" value="<?=$career_nameK; ?>">
				</p>
				<br><br>

				<p><strong>&#9632;&nbsp; Gender </strong></p>
				<input type="radio" name="career_gender" value="Male" style="vertical-align:middle;" <?=(($career_gender == "Male") ? "checked" : "" ); ?>> Male <br><br>
				<input type="radio" name="career_gender" value="Female" style="vertical-align:middle;" <?=(($career_gender == "Female") ? "checked" : "" ); ?>> Female 
				<br><br><br>

				<p>
					<strong>&#9632;&nbsp; Home Number 
					</strong><em>*</em>&nbsp;&nbsp;<br>
					<input id="career_phoneHome" name="career_phoneHome" class="textbox" type="text"  placeholder="  ex. 604-123-4567" onkeyup="autoHypenPhone(this);"  onFocus="input_focus(this)" onblur="input_blur(this)" maxlength=12 value="<?=$career_phoneHome; ?>" required>
				</p>
				<br><br>

				<p>
					<strong>&#9632;&nbsp; Mobile Number
					</strong><em>*</em>&nbsp;&nbsp;<br>
					<input name="career_phoneMobile" class="textbox" type="text"  placeholder="  ex. 604-123-4567" onkeyup="autoHypenPhone(this);" onFocus="input_focus(this)" onblur="input_blur(this)" maxlength=12 value="<?=$career_phoneMobile; ?>" required>
				</p>
				<br><br>

				<p>
					<strong>&#9632;&nbsp; Email 
					</strong><em>*</em>&nbsp;&nbsp;<br>
					<input name="career_email" class="textbox" type="text" placeholder="  example@hannamsm.com" onFocus="input_focus(this)" onblur="input_blur(this)" value="<?=$career_email; ?>" required>
				</p>
				<br><br>

				<p>
					<strong>&#9632;&nbsp; Current Address  - city</strong><em>*</em><br>
					<input name="career_address" class="textbox" type="text" placeholder=" Unit# - Street Address" value="<?=$career_address; ?>">
					<input name="career_city" class="textbox" type="text"  placeholder=" City*" style="width:160px;" onkeyup="this.value=this.value.replace(/[^a-zA-Z ]/g,'');" onFocus="input_focus(this)" onblur="input_blur(this)" value="<?=$career_city; ?>" required><em>*</em><br>
					<input name="career_postal" class="textbox" type="text"  placeholder=" Postal Code(A0A0A0)*" style="width:140px;" onkeyup="this.value=this.value.replace(/[^a-zA-Z0-9]/g,'');" onFocus="input_focus(this)" onblur="input_blur(this)" maxlength=6 value="<?=$career_postal; ?>" required><em>*</em>
					<select name="career_province" class="textbox" style="width:200px;">
						<option value=""> - Province - </option>
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

				<p><strong>&#9632;&nbsp; Prefered work hours 
				</strong><em>*</em><br></p>
				<input name="career_workHour[]" type="checkbox" style="vertical-align:middle;" value="Full" <?=((in_array("Full", $career_workHour)) ? "checked" : "" ); ?>> Full time<br><br>
				<input name="career_workHour[]" type="checkbox" style="vertical-align:middle;" value="Part" <?=((in_array("Part", $career_workHour)) ? "checked" : "" ); ?>> Part time
				<br><br><br>

				<p><strong>&#9632;&nbsp; Areas you want to work 
				</strong><br></p>
				<input name="career_workField[]" type="checkbox" style="vertical-align:middle;" value="Grocery" <?=((in_array("Grocery", $career_workField)) ? "checked" : "" ); ?>> Grocery <br><br>
				<input name="career_workField[]" type="checkbox" style="vertical-align:middle;" value="Meat" <?=((in_array("Meat", $career_workField)) ? "checked" : "" ); ?>> Meat <br><br>
				<input name="career_workField[]" type="checkbox" style="vertical-align:middle;" value="Produce" <?=((in_array("Produce", $career_workField)) ? "checked" : "" ); ?>> Produce <br><br>
				<input name="career_workField[]" type="checkbox" style="vertical-align:middle;" value="Hareware" <?=((in_array("Hareware", $career_workField)) ? "checked" : "" ); ?>> Hareware <br><br>
				<input name="career_workField[]" type="checkbox" style="vertical-align:middle;" value="Deli" <?=((in_array("Deli", $career_workField)) ? "checked" : "" ); ?>> Deli <br><br>
				<input name="career_workField[]" type="checkbox" style="vertical-align:middle;" value="Customer Service" <?=((in_array("Customer Service", $career_workField)) ? "checked" : "" ); ?>> Customer Service <br><br>
				<input name="career_workField[]" type="checkbox" style="vertical-align:middle;" value="Office" <?=((in_array("Office", $career_workField)) ? "checked" : "" ); ?>> Office <br><br>
				<input name="career_workField[]" type="checkbox" style="vertical-align:middle;" value="Maintenance" <?=((in_array("Maintenance", $career_workField)) ? "checked" : "" ); ?>> Maintenance <br><br>
				<input name="career_workField[]" type="checkbox" style="vertical-align:middle;" value="Others" <?=((in_array("Others", $career_workField)) ? "checked" : "" ); ?>> Others <br><br>
				<br>

				<p><strong>&#9632;&nbsp; Work Experience </strong><br></p>
				Type of work <br>
				<input name="career_prevWork" class="textbox" type="text"  placeholder="  ex. Grocery Store" style="width:200px; margin-top:3px;" value="<?=$career_prevWork; ?>">
				<br><br>
				How long? (in months)  <br>
				<input name="career_prevWorkMonth" class="textbox" type="text"  placeholder="  ex. 5" style="width:50px;margin-top:3px;margin-right:3px;" onkeyup="this.value=this.value.replace(/[^0-9-]/g,'');" onkeypress='return ((event.charCode >= 48 && event.charCode <= 57))' value="<?=$career_prevWorkMonth; ?>">months<br><br>
				<input name="career_workPastYear" type="checkbox" style="vertical-align:middle;" <?=(($career_workPastYear) ? "checked" : "" ); ?>><span style="line-height:200%;"> Check if you have work experience in the past year. </span><br><br>
				<br>

				<p><strong>&#9632;&nbsp; Upload Coverletter(1pg.) <br></p></strong><br>
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

				<p><strong>&#9632;&nbsp; Upload resume(1pg.) <br></p></strong><br>
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

			<div style="margin-left:220px;">
				<button type="button" class="btn" onclick="location.replace('career.php')">Cancel</button>
				<button type="button" class="btn" onclick="career_save_form()">Next</button>
			</div>
		</article>
	</div><!-- white_wrapper  -->
</div><!-- gray bg  -->

<?php include 'footer.php'; ?>