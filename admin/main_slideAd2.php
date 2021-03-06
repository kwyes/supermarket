<script>
function slideAd_delete_img(seq) {
	var answer = confirm("AD " + seq + " 이미지를 삭제 하시겠습니까?");
	if(answer) {
		document.forms.form_slideAd.slideAd_delImg.value += seq.toString();
		document.getElementsByName("image_wrapper")[seq-1].innerHTML = '<input type="file" name="slideAd_image[]" size="20" class="simpleform"><span style="color:#7a7a7a;">※ width: 980px / height: 443px</span>';
	}
}

function reserveAd_delete_img(seq) {
	var answer = confirm("AD " + seq + " 예약 이미지를 삭제 하시겠습니까?");
	if(answer) {
		seq += 4;
		document.forms.form_slideAd.reserveAd_delImg.value += seq.toString();
		document.getElementsByName("image_wrapper2")[seq-5].innerHTML = '<input type="file" name="reserveAd_image[]" size="20" class="simpleform"><span style="color:#7a7a7a;">※ width: 980px / height: 443px</span>';
	}
}

function toggle_reserve(toggle, seq) {
	seq -= 4;
	if(toggle == "enable") {
		document.getElementsByName("reserveAd_date[]")[seq-1].disabled = false;
		document.getElementsByName("reserveAd_link[]")[seq-1].disabled = false;
		document.getElementsByName("reserveAd_link_newTab[]")[seq-1].disabled = false;
		document.getElementsByName("reserveAd_image[]")[seq-1].disabled = false;
	} else if(toggle == "disable") {
		document.getElementsByName("reserveAd_date[]")[seq-1].disabled = true;
		document.getElementsByName("reserveAd_link[]")[seq-1].disabled = true;
		document.getElementsByName("reserveAd_link_newTab[]")[seq-1].disabled = true;
		document.getElementsByName("reserveAd_image[]")[seq-1].disabled = true;
	}
}

function addZero(i) {
	if(i < 10) {
		i = "0" + i;
	}
	return i;
}

