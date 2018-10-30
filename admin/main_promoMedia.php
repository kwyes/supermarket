<script>
function promoMedia_save_form() {
	var target = document.forms.form_promoMedia;
	var media_total = document.getElementsByName("promoMedia_link[]").length;

	var media_check = true;

	for(var i = 0; i < media_total; i++) {
		if(document.getElementsByName("promoMedia_link[]")[i].value == "") {
			alert(i+1 + "번 링크 오류");
			media_check = false;
			break;
		}
	}

	if(media_check) {
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
*	DB - new_mainPage (Type = 4)
****************************************/

$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);

if($mode == "save") {
	$promoMedia_link = $_POST['promoMedia_link'];

	for($i = 0; $i < sizeof($promoMedia_link); $i++) {
		$promoMediaModify_query = "UPDATE new_mainPage SET link = '".$promoMedia_link[$i]."' ".
								  "WHERE type = 4 AND seq = ".($i+1);
		mssql_query($promoMediaModify_query, $conn_hannam);
	}
}

$promoMedia_query = "SELECT seq, link FROM new_mainPage WHERE type = 4 ORDER BY seq";
$promoMedia_query_result = mssql_query($promoMedia_query, $conn_hannam);
?>

<form name="form_promoMedia" action="?menu=main&list=promoMedia" enctype="multipart/form-data" method="post" accept-charset="utf-8">
<input type="hidden" name="mode">
<div class="content_wrapper">
	<div class="h1_wrapper stripped">
		<h1>Main - Promotional Media</h1>
	</div>

	<table class="table_admin" cellspacing="0" cellpadding="0">
		<tr height="30px"></tr>
		<? while($promoMedia_row = mssql_fetch_array($promoMedia_query_result)) { ?>
			<tr>
				<td>
					<img src="../img/admin/detail_dot_red.gif">
					<span class="content_link">Promotional Media <?=$promoMedia_row['seq']; ?></span>
				</td>
			</tr>
			<tr class="bb" style="padding-bottom:10px;">
				<td>
					<table width="100%">
						<tr>
							<td width="100px;">
								<img src="../img/admin/detail_dot_grey.gif">
								링크:
							</td>
							<td>
								<span style="color:#7a7a7a;">http://www.youtube-nocookie.com/embed/&nbsp;</span>
								<input type="text" name="promoMedia_link[]" size="40" class="simpleform" value="<?=$promoMedia_row['link']; ?>">
							</td>
						</tr>
					</table>
				</td>
			</tr>
		<? } ?>
	</table>

	<div style="margin-top:30px;">
		<div style="float:left;"><input type="button" class="btn" onClick="location.href='?menu=main'" value="목록"></div>
		<div style="float:right;"><input type="button" class="btn" onClick="promoMedia_save_form()" value="저장하기"></div>
	</div>
</div>
</form>