<script>
function page_navigation(page) {
	location.href = "?menu=menu4&list=village&page=" + page;
}

function page_navigation_search(page) {
	var search_key = document.getElementsByName("search_key")[0].value;
	location.href = "?menu=menu4&list=village&mode=search&search_key=" + encodeURI(search_key) + "&page=" + page;
}
</script>

<?
/***************************************
*	DB - new_board (Type = 1)
****************************************/

$page = ($_GET['page']) ? $_GET['page'] : $_POST['page'];
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
$board_type = 1;

$premium_query = "SELECT seq, subject, content, CONVERT(char(10), upload_date, 126) AS upload_date, click_counter, active ".
				 "FROM new_board ".
				 "WHERE type = $board_type AND premium = 1 ".
				 "ORDER BY seq DESC";
$premium_query_result = mssql_query($premium_query, $conn_hannam);
$premium_num_row = mssql_num_rows($premium_query_result);

$per_page = 10;
if(!isset($page))	$page = 1;
if($page == 1) {
	$per_page = $per_page - $premium_num_row;
	$last_page = 0;
} else {
	$per_page = $per_page;
	$last_page = (($page - 1) * $per_page) - $premium_num_row;
}

if($mode == "search") {
	$search_key = ($_GET['search_key']) ? $_GET['search_key'] : $_POST['search_key'];
	$search_key = Br_dconv($search_key);

	$totalPage_query = "SELECT seq FROM new_board WHERE type = $board_type AND premium = 0 AND (subject LIKE '%$search_key%' OR content LIKE '%$search_key%') ";
	$totalPage_query_result = mssql_query($totalPage_query, $conn_hannam);
	$totalPage_num_row = mssql_num_rows($totalPage_query_result);

	$boardVillage_query = "SELECT TOP ".$per_page." seq, subject, content, CONVERT(char(10), upload_date, 126) AS upload_date, click_counter, active ".
						  "FROM new_board ".
						  "WHERE type = $board_type AND premium = 0 AND (subject LIKE '%$search_key%' OR content LIKE '%$search_key%') AND seq NOT IN (SELECT TOP ".$last_page." seq FROM new_board WHERE type = $board_type AND premium = 0 AND (subject LIKE '%$search_key%' OR content LIKE '%$search_key%') ORDER BY seq DESC) ".
						  "ORDER BY seq DESC";
	$boardVillage_query_result = mssql_query($boardVillage_query, $conn_hannam);
	$boardVillage_num_row = mssql_num_rows($boardVillage_query_result);

} else {
	$totalPage_query = "SELECT seq FROM new_board WHERE type = $board_type AND premium = 0 ";
	$totalPage_query_result = mssql_query($totalPage_query, $conn_hannam);
	$totalPage_num_row = mssql_num_rows($totalPage_query_result);

	$boardVillage_query = "SELECT TOP ".$per_page." seq, subject, content, CONVERT(char(10), upload_date, 126) AS upload_date, click_counter, active ".
						  "FROM new_board ".
						  "WHERE type = $board_type AND premium = 0 AND seq NOT IN (SELECT TOP ".$last_page." seq FROM new_board WHERE type = $board_type AND premium = 0 ORDER BY seq DESC) ".
						  "ORDER BY seq DESC";
	$boardVillage_query_result = mssql_query($boardVillage_query, $conn_hannam);
	$boardVillage_num_row = mssql_num_rows($boardVillage_query_result);
}

$per_page = 10;
$page_total = ceil(($totalPage_num_row + $premium_num_row) / $per_page);	// $per_page = 10
?>

<div class="content_wrapper">
	<div class="h1_wrapper stripped">
		<h1>Community - HN Village</h1>
	</div>

	<div class="search_wrapper" style="height:5px;padding:10px;text-align:right;">
		<input type="text" name="search_key" placeholder="  search" class="heighttext" value="<?=Br_iconv($search_key); ?>" onkeypress="if(event.keyCode == 13) {page_navigation_search(1);}"> <button type="button" class="searchbtn" onClick="page_navigation_search(1)">검색</button>
	</div>

	<table class="table_board" style="width:100%">
		<tr class="table_header">
			<td width="19px">NO.</td>
			<td width="482px">제목</td> 
			<td width="82px">작성일</td>
			<td width="64px">조회수</td>
			<td width="20px">Active</td>
		</tr>
		<? if($page == 1) { ?>
			<? while($premium_row = mssql_fetch_array($premium_query_result)) { ?>
				<tr class="table_premium">
					<td><?=$premium_row['seq']; ?></td>
					<td class="table_subject"><a href="?menu=menu4&list=village_write&seq=<?=$premium_row['seq']; ?>" class="post"><?=Br_iconv($premium_row['subject']); ?></a></td>
					<td><?=$premium_row['upload_date']; ?></td>
					<td><?=$premium_row['click_counter']; ?></td>
					<td><span style="color:red; font-weight:bold;"><?=(($premium_row['active'] == 0) ? 'X' : 'O' ); ?></span></td>
				</tr>
			<? } ?>
		<? } ?>
		<? if($boardVillage_num_row == 0) { ?>
			<tr height="60px" class="table_list table_last">
				<td colspan=5 style="font-size:16px; vertical-align:middle;">검색된 결과가 없습니다.</td>
			</tr>
		<? } else { ?>
			<? $i = 1; ?>
			<? while($boardVillage_row = mssql_fetch_array($boardVillage_query_result)) { ?>
				<tr class="table_list <?=(($i++ == $boardVillage_num_row) ? 'table_last' : '' ); ?>">
					<td><?=$boardVillage_row['seq']; ?></td>
					<td class="table_subject"><a href="?menu=menu4&list=village_write&seq=<?=$boardVillage_row['seq']; ?>" class="post"><?=Br_iconv($boardVillage_row['subject']); ?></a></td>
					<td><?=$boardVillage_row['upload_date']; ?></td>
					<td><?=$boardVillage_row['click_counter']; ?></td>
					<td><span style="color:red; font-weight:bold;"><?=(($boardVillage_row['active'] == 0) ? 'X' : 'O' ); ?></span></td>
				</tr>
			<? } ?>
		<? } ?>
	</table>

	<?
	if($boardVillage_num_row) {
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
		<div style="float:left;"><input type="button" class="btn" onClick="location.href='?menu=menu4'" value="목록"></div>
		<div style="float:right;"><input type="button" class="btn" onClick="location.href='?menu=menu4&list=village_write'" value="글쓰기"></div>
	</div>
</div>