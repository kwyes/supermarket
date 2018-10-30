<?php include 'header.php'; ?>

<style>
<?php include 'css/career_form.css'; ?>
<?php include 'css/footer.css'; ?>
</style>

<link href='http://fonts.googleapis.com/css?family=Chango' rel='stylesheet' type='text/css'>

<script>
function career_submit() {
	var form = document.forms.form_career_review;

	form.action = "admin/customer_handler.php";
	var answer = confirm("Submit the application?");
	if(answer) {
		form.mode.value = "career";
		form.submit();
	}
}

function career_modify() {
	var form = document.forms.form_career_review;

	form.action = "career_form.php";
	var answer = confirm("Modify the application?");
	if(answer) {
		form.mode.value = "modify";
		form.submit();
	}
}
</script>

<?
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
$career_workHour = $_POST['career_workHour'];
$career_workField = $_POST['career_workField'];
//$career_prevWork = $_POST['career_prevWork'];
//$career_prevWorkMonth = $_POST['career_prevWorkMonth'];
$career_workPastYear = $_POST['career_workPastYear'];
$delete_coverLetter = $_POST['delete_coverLetter'];
$delete_resume = $_POST['delete_resume'];

// Saving cover letter into temp folder
if($_FILES['career_coverLetter']) {
	if($_FILES['career_coverLetter']['error'] == 0) {
		do {
			$new_filename = rand()."_".Br_dconv($_FILES['career_coverLetter']['name']);
			$new_fullpath = $upload_path.$new_filename;
			$exist = true;

			if(!file_exists($new_fullpath)) {
				$old_coverLetter = $_FILES['career_coverLetter']['name'];
				$temp_coverLetter = $new_filename;
				move_uploaded_file($_FILES['career_coverLetter']['tmp_name'], $new_fullpath);
				$exist = false;

			}
		} while($exist);
	}
} else if($_POST['temp_coverLetter']) {
	$old_coverLetter = $_POST['old_coverLetter'];
	$temp_coverLetter = $_POST['temp_coverLetter'];
}

// Saving resume into temp folder
if($_FILES['career_resume']) {
	if($_FILES['career_resume']['error'] == 0) {
		do {
			$new_filename = rand()."_".Br_dconv($_FILES['career_resume']['name']);
			$new_fullpath = $upload_path.$new_filename;
			$exist = true;

			if(!file_exists($new_fullpath)) {
				$old_resume = $_FILES['career_resume']['name'];
				$temp_resume = $new_filename;
				move_uploaded_file($_FILES['career_resume']['tmp_name'], $new_fullpath);
				$exist = false;
			}
		} while($exist);
	}
} else if($_POST['temp_resume']) {
	$old_resume = $_POST['old_resume'];
	$temp_resume = $_POST['temp_resume'];
}

// Deleting cover letter from temp folder
if($delete_coverLetter) {
	$fullpath = $upload_path.$delete_coverLetter;
	unlink($fullpath);
}

// Deleting resume from temp folder
if($delete_resume) {
	$fullpath = $upload_path.$delete_resume;
	unlink($fullpath);
}

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
echo "delete_files - ".$delete_files."<br>";
*/
?>

