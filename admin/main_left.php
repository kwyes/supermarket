<script>
function bannerLeft_delete_img(seq) {
	var answer = confirm(seq + "번 이미지를 삭제 하시겠습니까?");
	if(answer) {
		document.forms.form_bannerLeft.bannerLeft_delImg.value += seq.toString();
		document.getElementsByName("image_wrapper")[seq-1].innerHTML = '<input type="file" name="bannerLeft_image[' + seq + ']" size="20" class="simpleform" required><span style="color:#7a7a7a;">※ width: 230px / height: 227px</span>';
	}
}

function reserveLeft_delete_img(seq) {
	var answer = confirm(seq + "번 예약 이미지를 삭제 하시겠습니까?");
	if(answer) {
		document.forms.form_bannerLeft.reserveLeft_delImg.value += seq.toString();
		if(document.getElementsByName("reserveLeft_status")[1].checked) {
			document.getElementsByName("image_wrapper2")[seq-1].innerHTML = '<input type="file" name="reserveLeft_image[' + seq + ']" size="20" class="simpleform"><span style="color:#7a7a7a;">※ width: 230px / height: 227px</span>';
		} else {
			document.getElementsByName("image_wrapper2")[seq-1].innerHTML = '<input type="file" name="reserveLeft_image[' + seq + ']" size="20" class="simpleform" disabled><span style="color:#7a7a7a;">※ width: 230px / height: 227px</span>';
		}
	}
}

function toggle_reserve(toggle) {
	if(toggle == "enable") {
		document.getElementsByName("reserveLeft_date")[0].disabled = false;
		document.getElementsByName("reserveLeft_link")[0].disabled = false;
		document.getElementsByName("reserveLeft_link_newTab")[0].disabled = false;
		document.getElementsByName("reserveLeft_subject")[0].disabled = false;
		document.getElementsByName("reserveLeft_content")[0].disabled = false;
		document.getElementsByName("reserveLeft_image[1]")[0].disabled = false;
		document.getElementsByName("reserveLeft_image[2]")[0].disabled = false;
	} else if(toggle == "disable") {
		document.getElementsByName("reserveLeft_date")[0].disabled = true;
		document.getElementsByName("reserveLeft_link")[0].disabled = true;
		document.getElementsByName("reserveLeft_link_newTab")[0].disabled = true;
		document.getElementsByName("reserveLeft_subject")[0].disabled = true;
		document.getElementsByName("reserveLeft_content")[0].disabled = true;
		document.getElementsByName("reserveLeft_image[1]")[0].disabled = true;
		document.getElementsByName("reserveLeft_image[2]")[0].disabled = true;
	}
}

function addZero(i) {
	if(i < 10) {
		i = "0" + i;
	}
	return i;
}

function bannerLeft_save_form() {
	var target = document.forms.form_bannerLeft;
	
	for(var i = 1; i <= 2; i++) {
		if(document.getElementsByName("bannerLeft_image[" + i + "]")[0]) {
			if(!document.getElementsByName("bannerLeft_image[" + i + "]")[0].value) {
				alert(i + "번 이미지를 업로드해 주세요.");
				return false;
			}
		}
	}

	if(document.getElementsByName("reserveLeft_status")[1].checked) {
		if(!document.getElementsByName("reserveLeft_date")[0].value) {
			alert("예약 교체 시간을 입력해주세요.");
			return false;
		} else {
			var todayDate = new Date();
			var today = todayDate.getFullYear() + "-" + addZero(todayDate.getMonth() + 1) + "-" + addZero(todayDate.getDate()) + " " + addZero(todayDate.getHours()) + ":" + addZero(todayDate.getMinutes()) + ":" + addZero(todayDate.getSeconds());

			if(document.getElementsByName("reserveLeft_date")[0].value < today || document.getElementsByName("reserveLeft_date")[0].value.length != 19) {
				alert("예약 교체 시간 오류.");
				return false;
			}
		}
		
		for(var i = 1; i <= 2; i++) {
			if(document.getElementsByName("reserveLeft_image[" + i + "]")[0]) {
				if(!document.getElementsByName("reserveLeft_image[" + i + "]")[0].value) {
					alert(i + "번 예약 이미지를 업로드해주세요.");
					return false;
				}
			}
		}
	}

	var answer = confirm("저장 하시겠습니까?");
	if(answer) {
		target.mode.value = "save";
		target.submit();
	}
}
</script>

<?
/***************************************
*	DB - new_mainPage (Type = 2)
****************************************/

$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
$image_path = "../upload/mainPage/";
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);

