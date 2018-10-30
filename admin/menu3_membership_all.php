<script>
function page_navigation(page) {
	location.href = "?menu=menu3&list=membership_all&page=" + page;
}

function page_navigation_search(page) {
	var search_key = document.getElementsByName("search_key")[0].value;
	location.href = "?menu=menu3&list=membership_new&mode=search&search_key=" + encodeURI(search_key) + "&page=" + page;
}
</script>

<?
/***************************************
*	DB - new_membership
****************************************/
$page = ($_GET['page']) ? $_GET['page'] : $_POST['page'];
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);

$per_page = 10;
if(!isset($page))	$page = 1;
$last_page = (($page - 1) * $per_page);

if($mode == "search") {
	$search_key = ($_GET['search_key']) ? $_GET['search_key'] : $_POST['search_key'];
	$search_key = Br_dconv($search_key);

	$totalPage_query = "SELECT seq FROM new_membership WHERE status = 1 AND (phone LIKE '%$search_key%' OR name_eng LIKE '%$search_key%' OR name_kor LIKE '%$search_key%' OR cardNo LIKE '%$search_key%') ";
	$totalPage_query_result = mssql_query($totalPage_query, $conn_hannam);
	$totalPage_num_row = mssql_num_rows($totalPage_query_result);

	$membershipAll_query = "SELECT TOP ".$per_page." seq, phone, name_kor, name_eng, CONVERT(char(10), cardIssueDate, 120) AS cardIssueDate ".
						   "FROM new_membership ".
						   "WHERE status = 0 AND (phone LIKE '%$search_key%' OR name_eng LIKE '%$search_key%' OR name_kor LIKE '%$search_key%' OR cardNo LIKE '%$search_key%') AND seq NOT IN (SELECT TOP ".$last_page." seq FROM new_membership WHERE status = 0 AND (phone LIKE '%$search_key%' OR name_eng LIKE '%$search_key%' OR name_kor LIKE '%$search_key%' OR cardNo LIKE '%$search_key%') ORDER BY seq DESC) ".
						   "ORDER BY seq DESC";
	$membershipAll_query_result = mssql_query($membershipAll_query, $conn_hannam);
	$membershipAll_num_row = mssql_num_rows($membershipAll_query_result);

} else {
	$totalPage_query = "SELECT seq FROM new_membership WHERE status = 1 ";
	$totalPage_query_result = mssql_query($totalPage_query, $conn_hannam);
	$totalPage_num_row = mssql_num_rows($totalPage_query_result);

	$membershipAll_query = "SELECT TOP ".$per_page." seq, phone, name_kor, name_eng, CONVERT(char(10), cardIssueDate, 120) AS cardIssueDate, cardNo ".
						   "FROM new_membership ".
						   "WHERE status = 1 AND seq NOT IN (SELECT TOP ".$last_page." seq FROM new_membership WHERE status = 1 ORDER BY seq DESC) ".
						   "ORDER BY seq DESC";
	$membershipAll_query_result = mssql_query($membershipAll_query, $conn_hannam);
	$membershipAll_num_row = mssql_num_rows($membershipAll_query_result);
}

$page_total = ceil($totalPage_num_row / $per_page);
?>

<div class="content_wrapper">
	<div class="h1_wrapper stripped">
		<h1>Customer Service - Membership Member List</h1>
	</div>

	<div class="search_wrapper" style="height:5px;padding:10px;text-align:right;">
		<input type="text" name="search_key" placeholder="  search" class="heighttext" value="<?=Br_iconv($search_key); ?>" onkeypress="if(event.keyCode == 13) {page_navigation_search(1);}"> <button type="button" class="searchbtn" onClick="page_navigation_search(1)">검색</button>
	</div>

	<table class="table_board" style="width:100%">
		<tr class="table_header">
			<td width="19px">NO.</td>
			<td width="228px">멤버쉽번호</td> 
			<td width="120px">전화번호</td>
			<td width="100px">한글이름</td>
			<td width="100px">영문이름</td>
			<td width="100px">신청일</td>
		</tr>

		<? if($membershipAll_num_row == 0) { ?>
			<tr height="60px" class="table_list table_last">
				<td colspan=6 style="font-size:16px; vertical-align:middle;">검색된 결과가 없습니다.</td>
			</tr>
		<? } else { ?>
			<? $i = 1; ?>
			<? while($membershipAll_row = mssql_fetch_array($membershipAll_query_result)) { ?>
				<tr class="table_list <?=(($i++ == $membershipAll_num_row) ? 'table_last' : '' ); ?>">
					<td><?=$membershipAll_row['seq']; ?></td>
					<td><a href="?menu=menu3&list=membership_detail&seq=<?=$membershipAll_row['seq']; ?>" class="post"><?=Br_iconv($membershipAll_row['cardNo']); ?></a></td>
					<td><a href="?menu=menu3&list=membership_detail&seq=<?=$membershipAll_row['seq']; ?>" class="post"><?=Br_iconv($membershipAll_row['phone']); ?></a></td>
					<td><?=Br_iconv($membershipAll_row['name_kor']); ?></td>
					<td><?=$membershipAll_row['name_eng']; ?></td>
					<td><?=$membershipAll_row['cardIssueDate']; ?></span></td>
				</tr>
			<? } ?>
		<? } ?>
	</table>

	<?
	if($membershipAll_num_row) {
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
	<? } ?>

	<div style="margin-top:30px;">
		<div style="float:left;"><input type="button" class="btn" onClick="location.href='?menu=menu3'" value="목록"></div>
	</div>
</div>