<div id="gray_bg" style="background: #f5f5f5; height:100%; padding:10px 0px 0px 0px;">
	<div id="white_wrapper" >
		<article>
			<div class="h1_wrapper stripped">
				<h1>Career at Hannam : Application </h1>
			</div>

			<form name="form_career_review" action="" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<input type="hidden" name="mode">
				<input type="hidden" name="career_nameE" value="<?=$career_nameE; ?>">
				<input type="hidden" name="career_nameK" value="<?=$career_nameK; ?>">
				<!--<input type="hidden" name="career_gender" value="<?=$career_gender; ?>">-->
				<input type="hidden" name="career_phone1" value="<?=$career_phone1; ?>">
				<input type="hidden" name="career_phone2" value="<?=$career_phone2; ?>">
				<input type="hidden" name="career_email" value="<?=$career_email; ?>">
				<!--<input type="hidden" name="career_address" value="<?=$career_address; ?>">-->
				<input type="hidden" name="career_city" value="<?=$career_city; ?>">
				<!--<input type="hidden" name="career_postal" value="<?=$career_postal; ?>">-->
				<input type="hidden" name="career_province" value="<?=$career_province; ?>">
				<!--<input type="hidden" name="career_prevWork" value="<?=$career_prevWork; ?>">-->
				<!--<input type="hidden" name="career_prevWorkMonth" value="<?=$career_prevWorkMonth; ?>">-->
				<input type="hidden" name="career_workPastYear" value="<?=$career_workPastYear; ?>">
				<input type="hidden" name="old_coverLetter" value="<?=$old_coverLetter; ?>">
				<input type="hidden" name="temp_coverLetter" value="<?=$temp_coverLetter; ?>">
				<input type="hidden" name="old_resume" value="<?=$old_resume; ?>">
				<input type="hidden" name="temp_resume" value="<?=$temp_resume; ?>">
				<? for($i = 0; $i < sizeof($career_workHour); $i++) { ?>
					<input type="hidden" name="career_workHour[]" value="<?=$career_workHour[$i]; ?>">
				<? } ?>
				<? for($i = 0; $i < sizeof($career_workField); $i++) { ?>
					<input type="hidden" name="career_workField[]" value="<?=$career_workField[$i]; ?>">
				<? } ?>
			</form>

			<div style="text-align: left; padding:10px;">
				<table class="career_review" cellspacing=0 cellpadding=0>
					<tr>
						<td class="career_title" colspan=2>Personal Information / 개인정보</td>
					</tr>
					<tr>
						<td class="career_subject"><strong>Legal Name <br> (영문 성명)</strong></td>
						<td class="career_content"><?=$career_nameE; ?></td>
					</tr>
					<tr>
						<td class="career_subject"><strong>Korean Name <br> (한글 성명)</strong></td>
						<td class="career_content"><?=$career_nameK; ?></td>
					</tr>
					<!--
					<tr>
						<td class="career_subject"><strong>Gender <br> (성별)</strong></td>
						<td class="career_content"><?=$career_gender; ?></td>
					</tr>
					-->
					<tr>
						<td class="career_subject"><strong>Phone Number 1 <br> (전화번호 1)</strong></td>
						<td class="career_content"><?=$career_phone1; ?></td>
					</tr>
					<tr>
						<td class="career_subject"><strong>Phone Number 2 <br> (전화번호 2)</strong></td>
						<td class="career_content"><?=$career_phone2; ?></td>
					</tr>
					<tr>
						<td class="career_subject"><strong>Email <br> (이메일)</strong></td>
						<td class="career_content"><?=$career_email; ?></td>
					</tr>
					<tr>
						<td class="career_subject"><strong>Current Address <br> (현재 거주 주소)</strong></td>
						<td class="career_content">
							<?
							//if($career_address)		echo $career_address.", ";
							if($career_city)		echo $career_city.", ";
							//if($career_postal)		echo $career_postal.", ";
							if($career_province)	echo $career_province;
							?>
						</td>
					</tr>
					<tr>
						<td class="career_subject"><strong>Preferred work hours <br> (희망근무시간)</strong></td>
						<td class="career_content">
							<? 
							for($i = 0; $i < sizeof($career_workHour); $i++) {
								if($career_workHour[$i]) {
									if($i == 0)		echo $career_workHour[$i]." time";
									else			echo " / ".$career_workHour[$i]." time";
								}
							}
							?>
						</td>
					</tr>
					<tr>
						<td class="career_subject"><strong>Areas you want to work <br> (희망근무분야)</strong></td>
						<td class="career_content">
							<? 
							for($i = 0; $i < sizeof($career_workField); $i++) {
								if($career_workField[$i]) {
									if($i == 0)		echo $career_workField[$i];
									else			echo " / ".$career_workField[$i];
								}
							}
							?>
						</td>
					</tr>
					
					<tr>
						<td class="career_title" colspan=2>Work Experience / 경력정보</td>
					</tr>
					<!--
					<tr>
						<td class="career_subject"><strong>Type of work <br> (관련분야)</strong></td>
						<td class="career_content"><?=$career_prevWork;; ?></td>
					</tr>
					<tr>
						<td class="career_subject"><strong>How long <br> (경력기간)</strong></td>
						<td class="career_content"><?=(($career_prevWorkMonth) ? $career_prevWorkMonth." months" : "" ); ?></td>
					</tr>
					-->
					<tr>
						<td class="career_subject"><strong>Have work experience in the past 3 years <br> (최근 3년이내 경력 유/무)</strong></td>
						<td class="career_content"><?=(($career_workPastYear) ? "YES" : "NO" ); ?></td>
					</tr>

					<tr>
						<td class="career_title" colspan=2>Upload Files / 업로드 파일</td>
					</tr>
					<tr>
						<td class="career_subject"><strong>Cover Letter <br> 자기소개서</strong></td>
						<td class="career_content">
							<? if($old_coverLetter) { ?>
								<a href="<?=$upload_path.Br_iconv($temp_coverLetter); ?>" target="_blank"><?=$old_coverLetter; ?></a>
							<? } ?>
						</td>
					</tr>
					<tr>
						<td class="career_subject"><strong>Resume <br> 이력서</strong></td>
						<td class="career_content">
							<? if($old_resume) { ?>
								<a href="<?=$upload_path.Br_iconv($temp_resume); ?>" target="_blank"><?=$old_resume; ?></a>
							<? } ?>
						</td>
					</tr>
				</table>
			</div>
			
			<div style="margin-left:220px;">
				<button type="button" class="btn" onclick="career_modify()" style="cursor:pointer;">Modify</button>
				<button type="button" class="btn" onclick="career_submit()" style="cursor:pointer;">Submit</button>
			</div>
		</article>
	</div><!-- white_wrapper  -->
</div><!-- gray bg  -->

<?php include 'footer.php'; ?>