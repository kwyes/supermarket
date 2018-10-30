<?php include 'header.php'; ?>

<style>
<?php include 'css/body_homepage.css'; ?>
<?php include 'css/footer.css'; ?>
</style>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
<script src="http://malsup.github.io/jquery.cycle2.js"></script>
<script src="http://malsup.github.io/jquery.cycle2.scrollVert.js"></script>
<script type="text/javascript" src="<?=ABSOLUTE_PATH?>/js/coin-slider/coin-slider.js"></script>
<link rel="stylesheet" href="<?=ABSOLUTE_PATH?>/js/coin-slider/coin-slider-styles.css" type="text/css" />

<script>
$(document).ready(function(){
	$('#coin-slider').coinslider({ width: 980, height:443, delay: 10000 });
});
</script>

<?
mssql_select_db(HANNAM_DB_NAME, $conn_hannam);

// type = 5
$db_type = 5;

$popupCheck_query = "SELECT newTab FROM new_mainPage WHERE type = $db_type";
$popupCheck_query_result = mssql_query($popupCheck_query, $conn_hannam);
$popupCheck_row = mssql_fetch_array($popupCheck_query_result);

if($popupCheck_row['newTab'] == 1) {
?>
	<div id="popup_new" style="z-index:1000;">
		<? include_once "popup.php"; ?>
	</div>
<?
}

// type = 1
$image_path = "upload/mainPage/";
$db_type = 1;

$slideAD_query = "SELECT filename, link, newTab FROM new_mainPage WHERE type = 1 ORDER BY seq";
$slideAD_query_result = mssql_query($slideAD_query, $conn_hannam);
?>

<div id="gray_wrapper">
	<div style="position:relative; width:980px; height:443px; margin:auto; z-index:100;">
		<div id='coin-slider'>
			<? while($slideAD_row = mssql_fetch_array($slideAD_query_result)) { ?>
				<a href="<?=$slideAD_row['link']; ?>" target="<?=(($slideAD_row['newTab']) ? '_blank' : '' )?>"><img src='<?=$image_path.$slideAD_row['filename']?>' ></a>
			<? } ?>
		</div>
	</div>
</div>

<?
$image_path = "upload/mainPage/";
$db_type = 2;

// type = 2
$bannerLeft_query = "SELECT seq, subject, content, filename, link, newTab FROM new_mainPage WHERE type = $db_type ORDER by seq";
$bannerLeft_query_result = mssql_query($bannerLeft_query, $conn_hannam);
$bannerLeft_row = mssql_fetch_array($bannerLeft_query_result);
mssql_data_seek($bannerLeft_query_result, 1);
$second_img = mssql_fetch_array($bannerLeft_query_result);
?>

