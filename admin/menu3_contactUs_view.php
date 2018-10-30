<script>
function contactus_save_form() {
	var form = document.forms.form_answer;
	var form_check = true;

	if(!form.contactus_answer.value) {
		form_check = false;
		form.contactus_answer.style.borderColor = "#dd4b39";
	}

	if(!form_check) {
		alert("답변 내용 오류");
		return false;
	} else {
		var answer = confirm("답변 하시겠습니까:?");
		if(answer) {
			form.mode.value = "contactus_answer";
			form.submit();
		}
	}
}
</script>

<?
/***************************************
*	DB - new_contactUs
****************************************/
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
$seq = ($_GET['seq']) ? $_GET['seq'] : $_POST['seq'];
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);

if($mode == "contactus_answer") {
	$quote = array("\'", '\"');
	$replace_quote = array("''", '"');
	$contactus_answer = nl2br(Br_dconv(str_replace($quote, $replace_quote, $_POST['contactus_answer'])));

	$contactusAddAnswer_query = "UPDATE new_contactus SET status = 1, answer = '$contactus_answer', answer_date = GETDATE() WHERE seq = $seq";
	mssql_query($contactusAddAnswer_query, $conn_hannam);

	// Send E-Mail
	$mail_query = "SELECT name, email, subject, content FROM new_contactUs WHERE seq = $seq";
	$mail_query_result = mssql_query($mail_query, $conn_hannam);
	$mail_row = mssql_fetch_array($mail_query_result);

	$fromName = "Hannam Supermarket";
	$fromEmail = "prteam@hannamsm.com";
	$toName = Br_iconv($mail_row['name']);
	$toEmail = $mail_row['email'];
	$subject = "[Hannam Supermarket] 답변: ".Br_iconv($mail_row['subject']);
	$content = Br_iconv($contactus_answer);
	$content .= "<br><br><br><br>"."<div style='border-top:1px solid #B5C4DF; padding-top:20px;'>".Br_iconv($mail_row['content'])."</div>";
	sendMail($fromName, $fromEmail, $toName, $toEmail, $subject, $content, $isDebug=0);
}

$contactus_query = "SELECT *, CONVERT(char(19), submit_date, 120) AS submit_date, CONVERT(char(19), answer_date, 120) AS answer_date FROM new_contactUs WHERE seq = $seq";
$contactus_query_result = mssql_query($contactus_query, $conn_hannam);
$contactus_row = mssql_fetch_array($contactus_query_result);
?>

<div class="content_wrapper">
	<div class="h1_wrapper stripped">
		<h1>Customer Service - Contact Us</h1>
	</div>

	<table class="table_admin" cellspacing="0" cellpadding="0">
		<tr height="30px"></tr>
		<tr>
			<td>
				<img src="../img/admin/detail_dot_red.gif">
				<span class="content_link">Contact Us - 답변하기</span>
			</td>
		</tr>

		<tr class="bb" style="padding-bottom:10px;">
			<td>
				<table width="100%">
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							번호:&nbsp;&nbsp;
						</td>
						<td>
							<?=$contactus_row['seq']; ?>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							상태:&nbsp;&nbsp;
						</td>
						<td>
							<?=(($contactus_row['status'] == 1) ? "<span style='color:blue'>답변완료 (".$contactus_row['answer_date'].")</span>" : "<span style='color:red'>답변대기</span>" ); ?>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							접수일:&nbsp;&nbsp;
						</td>
						<td>
							<?=$contactus_row['submit_date']; ?>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							E-Mail:&nbsp;&nbsp;
						</td>
						<td>
							<?=$contactus_row['email']; ?>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							접수자:&nbsp;&nbsp;
						</td>
						<td>
							<?=Br_iconv($contactus_row['name']); ?>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							제목:&nbsp;&nbsp;
						</td>
						<td>
							<?=Br_iconv($contactus_row['subject']); ?>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							내용:&nbsp;&nbsp;
						</td>
						<td>
							<textarea name="contactus_answer" cols="80" rows="10" class="simpleform" style="resize:vertical; margin:10px 0;" disabled><?=str_replace("<br />", "", Br_iconv($contactus_row['content'])); ?></textarea>
						</td>
					</tr>

					<form name="form_answer" action="?menu=menu3&list=contactus_view" method="post" accept-charset="utf-8">
					<input type="hidden" name="seq" value="<?=$seq; ?>">
					<input type="hidden" name="mode">
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							답변:&nbsp;&nbsp;
						</td>
						<td>
							<textarea name="contactus_answer" cols="80" rows="20" class="simpleform" style="resize:vertical; margin:10px 0;" <?=(($contactus_row['status'] == 1) ? "disabled" : ""); ?>><?=str_replace("<br />", "", Br_iconv($contactus_row['answer'])); ?></textarea>
						</td>
					</tr>
					</form>
				</table>
			</td>
		</tr>
	</table>

	<div style="margin-top:30px;">
		<div style="float:left;"><input type="button" class="btn" onClick="location.href='?menu=menu3&list=contactus'" value="목록"></div>
		<? if($contactus_row['status'] == 2) { ?>
			<div style="float:right;">
				<input type="button" class="btn" onClick="contactus_save_form()" value="답변하기">
			</div>
		<? } ?>
		</div>
</div>