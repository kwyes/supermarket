<script>
function magazine_delete_img() {
	var answer = confirm("이미지를 삭제 하시겠습니까?");
	if(answer) {
		document.forms.form_magazine.del_image.value = 1;
		document.getElementsByName("image_wrapper")[0].innerHTML = '<input type="file" name="magazine_image" size="20" class="simpleform" accept="image/jpeg, image/JPG, image/PNG, image/png, image/gif, image/GIF"><span style="color:#7a7a7a;">※ width: 226px / height: 292px</span>';
	}
}

function magazine_save_form(mode) {
	var target = document.forms.form_magazine;

	var img_check = true;
	if(document.getElementsByName("magazine_image")[0] && document.getElementsByName("magazine_image")[0].value == "") {
		alert("이미지 오류");
		return false;
	}

	var answer = confirm("저장 하시겠습니까?");
	if(answer) {
		target.mode.value = mode;
		target.submit();
	}
}

function magazine_delete() {
	var target = document.forms.form_magazine;
	var answer = confirm("삭제 하시겠습니까?");
	if(answer) {
		target.mode.value = "delete";
		target.submit();
	}
}
</script>

<?
/***************************************
*	DB - new_regularUpdate (Type = 3)
****************************************/

$magazine_seq = ($_GET['seq']) ? $_GET['seq'] : $_POST['seq'];
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
$db_type = 3;
$image_path = "../upload/magazine/";

if($mode == "save") {
	$magazine_subject = Br_dconv($_POST['magazine_subject']);
	$magazine_date = $_POST['magazine_date'];
	$magazine_date = $magazine_date."-01";
	$magazine_link = $_POST['magazine_link'];

	// inserting magazine
	$magazineGetSeq_query = "SELECT TOP 1 seq FROM new_regularUpdate WHERE type = $db_type ORDER BY seq DESC";
	$magazineGetSeq_query_result = mssql_query($magazineGetSeq_query, $conn_hannam);
	$magazineGetSeq_row = mssql_fetch_array($magazineGetSeq_query_result);

	if($magazineGetSeq_row['seq'])	$magazine_seq = $magazineGetSeq_row['seq'] + 1;
	else							$magazine_seq = 1;

	$magazineAdd_query = "INSERT INTO new_regularUpdate (type, seq, subject, start_date, link, click_counter) ".
						 "VALUES ($db_type, $magazine_seq, '$magazine_subject', '$magazine_date', '$magazine_link', 0)";
	mssql_query($magazineAdd_query, $conn_hannam);

	// inserting magazine image
	if($_FILES['magazine_image']) {
		if($_FILES['magazine_image']['error'] == 0) {
			$ableExt = array('jpg','jpeg','gif','png','bmp');
			$path = pathinfo($_FILES['magazine_image']['name']);
			$ext = strtolower($path['extension']);

			if(in_array($ext, $ableExt)) {
				$ableImage = array('image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif','image/bmp','image/pjpeg');

				if(in_array($_FILES['magazine_image']['type'], $ableImage)) {
					$new_filename = "magazine_".$magazine_seq.".".$ext;
					$new_fullpath = $image_path.$new_filename;

					$magazineAddImg_query = "UPDATE new_regularUpdate SET image_name = '$new_filename' WHERE type = $db_type AND seq = $magazine_seq";
					mssql_query($magazineAddImg_query, $conn_hannam);

					move_uploaded_file($_FILES['magazine_image']['tmp_name'], $new_fullpath);
				}
			}
		}
	}

} else if($mode == "update") {
	$magazine_seq = $_POST['magazine_seq'];
	$magazine_subject = Br_dconv($_POST['magazine_subject']);
	$magazine_date = $_POST['magazine_date'];
	$magazine_date = $magazine_date."-01";
	$magazine_link = $_POST['magazine_link'];
	$del_image = $_POST['del_image'];

	// deleting magazine image
	if($del_image) {
		$magazineImgName_query = "SELECT image_name FROM new_regularUpdate WHERE type = $db_type AND seq = $magazine_seq";
		$magazineImgName_query_result = mssql_query($magazineImgName_query, $conn_hannam);
		$magazineImgName_row = mssql_fetch_array($magazineImgName_query_result);

		$fullpath = $image_path.$magazineImgName_row['image_name'];
		unlink($fullpath);
	}

	// updating magazine image
	if($_FILES['magazine_image']) {
		if($_FILES['magazine_image']['error'] == 0) {
			$ableExt = array('jpg','jpeg','gif','png','bmp');
			$path = pathinfo($_FILES['magazine_image']['name']);
			$ext = strtolower($path['extension']);

			if(in_array($ext, $ableExt)) {
				$ableImage = array('image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif','image/bmp','image/pjpeg');

				if(in_array($_FILES['magazine_image']['type'], $ableImage)) {
					$new_filename = "magazine_".$magazine_seq.".".$ext;
					$new_fullpath = $image_path.$new_filename;

					$magazineAddImg_query = "UPDATE new_regularUpdate SET image_name = '$new_filename' WHERE type = $db_type AND seq = $magazine_seq";
					mssql_query($magazineAddImg_query, $conn_hannam);

					move_uploaded_file($_FILES['magazine_image']['tmp_name'], $new_fullpath);
				}
			}
		}
	}

	// updating magazine
	$magazineModify_query = "UPDATE new_regularUpdate SET ".
							"subject = '$magazine_subject', ".
							"start_date = '$magazine_date', ".
							"link = '$magazine_link' ".
							"WHERE type = $db_type AND seq = $magazine_seq";
	mssql_query($magazineModify_query, $conn_hannam);

} else if($mode == "delete") {
	$magazine_seq = $_POST['magazine_seq'];

	// deleting magazine image
	$magazineImgName_query = "SELECT image_name FROM new_regularUpdate WHERE type = $db_type AND seq = $magazine_seq";
	$magazineImgName_query_result = mssql_query($magazineImgName_query, $conn_hannam);
	$magazineImgName_row = mssql_fetch_array($magazineImgName_query_result);

	if($magazineImgName_row['image_name']) {
		$fullpath = $image_path.$magazineImgName_row['image_name'];
		unlink($fullpath);
	}

	// deleting DB
	$magazineDel_query = "DELETE FROM new_regularUpdate WHERE type = $db_type AND seq = $magazine_seq";
	mssql_query($magazineDel_query, $conn_hannam);

	echo "<script>location.href='?menu=menu4&list=magazine'</script>";
}

