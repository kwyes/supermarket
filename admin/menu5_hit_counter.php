<script>
function page_navigation(page) {
	location.href = "?menu=menu5&list=site_hit&page=" + page;
}

function autoHypenBday(target) {
	var str = target.value;
	str = str.replace(/[^0-9]/g, "");
	var tmp = '';
	if(str.length < 5) {
		target.value = str;
		return;
	} else if(str.length < 7) {
		tmp += str.substr(0, 4);
		tmp += '-';
		tmp += str.substr(4);
		target.value = tmp;
		return;
	} else if(str.length < 9) {
		tmp += str.substr(0, 4);
		tmp += '-';
		tmp += str.substr(4, 2);
		tmp += '-';
		tmp += str.substr(6);
		target.value = tmp;
		return;
	} else {
		tmp += str.substr(0, 4);
		tmp += '-';
		tmp += str.substr(4, 2);
		tmp += '-';
		tmp += str.substr(6, 2);
		target.value = tmp;
		return;
	}
	target.value = str;
	return;
}

function get_hitCount() {
	var date_start = document.getElementById("start_date").value;
	var date_end = document.getElementById("end_date").value;

	if(date_start.length != 10) {
		alert("시작일 오류!");
		return false;
	}
	if(date_end.length != 10) {
		alert("종료일 오류!");
		return false;
	}

	document.getElementById("hitCount_search_btn").style.display = "none";
	document.getElementById("hitCount_search_loading").style.display = "";

	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById("hit_search_result").innerHTML = xmlhttp.responseText;
			
			document.getElementById("hitCount_search_loading").style.display = "none";
			document.getElementById("hitCount_search_btn").style.display = "";
		}
	}
	xmlhttp.open("GET", "customer_handler.php?mode=hit_count&date_start=" + date_start + "&date_end=" + date_end, true);
	xmlhttp.send();

}
</script>

<?
/***************************************
*	DB - new_hit_counter
****************************************/
$page = ($_GET['page']) ? $_GET['page'] : $_POST['page'];
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);

$per_page = 7;
if(!isset($page))	$page = 1;
$last_page = (($page - 1) * $per_page);

$totalPage_query = "SELECT hit_date FROM new_hit_counter ";
$totalPage_query_result = mssql_query($totalPage_query, $conn_hannam);
$totalPage_num_row = mssql_num_rows($totalPage_query_result);

$hitCounter_query = "SELECT TOP ".$per_page." CONVERT(char(10), hit_date, 126) AS hit_date, hit_pc, hit_mobile ".
					"FROM new_hit_counter ".
					"WHERE hit_date NOT IN (SELECT TOP ".$last_page." hit_date FROM new_hit_counter ORDER BY hit_date DESC) ".
					"ORDER BY hit_date DESC";
$hitCounter_query_result = mssql_query($hitCounter_query, $conn_hannam);
$hitCounter_num_row = mssql_num_rows($hitCounter_query_result);

$page_total = ceil($totalPage_num_row / $per_page);
?>

<div class="content_wrapper">
	<div class="h1_wrapper stripped">
		<h1>Hit Counter</h1>
	</div>

	<table class="table_admin" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<img src="../img/admin/detail_dot_red.gif">
				<span class="content_link">방문자수 조회</span>
			</td>
		</tr>
		
		<tr>
			<td>
				<input type="text" id="start_date" placeholder="20150101" onkeyup="autoHypenBday(this);" maxlength=10>&nbsp;&nbsp;~&nbsp;&nbsp;
				<input type="text" id="end_date" placeholder="20150101" onkeyup="autoHypenBday(this);" maxlength=10>&nbsp;&nbsp;&nbsp;&nbsp;
				<button type="button" class="btn_search" id="hitCount_search_btn" onClick="get_hitCount()" style="cursor:pointer">조회</button>
				<button class="btn_search_loading" id="hitCount_search_loading" style="display:none;">&nbsp;<img src="../img/ajax-loader.gif"></button>
			</td>
		</tr>

		<tr class="bb">
			<td>
				<div id="hit_search_result" style="height:50px;"></div>
			</td>
		</tr>

		<tr>
			<td>
				<img src="../img/admin/detail_dot_red.gif">
				<span class="content_link">일일 방문자수</span>
			</td>
		</tr>

		<tr>
			<table class="table_board" style="width:100%">
				<tr class="table_header">
					<td width="80px">Date</td> 
					<td>PC</td>
					<td>Mobile</td>
				</tr>
				<? if($hitCounter_num_row == 0) { ?>
					<tr height="60px" class="table_list table_last">
						<td colspan=3 style="font-size:16px; vertical-align:middle;">데이터가 없습니다.</td>
					</tr>
				<? } else { ?>
					<? $i = 1; ?>
					<? while($hitCounter_row = mssql_fetch_array($hitCounter_query_result)) { ?>
						<? $hit_total_pc += $hitCounter_row['hit_pc']; ?>
						<? $hit_total_mobile += $hitCounter_row['hit_mobile']; ?>
						<tr class="table_list <?=(($i++ == $hitCounter_num_row) ? 'table_last' : '' ); ?>">
							<td><?=$hitCounter_row['hit_date']; ?></td>
							<td><?=$hitCounter_row['hit_pc']; ?></td>
							<td><?=$hitCounter_row['hit_mobile']; ?></td>
						</tr>
					<? } ?>
					<tr>
						<td><b>Total</b></td>
						<td><?=$hit_total_pc; ?></td>
						<td><?=$hit_total_mobile; ?></td>
					</tr>
				<? } ?>
			</table>
		</tr>
	</table>

	<?
	if($hitCounter_num_row) {
		$per_page_navi = 5;

		if(($page % $per_page_navi) == 0)	$prev_page = $page - $per_page_navi;
		else								$prev_page = ($page - ($page % $per_page_navi));
		if($prev_page < 1)					$prev_page = 1;
		if(($page % $per_page_navi) == 0)	$next_page = $page + 1;
		else								$next_page = ($page + $per_page_navi) - ($page % $per_page_navi) + 1;
		if($next_page > $page_total)		$next_page = $page_total;

		$start_navi = 1 + (floor(($page-1)/$per_page_navi) * $per_page_navi);
		$end_navi = $start_navi + $per_page_navi - 1;
		if($end_navi > $page_total)		$end_navi = $page_total;
		?>
		<div id="page" style="text-align:center;font-size:16px; margin-top:10px;">
			<a href="javascript:page_navigation(1)"><img src="../img/community/beginning_btn.png" style="vertical-align:middle"></a> 
			<a href="javascript:page_navigation(<?=$prev_page; ?>)"><img src="../img/community/left_btn.png" style="vertical-align:middle"></a> 

			<? for($i = $start_navi; $i <= $end_navi; $i++) { ?>
				<? if($i == $page) { ?>
					<span style="color:red; font-weight:bold;"><?=$i; ?></span>
				<? } else { ?>
					<a href="javascript:page_navigation(<?=$i; ?>)"><?=$i; ?></a>
				<? } ?>
			<? } ?>

			<a href="javascript:page_navigation(<?=$next_page; ?>)"><img src="../img/community/right_btn.png" style="vertical-align:middle"></a> 
			<a href="javascript:page_navigation(<?=$page_total; ?>)"><img src="../img/community/end_btn.png" style="vertical-align:middle"></a> 
		</div>
	<? } ?>
</div>