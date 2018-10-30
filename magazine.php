<?php include 'header.php'; ?>

<style>
<?php include 'css/magazine.css'; ?>
<?php include 'css/footer.css'; ?>
</style>

<link href='http://fonts.googleapis.com/css?family=Chango' rel='stylesheet' type='text/css'>

<script>
function view_count(seq) {
	document.getElementById("view_count").src = "admin/customer_handler.php?mode=magazine_view&seq=" + seq;
}

function page_navigation(page) {
	location.href = "magazine.php?page=" + page;
}
</script>

<?
/***************************************
*	DB - new_regularUpdate (Type = 3)
****************************************/
$page = ($_GET['page']) ? $_GET['page'] : $_POST['page'];
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
$db_type = 3;
$image_path = "upload/magazine/";

$per_page = 6;
if(!isset($page))	$page = 1;
$last_page = (($page - 1) * $per_page);

$totalPage_query = "SELECT seq FROM new_regularUpdate WHERE type = $db_type";
$totalPage_query_result = mssql_query($totalPage_query, $conn_hannam);
$totalPage_num_row = mssql_num_rows($totalPage_query_result);

$magazine_query = "SELECT TOP ".$per_page." seq, subject, CONVERT(VARCHAR(2), start_date, 101) AS start_month, CONVERT(VARCHAR(4), start_date, 102) AS start_year, image_name, link ".
				  "FROM new_regularUpdate ".
				  "WHERE type = $db_type AND seq NOT IN (SELECT TOP ".$last_page." seq FROM new_regularUpdate WHERE type = $db_type ORDER BY seq DESC) ".
				  "ORDER BY seq DESC";
$magazine_query_result = mssql_query($magazine_query, $conn_hannam);
$magazine_num_row = mssql_num_rows($magazine_query_result);

$page_total = ceil($totalPage_num_row / $per_page);
?>

<iframe id="view_count" style="display:none;"></iframe>
<div id="gray_bg" style="background: #f5f5f5; height:100%; padding:10px 0px 0px 0px;">
	<div id="white_wrapper" >
		<nav id="side" class="magazine"> <!--class to make it highlight-->
			<h3> Community </h3>

			<? $menu = "menu4"; ?>
			<? include_once "leftMenu.php"; ?>
		</nav>

		<div class="content_wrapper">
			<div class="h1_wrapper stripped">
				<h1> HN Magazine </h1>
			</div>

			<div class="choices_wrapper">
				<table style="width:100%">
					<? for($i = 0; $i <= $per_page; $i++) { ?>
						<? if($i == 0) { ?>
							<tr>
						<? } else if($i == ($per_page/2)) { ?>
							<? if($i < $magazine_num_row) { ?>
								</tr><tr>
							<? } else { ?>
								</tr>
								<? break; ?>
							<? } ?>
						<? } else if($i == $per_page) { ?>
							</tr>
							<? break; ?>
						<? } ?>

						<? if($i < $magazine_num_row) { ?>
							<? mssql_data_seek($magazine_query_result, $i); ?>
							<? $magazine_row = mssql_fetch_array($magazine_query_result); ?>
							<? $monthName = date("F", mktime(null, null, null, $magazine_row['start_month'])); ?>
							<td>
								<img src="<?=$image_path.$magazine_row['image_name']; ?>" alt="" class="under" width="226px" height="292px">
								<a href="<?=$magazine_row['link']; ?>" target="_blank">
								<img src="img/community/mag_thumb_over.jpg" alt="" class="over" width="226px" height="292px" onClick="view_count(<?=$magazine_row['seq']; ?>)"></a>
								<? if($LANG == "Korean") { ?>
									<span><h3><?=Br_iconv($magazine_row['subject']); ?></h3>
								<? } else { ?>
									<span><h3>Hannam Magazine <?=$magazine_row['seq']; ?></h3>
								<? } ?>
								<p>Vol.<?=$magazine_row['seq']; ?> / <?=$monthName." ".$magazine_row['start_year']; ?></p></span>
							</td>
						<? } else { ?>
							<td style="border:0;"></td>
						<? } ?>
					<? } ?>
				</table>
			</div>

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
			<div id="magazine_page" style="text-align:center;font-size:16px; margin-top:10px;">
				<a href="javascript:page_navigation(1)"><img src="img/community/beginning_btn.png" style="vertical-align:middle"></a> 
				<a href="javascript:page_navigation(<?=$prev_page; ?>)"><img src="img/community/left_btn.png" style="vertical-align:middle"></a> 

				<? for($i = $start_navi; $i <= $end_navi; $i++) { ?>
					<? if($i == $page) { ?>
						<span style="color:red; font-weight:bold;"><?=$i; ?></span>
					<? } else { ?>
						<a href="javascript:page_navigation(<?=$i; ?>)"><?=$i; ?></a>
					<? } ?>
				<? } ?>

				<a href="javascript:page_navigation(<?=$next_page; ?>)"><img src="img/community/right_btn.png" style="vertical-align:middle"></a> 
				<a href="javascript:page_navigation(<?=$page_total; ?>)"><img src="img/community/end_btn.png" style="vertical-align:middle"></a> 
			</div>
		</div><!-- content_wrapper  -->
	</div><!-- white_wrapper  -->

	<div class="tothetop" style="background: #f5f5f5;">
		<a href='#top'><img src="img/bottom/top_button.jpg" /></a>
	</div>
</div><!-- gray bg  -->

<?php include 'footer.php'; ?>