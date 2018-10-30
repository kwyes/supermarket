<?php include 'header.php'; ?>


<style>
<?php include 'css/career.css'; ?>
<?php include 'css/footer.css'; ?>
</style>

<link href='http://fonts.googleapis.com/css?family=Chango' rel='stylesheet' type='text/css'>


<div id="gray_bg" style="background: #f5f5f5; height:100%; padding:10px 0px 0px 0px;">
	<div id="white_wrapper" >
		<nav id="side" class="career"> <!--class to make it highlight-->
			<h3> HANNAM Supermarket </h3>

			<? $menu = "menu2"; ?>
			<? include_once "leftMenu.php"; ?>
		</nav>

		<div class="content_wrapper">
			<div class="h1_wrapper stripped">
				<h1>Career</h1>
			</div>

			<article>
				<img src="img/hannamsupermarket/circle_icon.jpg">
				<section>
					<H3>Career<br></H3><br>
					<p>
					At Hannam Supermarket, to find hardworkers and place them<br>
					wherever they can excel in their field,  <br>
					we welcome job applications anytime of the year.<br>
					When we have a job oppportunity we will review <br>
					the applications received and will contact the right candidate.<br>
					(The applications are only used for our career database.)<br>
					
					<a href="career_form.php"><button type="button" class="btn" style="cursor:pointer">Apply ></button></a><br>
					<em>Inquiries : Burnaby 604-420-8856 / Surrey 604-580-3433</em>
					</p>
				</section>
			</article>

			<img src="img/hannamsupermarket/career_bg.jpg" style="margin-right:30px;float:right;" >
		</div><!-- white_wrapper  -->
	</div>

	<div class="tothetop" style="background: #f5f5f5;">
		<a href='#top'><img src="img/bottom/top_button.jpg" /></a>
	</div>
</div><!-- gray bg  -->

<?php include 'footer.php'; ?>