function slideAd_save_form() {
	var target = document.forms.form_slideAd;

	for(var i = 1; i <= 4; i++) {
		if(document.getElementsByName("reserveAd_status_" + (i+4))[1].checked) {
			if(!document.getElementsByName("reserveAd_date[]")[i-1].value) {
				alert(i + "번 예약 교체 시간을 입력해주세요.");
				return false;
			} else {
				var todayDate = new Date();
				var today = todayDate.getFullYear() + "-" + addZero(todayDate.getMonth() + 1) + "-" + addZero(todayDate.getDate()) + " " + addZero(todayDate.getHours()) + ":" + addZero(todayDate.getMinutes()) + ":" + addZero(todayDate.getSeconds());
				if(document.getElementsByName("reserveAd_date[]")[i-1].value < today) {
					alert(i + "번 예약 교체 시간 오류.");
					return false;
				}
			}

			if(!document.getElementsByName("reserveAd_image[]")[i-1].value) {
				alert(i + "번 예약 교체 이미지를 업로드해주세요.");
				return false;
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
*	DB - new_mainPage (Type = 1)
****************************************/

$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
$image_path = "../upload/mainPage/";
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);

if($mode == "save") {
	// Reserve slide image
	$reserveAd_link = $_POST['reserveAd_link'];
	$reserveAd_link_newTab = $_POST['reserveAd_link_newTab'];
	$reserveAd_date = $_POST['reserveAd_date'];
	$reserveAd_delImg = $_POST['reserveAd_delImg'];

	if($reserveAd_delImg) {
		for($i = 0; $i < strlen($reserveAd_delImg); $i++) {
			$reserveAdImgName_query = "SELECT filename FROM new_mainPage WHERE type = 1 AND seq = ".(int)$reserveAd_delImg[$i];
			$reserveAdImgName_query_result = mssql_query($reserveAdImgName_query, $conn_hannam);
			$reserveAdImgName_row = mssql_fetch_array($reserveAdImgName_query_result);

			$fullpath = $image_path.$slideAdImgName_row['filename'];
			unlink($fullpath);

			$reserveAdInact_query = "UPDATE new_mainPage SET subject = '0' WHERE type = 1 AND seq = ".(int)$reserveAd_delImg[$i];
			mssql_query($reserveAdInact_query, $conn_hannam);
		}
	}

	$j = 0;
	for($i = 1; $i <= 4; $i++) {
		if(!$_POST['reserveAd_status_'.($i + 4)]) {
			// Inactivate reserve AD
			// Delete image if exists
			$fullpath = $image_path."reserve_ad_".$i.".jpg";
			if(file_exists($fullpath)) {
				unlink($fullpath);
				$reserveAdInact_query = "UPDATE new_mainPage SET subject = '0' WHERE type = 1 AND seq = ".($i + 4);
				mssql_query($reserveAdInact_query, $conn_hannam);
			}
		} else {
			// Activate reserve AD
			if($_FILES['reserveAd_image']['error'][$j] == 0) {
				$ableExt = array('jpg','jpeg','gif','png','bmp');
				$path = pathinfo($_FILES['reserveAd_image']['name'][$j]);
				$ext = strtolower($path['extension']);

				if(in_array($ext, $ableExt)) {
					$ableImage = array('image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif','image/bmp','image/pjpeg');

					if(in_array($_FILES['reserveAd_image']['type'][$j], $ableImage)) {
						$new_filename = "reserve_ad_".$i.".".$ext;
						$new_fullpath = $image_path.$new_filename;

						$reserveAdAddImg_query = "UPDATE new_mainPage SET subject = '1', content = '$reserveAd_date[$j]', filename = '$new_filename' WHERE type = 1 AND seq = ".($i + 4);
						mssql_query($reserveAdAddImg_query, $conn_hannam);
						move_uploaded_file($_FILES['reserveAd_image']['tmp_name'][$j], $new_fullpath);
					}
				}
			}
			$j++;
		}
	}

	/*
	if($reserveAd_link) {
		for($i = 0; $i < sizeof($reserveAd_link); $i++) {
			$slideAdModify_query = "UPDATE new_mainPage SET link = '".$slideAd_link[$i]."', ".
								   (($slideAd_link_newTab[$i]) ? "newTab = 1" : "newTab = 0" )." ".
								   "WHERE type = 1 AND seq = ".($i+1);
			mssql_query($slideAdModify_query, $conn_hannam);
		}
	}
	*/

	/*
	// Slide image
	$slideAd_link = $_POST['slideAd_link'];
	$slideAd_link_newTab = $_POST['slideAd_link_newTab'];
	$slideAd_delImg = $_POST['slideAd_delImg'];

	if($slideAd_delImg) {
		for($i = 0; $i < strlen($slideAd_delImg); $i++) {
			$slideAdImgName_query = "SELECT filename FROM new_mainPage WHERE type = 1 AND seq = ".(int)$slideAd_delImg[$i];
			$slideAdImgName_query_result = mssql_query($slideAdImgName_query, $conn_hannam);
			$slideAdImgName_row = mssql_fetch_array($slideAdImgName_query_result);

			$fullpath = $image_path.$slideAdImgName_row['filename'];
			unlink($fullpath);

			$slideAdDelImg_query = "UPDATE new_mainPage SET subject = '0' WHERE type = 1 AND seq = ".(int)$slideAd_delImg[$i];
			mssql_query($slideAdDelImg_query, $conn_hannam);
		}
	}

	if($_FILES['slideAd_image']) {
		$slideAdGetSeq_query = "SELECT seq FROM new_mainPage WHERE type = 1 AND subject = '0' ORDER BY seq";
		$slideAdGetSeq_query_result = mssql_query($slideAdGetSeq_query, $conn_hannam);
		$add_image = array();
		while($slideAdGetSeq_row = mssql_fetch_array($slideAdGetSeq_query_result)) {
			array_push($add_image, $slideAdGetSeq_row['seq']);
		}

		for($i = 0; $i < count($_FILES['slideAd_image']['name']); $i++) {			
			if($_FILES['slideAd_image']['error'][$i] == 0) {
				$ableExt = array('jpg','jpeg','gif','png','bmp');
				$path = pathinfo($_FILES['slideAd_image']['name'][$i]);
				$ext = strtolower($path['extension']);

				if(in_array($ext, $ableExt)) {
					$ableImage = array('image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif','image/bmp','image/pjpeg');

					if(in_array($_FILES['slideAd_image']['type'][$i], $ableImage)) {
						$new_filename = "slide_ad_".$add_image[$i].".".$ext;
						$new_fullpath = $image_path.$new_filename;

						$slideAdAddImg_query = "UPDATE new_mainPage SET subject = '1', filename = '$new_filename' WHERE type = 1 AND seq = ".$add_image[$i];
						mssql_query($slideAdAddImg_query, $conn_hannam);

						move_uploaded_file($_FILES['slideAd_image']['tmp_name'][$i], $new_fullpath);
					}
				}
			}
		}
	}

	if($slideAd_link) {
		for($i = 0; $i < sizeof($slideAd_link); $i++) {
			$slideAdModify_query = "UPDATE new_mainPage SET link = '".$slideAd_link[$i]."', ".
								   (($slideAd_link_newTab[$i]) ? "newTab = 1" : "newTab = 0" )." ".
								   "WHERE type = 1 AND seq = ".($i+1);
			mssql_query($slideAdModify_query, $conn_hannam);
		}
	}
	*/
}

$slideAd_query = "SELECT seq, link, newTab, filename FROM new_mainPage WHERE type = 1 AND seq <= 4 ORDER BY seq";
$slideAd_query_result = mssql_query($slideAd_query, $conn_hannam);
?>

<form name="form_slideAd" action="?menu=main&list=slideAd2" enctype="multipart/form-data" method="post" accept-charset="utf-8">
<input type="hidden" name="mode">
<input type="hidden" name="slideAd_delImg">
<input type="hidden" name="reserveAd_delImg">
<div class="content_wrapper">
	<div class="h1_wrapper stripped">
		<h1>Main - Slide Advertisement</h1>
	</div>

	<table class="table_admin" cellspacing="0" cellpadding="0">
		<tr height="30px"></tr>
		<? while($slideAd_row = mssql_fetch_array($slideAd_query_result)) { ?>
			<tr>
				<td>
					<img src="../img/admin/detail_dot_red.gif">
					<span class="content_link">AD <?=$slideAd_row['seq']; ?></span>
				</td>
			</tr>
			<tr style="padding-bottom:10px; border-bottom:2px solid red;">
				<td>
					<table width="100%">
						<tr>
							<td width="100px;">
								<img src="../img/admin/detail_dot_grey.gif">
								링크:
							</td>
							<td>
								<input type="text" name="slideAd_link[]" size="50" class="simpleform" value="<?=$slideAd_row['link']; ?>">&nbsp;/&nbsp;
								<input type="checkbox" name="slideAd_link_newTab[<?=$slideAd_row['seq']-1; ?>]" <?=(($slideAd_row['newTab'] == 1) ? "checked" : "" ); ?>>체크시 새 페이지로 열림
							</td>
						</tr>
						<tr class="bb">
							<td width="100px;">
								<img src="../img/admin/detail_dot_grey.gif">
								이미지:<br>
							</td>
							<td style="padding-bottom:5px;">
								<div name="image_wrapper">
									<? if(file_exists($image_path.$slideAd_row['filename'])) { ?>
										<img src='<?=$image_path.$slideAd_row['filename']; ?>' width="490px" height="221.5px">
										<span style="color:red; font-weight:bold; cursor:pointer;" onClick="slideAd_delete_img(<?=$slideAd_row['seq']; ?>)">[삭제]</span><br>
									<? } else { ?>
										<input type="file" name="slideAd_image[]" size="20" class="simpleform">
										<span style="color:#7a7a7a;">※ width: 980px / height: 443px</span>
									<? } ?>
								</div>
							</td>
						</tr>

						<?
						// subject = active/inactive, content = reservation time
						$reserveAd_query = "SELECT seq, subject, content, link, newTab, filename FROM new_mainPage WHERE type = 1 AND seq = ".($slideAd_row['seq'] + 4);
						$reserveAd_query_result = mssql_query($reserveAd_query, $conn_hannam);
						$reserveAd_row = mssql_fetch_array($reserveAd_query_result)
						?>
						<tr>
							<td width="100px;">
								<img src="../img/admin/detail_dot_grey.gif">
								예약
							</td>
							<td>
								<input type="radio" name="reserveAd_status_<?=$reserveAd_row['seq']; ?>" onChange="toggle_reserve('disable', <?=$reserveAd_row['seq']; ?>)" value="0" <?=(($reserveAd_row['subject'] == '0') ? "checked" : "" ); ?>>&nbsp;사용안함
								<input type="radio" name="reserveAd_status_<?=$reserveAd_row['seq']; ?>" onChange="toggle_reserve('enable', <?=$reserveAd_row['seq']; ?>)" value="1" <?=(($reserveAd_row['subject'] == '1') ? "checked" : "" ); ?>>&nbsp;사용
							</td>
						</tr>
						<tr>
							<td width="100px;">
								<img src="../img/admin/detail_dot_grey.gif">
								예약 교체시간:
							</td>
							<td>
								<input type="text" name="reserveAd_date[]" value="<?=(($reserveAd_row['subject'] == '0') ? date("Y-m-d 21:50:00") : $reserveAd_row['content'] ); ?>" <?=(($reserveAd_row['subject'] == '0') ? "disabled" : "" ); ?>>
							</td>
						</tr>
						<tr>
							<td width="100px;">
								<img src="../img/admin/detail_dot_grey.gif">
								예약 링크:
							</td>
							<td>
								<input type="text" name="reserveAd_link[]" size="50" class="simpleform" value="" <?=(($reserveAd_row['subject'] == '0') ? "disabled" : "" ); ?>>&nbsp;/&nbsp;
								<input type="checkbox" name="reserveAd_link_newTab[]" <?=(($reserveAd_row['subject'] == '0') ? "disabled" : "" ); ?>>체크시 새 페이지로 열림
							</td>
						</tr>
						<tr>
							<td width="100px;">
								<img src="../img/admin/detail_dot_grey.gif">
								예약 이미지:
							</td>
							<td style="padding-bottom:5px;">
								<div name="image_wrapper2">
									<? if(file_exists($image_path.$reserveAd_row['filename'])) { ?>
										<img src='<?=$image_path.$reserveAd_row['filename']; ?>' width="245px" height="110.75px">
										<span style="color:red; font-weight:bold; cursor:pointer;" onClick="reserveAd_delete_img(<?=$slideAd_row['seq']; ?>)">[삭제]</span><br>
									<? } else { ?>
										<input type="file" name="reserveAd_image[]" size="20" class="simpleform" <?=(($reserveAd_row['subject'] == '0') ? "disabled" : "" ); ?>>
										<span style="color:#7a7a7a;">※ width: 980px / height: 443px</span>
									<? } ?>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		<? } ?>
	</table>

	<div style="margin-top:30px;">
		<div style="float:left;"><input type="button" class="btn" onClick="location.href='?menu=main'" value="목록"></div>
		<div style="float:right;"><input type="button" class="btn" onClick="slideAd_save_form()" value="저장하기"></div>
	</div>
</div>
</form>