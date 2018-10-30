<script>
function flyer_delete_img(language, mode) {
	if(mode == 'image') {
		var answer = confirm("이미지를 삭제 하시겠습니까?");
		if(answer) {
			if(language == 'kor') {
				document.forms.form_flyer.del_image_kor.value = 1;
				document.getElementsByName("image_wrapper_kor")[0].innerHTML = '<input type="file" name="flyer_image_kor" size="20" class="simpleform" accept="image/jpeg, image/JPG, image/PNG, image/png, image/gif, image/GIF"><span style="color:#7a7a7a;">※ width: 226px / height: 292px</span>';
			}
			if(language == 'chi') {
				document.forms.form_flyer.del_image_chi.value = 1;
				document.getElementsByName("image_wrapper_chi")[0].innerHTML = '<input type="file" name="flyer_image_chi" size="20" class="simpleform" accept="image/jpeg, image/JPG, image/PNG, image/png, image/gif, image/GIF"><span style="color:#7a7a7a;">※ width: 226px / height: 292px</span>';
			}
		}
	} else {
		var answer = confirm("PDF를 삭제 하시겠습니까?");
		if(answer) {
			if(language == 'kor') {
				document.forms.form_flyer.del_pdf_kor.value = 1;
				document.getElementsByName("pdf_wrapper_kor")[0].innerHTML = '<input type="file" name="flyer_pdf_kor" size="20" class="simpleform">';
			}
			if(language == 'chi') {
				document.forms.form_flyer.del_pdf_chi.value = 1;
				document.getElementsByName("pdf_wrapper_chi")[0].innerHTML = '<input type="file" name="flyer_pdf_chi" size="20" class="simpleform">';
			}
		}
	}
}

function flyer_save_form(mode) {
	var target = document.forms.form_flyer;

	if(document.getElementsByName("flyer_image_kor")[0] && document.getElementsByName("flyer_image_kor")[0].value == "") {
		alert("Korean 이미지 오류");
		return false;
	}

	if(document.getElementsByName("flyer_pdf_kor")[0] && document.getElementsByName("flyer_pdf_kor")[0].value == "") {
		alert("Korea PDF 오류");
		return false;
	}

	var answer = confirm("저장 하시겠습니까?");
	if(answer) {
		target.mode.value = mode;
		target.submit();
	}
}

function flyer_delete() {
	var target = document.forms.form_flyer;
	var answer = confirm("삭제 하시겠습니까?");
	if(answer) {
		target.mode.value = "delete";
		target.submit();
	}
}
</script>

<?
/***************************************
*	DB - new_regularUpdate 
*	Type = 1 - Korean
*	Type = 4 - Chinese
****************************************/
$flyer_seq = ($_GET['seq']) ? $_GET['seq'] : $_POST['seq'];
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
$db_type_kor = 1;
$db_type_chi = 4;
$image_path_kor = "../upload/weekly_flyer/Korean/";
$image_path_chi = "../upload/weekly_flyer/Chinese/";

