<?php include 'header.php'; ?>

<style>
<?php include 'css/giftcard.css'; ?>
<?php include 'css/footer.css'; ?>
</style>

<link href='http://fonts.googleapis.com/css?family=Chango' rel='stylesheet' type='text/css'>

<script>
function check_giftcard() {
	var card_number = document.getElementsByName("giftcard_number")[0].value;

	if (!card_number || card_number == "") {
		alert("상품권 번호를 입력해주십시요.")
		return;
	} else if(card_number.length < 8) {
		alert("잘못된 상품권 번호입니다.");
		return;
	} else {
		document.getElementById("giftcard_btn").style.display = "none";
		document.getElementById("giftcard_loading").style.display = "";

		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				document.getElementById("result").innerHTML = xmlhttp.responseText;
				
				document.getElementsByName("input_giftcard")[0].style.display = "none";
				document.getElementsByName("output_giftcard")[0].style.display = "";
			}
		}
		xmlhttp.open("GET", "admin/customer_handler.php?mode=giftcard&cardNum=" + card_number, true);
		xmlhttp.send();
	}
}

function go_back() {
	document.getElementById("result").innerHTML = "";
	document.getElementById("giftcard_btn").style.display = "";
	document.getElementById("giftcard_loading").style.display = "none";
	document.getElementsByName("output_giftcard")[0].style.display = "none";
	document.getElementsByName("input_giftcard")[0].style.display = "";
}
</script>

<div id="gray_bg" style="background: #f5f5f5; height:100%; padding:10px 0px 0px 0px;">
	<div id="white_wrapper" >
		<nav id="side" class="giftcard"> <!--class to make it highlight-->
			<h3> Customer Service</h3>

			<? $menu = "menu3"; ?>
			<? include_once "leftMenu.php"; ?>
		</nav>

		<div class="content_wrapper">
			<div class="h1_wrapper stripped">
				<h1>Gift Card</h1>
			</div>

			<article>
				<img src="img/hannamsupermarket/circle_icon.jpg">

				<section>
					<H3>HN Gift Card<br></H3>
					<h4>한남수퍼마켓 기프트카드</H4><br>

					<p>
						HN Gift Card는 한남수퍼마켓 전 매장 (버나비/써리)에서<br>
						상품구입시 현금처럼 사용하실 수 있는 카드입니다.<br>
						각 매장‘CS-Customer Service’데스크에서 구입하실 수 있으며,<br>
						필요한 금액만큼 자유롭게 구입하실 수가 있습니다. (충전)<br>
						사용시 아무리 작은 금액도 필요한만큼만 사용하실 수 있으며,<br>
						사용후 잔액에 재충전을 언제든지 하실 수 있습니다.<br>
						<br>
						<em>상품권 충전 및 구입처 :<br>
						버나비점 604-420-8856 / 써리점 604-580-3433<br>
						<br>
						</em>
						- 상품권 번호는 상품권 뒷면의 8자리 숫자입니다. <br>
						- 한남 상품권은 고객님이 원하시는 금액만큼 재충전 및 재사용 하실 수 있습니다. <br>
					</p>
				</section>

				<img src="img/cs/giftcard_img.jpg" style="margin-right:20px;">
			</article>

			<article>
				<img src="img/hannamsupermarket/circle_icon.jpg">

				<section>
					<H3>Remaining Balance <br></H3>
					<h4>카드 잔액조회</h4><br>

					<div id="gray_box" name="input_giftcard">
						<strong>상품권 번호:&nbsp;&nbsp;</strong>
						<input class="textbox" type="text" name="giftcard_number" placeholder="  Gift Card Number" maxlength="8" onKeyPress="if(event.keyCode==13) check_giftcard(); if((event.keyCode < 48) || (event.keyCode > 58)) event.returnValue=false;">
						<button type="button" class="btn" id="giftcard_btn" onClick="check_giftcard()" style="width:86px; height: 38px;">GO</button>
						<button type="button" class="btn2" id="giftcard_loading" style="display:none;">&nbsp;<img src="img/ajax-loader.gif" style="margin-top:0;"></button>
					</div>

					<div id="gray_box" name="output_giftcard" style="display:none;">
						<span id="result"></span>
						<button type="button" class="btn" onClick="go_back()">Back</button>
					</div>

				</section>
			</article>
		</div><!-- content_wrapper  -->
	</div><!-- white_wrapper  -->

	<div class="tothetop" style="background: #f5f5f5;">
		<a href='#top'><img src="img/bottom/top_button.jpg" /></a>
	</div>
</div><!-- gray bg  -->

<?php include 'footer.php'; ?>