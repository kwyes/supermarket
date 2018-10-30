<?php include 'header.php'; ?>

<style>
<?php include 'css/managerschoice.css'; ?>
<?php include 'css/footer.css'; ?>
</style>

<link href='http://fonts.googleapis.com/css?family=Chango' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Dosis:700,800' rel='stylesheet' type='text/css'>

<?
/***************************************
*	DB - new_regularUpdate (Type = 2)
****************************************/
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
$db_type = 2;
$image_path = "upload/manager_choice/";

$choice_query = "SELECT TOP 1 seq, subject, image_name, link ".
			    "FROM new_regularUpdate ".
			    "WHERE type = $db_type AND start_date <= GETDATE() ".
			    "ORDER BY start_date DESC";
$choice_query_result = mssql_query($choice_query, $conn_hannam);
$choice_row = mssql_fetch_array($choice_query_result);

$updateHit_query = "UPDATE new_regularUpdate SET ".
				   "click_counter = (SELECT click_counter FROM new_regularUpdate WHERE type = $db_type AND seq = ".$choice_row['seq'].") + 1 ".
				   "WHERE type = $db_type AND seq = ".$choice_row['seq'];
mssql_query($updateHit_query, $conn_hannam);
?>

<div id="gray_bg" style="background: #f5f5f5; height:100%; padding:10px 0px 0px 0px;">
	<div id="white_wrapper" >
		<nav id="side" class="managerschoice"> <!--class to make it highlight-->
			<h3> Weekly Flyer </h3>

			<? $menu = "menu1"; ?>
			<? include_once "leftMenu.php"; ?>
		</nav>

		<div class="content_wrapper">
			<div class="h1_wrapper stripped">
				<h1> Manager's Choice </h1>
			</div>

			<div class="choice_wrapper">
				<div class="choice_subject"><?=Br_iconv($choice_row['subject']); ?></div>

				<a href="<?=$image_path.$choice_row['image_name']; ?>" target="_blank">
					<img src="<?=$image_path.$choice_row['image_name']; ?>" style="max-width:731px" alt="manager's choice"> 
				</a>
			</div>

		</div><!-- content_wrapper  -->
	</div><!-- white_wrapper  -->

	<div class="tothetop" style="background: #f5f5f5;">
		<a href='#top'><img src="img/bottom/top_button.jpg" /></a>
	</div>
</div><!-- gray bg  -->

<?php include 'footer.php'; ?>