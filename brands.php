<?php include 'header.php'; ?>

<link href='http://fonts.googleapis.com/css?family=Leckerli+One' rel='stylesheet' type='text/css'>
<style>
<?php include 'css/brands.css'; ?>
<?php include 'css/footer.css'; ?>
</style>
<link href='http://fonts.googleapis.com/css?family=Chango' rel='stylesheet' type='text/css'>

<div id="gray_bg" style="background: #f5f5f5; height:100%; padding:10px 0px 0px 0px;">
	<div id="white_wrapper" >
		<nav id="side" class="brands"> <!--class to make it highlight-->
			<h3> HANNAM Supermarket </h3>

			<? $menu = "menu2"; ?>
			<? include_once "leftMenu.php"; ?>	
		</nav>

		<div class="content_wrapper">
			<div class="h1_wrapper stripped">
				<h1>Hannam Family Brands</h1>
			</div>

			<article>
				<h2>Hannam carries the widest selection of Korean products.</h2>
				<?=(($LANG == "korean") ? '<h3>밴쿠버에서 가장 다양하고 가장 많은 한국식품을 만나실 수 있는 곳 
				한남수퍼마켓입니다.</h3>' : '' ); ?>
			</article>

			<article>
				<img src="img/hannamsupermarket/brands.jpg">
			</article>
		</div>
	</div><!-- white_wrapper  -->

	<div class="tothetop" style="background: #f5f5f5;">
		<a href='#top'><img src="img/bottom/top_button.jpg" /></a>
	</div>
</div><!-- gray bg  -->

<?php include 'footer.php'; ?>