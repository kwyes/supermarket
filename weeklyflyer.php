<?php include 'header.php'; ?>

<style>
<?php include 'css/weeklyflyer.css'; ?>
<?php include 'css/footer.css'; ?>
</style>

<link href='http://fonts.googleapis.com/css?family=Chango' rel='stylesheet' type='text/css'>

<script>
function view_count(browser, language, seq) {
	if(browser == "IPhone" || browser == "Android") {
		if(language == 'kor') {
			var type = 1;
			document.getElementById("view_count").src = "admin/customer_handler.php?mode=flyer_view_mobile&type=" + type + "&seq=" + seq;
		} else if(language == 'chi') {
			var type = 4;
			document.getElementById("view_count").src = "admin/customer_handler.php?mode=flyer_view_mobile&type=" + type + "&seq=" + seq;
		}
	} else {
		if(language == 'kor') {
			var type = 1;
			document.getElementById("view_count").src = "admin/customer_handler.php?mode=flyer_view&type=" + type + "&seq=" + seq;
		} else if(language == 'chi') {
			var type = 4;
			document.getElementById("view_count").src = "admin/customer_handler.php?mode=flyer_view&type=" + type + "&seq=" + seq;
		}
	}
}
</script>

<?
/***************************************
*	DB - new_regularUpdate (Type = 1)
****************************************/
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
$db_type_kor = 1;
$db_type_chi = 4;
$image_path_kor = "upload/weekly_flyer/Korean/";
$image_path_chi = "upload/weekly_flyer/Chinese/";

$flyer_query = "SELECT TOP 1 seq, subject, image_name, link ".
			   "FROM new_regularUpdate ".
			   "WHERE type = $db_type_kor AND start_date <= GETDATE() ".
			   "ORDER BY start_date DESC";
$flyer_query_result = mssql_query($flyer_query, $conn_hannam);
$flyer_row = mssql_fetch_array($flyer_query_result);

$flyerChi_query = "SELECT TOP 1 seq, image_name, link ".
			      "FROM new_regularUpdate ".
			      "WHERE type = $db_type_chi AND seq = ".$flyer_row['seq'];
$flyerChi_query_result = mssql_query($flyerChi_query, $conn_hannam);
$flyerChi_row = mssql_fetch_array($flyerChi_query_result);
?>

<iframe id="view_count" style="display:none;"></iframe>
<div id="gray_bg" style="background: #f5f5f5; height:100%; padding:10px 0px 0px 0px;">
	<div id="white_wrapper" >
		<nav id="side" class="weeklyflyer"> <!--class to make it highlight-->
			<h3> Weekly Flyer </h3>

			<? $menu = "menu1"; ?>
			<? include_once "leftMenu.php"; ?>
		</nav>

		<div class="content_wrapper">
			<div class="h1_wrapper stripped">
				<h1> Weekly Flyer </h1>
			</div>

			<div class="flyer_wrapper">
				<div class="flyer_subject"><?=Br_iconv($flyer_row['subject']); ?></div>

				<div>
					<a href="<?=$image_path_kor.$flyer_row['link']; ?>" target="_blank" onClick="view_count('<?=$_SESSION['browser']; ?>', 'kor', <?=$flyer_row['seq']; ?>)">
						<img src="<?=$image_path_kor.$flyer_row['image_name']; ?>" alt="weekly flyer Korean" class="image1" > 
						<img src="img/flyer/flyer_thumb_over_380x260.jpg" class="image2"> 
					</a>
					<div class="flyer_type1">[Korean]</div>
				</div>
				<div>
					<a href="<?=$image_path_chi.$flyerChi_row['link']; ?>" target="_blank" onClick="view_count('<?=$_SESSION['browser']; ?>','chi', <?=$flyerChi_row['seq']; ?>)">
						<img src="<?=$image_path_chi.$flyerChi_row['image_name']; ?>" alt="weekly flyer Chinese" class="image3" > 
						<img src="img/flyer/flyer_thumb_over_200x260.jpg" class="image4"> 
					</a>
					<div class="flyer_type2">[Chinese]</div>
				</div>
			</div>
		</div>
	</div><!-- white_wrapper  -->

	<div class="tothetop" style="background: #f5f5f5;">
		<a href='#top'><img src="img/bottom/top_button.jpg" /></a>
	</div>
</div><!-- gray bg  -->

<?php include 'footer.php'; ?>