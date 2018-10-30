<?php include 'header.php'; ?>

<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
<style>
<?php include 'css/location.css'; ?>
<?php include 'css/footer.css'; ?>
</style>
<link href='http://fonts.googleapis.com/css?family=Chango' rel='stylesheet' type='text/css'>

<div id="gray_bg" style="background: #f5f5f5; height:100%; padding:10px 0px 0px 0px;">
	<div id="white_wrapper" >
		<nav id="side" class="location"> <!--class to make it highlight-->
			<h3> HANNAM Supermarket </h3>

			<? $menu = "menu2"; ?>
			<? include_once "leftMenu.php"; ?>
		</nav>

		<div class="content_wrapper">
			<div class="h1_wrapper stripped">
				<h1>Locations & Business Hours</h1>
			</div>

			<article>
				<img src="img/hannamsupermarket/circle_icon.jpg">

				<section>
					<H3>HANNAM <br>Supermarket <br>Burnaby <br></H3>
					<?=(($LANG == "korean") ? '<h4>한남수퍼마켓 버나비점</H4>' : '' ); ?>
					<br>
					#106-4501 North Rd.  <br>
					Burnaby, BC V3N 4R7 <br>
					604-420-8856 <br>
					<br>
					<em>Store Hours: <br> </em>
					Mon. to Sun. 8:30am-10pm <br>
				</section>

				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2604.677412156374!2d-122.893517!3d49.24460500000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x5486783d3afa65b1%3A0xbb3a662147ce5ba8!2sHannam+Supermarket+Burnaby!5e0!3m2!1sko!2sca!4v1430151484258" width="530" height="320" frameborder="0" style="border:0"></iframe>
			</article>

			<article>
				<img src="img/hannamsupermarket/circle_icon.jpg">

				<section>
					<H3>HANNAM  <br>Supermarket <br>Surrey <br></H3>
					<?=(($LANG == "korean") ? '<h4>한남수퍼마켓 써리점</h4>' : '' ); ?>
					<br>
					#1-15357 104 Ave. <br>
					Surrey, BC V3R 1N5  <br>
					604-580-3433 <br>
					<br>
					<em>Store Hours: <br> </em>
					Mon. to Sun. 8:30am-10pm <br>
				</section>

				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2607.4743193546146!2d-122.79675499999999!3d49.191561!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x5485d7122c4b321f%3A0x20b64b487fc3ff4b!2sHannam+Supermarket+Inc!5e0!3m2!1sko!2sca!4v1430151514876" width="530" height="320" frameborder="0" style="border:0"></iframe>
			</article>
		</div>
	</div><!-- white_wrapper  -->

	<div class="tothetop" >
		<a href='#top'><img src="img/bottom/top_button.jpg" /></a>
	</div>
</div><!-- gray bg  -->

<?php include 'footer.php'; ?>