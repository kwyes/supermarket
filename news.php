<?php include 'header.php'; ?>

<style>
<?php include 'css/news.css'; ?>
<?php include 'css/footer.css'; ?>
</style>

<link href='http://fonts.googleapis.com/css?family=Chango' rel='stylesheet' type='text/css'>

<script>
function page_navigation(page) {
	location.href = "news.php?page=" + page;
}

function page_navigation_search(page) {
	var search_key = document.getElementsByName("search_key")[0].value;
	location.href = "news.php?mode=search&search_key=" + encodeURI(search_key) + "&page=" + page;
}
</script>

<?
/***************************************
*	DB - new_board (Type = 2)
****************************************/

$page = ($_GET['page']) ? $_GET['page'] : $_POST['page'];
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
$board_type = 2;

$premium_query = "SELECT seq, subject, content, CONVERT(char(10), upload_date, 126) AS upload_date ".
				 "FROM new_board ".
				 "WHERE type = $board_type AND active = 1 AND premium = 1 ".
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

	$totalPage_query = "SELECT seq FROM new_board WHERE type = $board_type AND active = 1 AND premium = 0 AND (subject LIKE '%$search_key%' OR content LIKE '%$search_key%') ";
	$totalPage_query_result = mssql_query($totalPage_query, $conn_hannam);
	$totalPage_num_row = mssql_num_rows($totalPage_query_result);

	$boardNews_query = "SELECT TOP ".$per_page." seq, subject, content, CONVERT(char(10), upload_date, 126) AS upload_date ".
						  "FROM new_board ".
						  "WHERE type = $board_type AND active = 1 AND premium = 0 AND (subject LIKE '%$search_key%' OR content LIKE '%$search_key%') AND seq NOT IN (SELECT TOP ".$last_page." seq FROM new_board WHERE type = $board_type AND active = 1 AND premium = 0 AND (subject LIKE '%$search_key%' OR content LIKE '%$search_key%') ORDER BY seq DESC) ".
						  "ORDER BY seq DESC";
	$boardNews_query_result = mssql_query($boardNews_query, $conn_hannam);
	$boardNews_num_row = mssql_num_rows($boardNews_query_result);

} else {
	$totalPage_query = "SELECT seq FROM new_board WHERE type = $board_type AND active = 1 AND premium = 0 ";
	$totalPage_query_result = mssql_query($totalPage_query, $conn_hannam);
	$totalPage_num_row = mssql_num_rows($totalPage_query_result);

	$boardNews_query = "SELECT TOP ".$per_page." seq, subject, content, CONVERT(char(10), upload_date, 126) AS upload_date ".
						  "FROM new_board ".
						  "WHERE type = $board_type AND active = 1 AND premium = 0 AND seq NOT IN (SELECT TOP ".$last_page." seq FROM new_board WHERE type = $board_type AND active = 1 AND premium = 0 ORDER BY seq DESC) ".
						  "ORDER BY seq DESC";
	$boardNews_query_result = mssql_query($boardNews_query, $conn_hannam);
	$boardNews_num_row = mssql_num_rows($boardNews_query_result);
}

$per_page = 10;
$page_total = ceil(($totalPage_num_row + $premium_num_row) / $per_page);	// $per_page = 10
?>

<div id="gray_bg" style="background: #f5f5f5; height:100%; padding:10px 0px 0px 0px;">
	<div id="white_wrapper" >
		<nav id="side" class="news"> <!--class to make it highlight-->
			<h3> Community </h3>

			<? $menu = "menu4"; ?>
			<? include_once "leftMenu.php"; ?>
		</nav>

		<div class="content_wrapper">
			<div class="h1_wrapper stripped">
				<h1> News </h1>
			</div>

			<div class="search_wrapper" style="height:5px;padding:10px;text-align:right;">
				<input type="text" name="search_key" placeholder="  search" class="heighttext" value="<?=Br_iconv($search_key); ?>" onkeypress="if(event.keyCode == 13) {page_navigation_search(1);}"> <button type="button" class="searchbtn" onClick="page_navigation_search(1)">검색</button>
			</div>

			<table style="width:100%">
				<tr class="table_header">
					<td width="30px">NO</td> 
					<td width="506px"><?=(($LANG == "korean") ? '제목' : 'SUBJECT' ); ?></td> 
					<td width="142px"><?=(($LANG == "korean") ? '작성일' : 'DATE' ); ?></td>
				</tr>
				<? if($page == 1) { ?>
					<? while($premium_row = mssql_fetch_array($premium_query_result)) { ?>
						<tr class="table_premium">
							<td><?=$premium_row['seq']; ?></td>
							<td class="table_subject"><a href="news_view.php?seq=<?=$premium_row['seq']; ?>" class="post"><?=Br_iconv($premium_row['subject']); ?></a></td>
							<td><?=$premium_row['upload_date']; ?></td>
						</tr>
					<? } ?>
				<? } ?>
				<? if($boardNews_num_row == 0) { ?>
					<tr height="60px" class="table_list table_last">
						<td colspan=3 style="font-size:16px; vertical-align:middle;">검색된 결과가 없습니다.</td>
					</tr>
				<? } else { ?>
					<? $i = 1; ?>
					<? while($boardNews_row = mssql_fetch_array($boardNews_query_result)) { ?>
						<tr class="table_list <?=(($i++ == $boardNews_num_row) ? 'table_last' : '' ); ?>">
							<td><?=$boardNews_row['seq']; ?></td>
							<td class="table_subject"><a href="news_view.php?seq=<?=$boardNews_row['seq']; ?>" class="post"><?=Br_iconv($boardNews_row['subject']); ?></a></td>
							<td><?=$boardNews_row['upload_date']; ?></td>
						</tr>
					<? } ?>
				<? } ?>
			</table>

			<?
			if($boardNews_num_row) {
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
					<a href="javascript:page_navigation<?=(($mode == "search") ? '_search' : '' ); ?>(1)"><img src="img/community/beginning_btn.png" style="vertical-align:middle"></a> 
					<a href="javascript:page_navigation<?=(($mode == "search") ? '_search' : '' ); ?>(<?=$prev_page; ?>)"><img src="img/community/left_btn.png" style="vertical-align:middle"></a> 

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

					<a href="javascript:page_navigation<?=(($mode == "search") ? '_search' : '' ); ?>(<?=$next_page; ?>)"><img src="img/community/right_btn.png" style="vertical-align:middle"></a> 
					<a href="javascript:page_navigation<?=(($mode == "search") ? '_search' : '' ); ?>(<?=$page_total; ?>)"><img src="img/community/end_btn.png" style="vertical-align:middle"></a> 
				</div>
			<? } ?>
		</div><!-- content_wrapper  -->
	</div><!-- white_wrapper  -->

	<div class="tothetop" style="background: #f5f5f5;">
		<a href='#top'><img src="img/bottom/top_button.jpg" /></a>
	</div>
</div><!-- gray bg  -->

<?php include 'footer.php'; ?>