if($mode == "save") {
	// reserve banner left update
	// if seq = 3 than subject = active/inactive, content = reservation time
	// if seq = 4 than subject = reservation subject, content = reservation content, link = reservation link, newTab = reservation newTab
	$reserveLeft_status = $_POST['reserveLeft_status'];
	$reserveLeft_subject = Br_dconv($_POST['reserveLeft_subject']);
	$reserveLeft_content = nl2br(Br_dconv($_POST['reserveLeft_content']));
	$reserveLeft_date = $_POST['reserveLeft_date'];
	$reserveLeft_link = $_POST['reserveLeft_link'];
	$reserveLeft_link_newTab = $_POST['reserveLeft_link_newTab'];
	$reserveLeft_delImg = $_POST['reserveLeft_delImg'];

	if(!$reserveLeft_status) {
		for($i = 1; $i <= 2; $i++) {
			// Inactivate reserve banner left
			// Delete image if exists
			$reserveLeftImgName_query = "SELECT filename FROM new_mainPage WHERE type = 2 AND seq = ".($i+2);
			$reserveLeftImgName_query_result = mssql_query($reserveLeftImgName_query, $conn_hannam);
			$reserveLeftImgName_row = mssql_fetch_array($reserveLeftImgName_query_result);
			$fullpath = $image_path.$reserveLeftImgName_row['filename'];
			if(file_exists($fullpath)) {
				unlink($fullpath);
			}
			$reserveleftInact_query = "UPDATE new_mainPage SET ".
									  (($i == 1) ? "subject = '0'," : "subject = NULL," )." ".
									  "content = NULL, ".
									  "link = NULL, ".
									  "newTab = 0 ".
									  "WHERE type = 2 AND seq = ".($i + 2);
			mssql_query($reserveleftInact_query, $conn_hannam);
		}
	} else {
		if($reserveLeft_delImg) {
			for($i = 0; $i < strlen($reserveLeft_delImg); $i++) {
				$reserveLeftImgName_query = "SELECT filename FROM new_mainPage WHERE type = 2 AND seq = ".((int)$reserveLeft_delImg[$i]+2);
				$reserveLeftImgName_query_result = mssql_query($reserveLeftImgName_query, $conn_hannam);
				$reserveLeftImgName_row = mssql_fetch_array($reserveLeftImgName_query_result);

				$fullpath = $image_path.$reserveLeftImgName_row['filename'];
				if(file_exists($fullpath)) {
					unlink($fullpath);
				}
			}
		} 

		for($i = 1; $i <= 2; $i++) {
			if($_FILES['reserveLeft_image']['error'][$i] == 0) {
				$ableExt = array('jpg','jpeg','gif','png','bmp');
				$path = pathinfo($_FILES['reserveLeft_image']['name'][$i]);
				$ext = strtolower($path['extension']);

				if(in_array($ext, $ableExt)) {
					$ableImage = array('image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif','image/bmp','image/pjpeg');

					if(in_array($_FILES['reserveLeft_image']['type'][$i], $ableImage)) {
						$new_filename = "reserve_left_".$i.".".$ext;
						$new_fullpath = $image_path.$new_filename;

						$bannerLeftAddImg_query = "UPDATE new_mainPage SET filename = '$new_filename' WHERE type = 2 AND seq = ".($i + 2);
						mssql_query($bannerLeftAddImg_query, $conn_hannam);

						move_uploaded_file($_FILES['reserveLeft_image']['tmp_name'][$i], $new_fullpath);
					}
				}
			}
		}

		// seq = 3
		$reserveLeftModify_query = "UPDATE new_mainPage SET ".
								   "subject = '1', ".
								   "content = '$reserveLeft_date' ".
								   "WHERE type = 2 AND seq = 3";
		mssql_query($reserveLeftModify_query, $conn_hannam);

		// seq = 4
		$reserveLeftModify_query = "UPDATE new_mainPage SET ".
								   "subject = '$reserveLeft_subject', ".
								   "content = '$reserveLeft_content', ".
								   "link = '$reserveLeft_link', ".
								   (($reserveLeft_link_newTab) ? "newTab = 1" : "newTab = 0" )." ".
								   "WHERE type = 2 AND seq = 4";
		mssql_query($reserveLeftModify_query, $conn_hannam);
	}

	// main banner left update
	$bannerLeft_subject = Br_dconv($_POST['bannerLeft_subject']);
	$bannerLeft_content = nl2br(Br_dconv($_POST['bannerLeft_content']));
	$bannerLeft_link = $_POST['bannerLeft_link'];
	$bannerLeft_link_newTab = $_POST['bannerLeft_link_newTab'];
	$bannerLeft_delImg = $_POST['bannerLeft_delImg'];

	if($bannerLeft_delImg) {
		for($i = 0; $i < strlen($bannerLeft_delImg); $i++) {
			$bannerLeftImgName_query = "SELECT filename FROM new_mainPage WHERE type = 2 AND seq = ".(int)$bannerLeft_delImg[$i];
			$bannerLeftImgName_query_result = mssql_query($bannerLeftImgName_query, $conn_hannam);
			$bannerLeftImgName_row = mssql_fetch_array($bannerLeftImgName_query_result);

			$fullpath = $image_path.$bannerLeftImgName_row['filename'];
			unlink($fullpath);

			if($_FILES['bannerLeft_image']['error'][$i+1] == 0) {
				$ableExt = array('jpg','jpeg','gif','png','bmp');
				$path = pathinfo($_FILES['bannerLeft_image']['name'][$i+1]);
				$ext = strtolower($path['extension']);

				if(in_array($ext, $ableExt)) {
					$ableImage = array('image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif','image/bmp','image/pjpeg');

					if(in_array($_FILES['bannerLeft_image']['type'][$i+1], $ableImage)) {
						$new_filename = "left_".$bannerLeft_delImg[$i].".".$ext;
						$new_fullpath = $image_path.$new_filename;

						$bannerLeftAddImg_query = "UPDATE new_mainPage SET filename = '$new_filename' WHERE type = 2 AND seq = ".(int)$bannerLeft_delImg[$i];
						mssql_query($bannerLeftAddImg_query, $conn_hannam);

						move_uploaded_file($_FILES['bannerLeft_image']['tmp_name'][$i+1], $new_fullpath);
					}
				}
			}
		}
	}

	$bannerLeftModify_query = "UPDATE new_mainPage SET ".
							  "subject = '$bannerLeft_subject', ".
							  "content = '$bannerLeft_content', ".
							  "link = '$bannerLeft_link', ".
							  (($bannerLeft_link_newTab) ? "newTab = 1" : "newTab = 0" )." ".
							  "WHERE type = 2 AND seq = 1";
	mssql_query($bannerLeftModify_query, $conn_hannam);
}