<div id="articles_wrapper">

	<article id="wrapper1">
		<!-- 230px X 227px-->
		<a href="<?=(($bannerLeft_row['link']) ? $bannerLeft_row['link'] : '' ) ?>" target='<?=(($bannerLeft_row['newTab']) ? '_blank' : '' )?>'>
			<img width="230px" height="227px" src="<?=$image_path.$bannerLeft_row['filename']; ?>" onmouseout="this.src='<?=$image_path.$bannerLeft_row['filename']; ?>'" onmouseover="this.src='<?=$image_path.$second_img['filename']; ?>'">
		</a>
		<article id="managers" class="zigzag">
			<article id="managers_textbox">
				<H3><?=Br_iconv($bannerLeft_row['subject']); ?></H3>
				<p class="body"><?=Br_iconv($bannerLeft_row['content']); ?></p>
				<p class="comment">  </p>
			</article>
			<div class="right_arrow_icon">
				<a href="<?=(($bannerLeft_row['link']) ? $bannerLeft_row['link'] : '' ) ?>" target='<?=(($bannerLeft_row['newTab']) ? '_blank' : '' )?>'><img src="<?=$image_path."go_btn.png"; ?>"></a>
			</div>
		</article>
	</article>

	<?
	// type = 3
	$bannerMid_query = "SELECT subject, content, filename, link, newTab FROM new_mainPage WHERE type = 3 ORDER by seq";
	$bannerMid_query_result = mssql_query($bannerMid_query, $conn_hannam);
	$bannerMid_row = mssql_fetch_array($bannerMid_query_result);
	?>

	<article id="wrapper2">
		<!-- 351px X 227px-->
		<a href="<?=(($bannerMid_row['link']) ? $bannerMid_row['link'] : '' ) ?>" target='<?=(($bannerMid_row['newTab']) ? '_blank' : '' )?>'>
			<img width="351px" height="227px" src="<?=$image_path.$bannerMid_row['filename']; ?>">
		</a>
		<article id="village" class="zigzag2">
			<article id="village_textbox">
				<H3><?=Br_iconv($bannerMid_row['subject']); ?></H3>
				<p class="body"><?=Br_iconv($bannerMid_row['content']); ?></p>
				<p class="comment"> </p>
			</article>
			<div class="right_arrow_icon">
				<a href="<?=(($bannerMid_row['link']) ? $bannerMid_row['link'] : '' ) ?>" target='<?=(($bannerMid_row['newTab']) ? '_blank' : '' )?>'><img src="<?=$image_path."go_btn.png"; ?>"></a>
			</div>
		</article>
	</article>


	<div class="tabmain">
		<input id="tab1" type="radio" name="tabs" checked>
		<label for="tab1">NEWS</label>

		<input id="tab2" type="radio" name="tabs">
		<label for="tab2">HN Village</label>

		<div class="tabcontent">
			<?
			$board_type = 2;
			$boardNews_query = "SELECT TOP 5 seq, subject, content, image_thumb FROM new_board ".
							   "WHERE type = $board_type AND active = 1 ORDER BY seq DESC";
			$boardNews_query_result = mssql_query($boardNews_query, $conn_hannam);
			?>
			
			<div id="tabcontent1" class="tabhide1">
				<? while($boardNews_row = mssql_fetch_array($boardNews_query_result)) { ?>
					<div class="tab1sub">
						<a href="news_view.php?seq=<?=$boardNews_row['seq']; ?>" style="text-decoration:none;"><h4 class="tab1h"><?=Br_iconv($boardNews_row['subject']); ?></h4></a>
						<!--<a href="news_view.php?seq=<?=$boardNews_row['seq']; ?>" style="text-decoration:none;"><p class="tab1body"><?=Br_iconv($boardNews_row['content']); ?></p></a>-->
					</div>
				<? } ?>
			</div>
			
			<?
			$board_type = 1;
			$upload_path = "upload/village/";
			$boardVillage_query = "SELECT TOP 5 seq, subject, content, image_thumb FROM new_board ".
								  "WHERE type = $board_type AND active = 1 ORDER BY seq DESC";
			$boardVillage_query_result = mssql_query($boardVillage_query, $conn_hannam);
			?>
			
			<div id="tabcontent2" class="tabhide2">
				<? while($boardVillage_row = mssql_fetch_array($boardVillage_query_result)) { ?>
					<div class="tab2sub">
						<div class="tab2pic">
							<? if($boardVillage_row['image_thumb']) { ?>
								<a href="village_view.php?seq=<?=$boardVillage_row['seq']; ?>"><img src="<?=$upload_path.$boardVillage_row['image_thumb']; ?>" alt="" width="65px" height="39px"></a>
							<? } else { ?>
								<a href="village_view.php?seq=<?=$boardVillage_row['seq']; ?>"><img src="upload/empty_thumb.jpg" alt=""></a>
							<? } ?>
						</div>
						
						<a href="village_view.php?seq=<?=$boardVillage_row['seq']; ?>">
							<div class="tab2text">
								<h4 class="tab2h"><?=Br_iconv($boardVillage_row['subject']); ?></h4>
								<!--<p class="tab2body"><?=Br_iconv($boardVillage_row['content']); ?></p>-->
							</div>
						</a>
					</div>
				<? } ?>
			</div>
		</div><!-- tabcontent  -->

		<div>
			<a href="news.php"><img src="img/newList_icon.png" style="float:left; margin-top:3px;"></a>
			<a href="village.php"><img src="img/villageList_icon.png" style="float:right; margin-top:3px;"></a>
		</div>
	</div> <!-- tabmain  -->
</div><!-- articles_wrapper  -->



<?
// type = 4
$db_type = 4;

$promoMedia_query = "SELECT link FROM new_mainPage WHERE type = $db_type ORDER BY seq";
$promoMedia_query_result = mssql_query($promoMedia_query, $conn_hannam);
?>
<div class="promovideo">
	<h2><i class="icon-movie"></i>HANNAM Brands Promotional Videos</h2>

	<? while($promoMedia_row = mssql_fetch_array($promoMedia_query_result)) { ?>
		<div class="video">
			<iframe width="190" height="107" src="http://www.youtube-nocookie.com/embed/<?=$promoMedia_row['link']; ?>?controls=0&showinfo=0&rel=0" frameborder="0" allowfullscreen></iframe>
		</div>
	<? } ?>
</div>

<div class="tothetop" style="background: #fff;">
	<a href='#top'><img src="img/bottom/top_button.jpg" /></a>
</div>

<?php include 'footer.php'; ?>