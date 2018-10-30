<?php include 'header.php'; ?>

<style>
<?php include 'css/subscribe.css'; ?>
<?php include 'css/footer.css'; ?>
</style>

<link href='http://fonts.googleapis.com/css?family=Chango' rel='stylesheet' type='text/css'>

<?
$type = ($_GET['type']) ? $_GET['type'] : $_POST['type'];

if($type) {
	$email = base64_decode(($_GET['mem']) ? $_GET['mem'] : $_POST['mem']);
	$seq = ($_GET['id']) ? $_GET['id'] : $_POST['id'];
	$key = ($_GET['key']) ? $_GET['key'] : $_POST['key'];

	$item_type = $type;
	$verifyEmail_query = "SELECT seq, verification_key FROM new_subscribe_member WHERE type = $item_type AND email = '$email'";
	$verifyEmail_query_result = mssql_query($verifyEmail_query, $conn_hannam);
	$verifyEmail_row = mssql_fetch_array($verifyEmail_query_result);

	if((md5($verifyEmail_row['seq']) == $seq) && (md5($verifyEmail_row['verification_key']) == $key)) {
		$subscribeUpdate_query = "UPDATE new_subscribe_member SET status = 1, status_date = GETDATE() WHERE type = $item_type AND email = '$email'";
		mssql_query($subscribeUpdate_query, $conn_hannam);

		$verified = true;
	} else {
		$verified = false;
	}
} else {
	ABSOLUTE_PATH."/subscribe_confirm.php?mem=".base64_encode($toEmail)."&flyerid=".md5($member_seq_flyer)."&eventid=".md5($member_seq_event)."&flyerkey=".md5($verification_key_flyer)."&eventkey=".md5($verification_key_event);

	$email = base64_decode(($_GET['mem']) ? $_GET['mem'] : $_POST['mem']);
	$flyerid = ($_GET['flyerid']) ? $_GET['flyerid'] : $_POST['flyerid'];
	$flyerkey = ($_GET['flyerkey']) ? $_GET['flyerkey'] : $_POST['flyerkey'];
	$eventid = ($_GET['eventid']) ? $_GET['eventid'] : $_POST['eventid'];
	$eventkey = ($_GET['eventkey']) ? $_GET['eventkey'] : $_POST['eventkey'];

	$verifyEmail_query = "SELECT seq, verification_key, type FROM new_subscribe_member WHERE (type = 1 OR type = 2) AND email = '$email'";
	$verifyEmail_query_result = mssql_query($verifyEmail_query, $conn_hannam);

	while($verifyEmail_row = mssql_fetch_array($verifyEmail_query_result)) {
		if(($verifyEmail_row['type'] == 1) && (md5($verifyEmail_row['seq']) == $flyerid) && (md5($verifyEmail_row['verification_key']) == $flyerkey)) {
			$flyer_verified = true;
		}
		if(($verifyEmail_row['type'] == 2) && (md5($verifyEmail_row['seq']) == $eventid) && (md5($verifyEmail_row['verification_key']) == $eventkey)) {
			$event_verified = true;
		}
	}

	if($flyer_verified && $event_verified) {
		$subscribeUpdate_query = "UPDATE new_subscribe_member SET status = 1, status_date = GETDATE() WHERE type = 1 AND email = '$email'";
		mssql_query($subscribeUpdate_query, $conn_hannam);
		$subscribeUpdate_query = "UPDATE new_subscribe_member SET status = 1, status_date = GETDATE() WHERE type = 2 AND email = '$email'";
		mssql_query($subscribeUpdate_query, $conn_hannam);

		$verified = true;
	} else {
		$verified = false;
	}
}

//$verified = check_subscribe_verification_email($email, $seq, $key);
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
					<? if($verified) { ?>
						한남 뉴스레터를 신청해주셔서 감사합니다.<br>
						다음 뉴스레터부터 이메일로 받아 보실수 있습니다.<br>
						<br>
						Thank you for signing up Hannam E-flyer.<br>
						You will be receiveing the E-newsletter.<br>
					<? } else { ?>
						이메일 인증중 오류가 발생 하였습니다.<br>
						다시 인증해주시기 바랍니다.<br>
						<br>
						Unable to verify email. Please try again.<br>
					<? } ?>
				</p>
			</div>
			
			<div style="text-align:center;">
				<a href="<?=(($verified) ? ABSOLUTE_PATH : 'subscribe.php' ); ?>"><button type="button" class="btn">확인</button></a><br>
			</div>
		</div>
	</div><!-- white_wrapper  -->

	<div class="tothetop" style="background: #f5f5f5;">
		<a href='#top'><img src="img/bottom/top_button.jpg" /></a>
	</div>
</div><!-- gray bg  -->

<?php include 'footer.php'; ?>