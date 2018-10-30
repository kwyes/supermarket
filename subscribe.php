<?php include 'header.php'; ?>

<style>
<?php include 'css/subscribe.css'; ?>
<?php include 'css/footer.css'; ?>
</style>

<link href='http://fonts.googleapis.com/css?family=Chango' rel='stylesheet' type='text/css'>

<script>
function subscribe_save_form() {
	var email = document.getElementsByName('subscribe_email')[0];
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(!filter.test(email.value)) {
		alert('잘못된 이메일 주소입니다.');
		email.focus;
		return false;
	}

	document.getElementById("submit_btn_div").innerHTML = '<button class="btn2">&nbsp;<img src="img/ajax-loader.gif" style="margin-top:-13px;"></button>';
	document.forms.form_subscribe.submit();
}
</script>

<div id="gray_bg" style="background: #f5f5f5; height:100%; padding:10px 0px 0px 0px;">
	<div id="white_wrapper" >
		<nav id="side" class="subscribe"> <!--class to make it highlight-->
			<h3> Weekly Flyer </h3>

			<? $menu = "menu1"; ?>
			<? include_once "leftMenu.php"; ?>
		</nav>

		<div class="content_wrapper">
			<div class="h1_wrapper stripped">
				<h1> Subscribe to E-Flyer </h1>
			</div>

			<form name="form_subscribe" action="admin/customer_handler.php" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<input type="hidden" name="mode" value="subscribe_request">
			<article>
				<img src="img/hannamsupermarket/circle_icon.jpg">

				<? if($LANG == "korean") { ?>
					<section>
						<H3>Subscribe to E-flyer <br></H3>
						<h4>이메일 전단지 신청하기</h4><br>

						<div id="gray_box">
							<table>
								<tr>
									<td width="150px"><strong>이메일:</strong></td>
									<td width="300px"><input class="textbox" type="text" name="subscribe_email" placeholder="  Email Address"></td>
									<td width="160px"></td>
								</tr>
								<tr>
									<td width="150px"><strong>우편번호:</strong></td>
									<td width="300px"><input class="textbox" type="text" name="subscribe_postal" placeholder="  A0A0A0" maxlength=6></td>
									<td width="140px" id="submit_btn_div"><button type="button" class="btn" onclick="subscribe_save_form()" style="cursor:pointer">신청하기</button></td>
								</tr>
								<tr>
									<td colspan=3><p>신청함으로 한남수퍼마켓에서 매주 발행하는 주간 (금~목) 세일광고를 받는데에 동의합니다.<br>(매주 목요일 밤 9:50분 주 1회 발송됩니다.)<br>매장별 보다 더 정확하고 다양한 상품공급 및 부가서비스 제공을 위하여 <br>고객님의 우편번호를 입력해 주시면 감사하겠습니다.</p></td>
								</tr>
							</table>
						</div>
					</section>
				<? } else { ?>
					<section>
						<H3>Subscribe to E-flyer <br></H3><br>

						<div id="gray_box">
							<table>
								<tr>
									<td width="150px"><strong>E-mail:</strong></td>
									<td width="300px"><input class="textbox" type="text" name="subscribe_email" placeholder="  Email Address"></td>
									<td width="160px"></td>
								</tr>
								<tr>
									<td width="150px"><strong>Postal Code:</strong></td>
									<td width="300px"><input class="textbox" type="text" name="subscribe_postal" placeholder="  A0A0A0" maxlength=6></td>
									<td width="140px" id="submit_btn_div"><button type="button" class="btn" onclick="subscribe_save_form()" style="cursor:pointer">Subscribe</button></td>
								</tr>
								<tr>
									<td colspan=3><p>By subscribing it, you agree to receive Hannam Supermarket weekly E-flyer.<br>(The E-flyer will be sent to you every Thursday 9:50pm)<br>We would appreciate your postal code to supply variety products and improve our service.</p></td>
								</tr>
							</table>
						</div>
					</section>
				<? } ?>
			</article>
		</div>
	</div><!-- white_wrapper  -->

	<div class="tothetop" style="background: #f5f5f5;">
		<a href='#top'><img src="img/bottom/top_button.jpg" /></a>
	</div>
</div><!-- gray bg  -->

<?php include 'footer.php'; ?>