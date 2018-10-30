<script>
function add_image() {
	var max_image_num = 5;
	if(document.getElementById("image_display_div") == null) {
		var added_image_num = document.getElementsByName("boardNews_image[]").length;
	} else {
		var added_image_num = document.getElementsByName("boardNews_image[]").length + document.getElementById("image_display_div").getElementsByTagName("div").length;
	}
	
	if(added_image_num >= max_image_num) {
		document.getElementsByName("more_image")[0].style.display = "none";
	} else {
		var wrapper = document.createElement("div");
		document.getElementById("image_input_div").appendChild(wrapper);

		var add_image = document.createElement("input");
		add_image.setAttribute("type", "file");
		add_image.setAttribute("name", "boardNews_image[]");
		add_image.setAttribute("class", "simpleform");
		add_image.setAttribute("style", "margin:5px;");
		add_image.setAttribute("accept", "image/jpeg, image/JPG, image/PNG, image/png, image/gif, image/GIF");
		wrapper.appendChild(add_image);

		var del_image = document.createElement("span");
		del_image.setAttribute("style", "color:red; font-weight:bold; cursor:pointer");
		del_image.setAttribute("onClick", "del_input(this, 'image')");
		del_image.appendChild(document.createTextNode("[삭제]"));
		add_image.parentNode.insertBefore(del_image, add_image.nextSibling);

		var line_break = document.createElement("br");
		del_image.parentNode.insertBefore(line_break, del_image.nextSibling);

		if(document.getElementById("image_display_div") == null) {
			var added_image_num = document.getElementsByName("boardNews_image[]").length;
		} else {
			var added_image_num = document.getElementsByName("boardNews_image[]").length + document.getElementById("image_display_div").getElementsByTagName("div").length;
		}
		if(added_image_num >= max_image_num) {
			document.getElementsByName("more_image")[0].style.display = "none";
		}
	}
}

function add_file() {
	var max_file_num = 5;
	if(document.getElementById("file_display_div") == null) {
		var added_file_num = document.getElementsByName("boardNews_file[]").length;
	} else {
		var added_file_num = document.getElementsByName("boardNews_file[]").length + document.getElementById("file_display_div").getElementsByTagName("div").length;
	}
	
	if(added_file_num >= max_file_num) {
		document.getElementsByName("more_file")[0].style.display = "none";
	} else {
		var wrapper = document.createElement("div");
		document.getElementById("file_input_div").appendChild(wrapper);

		var add_file = document.createElement("input");
		add_file.setAttribute("type", "file");
		add_file.setAttribute("name", "boardNews_file[]");
		add_file.setAttribute("class", "simpleform");
		add_file.setAttribute("style", "margin:5px;");
		wrapper.appendChild(add_file);

		var del_file = document.createElement("span");
		del_file.setAttribute("style", "color:red; font-weight:bold; cursor:pointer");
		del_file.setAttribute("onClick", "del_input(this, 'file')");
		del_file.appendChild(document.createTextNode("[삭제]"));
		add_file.parentNode.insertBefore(del_file, add_file.nextSibling);

		var line_break = document.createElement("br");
		del_file.parentNode.insertBefore(line_break, del_file.nextSibling);

		if(document.getElementById("file_display_div") == null) {
			var added_file_num = document.getElementsByName("boardNews_file[]").length;
		} else {
			var added_file_num = document.getElementsByName("boardNews_file[]").length + document.getElementById("file_display_div").getElementsByTagName("div").length;
		}
		if(added_file_num >= max_file_num) {
			document.getElementsByName("more_file")[0].style.display = "none";
		}
	}
}

function del_input(seq, type) {
	seq.parentNode.parentNode.removeChild(seq.parentNode);
	
	if(document.getElementsByName("more_" + type)[0].style.display == "none") {
		document.getElementsByName("more_" + type)[0].style.display = "";
	}
}

