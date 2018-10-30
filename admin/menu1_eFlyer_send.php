<script>
function page_navigation(page) {
	location.href = "?menu=menu1&list=eFlyer_send&page=" + page;
}

function page_navigation_search(page) {
	var search_key = document.getElementsByName("search_key")[0].value;
	location.href = "?menu=menu1&list=eFlyer_send&mode=search&search_key=" + search_key + "&page=" + page;
}
</script>

<?
/***************************************
*	DB - new_subscribe_send
****************************************/
$page = ($_GET['page']) ? $_GET['page'] : $_POST['page'];
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);

$per_page = 10;
if(!isset($page))	$page = 1;
$last_page = (($page - 1) * $per_page);
$item_type = 1;

if($mode == "search") {
	$search_key = ($_GET['search_key']) ? $_GET['search_key'] : $_POST['search_key'];
	$search_key = Br_dconv($search_key);

	$totalPage_query = "SELECT member.email ".
					   "FROM new_subscribe_send AS send ".
					   "INNER JOIN new_subscribe_member AS member ON member.seq = send.member_seq ".
					   "WHERE member.email LIKE '%$search_key%' ";
	$totalPage_query_result = mssql_query($totalPage_query, $conn_hannam);
	$totalPage_num_row = mssql_num_rows($totalPage_query_result);

	$subscribeSend_query = "SELECT TOP ".$per_page." send.item_seq, member.seq, member.email, CONVERT(char(19), send.sent_date, 120) AS sent_date, send.sent_result ".
						   "FROM new_subscribe_send AS send ".
						   "INNER JOIN new_subscribe_member AS member ON member.seq = send.member_seq ".
						   "WHERE send.item_type = $item_type AND member.email LIKE '%$search_key%' AND send.sent_date NOT IN (SELECT TOP ".$last_page." sent_date FROM new_subscribe_send WHERE item_type = $item_type ORDER BY item_seq DESC, member_seq DESC) ".
						   "ORDER BY item_seq DESC, member_seq DESC";
	$subscribeSend_query_result = mssql_query($subscribeSend_query, $conn_hannam);
	$subscribeSend_num_row = mssql_num_rows($subscribeSend_query_result);

} else {
	$totalPage_query = "SELECT item_seq FROM new_subscribe_send ";
	$totalPage_query_result = mssql_query($totalPage_query, $conn_hannam);
	$totalPage_num_row = mssql_num_rows($totalPage_query_result);

	$subscribeSend_query = "SELECT TOP ".$per_page." send.item_seq, member.seq, member.email, CONVERT(char(19), send.sent_date, 120) AS sent_date, send.sent_result ".
						   "FROM new_subscribe_send AS send ".
						   "INNER JOIN new_subscribe_member AS member ON member.seq = send.member_seq ".
						   "WHERE send.item_type = $item_type AND send.sent_date NOT IN (SELECT TOP ".$last_page." sent_date FROM new_subscribe_send WHERE item_type = $item_type ORDER BY item_seq DESC, member_seq DESC) ".
						   "ORDER BY item_seq DESC, member_seq DESC";
	$subscribeSend_query_result = mssql_query($subscribeSend_query, $conn_hannam);
	$subscribeSend_num_row = mssql_num_rows($subscribeSend_query_result);
}

$page_total = ceil($totalPage_num_row / $per_page);
?>

<div class="content_wrapper">
	<div class="h1_wrapper stripped">
		<h1>Weekly Flyer - E-Flyer Send Status</h1>
	</div>

	<div class="search_wrapper" style="height:5px;padding:10px;text-align:right;">
		<input type="text" name="search_key" placeholder="  search" class="heighttext" value="<?=Br_iconv($search_key); ?>" value="<?=Br_iconv($search_key); ?>" onkeypress="if(event.keyCode == 13) {page_navigation_search(1);}"> <button type="button" class="searchbtn" onClick="page_navigation_search(1)">검색</button>
	</div>

	<table class="table_board" style="width:100%">
		<tr class="table_header">
			<td width="90px">Flyer ID</td>
			<td width="80px">Member ID</td>
			<td width="210px">E-mail</td>
			<td width="102px">Send Result</td> 
			<td width="185px">Send Date</td>
		</tr>

		<? if($subscribeSend_num_row == 0) { ?>
			<tr height="60px" class="table_list table_last">
				<td colspan=4 style="font-size:16px; vertical-align:middle;">데이터가 없습니다.</td>
			</tr>
		<? } else { ?>
			<? $i = 1; ?>
			<? while($subscribeSend_row = mssql_fetch_array($subscribeSend_query_result)) { ?>
				<tr class="table_list <?=(($i++ == $subscribeSend_num_row) ? 'table_last' : '' ); ?>">
					<td><?="Flyer - ".$subscribeSend_row['item_seq']; ?></td>
					<td><?=$subscribeSend_row['seq']; ?></td>
					<td><?=$subscribeSend_row['email']; ?></td>
					<td><?=(($subscribeSend_row['sent_result']) ? "SENT" : "READY" ); ?></td>
					<td><?=$subscribeSend_row['sent_date']; ?></td>
				</tr>
			<? } ?>
		<? } ?>
	</table>

	<?
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
		<a href="javascript:page_navigation<?=(($mode == "search") ? '_search' : '' ); ?>(1)"><img src="../img/community/beginning_btn.png" style="vertical-align:middle"></a> 
		<a href="javascript:page_navigation<?=(($mode == "search") ? '_search' : '' ); ?>(<?=$prev_page; ?>)"><img src="../img/community/left_btn.png" style="vertical-align:middle"></a> 

		<? for($i = $start_navi; $i <= $end_navi; $i++) { ?>
			<? if($i == $page) { ?>
				<span style="color:red; font-weight:bold;"><?=$i; ?></span>
			<? } else { ?>
				<? if($mode == "search") { ?>
					<a href="javascript:page_navigation_search(<?=$i; ?>)"><?=$i; ?></a>
				<? } else { ?>
					<a href="javascript:page_navigation(<?=$i; ?>)"><?=$i; ?></a>
				<? } ?>
			<? } ?>
		<? } ?>

		<a href="javascript:page_navigation<?=(($mode == "search") ? '_search' : '' ); ?>(<?=$next_page; ?>)"><img src="../img/community/right_btn.png" style="vertical-align:middle"></a> 
		<a href="javascript:page_navigation<?=(($mode == "search") ? '_search' : '' ); ?>(<?=$page_total; ?>)"><img src="../img/community/end_btn.png" style="vertical-align:middle"></a> 
	</div>

	<div style="margin-top:30px;">
		<div style="float:left;"><input type="button" class="btn" onClick="location.href='?menu=menu1'" value="목록"></div>
	</div>
</div>