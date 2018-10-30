<script>
function choice_delete_img() {
	var answer = confirm("이미지를 삭제 하시겠습니까?");
	if(answer) {
		document.forms.form_choice.del_image.value = 1;
		document.getElementsByName("image_wrapper")[0].innerHTML = '<input type="file" name="choice_image" size="20" class="simpleform" accept="image/jpeg, image/JPG, image/PNG, image/png, image/gif, image/GIF"><span style="color:#7a7a7a;">※ width: 731px</span>';
	}
}

function choice_save_form(mode) {
	var target = document.forms.form_choice;

	var img_check = true;
	if(document.getElementsByName("choice_image")[0] && document.getElementsByName("choice_image")[0].value == "") {
		alert("이미지 오류");
		return false;
	}

	var answer = confirm("저장 하시겠습니까?");
	if(answer) {
		target.mode.value = mode;
		target.submit();
	}
}

function choice_delete() {
	var target = document.forms.form_choice;
	var answer = confirm("삭제 하시겠습니까?");
	if(answer) {
		target.mode.value = "delete";
		target.submit();
	}
}
</script>
<?
/***************************************
*	DB - new_regularUpdate (Type = 2)
****************************************/
$choice_seq = ($_GET['seq']) ? $_GET['seq'] : $_POST['seq'];
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
$db_type = 2;
$image_path = "../upload/manager_choice/";

if($mode == "save") {
	$choice_subject = Br_dconv($_POST['choice_subject']);
	$choice_date = $_POST['choice_date'];

	// inserting manager's choice
	$choiceGetSeq_query = "SELECT TOP 1 seq FROM new_regularUpdate WHERE type = $db_type ORDER BY seq DESC";
	$choiceGetSeq_query_result = mssql_query($choiceGetSeq_query, $conn_hannam);
	$choiceGetSeq_row = mssql_fetch_array($choiceGetSeq_query_result);

	if($choiceGetSeq_row['seq'])	$choice_seq = $choiceGetSeq_row['seq'] + 1;
	else							$choice_seq = 1;

	$choiceAdd_query = "INSERT INTO new_regularUpdate (type, seq, subject, start_date, click_counter) ".
					   "VALUES ($db_type, $choice_seq, '$choice_subject', '$choice_date', 0)";
	mssql_query($choiceAdd_query, $conn_hannam);

	// inserting manager's choice image
	if($_FILES['choice_image']) {
		if($_FILES['choice_image']['error'] == 0) {
			$ableExt = array('jpg','jpeg','gif','png','bmp');
			$path = pathinfo($_FILES['choice_image']['name']);
			$ext = strtolower($path['extension']);

			if(in_array($ext, $ableExt)) {
				$ableImage = array('image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif','image/bmp','image/pjpeg');

				if(in_array($_FILES['choice_image']['type'], $ableImage)) {
					$tempDate = explode(" ", $choice_date);
					$tempDate = str_replace("-", "", $tempDate[0]);
					$new_filename = "choice_".$tempDate.".".$ext;
					$new_fullpath = $image_path.$new_filename;

					$choiceAddImg_query = "UPDATE new_regularUpdate SET image_name = '$new_filename' WHERE type = $db_type AND seq = $choice_seq";
					mssql_query($choiceAddImg_query, $conn_hannam);

					move_uploaded_file($_FILES['choice_image']['tmp_name'], $new_fullpath);
				}
			}
		}
	}

} else if($mode == "update") {
	$choice_seq = $_POST['choice_seq'];
	$choice_subject = Br_dconv($_POST['choice_subject']);
	$choice_date = $_POST['choice_date'];
	$del_image = $_POST['del_image'];

	// deleting manager's choice image
	if($del_image) {
		$choiceImgName_query = "SELECT image_name FROM new_regularUpdate WHERE type = $db_type AND seq = $choice_seq";
		$choiceImgName_query_result = mssql_query($choiceImgName_query, $conn_hannam);
		$choiceImgName_row = mssql_fetch_array($choiceImgName_query_result);

		$fullpath = $image_path.$choiceImgName_row['image_name'];
		unlink($fullpath);
	}
	// updating manager's choice image
	if($_FILES['choice_image']) {
		if($_FILES['choice_image']['error'] == 0) {
			$ableExt = array('jpg','jpeg','gif','png','bmp');
			$path = pathinfo($_FILES['choice_image']['name']);
			$ext = strtolower($path['extension']);

			if(in_array($ext, $ableExt)) {
				$ableImage = array('image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif','image/bmp','image/pjpeg');

				if(in_array($_FILES['choice_image']['type'], $ableImage)) {
					$tempDate = explode(" ", $choice_date);
					$tempDate = str_replace("-", "", $tempDate[0]);
					$new_filename = "choice_".$tempDate.".".$ext;
					$new_fullpath = $image_path.$new_filename;

					$choiceAddImg_query = "UPDATE new_regularUpdate SET image_name = '$new_filename' WHERE type = $db_type AND seq = $choice_seq";
					mssql_query($choiceAddImg_query, $conn_hannam);

					move_uploaded_file($_FILES['choice_image']['tmp_name'], $new_fullpath);
				}
			}
		}
	}

	// updating manager's choice
	$choiceModify_query = "UPDATE new_regularUpdate SET ".
						  "subject = '$choice_subject', ".
						  "start_date = '$choice_date' ".
						  "WHERE type = $db_type AND seq = $choice_seq";
	mssql_query($choiceModify_query, $conn_hannam);

} else if($mode == "delete") {
	$choice_seq = $_POST['choice_seq'];

	// deleting manager's choice image
	$choiceImgName_query = "SELECT image_name FROM new_regularUpdate WHERE type = $db_type AND seq = $choice_seq";
	$choiceImgName_query_result = mssql_query($choiceImgName_query, $conn_hannam);
	$choiceImgName_row = mssql_fetch_array($choiceImgName_query_result);

	if($choiceImgName_row['image_name']) {
		$fullpath = $image_path.$choiceImgName_row['image_name'];
		unlink($fullpath);
	}

	// deleting manager's choice
	$choiceDel_query = "DELETE FROM new_regularUpdate WHERE type = $db_type AND seq = $choice_seq";
	mssql_query($choiceDel_query, $conn_hannam);

	echo "<script>location.href='?menu=menu1&list=managerChoice'</script>";
}

