<script>
function page_navigation(page) {
	location.href = "?menu=menu1&list=weeklyFlyer&page=" + page;
}
</script>


<?
/***************************************
*	DB - new_regularUpdate (Type = 1)
****************************************/
$page = ($_GET['page']) ? $_GET['page'] : $_POST['page'];
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
$db_type = 1;

$per_page = 10;
if(!isset($page))	$page = 1;
$last_page = (($page - 1) * $per_page);

$totalPage_query = "SELECT seq FROM new_regularUpdate WHERE type = $db_type";
$totalPage_query_result = mssql_query($totalPage_query, $conn_hannam);
$totalPage_num_row = mssql_num_rows($totalPage_query_result);

$flyer_query = "SELECT TOP ".$per_page." seq, subject, CONVERT(char(10), start_date, 126) AS start_date, click_counter ".
			   "FROM new_regularUpdate ".
			   "WHERE type = $db_type AND seq NOT IN (SELECT TOP ".$last_page." seq FROM new_regularUpdate WHERE type = $db_type ORDER BY seq DESC) ".
			   "ORDER BY seq DESC";
$flyer_query_result = mssql_query($flyer_query, $conn_hannam);
$flyer_num_row = mssql_num_rows($flyer_query_result);

$page_total = ceil($totalPage_num_row / $per_page);
?>

<div class="content_wrapper">
	<div class="h1_wrapper stripped">
		<h1>Weekly Flyer - Weekly Flyer</h1>
	</div>

	<table class="table_board" style="width:100%">
		<tr class="table_header">
			<td width="19px">NO.</td>
			<td width="468px">제목</td> 
			<td width="82px">시작일</td>
			<td width="54px">Kor</td>
			<td width="54px">Chi</td>
		</tr>

		<? if($flyer_num_row == 0) { ?>
			<tr height="60px" class="table_list table_last">
				<td colspan=5 style="font-size:16px; vertical-align:middle;">데이터가 없습니다.</td>
			</tr>
		<? } else { ?>
			<? $i = 1; ?>
			<? while($flyer_row = mssql_fetch_array($flyer_query_result)) { ?>
				<?
				$flyerChi_query = "SELECT TOP 1 click_counter FROM new_regularUpdate WHERE type = 4 AND seq = ".$flyer_row['seq'];
				$flyerChi_query_result = mssql_query($flyerChi_query, $conn_hannam);
				$flyerChi_row = mssql_fetch_array($flyerChi_query_result);
				?>
				<tr class="table_list <?=(($i++ == $flyer_num_row) ? 'table_last' : '' ); ?>">
					<td><?=$flyer_row['seq']; ?></td>
					<td><a href="?menu=menu1&list=weeklyFlyer_write&seq=<?=$flyer_row['seq']; ?>" class="post"><?=Br_iconv($flyer_row['subject']); ?></a></td>
					<td><?=$flyer_row['start_date']; ?></td>
					<td><?=$flyer_row['click_counter']; ?></td>
					<td><?=$flyerChi_row['click_counter']; ?></td>
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
		<div style="float:right;"><input type="button" class="btn" onClick="location.href='?menu=menu1&list=weeklyFlyer_write'" value="글쓰기"></div>
	</div>
</div>