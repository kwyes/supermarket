<?php include 'header.php'; ?>

<style>
<?php include 'css/subscribe.css'; ?>
<?php include 'css/footer.css'; ?>
</style>

<link href='http://fonts.googleapis.com/css?family=Chango' rel='stylesheet' type='text/css'>


<div id="gray_bg" style="background: #f5f5f5; height:100%; padding:10px 0px 0px 0px;">
	<div id="white_wrapper" >
		<nav id="side" class="subscribe"> <!--class to make it highlight-->
			<h3> Weekly Flyer </h3>

			<? $menu = "menu1"; ?>
			<? include_once "leftMenu.php"; ?>
		</nav>

		<div class="content_wrapper">
			<div class="h1_wrapper stripped">
				<h1>Subscribe to E-Flyer : E-Mail Verification</h1>
			</div>

			<div style="padding:10px;">
				<p style="font-size:20px;text-align: center;line-height:30px;">
					한남 이메일 뉴스레터 구독 신청.<br>
					고객님께서 입력하신 이메일을 확인 하시고,본 신청과 관련하여 수신된 이메일<br>
					본문에 제시된 링크를 클릭하셔서 이메일을 인증하시면됩니다.<br>
					혹시 바로 못 받으시면 스팸 메일박스를 체크해 주십시요.<br>
					<br>
					Subscribe to Hannam E-flyer.<br>
					Please check your E-mail and click a link to confirm the subscription.<br>
					Please check your spam folder if you do not receive the E-mail.<br>
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