function del_thumb(target) {
	var answer = confirm("Thumbnail 이미지를 삭제 하시겠습니까?");
	if(answer) {
		var div_thumb = target.parentNode;
		while(div_thumb.firstChild) {
			div_thumb.removeChild(div_thumb.firstChild);
		}

		var input = document.createElement("input");
		input.setAttribute("type", "file");
		input.setAttribute("name", "boardNews_thumb");
		input.setAttribute("class", "simpleform");
		input.setAttribute("accept", "image/jpeg, image/JPG, image/PNG, image/png, image/gif, image/GIF");
		div_thumb.appendChild(input);

		var span = document.createElement("span");
		span.setAttribute("style", "color:#7a7a7a;");
		span.innerHTML = "※ width: 65px / height: 39px";
		div_thumb.appendChild(span);

		var form_del_thumb = document.createElement("input");
		form_del_thumb.setAttribute("type", "hidden");
		form_del_thumb.setAttribute("name", "del_thumb");
		document.forms.form_news_write.appendChild(form_del_thumb);
		document.forms.form_news_write.form_del_thumb.value = 1;
	}
}

function del_upload(target, type) {
	var answer = confirm("삭제 하시겠습니까?");
	if(answer) {
		target.parentNode.parentNode.removeChild(target.parentNode);

		var form = document.forms.form_news_write;
		upload_seq = target.parentNode.id;

		if(type == "image") {
			if(form.del_image) {
				form.del_image.value += upload_seq.toString();
			} else {
				var form_del_image = document.createElement("input");
				form_del_image.setAttribute("type", "hidden");
				form_del_image.setAttribute("name", "del_image");
				form.appendChild(form_del_image);
				form.del_image.value = upload_seq.toString();
			}
		} else if(type == "file") {
			if(form.del_file) {
				form.del_file.value += upload_seq.toString();
			} else {
				var form_del_file = document.createElement("input");
				form_del_file.setAttribute("type", "hidden");
				form_del_file.setAttribute("name", "del_file");
				form.appendChild(form_del_file);
				form.del_file.value = upload_seq.toString();
			}
		}

		if(document.getElementsByName("more_" + type)[0].style.display == "none") {
			document.getElementsByName("more_" + type)[0].style.display = "";
		}

		var count_upload = document.getElementById(type + "_display_div").getElementsByTagName("div").length;
		if(count_upload == 0) {
			document.getElementById(type + "_display_div").parentNode.removeChild(document.getElementById(type + "_display_div"));
		}
	}
}

function news_write_save(mode) {
	var target = document.forms.form_news_write;
	if(target.boardNews_subject.value == "") {
		alert("제목 입력 오류");
		return false;
	}

	var answer = confirm("저장 하시겠습니까?");
	if(answer) {
		target.mode.value = mode;
		target.submit();
	}
}

function news_delete() {
	var target = document.forms.form_news_write;
	var answer = confirm("이 글을 삭제 하시겠습니까?");
	if(answer) {
		target.mode.value = "delete";
		target.submit();
	}
}
</script>

<?
/***************************************
*	DB - new_board (Type = 2)
****************************************/
/***************************************
*	DB - new_board_upload	
*	upload_type = 1 -> image
*	upload_type = 2 -> file
****************************************/

$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
$upload_path = "../upload/news/";
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);

$boardNews_seq = ($_GET['seq']) ? $_GET['seq'] : $_POST['seq'];
$board_type = 2;
$maxImg_count = 5;
$maxFile_count = 5;

