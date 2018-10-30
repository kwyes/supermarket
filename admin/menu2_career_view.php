<?
$seq = ($_GET['seq']) ? $_GET['seq'] : $_POST['seq'];
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
$upload_path = "../upload/career/";

$career_query = "SELECT name_eng, name_kor, gender, phone_1, phone_2, email, address, city, postal_code, province, pref_workHour, pref_department, exp_workType, exp_workTime, exp_pastYear, file1, file2 ".
				"FROM new_career ".
				"WHERE seq = $seq";
$career_query_result = mssql_query($career_query, $conn_hannam);
$career_row = mssql_fetch_array($career_query_result);
?>

<div class="content_wrapper">
	<div class="h1_wrapper stripped">
		<h1>HANNAM Supermarket - Career Detail</h1>
	</div>

	<div style="text-align: left; padding:10px;">
		<table class="career_review" cellspacing=0 cellpadding=0>
			<tr>
				<td class="career_title" colspan=2>Personal Information / 개인정보</td>
			</tr>
			<tr>
				<td class="career_subject"><strong>Legal Name <br> (영문 성명)</strong></td>
				<td class="career_content"><?=$career_row['name_eng']; ?></td>
			</tr>
			<tr>
				<td class="career_subject"><strong>Korean Name <br> (한글 성명)</strong></td>
				<td class="career_content"><?=Br_iconv($career_row['name_kor']); ?></td>
			</tr>
			<!--
			<tr>
				<td class="career_subject"><strong>Gender <br> (성별)</strong></td>
				<td class="career_content"><?=$career_row['gender']; ?></td>
			</tr>
			-->
			<tr>
				<td class="career_subject"><strong>Phone Number 1 <br> (전화번호 1)</strong></td>
				<td class="career_content"><?=$career_row['phone_1']; ?></td>
			</tr>
			<tr>
				<td class="career_subject"><strong>Phone Number 2 <br> (전화번호 2)</strong></td>
				<td class="career_content"><?=$career_row['phone_2']; ?></td>
			</tr>
			<tr>
				<td class="career_subject"><strong>Email <br> (이메일)</strong></td>
				<td class="career_content"><?=$career_row['email']; ?></td>
			</tr>
			<tr>
				<td class="career_subject"><strong>Current Address <br> (현재 거주 주소)</strong></td>
				<td class="career_content">
					<?
					//if($career_row['address'])		echo $career_row['address'].", ";
					if($career_row['city'])			echo $career_row['city'].", ";
					//if($career_row['postal_code'])	echo $career_row['postal_code'].", ";
					if($career_row['province'])		echo $career_row['province'];
					?>
				</td>
			</tr>
			<tr>
				<td class="career_subject"><strong>Preferred work hours <br> (희망근무시간)</strong></td>
				<td class="career_content">
					<? 
					$career_workHour = explode("/", $career_row['pref_workHour']);
					for($i = 0; $i < sizeof($career_workHour); $i++) {
						if($i == 0)		echo $career_workHour[$i]." time";
						else			echo " / ".$career_workHour[$i]." time";
					}
					?>
				</td>
			</tr>
			<tr>
				<td class="career_subject"><strong>Areas you want to work <br> (희망근무분야)</strong></td>
				<td class="career_content">
					<?
					$career_workField = explode("/", $career_row['pref_department']);
					for($i = 0; $i < sizeof($career_workField); $i++) {
						if($i == 0)		echo $career_workField[$i];
						else			echo " / ".$career_workField[$i];
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
				<td class="career_content"><?=Br_iconv($career_row['exp_workType']); ?></td>
			</tr>
			<tr>
				<td class="career_subject"><strong>How long <br> (경력기간)</strong></td>
				<td class="career_content"><?=(($career_row['exp_workTime']) ? $career_row['exp_workTime']." months" : "" ); ?></td>
			</tr>
			-->
			<tr>
				<td class="career_subject"><strong>Have work experience in the past 3 years <br> (최근 3년이내 경력 유/무)</strong></td>
				<td class="career_content"><?=(($career_row['exp_pastYear']) ? "YES" : "NO" ); ?></td>
			</tr>

			<tr>
				<td class="career_title" colspan=2>Upload Files / 업로드 파일</td>
			</tr>
			<tr>
				<td class="career_subject"><strong>Cover Letter <br> 자기소개서</strong></td>
				<td class="career_content">
					<? if($career_row['file1']) { ?>
						<? $original_coverLetter = substr(strstr($career_row['file1'], "_"), 1); ?>
						<a href="<?=$upload_path.Br_iconv($career_row['file1']); ?>" target="_blank"><?=Br_iconv($original_coverLetter); ?></a>
					<? } ?>
				</td>
			</tr>
			<tr>
				<td class="career_subject"><strong>Resume <br> 이력서</strong></td>
				<td class="career_content">
					<? if($career_row['file2']) { ?>
						<? $original_resume = substr(strstr($career_row['file2'], "_"), 1); ?>
						<a href="<?=$upload_path.Br_iconv($career_row['file2']); ?>" target="_blank"><?=Br_iconv($original_resume); ?></a>
					<? } ?>
				</td>
			</tr>
		</table>
	</div>

	<div style="margin-top:30px;">
		<div style="float:left;"><input type="button" class="btn" onClick="location.href='?menu=menu2&list=career'" value="목록"></div>
	</div>
</div>