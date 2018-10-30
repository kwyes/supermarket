<?php include 'header.php'; ?>


<style>
<?php include 'css/contact.css'; ?>
<?php include 'css/footer.css'; ?>
</style>

<link href='http://fonts.googleapis.com/css?family=Chango' rel='stylesheet' type='text/css'>

<div id="gray_bg" style="background: #f5f5f5; height:100%; padding:10px 0px 0px 0px;">
	<div id="white_wrapper" >
		<nav id="side" class="contact"> <!--class to make it highlight-->
			<h3> Customer Service</h3>

			<? $menu = "menu3"; ?>
			<? include_once "leftMenu.php"; ?>
		</nav>

		<div class="content_wrapper">
			<div class="h1_wrapper stripped">
				<h1>Contact Us : Confirmation</h1>
			</div>

			<div style="padding:10px;">
				<p style="font-size:20px;text-align: center;line-height:30px;">
					Thank you!<br>
					Your suggestion was successfully submitted.<br>
					Hannam will do its best to serve customers.<br>
					<br>
					감사합니다!<br>
					성공적으로 의견이 접수되었습니다!<br>
					더 나은 한남이 되기위해 노력하겠습니다.<br>
				</p>
			</div>
			
			<div style="text-align:center;">
				<a href="contact.php"><button type="button" class="btn">확인</button></a><br>
			</div>
		</div><!-- content_wrapper  -->
	</div><!-- white_wrapper  -->

	<div class="tothetop" style="background: #f5f5f5;">
		<a href='#top'><img src="img/bottom/top_button.jpg" /></a>
	</div>
</div><!-- gray bg  -->

<?php include 'footer.php'; ?>