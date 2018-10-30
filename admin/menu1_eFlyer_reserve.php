<script>
function page_navigation(page) {
	location.href = "?menu=menu1&list=eFlyer_reserve&page=" + page;
}
</script>

<?
/***************************************
*	DB - new_subscribe_reserve
****************************************/
$page = ($_GET['page']) ? $_GET['page'] : $_POST['page'];
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);

$per_page = 10;
if(!isset($page))	$page = 1;
$last_page = (($page - 1) * $per_page);

$item_type = 1;
$totalPage_query = "SELECT item_seq FROM new_subscribe_reserve ";
$totalPage_query_result = mssql_query($totalPage_query, $conn_hannam);
$totalPage_num_row = mssql_num_rows($totalPage_query_result);

$subscribeReserve_query = "SELECT TOP ".$per_page." reserve.item_seq, CONVERT(char(10), reserve.send_date, 120) AS send_date, reserve.process, flyer.subject ".
						  "FROM new_subscribe_reserve AS reserve ".
						  "INNER JOIN new_regularUpdate AS flyer ON reserve.item_type = flyer.type AND reserve.item_seq = flyer.seq ".
						  "WHERE reserve.item_type = $item_type AND reserve.item_seq NOT IN (SELECT TOP ".$last_page." item_seq FROM new_subscribe_reserve WHERE item_type = $item_type ORDER BY item_seq DESC) ".
						  "ORDER BY reserve.item_seq DESC";
$subscribeReserve_query_result = mssql_query($subscribeReserve_query, $conn_hannam);
$subscribeReserve_num_row = mssql_num_rows($subscribeReserve_query_result);

$page_total = ceil($totalPage_num_row / $per_page);
?>

<div class="content_wrapper">
	<div class="h1_wrapper stripped">
		<h1>Weekly Flyer - E-Flyer Reserve Status</h1>
	</div>

	<table class="table_board" style="width:100%">
		<tr class="table_header">
			<td width="80px">Flyer ID</td>
			<td width="445px">Subject</td> 
			<td width="82px">Send Date</td>
			<td width="60px">Status</td>
		</tr>

		<? if($subscribeReserve_num_row == 0) { ?>
			<tr height="60px" class="table_list table_last">
				<td colspan=4 style="font-size:16px; vertical-align:middle;">데이터가 없습니다.</td>
			</tr>
		<? } else { ?>
			<? $i = 1; ?>
			<? while($subscribeReserve_row = mssql_fetch_array($subscribeReserve_query_result)) { ?>
				<tr class="table_list <?=(($i++ == $subscribeReserve_num_row) ? 'table_last' : '' ); ?>">
					<td><?="Flyer - ".$subscribeReserve_row['item_seq']; ?></td>
					<td><?=$subscribeReserve_row['subject']; ?></td>
					<td><?=$subscribeReserve_row['send_date']; ?></td>
					<td><?=(($subscribeReserve_row['process'] == 0) ? "Ready" : "Complete" ); ?></td>
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

	<div style="margin-top:30px;">
		<div style="float:left;"><input type="button" class="btn" onClick="location.href='?menu=menu1'" value="목록"></div>
	</div>
</div>