<?php include 'header.php'; ?>

<style>
<?php include 'css/subscribe.css'; ?>
<?php include 'css/footer.css'; ?>
</style>

<link href='http://fonts.googleapis.com/css?family=Chango' rel='stylesheet' type='text/css'>

<?
$email = base64_decode(($_GET['mem']) ? $_GET['mem'] : $_POST['mem']);
$seq = ($_GET['id']) ? $_GET['id'] : $_POST['id'];
$key = ($_GET['key']) ? $_GET['key'] : $_POST['key'];
$type = ($_GET['type']) ? $_GET['type'] : $_POST['type'];

$item_type = $type;
$verifyEmail_query = "SELECT seq, verification_key FROM new_subscribe_member WHERE type = $item_type AND email = '$email'";
$verifyEmail_query_result = mssql_query($verifyEmail_query, $conn_hannam);
$verifyEmail_row = mssql_fetch_array($verifyEmail_query_result);

if((md5($verifyEmail_row['seq']) == $seq) && (md5($verifyEmail_row['verification_key']) == $key)) {
	$subscribeUpdate_query = "UPDATE new_subscribe_member SET status = 0, status_date = GETDATE() WHERE type = $item_type AND email = '$email'";
	mssql_query($subscribeUpdate_query, $conn_hannam);
}

?>

<div id="gray_bg" style="background: #f5f5f5; height:100%; padding:10px 0px 0px 0px;">
	<div id="white_wrapper" >
		<nav id="side" class="subscribe"> <!--class to make it highlight-->
			<h3> Weekly Flyer </h3>

			<? $menu = "menu1"; ?>
			<? include_once "leftMenu.php"; ?>
		</nav>

		<div class="content_wrapper">
			<div class="h1_wrapper stripped">
				<h1>Subscribe to E-Flyer : E-Mail Confirmation</h1>
			</div>

			<div style="padding:10px;">
				<p style="font-size:20px;text-align: center;line-height:30px;">
					한남 이메일 뉴스레터 수신거부가 접수되었습니다.<br>
					감사합니다.<br>
					<br>
					You have been unsubscribed from our E-newsletter list.<br>
					Thank you.<br>
				</p>
			</div>
			
			<div style="text-align:center;">
				<a href="subscribe.php"><button type="button" class="btn">확인</button></a><br>
			</div>
		</div>
	</div><!-- white_wrapper  -->

	<div class="tothetop" style="background: #f5f5f5;">
		<a href='#top'><img src="img/bottom/top_button.jpg" /></a>
	</div>
</div><!-- gray bg  -->

<?php include 'footer.php'; ?>