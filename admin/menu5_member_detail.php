<script>
function check_member_id() {
	var member_id = document.getElementsByName("member_id")[0].value;

	if (!member_id || member_id == "") {
		alert("멤버 아이디 오류");
		return;
	} else {
		document.getElementById("member_id_check_result").innerHTML = '<img src="../img/ajax-loader.gif" height="14px">';

		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var parse_script = xmlhttp.responseText;
				if (match = parse_script.match(/<script[^>]*>(([^<]|\n|\r|<[^\/])+)<\/script>/)) {
					eval.call(window, match[1]);
				}
				document.getElementById("member_id_check_result").innerHTML = xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET", "customer_handler.php?mode=member_idCheck&id=" + member_id, true);
		xmlhttp.send();
	}
}

function reset_member_id() {
	document.getElementsByName("mId_check")[0].value = 0;

	document.getElementById("member_id_check_result").innerHTML = '<input type="button" onClick="check_member_id()" value="중복확인">';
}

function member_save_form(type) {
	var mode = type + "_save";
	var form = document.forms.form_member;
	var form_check = true;

	if(!form.member_id.value) {
		form_check = false;
		form.member_id.style.borderColor = "#dd4b39";
	}
	if(!form.member_password.value) {
		form_check = false;
		form.member_password.style.borderColor = "#dd4b39";
	}
	if(!form.member_name.value) {
		form_check = false;
		form.member_name.style.borderColor = "#dd4b39";
	}

	if(!form_check) {
		alert("Please fill out all mandatory fields (*).\n빨간색(*) 표시된 필수기입사항을 입력해주세요.");
		return false;
	} else {
		if(form.mId_check.value == 0 || form.mId_check.value == "" || !form.mId_check.value) {
			alert("아이디 중복체크 오류");
			form.member_id.focus();
			form.member_id.style.borderColor = "#dd4b39";
			return false;
		}

		if(type == "old")	var answer = confirm("수정 하시겠습니까?");
		if(type == "new")	var answer = confirm("등록 하시겠습니까?");

		if(answer) {
			form.mode.value = mode;
			form.submit();
		}
	}
}
</script>

<?
/***************************************
*	DB - new_member
****************************************/
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
$seq = ($_GET['seq']) ? $_GET['seq'] : $_POST['seq'];
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);

if($mode) {
	$member_id = $_POST['member_id'];
	$member_password = $_POST['member_password'];
	$member_name = Br_dconv($_POST['member_name']);
	$member_code = $_POST['member_code'];
	$member_company = $_POST['member_company'];
	$member_level = $_POST['member_level'];
	$member_status = $_POST['member_status'];
	$memebr_inputUser = $_SESSION['memberId'];

	if($mode == "old_save") {
		$memberUpdate_query = "UPDATE new_member SET ".
							  "mPassword = '$member_password', mName = '$member_name', mCode = '$member_code', ".
							  "mLevel = '$member_level', mCompany = '$member_company', mStatus = $member_status, ".
							  "mModDate = GETDATE(), mModId = '$memebr_inputUser' ".
							  "WHERE seq = $seq ";
		mssql_query($memberUpdate_query, $conn_hannam);
	}
	if($mode == "new_save") {
		$memberGetSeq_query = "SELECT TOP 1 seq FROM new_member ORDER BY seq DESC";
		$memberGetSeq_query_result = mssql_query($memberGetSeq_query, $conn_hannam);
		$memberGetSeq_row = mssql_fetch_array($memberGetSeq_query_result);

		if($memberGetSeq_row['seq'])	$member_seq = $memberGetSeq_row['seq'] + 1;
		else							$member_seq = 1;

		$memberAdd_query = "INSERT INTO new_member (seq, mId, mPassword, mName, mCode, mCompany, mLevel, mStatus, mIssuerId) ".
						   "VALUES ($member_seq, '$member_id', '$member_password', '$member_name', '$member_code', '$member_company', '$member_level', $member_status, '$memebr_inputUser') ";
		mssql_query($memberAdd_query, $conn_hannam);
		$seq = $member_seq;
	}
}

if($list == "member_detail") {
	$member_query = "SELECT TOP 1 *, CONVERT(char(19), mRegDate, 120) AS mRegDate, CONVERT(char(19), mLoginDate, 120) AS mLoginDate FROM new_member WHERE seq = $seq ";
	$member_query_result = mssql_query($member_query, $conn_hannam);
	$member_row = mssql_fetch_array($member_query_result);
}
?>

