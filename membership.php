<?php include 'header.php'; ?>

<style>
<?php include 'css/membership.css'; ?>
<?php include 'css/footer.css'; ?>
</style>

<script>
function check_membership() {
	var membership_number = document.getElementsByName("membership_number")[0].value;

	if (!membership_number || membership_number == "") {
		alert("멤버쉽 번호를 입력해주십시요.");
		return;
	} else if(membership_number.length < 6) {
		alert("잘못된 멤버쉽 번호입니다.");
		return;
	} else {
		document.getElementById("membership_btn").style.display = "none";
		document.getElementById("membership_loading").style.display = "";

		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				document.getElementById("result").innerHTML = xmlhttp.responseText;
				
				document.getElementsByName("input_membership")[0].style.display = "none";
				document.getElementsByName("output_membership")[0].style.display = "";
			}
		}
		xmlhttp.open("GET", "admin/customer_handler.php?mode=membership&cardNum=" + membership_number, true);
		xmlhttp.send();
	}
}

function go_back() {
	document.getElementById("result").innerHTML = "";
	document.getElementById("membership_btn").style.display = "";
	document.getElementById("membership_loading").style.display = "none";
	document.getElementsByName("output_membership")[0].style.display = "none";
	document.getElementsByName("input_membership")[0].style.display = "";
}
</script>

<link href='http://fonts.googleapis.com/css?family=Chango' rel='stylesheet' type='text/css'>


<div id="gray_bg" style="background: #f5f5f5; height:100%; padding:10px 0px 0px 0px;">
	<div id="white_wrapper" >
		<nav id="side" class="membership"> <!--class to make it highlight-->
			<h3> Customer Service </h3>

			<? $menu = "menu3"; ?>
			<? include_once "leftMenu.php"; ?>
		</nav>

		<div class="content_wrapper">
			<div class="h1_wrapper stripped">
				<h1>Hannam Membership</h1>
			</div>

			<article>
				<img src="img/hannamsupermarket/circle_icon.jpg">

				<section>
					<H3>Hannam Membership<br></H3>
					<h4>한남 멤버쉽</H4>
					<br>
					<p>
					[한남멤버쉽]에 가입하시고, 쇼핑시마다 누적되는 다양한 <br>
					혜택을 누리세요! 계산시 멤버쉽카드를 제시하시면 포인트가 <br>
					적립되며 영수증에 표시가 됩니다. (적립된 포인트는 매 <br>
					계산시의 영수증 하단 및 웹사이트에서 확인 가능합니다.) <br>
					<br>
					<strong>혜택 1. 멤버쉽 포인트</strong><br>
					적립된 포인트는 포인트에 따라 현금처럼 사용 가능하며,  <br>
					각종 행사시 사은품 및 리딤상품 등 다양한 혜택을 받으실 <br>
					수 있습니다.<br>
					<br>
					<strong>혜택 2. 캐쉬쿠폰</strong><br>
					한남에서 쇼핑하실때 매 75불 구매시마다 영수증 하단에 <br>
					1.5불 상당의 현금처럼 사용이 가능한 ‘캐쉬쿠폰’이 함께 <br>
					발행됩니다. (매 행사시마다 일부상품은 총금액에 포함되지 <br>
					않을 수 있습니다. 1회 최대 4장까지 발행) <br>
					</p>
				</section>

				<img src="img/cs/membership_img.jpg">
			</article>

			<article>
				<img src="img/hannamsupermarket/circle_icon.jpg">

				<section>
					<H3>Join Hannam Membership  <br></H3>
					<h4>한남멤버쉽 가입하기</h4>
					<br>
					<p>
					한남멤버쉽 가입신청은 버나비/써리 각 매장 CS(Customer Service) 데스크에서도 가능하며, <br> 
					온라인에서 가입신청하실 경우 온라인 신청(하기 클릭)후 각 매장 CS에서 회원카드를 받으시면 됩니다. <br>
					<font color="red"><b>*온라인으로 가입하신경우 회원카드를 받으신 후 사용할 수 있습니다.</b></font> <br>
					</p>
					<a href ="membership_form.php">
					<button type="button" class="btn" style="cursor:pointer">온라인 가입 신청하기 ></button></a>
					<br>
					<em>멤버쉽 문의전화 : 버나비 604-420-8856 / 써리 604-580-3433 </em>
				</section>
			</article>

			<article>
				<img src="img/hannamsupermarket/circle_icon.jpg">

				<section>
					<H3>Check Point Balance <br></H3>
					<h4>포인트 조회</h4>
					<br>
					<div id="gray_box" name="input_membership">
						<strong>멤버쉽 번호:&nbsp;&nbsp;882004</strong>
						<input class="textbox" type="text" name="membership_number" placeholder=" Membership Card Number" maxlength="6" onKeyPress="if(event.keyCode==13) check_membership(); if((event.keyCode<46) || (event.keyCode>57)) event.returnValue=false;">
						<button type="button" class="btn" id="membership_btn" onClick="check_membership()" style="width:122px; height:38px; cursor:pointer">GO</button>
						<button class="btn2" id="membership_loading" style="display:none;">&nbsp;<img src="img/ajax-loader.gif" style="margin-top:-13px;"></button>
						<p>*membership point 조회는 웹사이트에서 확인 가능합니다.(동일)<br>
						*보유하신 영수증 하단에서 멤버쉽 번호를 확인 하실수 있습니다.</p>
					</div>

					<div id="gray_box" name="output_membership" style="display:none;">
						<span id="result"></span>
						<button type="button" class="btn" onClick="go_back()" style="cursor:pointer">Back</button>
					</div>
				</section>
			</article>
		</div>
	</div><!-- white_wrapper  -->

	<div class="tothetop" style="background: #f5f5f5;">
		<a href='#top'><img src="img/bottom/top_button.jpg" /></a>
	</div>
</div><!-- gray bg  -->

<a name="points"></a>

<?php include 'footer.php'; ?>