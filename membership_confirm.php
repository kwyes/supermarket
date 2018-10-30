<?php include 'header.php'; ?>

<style>
<?php include 'css/join_membership.css'; ?>
<?php include 'css/footer.css'; ?>
</style>

<link href='http://fonts.googleapis.com/css?family=Chango' rel='stylesheet' type='text/css'>

<?
$email = base64_decode(($_GET['mem']) ? $_GET['mem'] : $_POST['mem']);
$flyer_seq = ($_GET['flyerid']) ? $_GET['flyerid'] : $_POST['flyerid'];
$event_seq = ($_GET['eventid']) ? $_GET['eventid'] : $_POST['eventid'];
$flyer_key = ($_GET['flyerkey']) ? $_GET['flyerkey'] : $_POST['flyerkey'];
$event_key = ($_GET['eventkey']) ? $_GET['eventkey'] : $_POST['eventkey'];

$verifyEmailFlyer_query = "SELECT seq, verification_key FROM new_subscribe_member WHERE type = 1 AND email = '$email'";
$verifyEmailFlyer_query_result = mssql_query($verifyEmailFlyer_query, $conn_hannam);
$verifyEmailFlyer_row = mssql_fetch_array($verifyEmailFlyer_query_result);

$verifyEmailEvent_query = "SELECT seq, verification_key FROM new_subscribe_member WHERE type = 2 AND email = '$email'";
$verifyEmailEvent_query_result = mssql_query($verifyEmailEvent_query, $conn_hannam);
$verifyEmailEvent_row = mssql_fetch_array($verifyEmailEvent_query_result);

if((md5($verifyEmailFlyer_row['seq']) == $flyer_seq) && (md5($verifyEmailFlyer_row['verification_key']) == $flyer_key) && (md5($verifyEmailEvent_row['seq']) == $event_seq) && (md5($verifyEmailEvent_row['verification_key']) == $event_key)) {
	$subscribeUpdate_query = "UPDATE new_subscribe_member SET status = 1, status_date = GETDATE() WHERE type = 1 AND email = '$email'";
	mssql_query($subscribeUpdate_query, $conn_hannam);

	$subscribeUpdate_query2 = "UPDATE new_subscribe_member SET status = 1, status_date = GETDATE() WHERE type = 2 AND email = '$email'";
	mssql_query($subscribeUpdate_query2, $conn_hannam);

	$verified = true;
} else {
	$verified = false;
}
?>

<div id="gray_bg" style="background: #f5f5f5; height:100%; padding:10px 0px 0px 0px;">
	<div id="white_wrapper" >
		<article>
			<div class="h1_wrapper stripped">
				<h1> Join Hannam Membership </h1>
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
		</article>
	</div><!-- white_wrapper  -->
</div><!-- gray bg  -->

<?php include 'footer.php'; ?>