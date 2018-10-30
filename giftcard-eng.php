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
		alert("Please fill out your gift card number.")
		return;
	} else if(card_number.length < 8) {
		alert("Invalid gift card number.");
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
					<H3>HN Gift Card<br></H3><br>
					<p>
						HN Gift Card can be used in all Hannam Supermarket (Burnaby/Surrey)<br>
						like cash when you purchase products.<br>
						You can buy them in any of the Customer Service Desk.<br>
						You can choose how much money you would put in.(charge)<br>
						You can spend any small amount and can be recharged anytime.<br>
						<br>
						<em>Any Inquiries about Gift Card :<br>
						Burnaby 604-420-8856 / Surrey 604-580-3433<br>
						<br>
						</em>
						- The Gift Card number is the last 8 digit of the card. <br>
						- You can recharge any amount and can be reused. <br>
					</p>
				</section>
				<img src="img/cs/giftcard_img.jpg" style="margin-right:20px;">
			</article>

			<article>
				<img src="img/hannamsupermarket/circle_icon.jpg">

				<section>
					<H3>Remaining Balance <br></H3><br>

					<div id="gray_box" name="input_giftcard">
						<strong>Gift Card Number:&nbsp;&nbsp;</strong>
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