$bannerLeft_query = "SELECT seq, subject, content, filename, link, newTab FROM new_mainPage WHERE type = 2 AND seq <= 2 ORDER BY seq";
$bannerLeft_query_result = mssql_query($bannerLeft_query, $conn_hannam);
$bannerLeft_row = mssql_fetch_array($bannerLeft_query_result);
?>

<form name="form_bannerLeft" action="?menu=main&list=left" enctype="multipart/form-data" method="post" accept-charset="utf-8">
<input type="hidden" name="mode">
<input type="hidden" name="bannerLeft_delImg">
<input type="hidden" name="reserveLeft_delImg">
<div class="content_wrapper">
	<div class="h1_wrapper stripped">
		<h1>Main - Banner (Left)</h1>
	</div>

	<table class="table_admin" cellspacing="0" cellpadding="0">
		<tr height="30px"></tr>
		<tr>
			<td>
				<img src="../img/admin/detail_dot_red.gif">
				<span class="content_link">Banner - Left</span>
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
							<input type="text" name="bannerLeft_subject" size="50" class="simpleform" value="<?=Br_iconv($bannerLeft_row['subject']); ?>">
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							내용:
						</td>
						<td>
							<textarea type="text" name="bannerLeft_content" rows="2" cols="51" maxlength="50" style="resize:none; overflow:hidden;" class="simpleform"><?=str_replace("<br />", "", Br_iconv($bannerLeft_row['content'])); ?></textarea>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							링크:
						</td>
						<td>
							<input type="text" name="bannerLeft_link" size="50" class="simpleform" value="<?=$bannerLeft_row['link']; ?>">&nbsp;/&nbsp;
							<input type="checkbox" name="bannerLeft_link_newTab" <?=(($bannerLeft_row['newTab'] == 1) ? "checked" : "" ); ?>>체크시 새 페이지로 열림
						</td>
					</tr>

					<? $bannerLeft_query_result = mssql_query($bannerLeft_query, $conn_hannam); ?>
					<? while($bannerLeft_row = mssql_fetch_array($bannerLeft_query_result)) { ?>
						<tr>
							<td width="100px;">
								<img src="../img/admin/detail_dot_grey.gif">
								이미지 <?=$bannerLeft_row['seq']; ?>:<br>
							</td>
							<td style="padding-bottom:5px;">
								<div name="image_wrapper">
									<? if(file_exists($image_path.$bannerLeft_row['filename'])) { ?>
										<img src='<?=$image_path.$bannerLeft_row['filename']; ?>' width="230px" height="227px">
										<span style="color:red; font-weight:bold; cursor:pointer;" onClick="bannerLeft_delete_img(<?=$bannerLeft_row['seq']; ?>)">[삭제]</span><br>
									<? } else { ?>
										<input type="file" name="bannerLeft_image[<?=$bannerLeft_row['seq']; ?>]" size="20" class="simpleform" required>
										<span style="color:#7a7a7a;">※ width: 230px / height: 227px</span>
									<? } ?>
								</div>
							</td>
						</tr>
					<? } ?>

					<?
					$reserveLeft_query = "SELECT seq, filename FROM new_mainPage WHERE type = 2 AND seq >= 3 ORDER BY seq ASC";
					$reserveLeft_query_result = mssql_query($reserveLeft_query, $conn_hannam);

					// seq = 3 || subject = active/inactive, content = reservation time
					$reserveLeft_query2 = "SELECT subject, content FROM new_mainPage WHERE type = 2 AND seq = 3";
					$reserveLeft_query_result2 = mssql_query($reserveLeft_query2, $conn_hannam);
					$reserveLeft_row2 = mssql_fetch_array($reserveLeft_query_result2);
					
					// seq = 4 || subject = reservation subject, content = reservation content, link = reservation link, newTab = reservation newTab
					$reserveLeft_query3 = "SELECT subject, content, link, newTab FROM new_mainPage WHERE type = 2 AND seq = 4";
					$reserveLeft_query_result3 = mssql_query($reserveLeft_query3, $conn_hannam);
					$reserveLeft_row3 = mssql_fetch_array($reserveLeft_query_result3);
					?>
					<tr class="bt">
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							예약
						</td>
						<td>
							<input type="radio" name="reserveLeft_status" onChange="toggle_reserve('disable')" value="0" <?=(($reserveLeft_row2['subject'] == '0') ? "checked" : "" ); ?>>&nbsp;사용안함
							<input type="radio" name="reserveLeft_status" onChange="toggle_reserve('enable')" value="1" <?=(($reserveLeft_row2['subject'] == '1') ? "checked" : "" ); ?>>&nbsp;사용
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							예약 교체시간:
						</td>
						<td>
							<input type="text" name="reserveLeft_date" value="<?=(($reserveLeft_row2['subject'] == '0') ? date("Y-m-d 21:50:00") : $reserveLeft_row2['content'] ); ?>" <?=(($reserveLeft_row2['subject'] == '0') ? "disabled" : "" ); ?>>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							예약 제목:
						</td>
						<td>
							<input type="text" name="reserveLeft_subject" size="50" class="simpleform" value="<?=Br_iconv($reserveLeft_row3['subject']); ?>" <?=(($reserveLeft_row2['subject'] == '0') ? "disabled" : "" ); ?>>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							예약 내용:
						</td>
						<td>
							<textarea type="text" name="reserveLeft_content" rows="2" cols="51" maxlength="50" style="resize:none; overflow:hidden;" class="simpleform" <?=(($reserveLeft_row2['subject'] == '0') ? "disabled" : "" ); ?>><?=str_replace("<br />", "", Br_iconv($reserveLeft_row3['content'])); ?></textarea>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							예약 링크:
						</td>
						<td>
							<input type="text" name="reserveLeft_link" size="50" class="simpleform" value="<?=$reserveLeft_row3['link']; ?>" <?=(($reserveLeft_row2['subject'] == '0') ? "disabled" : "" ); ?>>&nbsp;/&nbsp;
							<input type="checkbox" name="reserveLeft_link_newTab" <?=(($reserveLeft_row3['newTab'] == 1) ? "checked" : "" ); ?> <?=(($reserveLeft_row2['subject'] == '0') ? "disabled" : "" ); ?>>체크시 새 페이지로 열림
						</td>
					</tr>

					<? while($reserveLeft_row = mssql_fetch_array($reserveLeft_query_result)) { ?>
						<tr>
							<td width="100px;">
								<img src="../img/admin/detail_dot_grey.gif">
								예약 이미지 <?=$reserveLeft_row['seq']-2; ?>:<br>
							</td>
							<td style="padding-bottom:5px;">
								<div name="image_wrapper2">
									<? if(file_exists($image_path.$reserveLeft_row['filename'])) { ?>
										<img src='<?=$image_path.$reserveLeft_row['filename']; ?>' width="230px" height="227px">
										<span style="color:red; font-weight:bold; cursor:pointer;" onClick="reserveLeft_delete_img(<?=$reserveLeft_row['seq']-2; ?>)">[삭제]</span><br>
									<? } else { ?>
										<input type="file" name="reserveLeft_image[<?=$reserveLeft_row['seq']-2; ?>]" size="20" class="simpleform" <?=(($reserveLeft_row2['subject'] == '0') ? "disabled" : "" ); ?>>
										<span style="color:#7a7a7a;">※ width: 230px / height: 227px</span>
									<? } ?>
								</div>
							</td>
						</tr>
					<? } ?>
				</table>
			</td>
		</tr>
	</table>

	<div style="margin-top:30px;">
		<div style="float:left;"><input type="button" class="btn" onClick="location.href='?menu=main'" value="목록"></div>
		<div style="float:right;"><input type="button" class="btn" onClick="bannerLeft_save_form()" value="저장하기"></div>
	</div>
</div>
</form>