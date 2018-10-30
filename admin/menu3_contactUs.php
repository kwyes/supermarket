<script>
function page_navigation(page) {
	location.href = "?menu=menu3&list=contactus&page=" + page;
}
</script>

<?
/***************************************
*	DB - new_contactUs
****************************************/
$page = ($_GET['page']) ? $_GET['page'] : $_POST['page'];
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);

$per_page = 10;
if(!isset($page))	$page = 1;
$last_page = (($page - 1) * $per_page);

$totalPage_query = "SELECT seq FROM new_contactUs";
$totalPage_query_result = mssql_query($totalPage_query, $conn_hannam);
$totalPage_num_row = mssql_num_rows($totalPage_query_result);

$contactus_query = "SELECT TOP ".$per_page." seq, status, CONVERT(char(10), submit_date, 126) AS submit_date, name, subject, content ".
				   "FROM new_contactUs ".
				   "WHERE seq NOT IN (SELECT TOP ".$last_page." seq FROM new_contactUs ORDER BY seq DESC) ".
				   "ORDER BY seq DESC";
$contactus_query_result = mssql_query($contactus_query, $conn_hannam);
$contactus_num_row = mssql_num_rows($contactus_query_result);

$page_total = ceil($totalPage_num_row / $per_page);
?>

<div class="content_wrapper">
	<div class="h1_wrapper stripped">
		<h1>Customer Service - Contact Us</h1>
	</div>

	<table class="table_board" style="width:100%">
		<tr class="table_header">
			<td width="19px">NO.</td>
			<td width="123px">접수일</td>
			<td width="339px">제목</td> 
			<td width="123px">신청인</td>
			<td width="63px">답변</td>
		</tr>

		<? if($contactus_num_row == 0) { ?>
			<tr height="60px" class="table_list table_last">
				<td colspan=5 style="font-size:16px; vertical-align:middle;">데이터가 없습니다.</td>
			</tr>
		<? } else { ?>
			<? $i = 1; ?>
			<? while($contactus_row = mssql_fetch_array($contactus_query_result)) { ?>
				<tr class="table_list <?=(($i++ == $contactus_num_row) ? 'table_last' : '' ); ?>">
					<td><?=$contactus_row['seq']; ?></td>
					<td><?=$contactus_row['submit_date']; ?></td>
					<td><a href="?menu=menu3&list=contactus_view&seq=<?=$contactus_row['seq']; ?>" class="post"><?=Br_iconv($contactus_row['subject']); ?></a></td>
					<td><?=Br_iconv($contactus_row['name']); ?></td>
					<td><?=(($contactus_row['status'] == 1) ? "<span style='color:blue'>완료</span>" : "<span style='color:red'>대기</span>" ); ?></td>
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
		<div style="float:left;"><input type="button" class="btn" onClick="location.href='?menu=menu3'" value="목록"></div>
	</div>
</div>