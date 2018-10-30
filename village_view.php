<?php include 'header.php'; ?>

<style>
<?php include 'css/village_view.css'; ?>
<?php include 'css/footer.css'; ?>
</style>

<link href='http://fonts.googleapis.com/css?family=Chango' rel='stylesheet' type='text/css'>

<?
/***************************************
*	DB - new_board (Type = 1)
****************************************/
$boardVillage_seq = ($_GET['seq']) ? $_GET['seq'] : $_POST['seq'];
$upload_path = "upload/village/";
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
$board_type = 1;

// Updating click_counter
$boardCilck_query = "UPDATE new_board SET ".
					"click_counter = (SELECT click_counter FROM new_board WHERE type = $board_type AND seq = $boardVillage_seq) + 1 ".
					"WHERE type = $board_type AND seq = $boardVillage_seq";
mssql_query($boardCilck_query, $conn_hannam);

// Getting Village content
$boardContent_query = "SELECT seq, subject, content, CONVERT(char(10), upload_date, 126) AS upload_date, image_thumb ".
					  "FROM new_board WHERE type = $board_type AND seq = $boardVillage_seq";
$boardContent_query_result = mssql_query($boardContent_query, $conn_hannam);
$boardContent_row = mssql_fetch_array($boardContent_query_result);

// Getting Village uploaded image
$upload_type = 1;
$boardImg_query = "SELECT upload_name FROM new_board_upload ".
				  "WHERE type = $board_type AND seq = $boardVillage_seq AND upload_type = $upload_type ORDER BY upload_seq";
$boardImg_query_result = mssql_query($boardImg_query, $conn_hannam);
$boardImg_num_row = mssql_num_rows($boardImg_query_result);

// Getting Village uploaded file
$upload_type = 2;
$boardFile_query = "SELECT upload_name FROM new_board_upload ".
				  "WHERE type = $board_type AND seq = $boardVillage_seq AND upload_type = $upload_type ORDER BY upload_seq";
$boardFile_query_result = mssql_query($boardFile_query, $conn_hannam);
$boardFile_num_row = mssql_num_rows($boardFile_query_result);
?>


<div id="gray_bg" style="background: #f5f5f5; height:100%; padding:10px 0px 0px 0px;">
	<div id="white_wrapper" >
		<nav id="side" class="village"> <!--class to make it highlight-->
			<h3> Community </h3>

			<? $menu = "menu4"; ?>
			<? include_once "leftMenu.php"; ?>
		</nav>

		<div class="content_wrapper">
			<div class="h1_wrapper stripped">
				<h1> HN Village </h1>
			</div>

			<table style="width:100%">
				<tr>
					<td>NO.</td>
					<td><?=(($LANG == "korean") ? '제목' : 'SUBJECT' ); ?></td> 
					<td><?=(($LANG == "korean") ? '작성일' : 'DATE' ); ?></td>
				</tr>
				<tr>
					<td><?=$boardContent_row['seq']; ?></td>
					<td><?=Br_iconv($boardContent_row['subject']); ?></td> 
					<td><?=$boardContent_row['upload_date']; ?></td>
				</tr>
			</table>

			<div class="board_wrapper">
				<? if($boardContent_row['content'] != " ") { ?>
					<div class="board_content"><?=Br_iconv($boardContent_row['content']); ?></div>
				<? } ?>
				<? if($boardImg_num_row > 0) { ?>
					<div class="board_image">
						<? while($boardImg_row = mssql_fetch_array($boardImg_query_result)) { ?>
							<a href='<?=$upload_path.$boardImg_row['upload_name']; ?>' target="_blank"><img src="<?=$upload_path.$boardImg_row['upload_name']; ?>"></a><br>
						<? } ?>
					</div>
				<? } ?>
				<? if($boardFile_num_row > 0) { ?>
					<div class="attached">
						<img src="img/admin/detail_dot_red.gif">
						<span>첨부파일</span><br>
					</div>
					<div class="board_file">
						<? while($boardFile_row = mssql_fetch_array($boardFile_query_result)) { ?>
							<img src='img/community/file_icon.gif' style="vertical-align:middle">
							<a href='<?=$upload_path.$boardFile_row['upload_name']; ?>' style="text-decoration:none; vertical-align:middle" target="pdf"><?=$boardFile_row['upload_name']?></a><br>
						<? } ?>
					</div>
				<? } ?>
			</div>

			<a href="village.php"><button type="button" class="list">List 목록</button></a>

		</div><!-- content_wrapper  -->
	</div><!-- white_wrapper  -->

	<div class="tothetop" style="background: #f5f5f5;">
		<a href='#top'><img src="img/bottom/top_button.jpg" /></a>
	</div>

</div><!-- gray bg  -->

<?php include 'footer.php'; ?>