<form name="form_member" action="?menu=menu5&list=member_detail" method="post" accept-charset="utf-8">
<input type="hidden" name="seq" value="<?=$seq; ?>">
<input type="hidden" name="mode">
<input type="hidden" name="mId_check" value="<?=(($seq) ? 1 : 0 ); ?>">
<div class="content_wrapper">
	<div class="h1_wrapper stripped">
		<h1>Member List - <?=(($mId) ? "Detail" : "New" ); ?></h1>
	</div>

	<table class="table_admin" cellspacing="0" cellpadding="0">
		<tr height="30px"></tr>
		<tr>
			<td>
				<img src="../img/admin/detail_dot_red.gif">
				<span class="content_link">Member - 추가 / 수정</span>
			</td>
		</tr>

		<tr class="bb" style="padding-bottom:10px;">
			<td>
				<table width="100%">
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							아이디:<span style="color:red;">*</span>
						</td>
						<td>
							<? if($list == "member_add") { ?>
								<input type="text" name="member_id" size="30" class="simpleform" value="<?=trim($member_row['mId']); ?>" onkeyup='reset_member_id(); if(event.keyCode==13) check_member_id();'>
								<span id="member_id_check_result" style="padding-left:10px;"><input type="button" onClick="check_member_id()" value="중복확인"></span>
							<? } else { ?>
								<input type="text" name="member_id" size="30" class="simpleform" value="<?=trim($member_row['mId']); ?>" disabled>
							<? } ?>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							비밀번호:<span style="color:red;">*</span>
						</td>
						<td><input type="text" name="member_password" size="30" class="simpleform" value="<?=trim($member_row['mPassword']); ?>"></td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							이름:<span style="color:red;">*</span>
						</td>
						<td><input type="text" name="member_name" size="10" class="simpleform" value="<?=Br_iconv($member_row['mName']); ?>"></td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							사원번호:
						</td>
						<td><input type="text" name="member_code" size="10" class="simpleform" value="<?=trim($member_row['mCode']); ?>"></td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							소속회사:
						</td>
						<td>
							<select name="member_company" class="simpleform">
								<option value="B" <?=(($member_row['mCompany'] == "B") ? "selected" : "" ); ?>>BBY</option>
								<option value="S" <?=(($member_row['mCompany'] == "S") ? "selected" : "" ); ?>>SRY</option>
								<option value="T" <?=(($member_row['mCompany'] == "T") ? "selected" : "" ); ?>>TB</option>
								<option value="M" <?=(($member_row['mCompany'] == "M") ? "selected" : "" ); ?>>MANNA</option>
								<option value="W" <?=(($member_row['mCompany'] == "W") ? "selected" : "" ); ?>>WESTVIEW</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							계정등급:
						</td>
						<td>
							<select name="member_level" class="simpleform">
								<option value="admin" <?=(($member_row['mLevel'] == "admin") ? "selected" : "" ); ?>>Admin</option>
								<option value="manager" <?=(($member_row['mLevel'] == "manager") ? "selected" : "" ); ?>>Manager</option>
								<option value="regular" <?=(($member_row['mLevel'] == "regular") ? "selected" : "" ); ?>>Regular</option>
							</select>
						</td>
					</tr>
					<tr class="bb">
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							계정상태:
						</td>
						<td>
							<select name="member_status" class="simpleform">
								<option value="1" <?=(($member_row['mStatus']) ? "selected" : "" ); ?>>Active</option>
								<option value="0" <?=((!$member_row['mStatus']) ? "selected" : "" ); ?>>Inactive</option>
							</select>
						</td>
					</tr>

					<? if($list == 'member_detail') { ?>
						<tr>
							<td width="100px;">
								<img src="../img/admin/detail_dot_grey.gif">
								계정등록 사원:
							</td>
							<td><?=$member_row['mIssuerId'] ?></td>
						</tr>
						<tr>
							<td width="100px;">
								<img src="../img/admin/detail_dot_grey.gif">
								계정등록일:
							</td>
							<td><?=$member_row['mRegDate'] ?></td>
						</tr>
						<tr>
							<td width="100px;">
								<img src="../img/admin/detail_dot_grey.gif">
								마지막 접속일:
							</td>
							<td><?=$member_row['mLoginDate'] ?></td>
						</tr>
					<? } ?>
				</table>
			</td>
		</tr>
	</table>

	<div style="margin-top:30px;">
		<div style="float:left;"><input type="button" class="btn" onClick="location.href='?menu=menu5&list=member_list'" value="목록"></div>
		<div style="float:right;"><input type="button" class="btn" onClick="member_save_form('<?=(($list == 'member_detail') ? 'old' : 'new' ); ?>')" value="<?=(($list == 'member_detail') ? '수정' : '등록' ); ?>"></div>
	</div>
</div>
</form>