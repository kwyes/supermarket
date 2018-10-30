<script>
function page_navigation(page) {
	location.href = "?menu=menu1&list=eFlyer_member&page=" + page;
}

function page_navigation_search(page) {
	var search_key = document.getElementsByName("search_key")[0].value;
	location.href = "?menu=menu1&list=eFlyer_member&mode=search&search_key=" + search_key + "&page=" + page;
}

function unsubscribe_eFlyer(mem_seq) {
	var form = document.createElement("form");
	form.setAttribute("method", "POST");
	form.setAttribute("action", "?menu=menu1&list=eFlyer_member");
	document.body.appendChild(form);

	var input1 = document.createElement("input");
	input1.setAttribute("type", "hidden");
	input1.setAttribute("name", "mode");
	input1.setAttribute("value", "unsubscribe");
	form.appendChild(input1);

	var input2 = document.createElement("input");
	input2.setAttribute("type", "hidden");
	input2.setAttribute("name", "mem_seq");
	input2.setAttribute("value", mem_seq);
	form.appendChild(input2);

	form.submit();
}
</script>

<?
/***************************************
*	DB - new_subscribe_member
****************************************/

$page = ($_GET['page']) ? $_GET['page'] : $_POST['page'];
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);

$per_page = 10;
if(!isset($page))	$page = 1;
$last_page = (($page - 1) * $per_page);

if($mode == "unsubscribe") {
	$mem_seq = $_POST['mem_seq'];
	$unsubscribe_query = "UPDATE new_subscribe_member SET status = 0, status_date = GETDATE() WHERE seq = $mem_seq";
	mssql_query($unsubscribe_query, $conn_hannam);
}

if($mode == "search") {
	$search_key = ($_GET['search_key']) ? $_GET['search_key'] : $_POST['search_key'];
	$search_key = Br_dconv($search_key);

	$totalPage_query = "SELECT seq FROM new_subscribe_member WHERE email LIKE '%$search_key%' OR postal_code LIKE '%$search_key%' ";
	$totalPage_query_result = mssql_query($totalPage_query, $conn_hannam);
	$totalPage_num_row = mssql_num_rows($totalPage_query_result);

	$subscribeMember_query = "SELECT TOP ".$per_page." seq, email, postal_code, status ".
							 "FROM new_subscribe_member ".
							 "WHERE (email LIKE '%$search_key%' OR postal_code LIKE '%$search_key%') AND seq NOT IN (SELECT TOP ".$last_page." seq FROM new_subscribe_member WHERE email LIKE '%$search_key%' OR postal_code LIKE '%$search_key%' ORDER BY seq DESC) ".
							 "ORDER BY seq DESC";
	$subscribeMember_query_result = mssql_query($subscribeMember_query, $conn_hannam);
	$subscribeMember_num_row = mssql_num_rows($subscribeMember_query_result);

} else {
	$totalPage_query = "SELECT seq FROM new_subscribe_member ";
	$totalPage_query_result = mssql_query($totalPage_query, $conn_hannam);
	$totalPage_num_row = mssql_num_rows($totalPage_query_result);

	$subscribeMember_query = "SELECT TOP ".$per_page." seq, email, postal_code, status ".
							 "FROM new_subscribe_member ".
							 "WHERE seq NOT IN (SELECT TOP ".$last_page." seq FROM new_subscribe_member ORDER BY seq DESC) ".
							 "ORDER BY seq DESC";
	$subscribeMember_query_result = mssql_query($subscribeMember_query, $conn_hannam);
	$subscribeMember_num_row = mssql_num_rows($subscribeMember_query_result);
}

$page_total = ceil($totalPage_num_row / $per_page);
?>

<div class="content_wrapper">
	<div class="h1_wrapper stripped">
		<h1>Weekly Flyer - E-Flyer Member List</h1>
	</div>

	<div class="search_wrapper" style="height:5px;padding:10px;text-align:right;">
		<input type="text" name="search_key" placeholder="  search" class="heighttext" value="<?=Br_iconv($search_key); ?>" onkeypress="if(event.keyCode == 13) {page_navigation_search(1);}"> <button type="button" class="searchbtn" onClick="page_navigation_search(1)">검색</button>
	</div>

	<table class="table_board" style="width:100%">
		<tr class="table_header">
			<td width="19px">NO.</td>
			<td width="506px">E-Mail</td> 
			<td width="82px">Postal Code</td>
			<td width="40px">Status</td>
			<td width="20px"></td>
		</tr>

		<? $i = 1; ?>
		<? while($subscribeMember_row = mssql_fetch_array($subscribeMember_query_result)) { ?>
			<tr class="table_list <?=(($i++ == $subscribeMember_num_row) ? 'table_last' : '' ); ?>">
				<td><?=$subscribeMember_row['seq']; ?></td>
				<td><?=$subscribeMember_row['email']; ?></td>
				<td><?=$subscribeMember_row['postal_code']; ?></td>
				<td><span style="color:red; font-weight:bold;"><?=(($subscribeMember_row['status'] == 0) ? 'X' : 'O' ); ?></span></td>
				<td>
					<? if($subscribeMember_row['status'] == 1) { ?>
						<button style="padding:0 5px;" onClick="unsubscribe_eFlyer(<?=$subscribeMember_row['seq']; ?>)">X</button>
					<? } ?>
				</td>
			</tr>
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