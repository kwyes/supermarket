<script>
function bannerMid_delete_img(seq) {
	var answer = confirm("이미지를 삭제 하시겠습니까?");
	if(answer) {
		document.forms.form_bannerMid.bannerMid_delImg.value += seq.toString();
		document.getElementsByName("image_wrapper")[seq-1].innerHTML = '<input type="file" name="bannerMid_image" size="20" class="simpleform" required><span style="color:#7a7a7a;">※ width: 351px / height: 227px</span>';
	}
}

function bannerMid_save_form() {
	var target = document.forms.form_bannerMid;
	var img_total = target.bannerMid_delImg.value.length;
	
	var img_check = true;

	if(img_total) {
		if(document.getElementsByName("bannerMid_image")[0].value == "") {
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
*	DB - new_mainPage (Type = 3)
****************************************/

$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
$image_path = "../upload/mainPage/";
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);

if($mode == "save") {
	$bannerMid_subject = Br_dconv($_POST['bannerMid_subject']);
	$bannerMid_content = nl2br(Br_dconv($_POST['bannerMid_content']));
	$bannerMid_link = $_POST['bannerMid_link'];
	$bannerMid_link_newTab = $_POST['bannerMid_link_newTab'];
	$bannerMid_delImg = $_POST['bannerMid_delImg'];

	if($bannerMid_delImg) {
		$bannerMidImgName_query = "SELECT filename FROM new_mainPage WHERE type = 3 AND seq = ".$bannerMid_delImg;
		$bannerMidImgName_query_result = mssql_query($bannerMidImgName_query, $conn_hannam);
		$bannerMidImgName_row = mssql_fetch_array($bannerMidImgName_query_result);

		$fullpath = $image_path.$bannerMidImgName_row['filename'];
		unlink($fullpath);

		if($_FILES['bannerMid_image']['error'] == 0) {
			$ableExt = array('jpg','jpeg','gif','png','bmp');
			$path = pathinfo($_FILES['bannerMid_image']['name']);
			$ext = strtolower($path['extension']);

			if(in_array($ext, $ableExt)) {
				$ableImage = array('image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif','image/bmp','image/pjpeg');

				if(in_array($_FILES['bannerMid_image']['type'], $ableImage)) {
					$new_filename = "middle_".$bannerMid_delImg.".".$ext;
					$new_fullpath = $image_path.$new_filename;

					$bannerMidAddImg_query = "UPDATE new_mainPage SET filename = '$new_filename' WHERE type = 3 AND seq = ".$bannerMid_delImg;
					mssql_query($bannerMidAddImg_query, $conn_hannam);

					move_uploaded_file($_FILES['bannerMid_image']['tmp_name'], $new_fullpath);
				}
			}
		}
	}

	$bannerMidModify_query = "UPDATE new_mainPage SET ".
							 "subject = '$bannerMid_subject', ".
							 "content = '$bannerMid_content', ".
							 "link = '$bannerMid_link', ".
							 (($bannerMid_link_newTab) ? "newTab = 1" : "newTab = 0" )." ".
							 "WHERE type = 3 AND seq = 1";
	mssql_query($bannerMidModify_query, $conn_hannam);
}

$bannerMid_query = "SELECT seq, subject, content, filename, link, newTab FROM new_mainPage WHERE type = 3 ORDER BY seq";
$bannerMid_query_result = mssql_query($bannerMid_query, $conn_hannam);
$bannerMid_row = mssql_fetch_array($bannerMid_query_result);
?>


<form name="form_bannerMid" action="?menu=main&list=middle" enctype="multipart/form-data" method="post" accept-charset="utf-8">
<input type="hidden" name="mode">
<input type="hidden" name="bannerMid_delImg">
<div class="content_wrapper">
	<div class="h1_wrapper stripped">
		<h1>Main - Banner (Middle)</h1>
	</div>

	<table class="table_admin" cellspacing="0" cellpadding="0">
		<tr height="30px"></tr>
		<tr>
			<td>
				<img src="../img/admin/detail_dot_red.gif">
				<span class="content_link">Banner - Middle</span>
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
							<input type="text" name="bannerMid_subject" size="50" class="simpleform" value='<?=Br_iconv($bannerMid_row['subject']); ?>'>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							내용:
						</td>
						<td>
							<textarea type="text" name="bannerMid_content" rows="2" cols="51" maxlength="50" style="resize:none; overflow:hidden;" class="simpleform"><?=str_replace("<br />", "", Br_iconv($bannerMid_row['content'])); ?></textarea>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							링크:
						</td>
						<td>
							<input type="text" name="bannerMid_link" size="50" class="simpleform" value="<?=$bannerMid_row['link']; ?>">&nbsp;/&nbsp;
							<input type="checkbox" name="bannerMid_link_newTab" <?=(($bannerMid_row['newTab'] == 1) ? "checked" : "" ); ?>>체크시 새 페이지로 열림
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							이미지 :<br>
						</td>
						<td style="padding-bottom:5px;">
							<div name="image_wrapper">
								<? if(file_exists($image_path.$bannerMid_row['filename'])) { ?>
									<img src='<?=$image_path.$bannerMid_row['filename']; ?>' width="351px" height="227px">
									<span style="color:red; font-weight:bold; cursor:pointer;" onClick="bannerMid_delete_img(<?=$bannerMid_row['seq']; ?>)">[삭제]</span><br>
								<? } else { ?>
									<input type="file" name="bannerMid_image" size="20" class="simpleform" required>
									<span style="color:#7a7a7a;">※ width: 351px / height: 227px</span>
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
		<div style="float:right;"><input type="button" class="btn" onClick="bannerMid_save_form()" value="저장하기"></div>
	</div>
</div>
</form>