if($mode == "save") {
	$boardNews_subject = Br_dconv($_POST['boardNews_subject']);
	$boardNews_date = Br_dconv($_POST['boardNews_date']);
	$boardNews_status = $_POST['boardNews_status'];
	$boardNews_premium = $_POST['boardNews_premium'];
	$boardNews_content = nl2br(Br_dconv($_POST['boardNews_content']));

	//echo "boardNews_subject - ".$boardNews_subject."<br>";
	//echo "boardNews_status - ".$boardNews_status."<br>";
	//echo "boardNews_premium - ".$boardNews_premium."<br>";
	//echo "boardNews_content - ".$boardNews_content."<br>";

	if($boardNews_subject) {
		// Getting last seq number
		$boardNewsGetSeq_query = "SELECT TOP 1 seq FROM new_board WHERE type = $board_type ORDER BY seq DESC";
		$boardNewsGetSeq_query_result = mssql_query($boardNewsGetSeq_query, $conn_hannam);
		$boardNewsGetSeq_row = mssql_fetch_array($boardNewsGetSeq_query_result);

		if($boardNewsGetSeq_row['seq'])	$boardNews_seq = $boardNewsGetSeq_row['seq'] + 1;
		else							$boardNews_seq = 1;
		
		// Inserting new content
		$boardNewsAdd_query = "INSERT INTO new_board (type, seq, subject, content, upload_date, click_counter, active, premium) ".
							  "VALUES ($board_type, $boardNews_seq, '$boardNews_subject', '$boardNews_content', '$boardNews_date', 0, $boardNews_status, $boardNews_premium) ";
		mssql_query($boardNewsAdd_query, $conn_hannam);

		// Inserting new thumb
		if($_FILES['boardNews_thumb']['error'] == 0) {
			$ableExt = array('jpg','jpeg','gif','png','bmp');
			$path = pathinfo($_FILES['boardNews_thumb']['name']);
			$ext = strtolower($path['extension']);

			if(in_array($ext, $ableExt)) {
				$ableImage = array('image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif','image/bmp','image/pjpeg');

				if(in_array($_FILES['boardNews_thumb']['type'], $ableImage)) {
					$new_filename = $boardNews_seq."_thumb.".$ext;
					$new_fullpath = $upload_path.$new_filename;

					$boardNewsUpload_query = "UPDATE new_board SET image_thumb = '$new_filename' WHERE type = $board_type AND seq = '$boardNews_seq' ";
					mssql_query($boardNewsUpload_query, $conn_hannam);

					move_uploaded_file($_FILES['boardNews_thumb']['tmp_name'], $new_fullpath);
				}
			}
		}

		// Inserting new image
		if($_FILES['boardNews_image']) {
			for($i = 0; $i < count($_FILES['boardNews_image']['name']); $i++) {
				if($_FILES['boardNews_image']['error'][$i] == 0) {
					$ableExt = array('jpg','jpeg','gif','png','bmp');
					$path = pathinfo($_FILES['boardNews_image']['name'][$i]);
					$ext = strtolower($path['extension']);

					if(in_array($ext, $ableExt)) {
						$ableImage = array('image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif','image/bmp','image/pjpeg');

						if(in_array($_FILES['boardNews_image']['type'][$i], $ableImage)) {
							$upload_type = 1;
							$new_filename = $boardNews_seq."_".($i+1).".".$ext;
							$new_fullpath = $upload_path.$new_filename;

							$boardNewsUpload_query = "INSERT INTO new_board_upload (type, seq, upload_type, upload_seq, upload_name, upload_date) ".
													 "VALUES ($board_type, $boardNews_seq, $upload_type, ($i+1), '$new_filename', GETDATE()) ";
							mssql_query($boardNewsUpload_query, $conn_hannam);

							move_uploaded_file($_FILES['boardNews_image']['tmp_name'][$i], $new_fullpath);
						}
					}
				}
			}
		}
		
		// Inserting new file
		if($_FILES['boardNews_file']) {
			for($i = 0; $i < count($_FILES['boardNews_file']['name']); $i++) {
				if($_FILES['boardNews_file']['error'][$i] == 0) {
					if($_FILES['boardNews_file']['size'][$i] <= 5242880) {
						$upload_type = 2;
						$new_filename = $boardNews_seq."_".($i+1)."_".$_FILES['boardNews_file']['name'][$i];
						$new_fullpath = $upload_path.$new_filename;

						$boardNewsUpload_query = "INSERT INTO new_board_upload (type, seq, upload_type, upload_seq, upload_name, upload_date) ".
												 "VALUES ($board_type, $boardNews_seq, $upload_type, ($i+1), '$new_filename', GETDATE()) ";
						mssql_query($boardNewsUpload_query, $conn_hannam);

						move_uploaded_file($_FILES['boardNews_file']['tmp_name'][$i], $new_fullpath);
					}
				}
			}
		}
	}

} else if($mode == "update") {
	$boardNews_seq = $_POST['boardNews_seq'];
	$boardNews_subject = Br_dconv($_POST['boardNews_subject']);
	$boardNews_date = Br_dconv($_POST['boardNews_date']);
	$boardNews_status = $_POST['boardNews_status'];
	$boardNews_premium = $_POST['boardNews_premium'];
	$boardNews_content = nl2br(Br_dconv($_POST['boardNews_content']));
	$del_thumb = $_POST['del_thumb'];
	$del_image = $_POST['del_image'];
	$del_file = $_POST['del_file'];

	// Deleting Thumb
	if($del_thumb) {
		$boardGetThumbName_query = "SELECT image_thumb FROM new_board WHERE type = $board_type AND seq = $boardNews_seq";
		$boardGetThumbName_query_result = mssql_query($boardGetThumbName_query, $conn_hannam);
		$boardGetThumbName_row = mssql_fetch_array($boardGetThumbName_query_result);

		$fullpath = $upload_path.$boardGetThumbName_row['image_thumb'];
		unlink($fullpath);

		$boardDelThumb_query = "Update new_board SET image_thumb = '' WHERE type = $board_type AND seq = $boardNews_seq";
		mssql_query($boardDelThumb_query, $conn_hannam);
	}
	// Updating Thumb
	if($_FILES['boardNews_thumb']) {
		if($_FILES['boardNews_thumb']['error'] == 0) {
			$ableExt = array('jpg','jpeg','gif','png','bmp');
			$path = pathinfo($_FILES['boardNews_thumb']['name']);
			$ext = strtolower($path['extension']);

			if(in_array($ext, $ableExt)) {
				$ableImage = array('image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif','image/bmp','image/pjpeg');

				if(in_array($_FILES['boardNews_thumb']['type'], $ableImage)) {
					$new_filename = $boardNews_seq."_thumb.".$ext;
					$new_fullpath = $upload_path.$new_filename;

					$boardNewsUpload_query = "UPDATE new_board SET image_thumb = '$new_filename' WHERE type = $board_type AND seq = $boardNews_seq ";
					mssql_query($boardNewsUpload_query, $conn_hannam);

					move_uploaded_file($_FILES['boardNews_thumb']['tmp_name'], $new_fullpath);
				}
			}
		}
	}

	// Deleting Image
	if($del_image) {
		$upload_type = 1;
		for($i = 0; $i < strlen($del_image); $i++) {
			$boardGetImgName_query = "SELECT upload_name FROM new_board_upload WHERE type = $board_type AND seq = $boardNews_seq AND upload_type = $upload_type AND upload_seq = ".$del_image[$i];
			$boardGetImgName_query_result = mssql_query($boardGetImgName_query, $conn_hannam);
			$boardGetImgName_row = mssql_fetch_array($boardGetImgName_query_result);

			$fullpath = $upload_path.$boardGetImgName_row['upload_name'];
			unlink($fullpath);

			$boardDelImg_query = "DELETE FROM new_board_upload WHERE type = $board_type AND seq = $boardNews_seq AND upload_type = $upload_type AND upload_seq = ".$del_image[$i];
			mssql_query($boardDelImg_query, $conn_hannam);
		}
	}
	// Updating Image
	if($_FILES['boardNews_image']) {
		$upload_type = 1;
		$boardGetImgSeq_query = "SELECT TOP 1 upload_seq FROM new_board_upload WHERE type = $board_type AND seq = $boardNews_seq AND upload_type = $upload_type ORDER BY upload_seq DESC";
		$boardGetImgSeq_query_result = mssql_query($boardGetImgSeq_query, $conn_hannam);
		$boardGetImgSeq_row = mssql_fetch_array($boardGetImgSeq_query_result);
		
		if($boardGetImgSeq_row['upload_seq'])	$upload_seq = $boardGetImgSeq_row['upload_seq'] + 1;
		else									$upload_seq = 1;

		for($i = 0; $i < count($_FILES['boardNews_image']['name']); $i++) {
			if($_FILES['boardNews_image']['error'][$i] == 0) {
				$ableExt = array('jpg','jpeg','gif','png','bmp');
				$path = pathinfo($_FILES['boardNews_image']['name'][$i]);
				$ext = strtolower($path['extension']);

				if(in_array($ext, $ableExt)) {
					$ableImage = array('image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif','image/bmp','image/pjpeg');

					if(in_array($_FILES['boardNews_image']['type'][$i], $ableImage)) {
						$new_filename = $boardNews_seq."_".$upload_seq.".".$ext;
						$new_fullpath = $upload_path.$new_filename;

						$boardNewsUpload_query = "INSERT INTO new_board_upload (type, seq, upload_type, upload_seq, upload_name, upload_date) ".
												 "VALUES ($board_type, $boardNews_seq, $upload_type, $upload_seq, '$new_filename', GETDATE()) ";
						mssql_query($boardNewsUpload_query, $conn_hannam);

						move_uploaded_file($_FILES['boardNews_image']['tmp_name'][$i], $new_fullpath);
					}
				}
			}
			$upload_seq++;
		}
	}

	// Deleting File
	if($del_file) {
		$upload_type = 2;
		for($i = 0; $i < strlen($del_file); $i++) {
			$boardGetFileName_query = "SELECT upload_name FROM new_board_upload WHERE type = $board_type AND seq = $boardNews_seq AND upload_type = $upload_type AND upload_seq = ".$del_file[$i];
			$boardGetFileName_query_result = mssql_query($boardGetFileName_query, $conn_hannam);
			$boardGetFileName_row = mssql_fetch_array($boardGetFileName_query_result);

			$fullpath = $upload_path.$boardGetFileName_row['upload_name'];
			unlink($fullpath);

			$boardDelFile_query = "DELETE FROM new_board_upload WHERE type = $board_type AND seq = $boardNews_seq AND upload_type = $upload_type AND upload_seq = ".$del_file[$i];
			mssql_query($boardDelFile_query, $conn_hannam);
		}
	}
	// Updating File
	if($_FILES['boardNews_file']) {
		$upload_type = 2;
		$boardGetFileSeq_query = "SELECT TOP 1 upload_seq FROM new_board_upload WHERE type = $board_type AND seq = $boardNews_seq AND upload_type = $upload_type ORDER BY upload_seq DESC";
		$boardGetFileSeq_query_result = mssql_query($boardGetFileSeq_query, $conn_hannam);
		$boardGetFileSeq_row = mssql_fetch_array($boardGetFileSeq_query_result);
		
		if($boardGetFileSeq_row['upload_seq'])	$upload_seq = $boardGetFileSeq_row['upload_seq'] + 1;
		else							$upload_seq = 1;

		for($i = 0; $i < count($_FILES['boardNews_file']['name']); $i++) {
			if($_FILES['boardNews_file']['error'][$i] == 0) {
				if($_FILES['boardNews_file']['size'][$i] <= 5242880) {
					$upload_type = 2;
					$new_filename = $boardNews_seq."_".$upload_seq."_".$_FILES['boardNews_file']['name'][$i];
					$new_fullpath = $upload_path.$new_filename;

					$boardNewsUpload_query = "INSERT INTO new_board_upload (type, seq, upload_type, upload_seq, upload_name, upload_date) ".
											 "VALUES ($board_type, $boardNews_seq, $upload_type, $upload_seq, '$new_filename', GETDATE()) ";
					mssql_query($boardNewsUpload_query, $conn_hannam);

					move_uploaded_file($_FILES['boardNews_file']['tmp_name'][$i], $new_fullpath);
				}
			}
			$upload_seq++;
		}
	}
	
	// Updating Content
	if($boardNews_subject) {
		$boardNewsUpdate_query = "UPDATE new_board SET ".
								 "subject = '$boardNews_subject', content = '$boardNews_content', upload_date = '$boardNews_date', active = $boardNews_status, premium = $boardNews_premium ".
								 "WHERE type = $board_type AND seq = $boardNews_seq ";
		mssql_query($boardNewsUpdate_query, $conn_hannam);
	}

} else if($mode == "delete") {
	$boardNews_seq = $_POST['boardNews_seq'];

	// Deleting Thumb & DB
	$boardGetThumbName_query = "SELECT image_thumb FROM new_board WHERE type = $board_type and seq = $boardNews_seq";
	$boardGetThumbName_query_result = mssql_query($boardGetThumbName_query, $conn_hannam);
	$boardGetThumbName_row = mssql_fetch_array($boardGetThumbName_query_result);

	if($boardGetThumbName_row['image_thumb']) {
		$fullpath = $upload_path.$boardGetThumbName_row['image_thumb'];
		unlink($fullpath);
	}

	$boardDel_query = "DELETE FROM new_board WHERE type = $board_type AND seq = $boardNews_seq";
	mssql_query($boardDel_query, $conn_hannam);

	// Deleting Image
	$upload_type = 1;
	$boardGetImgName_query = "SELECT upload_name FROM new_board_upload WHERE type = $board_type AND seq = $boardNews_seq AND upload_type = $upload_type ORDER BY upload_seq";
	$boardGetImgName_query_result = mssql_query($boardGetImgName_query, $conn_hannam);

	while($boardGetImgName_row = mssql_fetch_array($boardGetImgName_query_result)) {
		$fullpath = $upload_path.$boardGetImgName_row['upload_name'];
		unlink($fullpath);
	}

	$boardDelImg_query = "DELETE FROM new_board_upload WHERE type = $board_type AND seq = $boardNews_seq AND upload_type = $upload_type";
	mssql_query($boardDelImg_query, $conn_hannam);

	// Deleting File
	$upload_type = 2;
	$boardGetFileName_query = "SELECT upload_name FROM new_board_upload WHERE type = $board_type AND seq = $boardNews_seq AND upload_type = $upload_type ORDER BY upload_seq";
	$boardGetFileName_query_result = mssql_query($boardGetFileName_query, $conn_hannam);

	while($boardGetFileName_row = mssql_fetch_array($boardGetFileName_query_result)) {
		$fullpath = $upload_path.$boardGetFileName_row['upload_name'];
		unlink($fullpath);
	}

	$boardDelFile_query = "DELETE FROM new_board_upload WHERE type = $board_type AND seq = $boardNews_seq AND upload_type = $upload_type";
	mssql_query($boardDelFile_query, $conn_hannam);

	echo "<script>location.href='?menu=menu4&list=news'</script>";
}

