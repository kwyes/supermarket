<?php include 'header.php'; ?>

<style>
<?php include 'css/announcement.css'; ?>
<?php include 'css/footer.css'; ?>
</style>
<link href='http://fonts.googleapis.com/css?family=Chango' rel='stylesheet' type='text/css'>

<div id="gray_bg" style="background: #f5f5f5; height:100%; padding:10px 0px 0px 0px;">
	<div id="white_wrapper" >
		<nav id="side" class="announcement"> <!--class to make it highlight-->
			<h3> HANNAM Supermarket </h3>

			<? $menu = "menu2"; ?>
			<? include_once "leftMenu.php"; ?>
		</nav>

		<div class="content_wrapper">
			<div class="h1_wrapper stripped">
				<h1>15th Anniversary Announcement (2013.12)</h1>
			</div>

			<article>
				<a href="#"><img src="img/hannamsupermarket/announcement_img.jpg"></a>
			</article>
		</div>
	</div><!-- white_wrapper  -->

	<div class="tothetop" style="background: #f5f5f5;">
		<a href='#top'><img src="img/bottom/top_button.jpg" /></a>
	</div>
</div><!-- gray bg  -->

<?php include 'footer.php'; ?>