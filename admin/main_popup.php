<script>
function popup_delete_img() {
	var answer = confirm("이미지를 삭제 하시겠습니까?");
	if(answer) {
		document.forms.form_popup.del_image.value = 1;
		document.getElementsByName("image_wrapper")[0].innerHTML = '<input type="file" name="popup_image" size="20" class="simpleform" accept="image/jpeg, image/JPG, image/PNG, image/png, image/gif, image/GIF">';
	}
}

function popup_save_form() {
	var target = document.forms.form_popup;
	var img_total = target.del_image.value.length;
	
	var img_check = true;
	if(img_total) {
		if(document.getElementsByName("popup_image")[0].value == "") {
			alert("이미지 오류");
			img_check = false;
		}
	}

	if(img_check) {
		var answer = confirm("저장 하시겠습니까?");
		if(answer) {
			target.mode.value = "save";
			target.submit();
		}
	}
}
</script>

<?
/***************************************
*	DB - new_mainPage 
*	   - Type = 5
*	   - subject = width
*	   - content = height
*	   - newTab = status
****************************************/

$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
$image_path = "../upload/mainPage/";
$db_type = 5;
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);

if($mode == "save") {
	$del_image = $_POST['del_image'];
	$popup_status = $_POST['popup_status'];
	$popup_link = $_POST['popup_link'];
	$popup_width = $_POST['popup_width'];
	$popup_height = $_POST['popup_height'];

	if($del_image) {
		$popupImgName_query = "SELECT filename FROM new_mainPage WHERE type = $db_type AND seq = $del_image";
		$popupImgName_query_result = mssql_query($popupImgName_query, $conn_hannam);
		$popupImgName_row = mssql_fetch_array($popupImgName_query_result);

		$fullpath = $image_path.$popupImgName_row['filename'];
		unlink($fullpath);
	}

	if($_FILES['popup_image']['error'] == 0) {
		$ableExt = array('jpg','jpeg','gif','png','bmp');
		$path = pathinfo($_FILES['popup_image']['name']);
		$ext = strtolower($path['extension']);

		if(in_array($ext, $ableExt)) {
			$ableImage = array('image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif','image/bmp','image/pjpeg');

			if(in_array($_FILES['popup_image']['type'], $ableImage)) {
				$new_filename = "popup.".$ext;
				$new_fullpath = $image_path.$new_filename;

				move_uploaded_file($_FILES['popup_image']['tmp_name'], $new_fullpath);
			}
		}
	}

	$popupModify_query = "UPDATE new_mainPage SET ".
						 "subject = '$popup_width', ".
						 "content = '$popup_height', ".
						 "link = '$popup_link', ".
						 "newTab = $popup_status ".
						 "WHERE type = $db_type AND seq = 1";
	mssql_query($popupModify_query, $conn_hannam);
}

$popup_query = "SELECT * FROM new_mainPage WHERE type = $db_type";
$popup_query_result = mssql_query($popup_query, $conn_hannam);
$popup_row = mssql_fetch_array($popup_query_result);

?>

<form name="form_popup" action="?menu=main&list=popup" enctype="multipart/form-data" method="post" accept-charset="utf-8">
<input type="hidden" name="mode">
<input type="hidden" name="del_image">
<div class="content_wrapper">
	<div class="h1_wrapper stripped">
		<h1>Main - POP UP</h1>
	</div>

	
	<table class="table_admin" cellspacing="0" cellpadding="0">
		<tr height="30px"></tr>
		<tr>
			<td>
				<img src="../img/admin/detail_dot_red.gif">
				<span class="content_link">POP UP</span>
			</td>
		</tr>

		<tr class="bb" style="padding-bottom:10px;">
			<td>
				<table width="100%">
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							상태:
						</td>
						<td>
							<select name="popup_status" class="simpleform">
								<option value="1" <?=(($popup_row['newTab'] == 1) ? "selected" : "" ); ?>>Open</option>
								<option value="0" <?=(($popup_row['newTab'] == 0) ? "selected" : "" ); ?>>Close</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							링크:
						</td>
						<td>
							<input type="text" name="popup_link" size="50" class="simpleform" value="<?=$popup_row['link']; ?>">
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							사이즈:
						</td>
						<td>
							Width:&nbsp;<input type="text" size="2" name="popup_width" value="<?=$popup_row['subject']; ?>">px&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;
							Height:&nbsp;<input type="text" size="2" name="popup_height" value="<?=$popup_row['content']; ?>">px
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							이미지 :<br>
						</td>
						<td style="padding-bottom:5px;">
							<div name="image_wrapper">
								<? if(file_exists($image_path.$popup_row['filename'])) { ?>
									<img src='<?=$image_path.$popup_row['filename']; ?>' width="<?=$popup_row['subject']; ?>" height="<?=$popup_row['content']; ?>" style="max-width:590px">
									<span style="color:red; font-weight:bold; cursor:pointer;" onClick="popup_delete_img()">[삭제]</span><br>
								<? } else { ?>
									<input type="file" name="popup_image" size="20" class="simpleform" accept="image/jpeg, image/JPG, image/PNG, image/png, image/gif, image/GIF">
								<? } ?>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<div style="margin-top:30px;">
		<div style="float:left;"><input type="button" class="btn" onClick="location.href='?menu=main'" value="목록"></div>
		<div style="float:right;"><input type="button" class="btn" onClick="popup_save_form()" value="저장하기"></div>
	</div>
</div>
</form>