if($choice_seq) {
	$choice_query = "SELECT seq, subject, CONVERT(char(19), start_date, 120) AS start_date, image_name, link ".
				    "FROM new_regularUpdate ".
				    "WHERE type = $db_type AND seq = $choice_seq";
	$choice_query_result = mssql_query($choice_query, $conn_hannam);
	$choice_row = mssql_fetch_array($choice_query_result);
}
?>

<form name="form_choice" action="?menu=menu1&list=managerChoice_write" enctype="multipart/form-data" method="post" accept-charset="utf-8">
<input type="hidden" name="choice_seq" value="<?=$choice_seq; ?>">
<input type="hidden" name="del_image">
<input type="hidden" name="mode">
<div class="content_wrapper">
	<div class="h1_wrapper stripped">
		<h1>Weekly Flyer - Manager's Choice</h1>
	</div>

	<table class="table_admin" cellspacing="0" cellpadding="0">
		<tr height="30px"></tr>
		<tr>
			<td>
				<img src="../img/admin/detail_dot_red.gif">
				<span class="content_link">Weekly Flyer - 글 작성/수정</span>
			</td>
		</tr>

		<tr class="bb" style="padding-bottom:10px;">
			<td>
				<table width="100%">
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							제목:
						</td>
						<td>
							<input type="text" name="choice_subject" size="70" class="simpleform" value="<?=Br_iconv($choice_row['subject']); ?>">
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							시작일:
						</td>
						<td>
							<input type="text" name="choice_date" size="15" class="simpleform" value='<?=(($choice_row['start_date']) ? $choice_row['start_date'] : date('Y-m-d 21:50:00') ); ?>' required>
							<span style="color:#7a7a7a;">※ 매주 목요일 21:50:00 으로 설정</span>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							이미지:
						</td>
						<td style="padding-bottom:5px;">
							<div name="image_wrapper">
								<? if(file_exists($image_path.(($choice_row['image_name']) ? $choice_row['image_name'] : "NULL" ))) { ?>
									<img src='<?=$image_path.$choice_row['image_name']; ?>' width="365px" height="490px">
									<span style="color:red; font-weight:bold; cursor:pointer;" onClick="choice_delete_img()">[삭제]</span><br>
								<? } else { ?>
									<input type="file" name="choice_image" size="20" class="simpleform" accept="image/jpeg, image/JPG, image/PNG, image/png, image/gif, image/GIF">
									<span style="color:#7a7a7a;">※ width: 731px</span>
								<? } ?>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<div style="margin-top:30px;">
		<div style="float:left;"><input type="button" class="btn" onClick="location.href='?menu=menu1&list=managerChoice'" value="목록"></div>
		<div style="float:right;">
			<? if(!$choice_seq) { ?>	<input type="button" class="btn" onClick="choice_save_form('save')" value="저장">
			<? } else { ?>
				<input type="button" class="btn" onClick="choice_delete()" value="삭제">
				<input type="button" class="btn" onClick="choice_save_form('update')" value="저장">
			<? } ?>
		</div>
	</div>
</div>
</form>