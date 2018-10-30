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
					<H3>Career<br></H3>
					<h4>직원모집</H4>
					<br>
					<p>
					한남수퍼마켓에서는 우수한 인재들을 발굴하고 적시적소에 배치, <br>
					운영하기 위하여 수시로 입사희망지원을 접수합니다. <br>
					접수된 지원서는 각 부서별 결원 발생시 우선하여 검토하여<br>
					적격자에 한하여 인터뷰 요청이 갈 수 있습니다. <br>
					(접수된 정보들은 한남수퍼마켓 근무희망 인재뱅킹에 등록, <br>
					본 목적으로만 활용됨을 알려드립니다)<br>
					<a href="career_form.php"><button type="button" class="btn" style="cursor:pointer">입사지원 ></button></a><br>
					<em>문의전화 : 버나비 604-420-8856 / 써리점 604-580-3433</em>
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