if($magazine_seq) {
	$magazine_query = "SELECT seq, subject, CONVERT(char(7), start_date, 126) AS start_date, image_name, link ".
					  "FROM new_regularUpdate ".
					  "WHERE type = $db_type AND seq = $magazine_seq";
	$magazine_query_result = mssql_query($magazine_query, $conn_hannam);
	$magazine_row = mssql_fetch_array($magazine_query_result);
}
?>

<form name="form_magazine" action="?menu=menu4&list=magazine_write" enctype="multipart/form-data" method="post" accept-charset="utf-8">
<input type="hidden" name="magazine_seq" value="<?=$magazine_seq; ?>">
<input type="hidden" name="del_image">
<input type="hidden" name="mode">
<div class="content_wrapper">
	<div class="h1_wrapper stripped">
		<h1>Community - HN Magazine</h1>
	</div>

	<table class="table_admin" cellspacing="0" cellpadding="0">
		<tr height="30px"></tr>
		<tr>
			<td>
				<img src="../img/admin/detail_dot_red.gif">
				<span class="content_link">Magazine - 글 작성/수정</span>
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
							<input type="text" name="magazine_subject" size="50" class="simpleform" value='<?=Br_iconv($magazine_row['subject']); ?>'>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							발행일:
						</td>
						<td>
							<input type="text" name="magazine_date" size="15" class="simpleform" value='<?=(($magazine_row['start_date']) ? $magazine_row['start_date'] : date('Y-m') ); ?>' required>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							링크:
						</td>
						<td>
							<input type="text" name="magazine_link" size="50" class="simpleform" value="<?=$magazine_row['link']; ?>">
						</td>
					</tr>
					<tr class="bb">
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							이미지:
						</td>
						<td style="padding-bottom:5px;">
							<div name="image_wrapper">
								<? if(file_exists($image_path.(($magazine_row['image_name']) ? $magazine_row['image_name'] : "NULL" ))) { ?>
									<img src='<?=$image_path.$magazine_row['image_name']; ?>' width="226px" height="292px">
									<span style="color:red; font-weight:bold; cursor:pointer;" onClick="magazine_delete_img()">[삭제]</span><br>
								<? } else { ?>
									<input type="file" name="magazine_image" size="20" class="simpleform" accept="image/jpeg, image/JPG, image/PNG, image/png, image/gif, image/GIF">
									<span style="color:#7a7a7a;">※ width: 226px / height: 292px</span>
								<? } ?>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>

	</table>

	<div style="margin-top:30px;">
		<div style="float:left;"><input type="button" class="btn" onClick="location.href='?menu=menu4&list=magazine'" value="목록"></div>
		<div style="float:right;">
			<? if(!$magazine_seq) { ?>	<input type="button" class="btn" onClick="magazine_save_form('save')" value="저장">
			<? } else { ?>
				<input type="button" class="btn" onClick="magazine_delete()" value="삭제">
				<input type="button" class="btn" onClick="magazine_save_form('update')" value="저장">
			<? } ?>
		</div>
	</div>
</div>