if($mode == "save") {
	$flyer_subject = Br_dconv($_POST['flyer_subject']);
	$flyer_date = $_POST['flyer_date'];
	$reserveDate = explode(" ", $flyer_date);
	$randNum = $randNum = rand(1000000000, 9999999999);
	
	// getting seq
	$flyerGetSeq_query = "SELECT TOP 1 seq FROM new_regularUpdate WHERE type = $db_type_kor ORDER BY seq DESC";
	$flyerGetSeq_query_result = mssql_query($flyerGetSeq_query, $conn_hannam);
	$flyerGetSeq_row = mssql_fetch_array($flyerGetSeq_query_result);

	if($flyerGetSeq_row['seq'])	$flyer_seq = $flyerGetSeq_row['seq'] + 1;
	else						$flyer_seq = 1;

	// inserting weekly flyer Korean
	$flyerAdd_query = "INSERT INTO new_regularUpdate (type, seq, subject, start_date) ".
					  "VALUES ($db_type_kor, $flyer_seq, '$flyer_subject', '$flyer_date')";
	mssql_query($flyerAdd_query, $conn_hannam);

	// inserting weekly flyer Chinese
	$flyerAddChi_query = "INSERT INTO new_regularUpdate (type, seq) ".
						 "VALUES ($db_type_chi, $flyer_seq)";
	mssql_query($flyerAddChi_query, $conn_hannam);

	// inserting weekly flyer image Korean
	if($_FILES['flyer_image_kor']) {
		if($_FILES['flyer_image_kor']['error'] == 0) {
			$ableExt = array('jpg','jpeg','gif','png','bmp');
			$path = pathinfo($_FILES['flyer_image_kor']['name']);
			$ext = strtolower($path['extension']);

			if(in_array($ext, $ableExt)) {
				$ableImage = array('image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif','image/bmp','image/pjpeg');

				if(in_array($_FILES['flyer_image_kor']['type'], $ableImage)) {
					do {
						if($exist)		$randNum = rand(1000000000, 9999999999);

						$new_filename = "flyer_1_".$randNum.".".$ext;
						$new_fullpath = $image_path_kor.$new_filename;
						$exist = true;

						if(!file_exists($new_fullpath)) {
							$flyerAddImg_query = "UPDATE new_regularUpdate SET image_name = '$new_filename' WHERE type = $db_type_kor AND seq = $flyer_seq";
							mssql_query($flyerAddImg_query, $conn_hannam);

							move_uploaded_file($_FILES['flyer_image_kor']['tmp_name'], $new_fullpath);
							$exist = false;
						}
					} while($exist);
				}
			}
		}
	}

	// inserting weekly flyer image Chinese
	if($_FILES['flyer_image_chi']) {
		if($_FILES['flyer_image_chi']['error'] == 0) {
			$ableExt = array('jpg','jpeg','gif','png','bmp');
			$path = pathinfo($_FILES['flyer_image_chi']['name']);
			$ext = strtolower($path['extension']);

			if(in_array($ext, $ableExt)) {
				$ableImage = array('image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif','image/bmp','image/pjpeg');

				if(in_array($_FILES['flyer_image_chi']['type'], $ableImage)) {
					do {
						if($exist)		$randNum = rand(1000000000, 9999999999);

						$new_filename = "flyer_3_".$randNum.".".$ext;
						$new_fullpath = $image_path_chi.$new_filename;
						$exist = true;

						if(!file_exists($new_fullpath)) {
							$flyerAddImg_query = "UPDATE new_regularUpdate SET image_name = '$new_filename' WHERE type = $db_type_chi AND seq = $flyer_seq";
							mssql_query($flyerAddImg_query, $conn_hannam);

							move_uploaded_file($_FILES['flyer_image_chi']['tmp_name'], $new_fullpath);
							$exist = false;
						}
					} while($exist);
				}
			}
		}
	}

	// inserting weekly flyer PDF Korean
	if($_FILES['flyer_pdf_kor']) {
		if($_FILES['flyer_pdf_kor']['error'] == 0) {
			$path = pathinfo($_FILES['flyer_pdf_kor']['name']);
			$ext = strtolower($path['extension']);

			do {
				if($exist)		$randNum = rand(1000000000, 9999999999);

				$new_filename = "flyer_2_".$randNum.".".$ext;
				$new_fullpath = $image_path_kor.$new_filename;
				$exist = true;

				if(!file_exists($new_fullpath)) {
					$flyerAddImg_query = "UPDATE new_regularUpdate SET link = '$new_filename' WHERE type = $db_type_kor AND seq = $flyer_seq";
					mssql_query($flyerAddImg_query, $conn_hannam);

					move_uploaded_file($_FILES['flyer_pdf_kor']['tmp_name'], $new_fullpath);
					$exist = false;
				}
			} while($exist);
		}
	}

	// inserting weekly flyer PDF Chinese
	if($_FILES['flyer_pdf_chi']) {
		if($_FILES['flyer_pdf_chi']['error'] == 0) {
			$path = pathinfo($_FILES['flyer_pdf_chi']['name']);
			$ext = strtolower($path['extension']);

			do {
				if($exist)		$randNum = rand(1000000000, 9999999999);

				$new_filename = "flyer_4_".$randNum.".".$ext;
				$new_fullpath = $image_path_chi.$new_filename;
				$exist = true;

				if(!file_exists($new_fullpath)) {
					$flyerAddImg_query = "UPDATE new_regularUpdate SET link = '$new_filename' WHERE type = $db_type_chi AND seq = $flyer_seq";
					mssql_query($flyerAddImg_query, $conn_hannam);

					move_uploaded_file($_FILES['flyer_pdf_chi']['tmp_name'], $new_fullpath);
					$exist = false;
				}
			} while($exist);
		}
	}
	
	// inserting e-flyer subscription reservation
	$item_type = 1;
	$subscribeInsert_query = "INSERT INTO new_subscribe_reserve (item_type, item_seq, send_date) ".
							 "VALUES ($item_type, $flyer_seq, '".$reserveDate[0]."')";
	mssql_query($subscribeInsert_query, $conn_hannam);

} else if($mode == "update") {
	$flyer_seq = $_POST['flyer_seq'];
	$flyer_subject = Br_dconv($_POST['flyer_subject']);
	$flyer_date = $_POST['flyer_date'];
	$reserveDate = explode(" ", $flyer_date);
	$randNum = rand(1000000000, 9999999999);
	$del_image_kor = $_POST['del_image_kor'];
	$del_pdf_kor = $_POST['del_pdf_kor'];
	$del_image_chi = $_POST['del_image_chi'];
	$del_pdf_chi = $_POST['del_pdf_chi'];

	// deleting weekly flyer image Korean
	if($del_image_kor) {
		$flyerImgName_query = "SELECT image_name FROM new_regularUpdate WHERE type = $db_type_kor AND seq = $flyer_seq";
		$flyerImgName_query_result = mssql_query($flyerImgName_query, $conn_hannam);
		$flyerImgName_row = mssql_fetch_array($flyerImgName_query_result);

		$fullpath = $image_path_kor.$flyerImgName_row['image_name'];
		unlink($fullpath);
	}
	// updating weekly flyer image Korean
	if($_FILES['flyer_image_kor']) {
		if($_FILES['flyer_image_kor']['error'] == 0) {
			$ableExt = array('jpg','jpeg','gif','png','bmp');
			$path = pathinfo($_FILES['flyer_image_kor']['name']);
			$ext = strtolower($path['extension']);

			if(in_array($ext, $ableExt)) {
				$ableImage = array('image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif','image/bmp','image/pjpeg');

				if(in_array($_FILES['flyer_image_kor']['type'], $ableImage)) {
					do {
						if($exist)		$randNum = rand(1000000000, 9999999999);

						if($del_image_kor)	$new_filename = $flyerImgName_row['image_name'];
						else				$new_filename = "flyer_1_".$randNum.".".$ext;
						$new_fullpath = $image_path_kor.$new_filename;
						$exist = true;

						if(!file_exists($new_fullpath)) {
							$flyerAddImg_query = "UPDATE new_regularUpdate SET image_name = '$new_filename' WHERE type = $db_type_kor AND seq = $flyer_seq";
							mssql_query($flyerAddImg_query, $conn_hannam);

							move_uploaded_file($_FILES['flyer_image_kor']['tmp_name'], $new_fullpath);
							$exist = false;
						}
					} while($exist);
				}
			}
		}
	}

	// deleting weekly flyer image Chinese
	if($del_image_chi) {
		$flyerImgName_query = "SELECT image_name FROM new_regularUpdate WHERE type = $db_type_chi AND seq = $flyer_seq";
		$flyerImgName_query_result = mssql_query($flyerImgName_query, $conn_hannam);
		$flyerImgName_row = mssql_fetch_array($flyerImgName_query_result);

		$fullpath = $image_path_chi.$flyerImgName_row['image_name'];
		unlink($fullpath);

		$flyerDelImg_query = "UPDATE new_regularUpdate SET image_name = NULL WHERE type = $db_type_chi AND seq = $flyer_seq";
		mssql_query($flyerDelImg_query, $conn_hannam);
	}
	// updating weekly flyer image Chinese
	if($_FILES['flyer_image_chi']) {
		if($_FILES['flyer_image_chi']['error'] == 0) {
			$ableExt = array('jpg','jpeg','gif','png','bmp');
			$path = pathinfo($_FILES['flyer_image_chi']['name']);
			$ext = strtolower($path['extension']);

			if(in_array($ext, $ableExt)) {
				$ableImage = array('image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif','image/bmp','image/pjpeg');

				if(in_array($_FILES['flyer_image_chi']['type'], $ableImage)) {
					do {
						if($exist)		$randNum = rand(1000000000, 9999999999);

						if($del_image_chi)	$new_filename = $flyerImgName_row['image_name'];
						else				$new_filename = "flyer_3_".$randNum.".".$ext;

						$new_fullpath = $image_path_chi.$new_filename;
						$exist = true;

						if(!file_exists($new_fullpath)) {
							$flyerAddImg_query = "UPDATE new_regularUpdate SET image_name = '$new_filename' WHERE type = $db_type_chi AND seq = $flyer_seq";
							mssql_query($flyerAddImg_query, $conn_hannam);

							move_uploaded_file($_FILES['flyer_image_chi']['tmp_name'], $new_fullpath);
							$exist = false;
						}
					} while($exist);
				}
			}
		}
	}

	//deleting weekly flyer PDF Korean
	if($del_pdf_kor) {
		$flyerPdfName_query = "SELECT link FROM new_regularUpdate WHERE type = $db_type_kor AND seq = $flyer_seq";
		$flyerPdfName_query_result = mssql_query($flyerPdfName_query, $conn_hannam);
		$flyerPdfName_row = mssql_fetch_array($flyerPdfName_query_result);

		$fullpath = $image_path_kor.$flyerPdfName_row['link'];
		unlink($fullpath);
	}
	// updating weekly flyer PDF Korean
	if($_FILES['flyer_pdf_kor']) {
		if($_FILES['flyer_pdf_kor']['error'] == 0) {
			$path = pathinfo($_FILES['flyer_pdf_kor']['name']);
			$ext = strtolower($path['extension']);

			do {
				if($exist)		$randNum = rand(1000000000, 9999999999);

				if($del_pdf_kor)	$new_filename = $flyerPdfName_row['link'];
				else				$new_filename = "flyer_2_".$randNum.".".$ext;

				$new_fullpath = $image_path_kor.$new_filename;
				$exist = true;

				if(!file_exists($new_fullpath)) {
					$flyerAddPDF_query = "UPDATE new_regularUpdate SET link = '$new_filename' WHERE type = $db_type_kor AND seq = $flyer_seq";
					mssql_query($flyerAddPDF_query, $conn_hannam);

					move_uploaded_file($_FILES['flyer_pdf_kor']['tmp_name'], $new_fullpath);
					$exist = false;
				}
			} while($exist);
		}
	}

	//deleting weekly flyer PDF Chinese
	if($del_pdf_chi) {
		$flyerPdfName_query = "SELECT link FROM new_regularUpdate WHERE type = $db_type_chi AND seq = $flyer_seq";
		$flyerPdfName_query_result = mssql_query($flyerPdfName_query, $conn_hannam);
		$flyerPdfName_row = mssql_fetch_array($flyerPdfName_query_result);

		$fullpath = $image_path_chi.$flyerPdfName_row['link'];
		unlink($fullpath);

		$flyerDelPDF_query = "UPDATE new_regularUpdate SET link = NULL WHERE type = $db_type_chi AND seq = $flyer_seq";
		mssql_query($flyerDelPDF_query, $conn_hannam);
	}
	// updating weekly flyer PDF Chinese
	if($_FILES['flyer_pdf_chi']) {
		if($_FILES['flyer_pdf_chi']['error'] == 0) {
			$path = pathinfo($_FILES['flyer_pdf_chi']['name']);
			$ext = strtolower($path['extension']);

			do {
				if($exist)		$randNum = rand(1000000000, 9999999999);

				if($del_pdf_chi)	$new_filename = $flyerPdfName_row['link'];
				else				$new_filename = "flyer_4_".$randNum.".".$ext;

				$new_fullpath = $image_path_chi.$new_filename;
				$exist = true;

				if(!file_exists($new_fullpath)) {
					$flyerAddImg_query = "UPDATE new_regularUpdate SET link = '$new_filename' WHERE type = $db_type_chi AND seq = $flyer_seq";
					mssql_query($flyerAddImg_query, $conn_hannam);

					move_uploaded_file($_FILES['flyer_pdf_chi']['tmp_name'], $new_fullpath);
					$exist = false;
				}
			} while($exist);
		}
	}

	// updating weekly flyer
	$flyerModify_query = "UPDATE new_regularUpdate SET ".
						 "subject = '$flyer_subject', ".
						 "start_date = '$flyer_date' ".
						 "WHERE type = $db_type_kor AND seq = $flyer_seq";
	mssql_query($flyerModify_query, $conn_hannam);

	// updating e-flyer subscription reservation
	$item_type = 1;
	$subscribeCheck_query = "SELECT CONVERT(char(10), send_date, 120) AS send_date, process FROM new_subscribe_reserve WHERE item_type = $item_type AND item_seq = $flyer_seq ";
	$subscribeCheck_query_result = mssql_query($subscribeCheck_query, $conn_hannam);
	$subscribeCheck_row = mssql_fetch_array($subscribeCheck_query_result);

	if($subscribeCheck_row['process'] == 0) {
		if($subscribeCheck_row['send_date'] != $reserveDate[0]) {
			$subscribeUpdate_query = "UPDATE new_subscribe_reserve SET send_date = '".$reserveDate[0]."' WHERE item_type = $item_type AND item_seq = $flyer_seq";
			mssql_query($subscribeUpdate_query, $conn_hannam);
		}
	}

} else if($mode == "delete") {
	$flyer_seq = $_POST['flyer_seq'];

	// deleting weekly flyer image Korean
	$flyerImgName_query = "SELECT image_name FROM new_regularUpdate WHERE type = $db_type_kor AND seq = $flyer_seq";
	$flyerImgName_query_result = mssql_query($flyerImgName_query, $conn_hannam);
	$flyerImgName_row = mssql_fetch_array($flyerImgName_query_result);

	if($flyerImgName_row['image_name']) {
		$fullpath = $image_path_kor.$flyerImgName_row['image_name'];
		unlink($fullpath);
	}

	// deleting weekly flyer image Chinese
	$flyerImgName_query = "SELECT image_name FROM new_regularUpdate WHERE type = $db_type_chi AND seq = $flyer_seq";
	$flyerImgName_query_result = mssql_query($flyerImgName_query, $conn_hannam);
	$flyerImgName_row = mssql_fetch_array($flyerImgName_query_result);

	if($flyerImgName_row['image_name']) {
		$fullpath = $image_path_chi.$flyerImgName_row['image_name'];
		unlink($fullpath);
	}

	// deleting weekly flyer PDF Korean
	$flyerPdfName_query = "SELECT link FROM new_regularUpdate WHERE type = $db_type_kor AND seq = $flyer_seq";
	$flyerPdfName_query_result = mssql_query($flyerPdfName_query, $conn_hannam);
	$flyerPdfName_row = mssql_fetch_array($flyerPdfName_query_result);

	if($flyerPdfName_row['link']) {
		$fullpath = $image_path_kor.$flyerPdfName_row['link'];
		unlink($fullpath);
	}

	// deleting weekly flyer PDF Chinese
	$flyerPdfName_query = "SELECT link FROM new_regularUpdate WHERE type = $db_type_chi AND seq = $flyer_seq";
	$flyerPdfName_query_result = mssql_query($flyerPdfName_query, $conn_hannam);
	$flyerPdfName_row = mssql_fetch_array($flyerPdfName_query_result);

	if($flyerPdfName_row['link']) {
		$fullpath = $image_path_chi.$flyerPdfName_row['link'];
		unlink($fullpath);
	}

	// deleting weekly flyer Korean
	$flyerDel_query = "DELETE FROM new_regularUpdate WHERE type = $db_type_kor AND seq = $flyer_seq";
	mssql_query($flyerDel_query, $conn_hannam);

	// deleting weekly flyer Chinese
	$flyerDel_query = "DELETE FROM new_regularUpdate WHERE type = $db_type_chi AND seq = $flyer_seq";
	mssql_query($flyerDel_query, $conn_hannam);

	// deleting e-flyer subscription reservation
	$item_type = 1;
	$subscribeCheck_query = "SELECT process FROM new_subscribe_reserve WHERE item_type = $item_type AND item_seq = $flyer_seq ";
	$subscribeCheck_query_result = mssql_query($subscribeCheck_query, $conn_hannam);
	$subscribeCheck_row = mssql_fetch_array($subscribeCheck_query_result);

	if($subscribeCheck_row['process'] == 0) {
		$subscribeDelete_query = "DELETE FROM new_subscribe_reserve WHERE item_type = $item_type AND item_seq = $flyer_seq";
		mssql_query($subscribeDelete_query, $conn_hannam);
	}

	echo "<script>location.href='?menu=menu1&list=weeklyFlyer'</script>";
}