if($boardNews_seq) {
	$boardGetImg_query = "SELECT upload_seq, upload_name FROM new_board_upload WHERE type = $board_type AND seq = $boardNews_seq AND upload_type = 1 ORDER BY upload_seq";
	$boardGetImg_query_result = mssql_query($boardGetImg_query, $conn_hannam);
	$uploadImg_count = mssql_num_rows($boardGetImg_query_result);

	$boardGetFile_query = "SELECT upload_seq, upload_name FROM new_board_upload WHERE type = $board_type AND seq = $boardNews_seq AND upload_type = 2 ORDER BY upload_seq";
	$boardGetFile_query_result = mssql_query($boardGetFile_query, $conn_hannam);
	$uploadFile_count = mssql_num_rows($boardGetFile_query_result);

	$boardNews_query = "SELECT *, CONVERT(char(10), upload_date, 120) AS upload_date FROM new_board WHERE type = $board_type AND seq = $boardNews_seq";
	$boardNews_query_result = mssql_query($boardNews_query, $conn_hannam);
	$boardNews_row = mssql_fetch_array($boardNews_query_result);
}

?>

<form name="form_news_write" action="?menu=menu4&list=news_write" enctype="multipart/form-data" method="post" accept-charset="utf-8">
<input type="hidden" name="boardNews_seq" value="<?=$boardNews_seq; ?>">
<input type="hidden" name="mode">
<div class="content_wrapper">
	<div class="h1_wrapper stripped">
		<h1>Community - News</h1>
	</div>

	<table class="table_admin" cellspacing="0" cellpadding="0">
		<tr height="30px"></tr>
		<tr>
			<td>
				<img src="../img/admin/detail_dot_red.gif">
				<span class="content_link">News - 글 작성 / 수정</span>
				<span style="color:red; float:right;">* 필수 입력 사항</span>
			</td>
		</tr>

		<tr class="bb" style="padding-bottom:10px;">
			<td>
				<table width="100%">
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							제목:&nbsp;&nbsp;<span style="color:red;">*</span>
						</td>
						<td>
							<input type="text" name="boardNews_subject" size="70" class="simpleform" value='<?=Br_iconv($boardNews_row['subject']); ?>' required>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							날짜:&nbsp;&nbsp;<span style="color:red;">*</span>
						</td>
						<td>
							<input type="text" name="boardNews_date" size="15" class="simpleform" value='<?=(($boardNews_row['upload_date']) ? $boardNews_row['upload_date'] : date('Y-m-d') ); ?>' required>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							Thumb:
						</td>
						<td>
							<? if(file_exists($upload_path.(($boardNews_row['image_thumb']) ? $boardNews_row['image_thumb'] : "NULL" ))) { ?>
								<div>
									<img src='<?=$upload_path.$boardNews_row['image_thumb']; ?>' width="65px" height="39px">
									<span style="color:red; font-weight:bold; cursor:pointer;" onClick="del_thumb(this)">[삭제]</span>
								</div>
							<? } else { ?>
								<input type="file" name="boardNews_thumb" class="simpleform" accept="image/jpeg, image/JPG, image/PNG, image/png, image/gif, image/GIF">
								<span style="color:#7a7a7a;">※ width: 65px / height: 39px</span>
							<? } ?>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							상태:
						</td>
						<td>
							<select name="boardNews_status" class="simpleform">
								<option value="0" <?=(($boardNews_row['active'] == 0) ? "selected" : "" ); ?>>Inactive</option>
								<option value="1" <?=(($boardNews_row['active'] == 1 || !$boardNews_row['active']) ? "selected" : "" ); ?>>Active</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							최상단 등록:
						</td>
						<td>
							<select name="boardNews_premium" class="simpleform">
								<option value="0" <?=(($boardNews_row['premium'] == 0) ? "selected" : "" ); ?>>No</option>
								<option value="1" <?=(($boardNews_row['premium'] == 1) ? "selected" : "" ); ?>>YES</option>
							</select>
						</td>
					</tr>
					<tr class="bb">
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							내용:
						</td>
						<td>
							<textarea name="boardNews_content" cols="80" rows="20" class="simpleform" style="resize:vertical; margin-bottom:10px;"><?=str_replace("<br />", "", Br_iconv($boardNews_row['content'])); ?></textarea>
						</td>
					</tr>
					<tr class="bb">
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							이미지:
						</td>
						<td>
							<? if($uploadImg_count) { ?>
								<div id="image_display_div" style="margin-top:10px; width:100%; height:150px;">
									<? while($boardGetImg_row = mssql_fetch_array($boardGetImg_query_result)) { ?>
										<? if(file_exists($upload_path.(($boardGetImg_row['upload_name']) ? $boardGetImg_row['upload_name'] : "NULL" ))) { ?>
											<div style="width:20%; height:150px; float:left" id="<?=$boardGetImg_row['upload_seq']; ?>">
												<a href='<?=$upload_path.$boardGetImg_row['upload_name']; ?>' target="pdf"><img src='<?=$upload_path.$boardGetImg_row['upload_name']; ?>' width="90px" height="150px"></a>
												<span style="color:red; font-weight:bold; cursor:pointer;" onClick="del_upload(this, 'image')">[삭제]</span>
											</div>
										<? } ?>
									<? } ?>
								</div>
							<? } ?>

							<div id="image_input_div" style="margin:20px; float:left;"></div>
							<input type="button" name="more_image" class="addbtn" style="width:100px; cursor:pointer; margin:10px; float:right; <?=(($uploadImg_count < $maxImg_count) ? '' : 'display:none' ); ?>" onClick="add_image();" value="이미지 추가"/>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							첨부파일:
						</td>
						<td>
							<? if($uploadFile_count) { ?>
								<div id="file_display_div" style="margin-top:10px; width:100%; float:left;">
									<? while($boardGetFile_row = mssql_fetch_array($boardGetFile_query_result)) { ?>
										<? if(file_exists($upload_path.(($boardGetFile_row['upload_name']) ? $boardGetFile_row['upload_name'] : "NULL" ))) { ?>
											<div id="<?=$boardGetFile_row['upload_seq']; ?>">
												<img src='../img/admin/file_icon.gif' style="vertical-align:middle">
												<a href='<?=$upload_path.$boardGetFile_row['upload_name']; ?>' style="text-decoration:none; vertical-align:middle" target="pdf"><?=$boardGetFile_row['upload_name']?></a>
												<span style="color:red; font-weight:bold; cursor:pointer; vertical-align:middle" onClick="del_upload(this, 'file')">[삭제]</span>
											</div>
										<? } ?>
									<? } ?>
								</div>
							<? } ?>

							<div id="file_input_div" style="margin:20px; float:left;"></div>
							<input type="button" name="more_file" class="addbtn" style="width:100px; cursor:pointer; margin:10px; float:right; <?=(($uploadFile_count < $maxFile_count) ? '' : 'display:none' ); ?>" onClick="add_file();" value="파일 추가"/>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<div style="margin-top:30px;">
		<div style="float:left;"><input type="button" class="btn" onClick="location.href='?menu=menu4&list=news'" value="목록"></div>
		<div style="float:right;">
			<? if(!$boardNews_seq) { ?>	<input type="button" class="btn" onClick="news_write_save('save')" value="저장">
			<? } else { ?>
				<input type="button" class="btn" onClick="news_delete()" value="삭제">
				<input type="button" class="btn" onClick="news_write_save('update')" value="수정">
			<? } ?>
		</div>
	</div>
</div>
</form>