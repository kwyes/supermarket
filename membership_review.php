<?php include 'header.php'; ?>

<style>
<?php include 'css/join_membership.css'; ?>
<?php include 'css/footer.css'; ?>
</style>

<link href='http://fonts.googleapis.com/css?family=Chango' rel='stylesheet' type='text/css'>

<script>
function membership_submit() {
	var form = document.forms.form_membership_review;

	form.action = "admin/customer_handler.php";
	var answer = confirm("Submit the application?");
	if(answer) {
		form.mode.value = "membership_apply";
		form.submit();
	}
}

function membership_modify() {
	var form = document.forms.form_membership_review;

	form.action = "membership_form.php";
	var answer = confirm("Modify the application?");
	if(answer) {
		form.mode.value = "modify";
		form.submit();
	}
}
</script>

<?
$membership_nameEng = $_POST['membership_nameEng'];
$membership_nameKor = $_POST['membership_nameKor'];
$membership_phone = $_POST['membership_phone'];
$membership_email = $_POST['membership_email'];
$membership_language = $_POST['membership_language'];
$membership_street = $_POST['membership_street'];
$membership_city = $_POST['membership_city'];
$membership_postal = $_POST['membership_postal'];
$membership_province = $_POST['membership_province'];
$membership_bDay = $_POST['membership_bDay'];
$membership_familyNum = $_POST['membership_familyNum'];
$membership_agree = $_POST['membership_agree'];
$membership_weeklyFlyer = $_POST['membership_weeklyFlyer'];
$membership_eventFlyer = $_POST['membership_eventFlyer'];
?>

<div id="gray_bg" style="background: #f5f5f5; height:100%; padding:10px 0px 0px 0px;">
	<div id="white_wrapper" >
		<article>
			<div class="h1_wrapper stripped">
				<h1> Join Hannam Membership : Application </h1>
			</div>

			<form name="form_membership_review" action="" method="post" accept-charset="utf-8">
				<input type="hidden" name="mode">
				<input type="hidden" name="membership_nameEng" value="<?=$membership_nameEng; ?>">
				<input type="hidden" name="membership_nameKor" value="<?=$membership_nameKor; ?>">
				<input type="hidden" name="membership_phone" value="<?=$membership_phone; ?>">
				<input type="hidden" name="membership_email" value="<?=$membership_email; ?>">
				<input type="hidden" name="membership_language" value="<?=$membership_language; ?>">
				<input type="hidden" name="membership_street" value="<?=$membership_street; ?>">
				<input type="hidden" name="membership_city" value="<?=$membership_city; ?>">
				<input type="hidden" name="membership_postal" value="<?=$membership_postal; ?>">
				<input type="hidden" name="membership_province" value="<?=$membership_province; ?>">
				<input type="hidden" name="membership_bDay" value="<?=$membership_bDay; ?>">
				<input type="hidden" name="membership_familyNum" value="<?=$membership_familyNum; ?>">
				<input type="hidden" name="membership_agree" value="<?=$membership_agree; ?>">
				<input type="hidden" name="membership_weeklyFlyer" value="<?=$membership_weeklyFlyer; ?>">
				<input type="hidden" name="membership_eventFlyer" value="<?=$membership_eventFlyer; ?>">
			</form>
			
			<div style="text-align: left; padding:10px;">
				<table class="membership_review" cellspacing=0 cellpadding=0>
					<tr>
						<td class="membership_title" colspan=2>Membership Application Review</td>
					</tr>
					<tr>
						<td class="membership_subject"><strong>Legal Name <br> (영문 성명)</strong></td>
						<td class="membership_content"><?=$membership_nameEng; ?></td>
					</tr>
					<tr>
						<td class="membership_subject"><strong>Korean Name <br> (한글 성명)</strong></td>
						<td class="membership_content"><?=$membership_nameKor; ?></td>
					</tr>
					<tr>
						<td class="membership_subject"><strong>Phone Number <br> (전화번호)</strong></td>
						<td class="membership_content"><?=$membership_phone; ?></td>
					</tr>
					<tr>
						<td class="membership_subject"><strong>Email <br> (이메일)</strong></td>
						<td class="membership_content"><?=$membership_email; ?></td>
					</tr>
					<tr>
						<td class="membership_subject"><strong>Preferred Language <br> (언어)</strong></td>
						<td class="membership_content"><?=$membership_language; ?></td>
					</tr>
					<tr>
						<td class="membership_subject"><strong>Current Address <br> (현재 거주 주소)</strong></td>
						<td class="membership_content">
							<?
							if($membership_street)		echo $membership_street.", ";
							if($membership_city)		echo $membership_city.", ";
							if($membership_postal)		echo $membership_postal.", ";
							if($membership_province)	echo $membership_province;
							?>
						</td>
					</tr>
					<tr>
						<td class="membership_subject"><strong>Date of Birth <br> (생년월일)</strong></td>
						<td class="membership_content"><?=$membership_bDay; ?></td>
					</tr>
					<tr>
						<td class="membership_subject"><strong>Number of Family Members <br> (가족수)</strong></td>
						<td class="membership_content"><?=$membership_familyNum; ?></td>
					</tr>
					<tr>
						<td class="membership_subject"><strong>Subscribe to E-Flyer <br> (주간광고 이메일 수신)</strong></td>
						<td class="membership_content"><?=(($membership_weeklyFlyer) ? "Yes" : "No" ); ?></td>
					</tr>
					<tr>
						<td class="membership_subject"><strong>Subscribe to Events <br> (이벤트 이메일 수신)</strong></td>
						<td class="membership_content"><?=(($membership_eventFlyer) ? "Yes" : "No" ); ?></td>
					</tr>
				</table>
			</div>
			
			<div style="margin-left:220px;">
				<button type="button" class="btn" onclick="membership_modify()" style="cursor:pointer;">Modify</button>
				<button type="button" class="btn" onclick="membership_submit()" style="cursor:pointer;">Submit</button>
			</div>
		</article>
	</div><!-- white_wrapper  -->
</div><!-- gray bg  -->

<?php include 'footer.php'; ?>