if($flyer_seq) {
	$flyer_query = "SELECT seq, subject, CONVERT(char(19), start_date, 120) AS start_date, image_name, link, click_counter, click_counter_email, click_counter_mobile ".
				   "FROM new_regularUpdate ".
				   "WHERE type = $db_type_kor AND seq = $flyer_seq";
	$flyer_query_result = mssql_query($flyer_query, $conn_hannam);
	$flyer_row = mssql_fetch_array($flyer_query_result);

	$flyerChi_query = "SELECT image_name, link, click_counter, click_counter_email, click_counter_mobile ".
					  "FROM new_regularUpdate ".
					  "WHERE type = $db_type_chi AND seq = $flyer_seq";
	$flyerChi_query_result = mssql_query($flyerChi_query, $conn_hannam);
	$flyerChi_row = mssql_fetch_array($flyerChi_query_result);
}
?>

<form name="form_flyer" action="?menu=menu1&list=weeklyFlyer_write" enctype="multipart/form-data" method="post" accept-charset="utf-8">
<input type="hidden" name="flyer_seq" value="<?=$flyer_seq; ?>">
<input type="hidden" name="del_image_kor">
<input type="hidden" name="del_pdf_kor">
<input type="hidden" name="del_image_chi">
<input type="hidden" name="del_pdf_chi">
<input type="hidden" name="mode">
<div class="content_wrapper">
	<div class="h1_wrapper stripped">
		<h1>Weekly Flyer - Weekly Flyer</h1>
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
							<input type="text" name="flyer_subject" size="70" class="simpleform" value='<?=Br_iconv($flyer_row['subject']); ?>'>
						</td>
					</tr>
					<tr class="bb">
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							시작일:
						</td>
						<td>
							<input type="text" name="flyer_date" size="15" class="simpleform" value='<?=(($flyer_row['start_date']) ? $flyer_row['start_date'] : date('Y-m-d 21:50:00') ); ?>' required>
							<span style="color:#7a7a7a;">※ 매주 목요일 21:50:00 으로 설정</span>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							이미지<br>(Korean)
						</td>
						<td style="padding-bottom:5px;">
							<div name="image_wrapper_kor">
								<? if(file_exists($image_path_kor.(($flyer_row['image_name']) ? $flyer_row['image_name'] : "NULL" ))) { ?>
									<img src='<?=$image_path_kor.$flyer_row['image_name']; ?>' width="410px" height="300px">
									<span style="color:red; font-weight:bold; cursor:pointer;" onClick="flyer_delete_img('kor', 'image')">[삭제]</span><br>
								<? } else { ?>
									<input type="file" name="flyer_image_kor" size="20" class="simpleform" accept="image/jpeg, image/JPG, image/PNG, image/png, image/gif, image/GIF">
									<span style="color:#7a7a7a;">※ width: 226px / height: 292px</span>
								<? } ?>
							</div>
						</td>
					</tr>
					<tr class="bb">
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							PDF<br>(Korean)
						</td>
						<td style="padding-bottom:5px;">
							<div name="pdf_wrapper_kor">
								<? if(file_exists($image_path_kor.(($flyer_row['link']) ? $flyer_row['link'] : "NULL" ))) { ?>
									<img src='../img/admin/file_icon.gif' style="vertical-align:middle">
									<a href='<?=$image_path_kor.$flyer_row['link']; ?>' style="text-decoration:none; vertical-align:middle" target="pdf"><?=$flyer_row['link']?></a>&nbsp;
									<span style="color:red; font-weight:bold; cursor:pointer;" onClick="flyer_delete_img('kor', 'pdf')">[삭제]</span><br>
								<? } else { ?>
									<input type="file" name="flyer_pdf_kor" size="20" class="simpleform">
								<? } ?>
							</div>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							이미지<br>(Chinese)
						</td>
						<td style="padding:5px 0;">
							<div name="image_wrapper_chi">
								<? if(file_exists($image_path_chi.(($flyerChi_row['image_name']) ? $flyerChi_row['image_name'] : "NULL" ))) { ?>
									<img src='<?=$image_path_chi.$flyerChi_row['image_name']; ?>' width="215px" height="300px">
									<span style="color:red; font-weight:bold; cursor:pointer;" onClick="flyer_delete_img('chi', 'image')">[삭제]</span><br>
								<? } else { ?>
									<input type="file" name="flyer_image_chi" size="20" class="simpleform" accept="image/jpeg, image/JPG, image/PNG, image/png, image/gif, image/GIF">
									<span style="color:#7a7a7a;">※ width: 226px / height: 292px</span>
								<? } ?>
							</div>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							PDF<br>(Chinese)
						</td>
						<td style="padding-bottom:5px;">
							<div name="pdf_wrapper_chi">
								<? if(file_exists($image_path_chi.(($flyerChi_row['link']) ? $flyerChi_row['link'] : "NULL" ))) { ?>
									<img src='../img/admin/file_icon.gif' style="vertical-align:middle">
									<a href='<?=$image_path_chi.$flyerChi_row['link']; ?>' style="text-decoration:none; vertical-align:middle" target="pdf"><?=$flyerChi_row['link']?></a>&nbsp;
									<span style="color:red; font-weight:bold; cursor:pointer;" onClick="flyer_delete_img('chi', 'pdf')">[삭제]</span><br>
								<? } else { ?>
									<input type="file" name="flyer_pdf_chi" size="20" class="simpleform">
								<? } ?>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<? if($flyer_seq) { ?>
			<tr>
				<td>
					<img src="../img/admin/detail_dot_red.gif">
					<span class="content_link">조회수 - Korean</span>
				</td>
			</tr>
			<tr class="bb" style="padding-bottom:10px;">
				<td>
					<table width="100%">
						<tr>
							<td width="100px;">
								<img src="../img/admin/detail_dot_grey.gif">
								Weekly Flyer
							</td>
							<td style="padding-left:10px;"><?=$flyer_row['click_counter']; ?></td>
						</tr>
						<tr>
							<td width="100px;">
								<img src="../img/admin/detail_dot_grey.gif">
								Email
							</td>
							<td style="padding-left:10px;"><?=$flyer_row['click_counter_email']; ?></td>
						</tr>
						<tr>
							<td width="100px;">
								<img src="../img/admin/detail_dot_grey.gif">
								Mobile
							</td>
							<td style="padding-left:10px;"><?=$flyer_row['click_counter_mobile']; ?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<img src="../img/admin/detail_dot_red.gif">
					<span class="content_link">조회수 - Chinese</span>
				</td>
			</tr>
			<tr class="bb" style="padding-bottom:10px;">
				<td>
					<table width="100%">
						<tr>
							<td width="100px;">
								<img src="../img/admin/detail_dot_grey.gif">
								Weekly Flyer
							</td>
							<td style="padding-left:10px;"><?=$flyerChi_row['click_counter']; ?></td>
						</tr>
						<tr>
							<td width="100px;">
								<img src="../img/admin/detail_dot_grey.gif">
								Email
							</td>
							<td style="padding-left:10px;"><?=$flyerChi_row['click_counter_email']; ?></td>
						</tr>
						<tr>
							<td width="100px;">
								<img src="../img/admin/detail_dot_grey.gif">
								Mobile
							</td>
							<td style="padding-left:10px;"><?=$flyerChi_row['click_counter_mobile']; ?></td>
						</tr>
					</table>
				</td>
			</tr>
		<? } ?>
	</table>

	<div style="margin-top:30px;">
		<div style="float:left;"><input type="button" class="btn" onClick="location.href='?menu=menu1&list=weeklyFlyer'" value="목록"></div>
		<div style="float:right;">
			<? if(!$flyer_seq) { ?>	<input type="button" class="btn" onClick="flyer_save_form('save')" value="저장">
			<? } else { ?>
				<input type="button" class="btn" onClick="flyer_delete()" value="삭제">
				<input type="button" class="btn" onClick="flyer_save_form('update')" value="저장">
			<? } ?>
		</div>
	</div>
</div>
</form>