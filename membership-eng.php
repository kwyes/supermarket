<?php include 'header.php'; ?>

<style>
<?php include 'css/membership.css'; ?>
<?php include 'css/footer.css'; ?>
</style>

<script>
function check_membership() {
	var membership_number = document.getElementsByName("membership_number")[0].value;

	if (!membership_number || membership_number == "") {
		alert("Please fill out your membership number.");
		return;
	} else if(membership_number.length < 12) {
		alert("Invalid membership number.");
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
					<br>
					<p>
					Join Hannam Membership and earn points <br>
					for everytime you spend!<br>
					The point is shown at the bottom of <br>
					every receipt and also can be check online.<br>
					<br>
					<strong>Benefit 1. Membership Point</strong><br>
					The points can be used as cash.<br>
					Also can be used in different events.<br>
					<br>

					<strong>Benefit 2. Cash Coupon event</strong><br>
					When you shop at Hannam, every $75 spent<br>
					$1.50 cash coupon that can be used as cash <br>
					are printed. (Some products are not added on <br>
					the final amount spent. maximum 4 per visit.)<br>
					
					</p>
				</section>

				<img src="img/cs/membership_img.jpg">
			</article>

			<article>
				<img src="img/hannamsupermarket/circle_icon.jpg">

				<section>
					<H3>Join Hannam Membership  <br></H3>
					
					<br>
					<p>
					Signing up for Hannam membership can be done Customer Service desks.<br>
					If you sign up online, you just have to pick up the membersihp card.<br>
					
					</p>
					<a href ="membership_form.php">
					<button type="button" class="btn" style="cursor:pointer">Join Hannam membership Online ></button></a>
					<br>
					<em>Membership Inquiries : Burnaby 604-420-8856 / Surrey 604-580-3433 </em>
				</section>
			</article>

			<article>
				<img src="img/hannamsupermarket/circle_icon.jpg">

				<section>
					<H3>Check Point Balance <br></H3>
					
					<br>
					<div id="gray_box" name="input_membership">
						<strong>Membership Number:&nbsp;&nbsp;</strong>
						<input class="textbox" type="text" name="membership_number" placeholder=" Membership Card Number" maxlength="12" onKeyPress="if(event.keyCode==13) check_membership(); if((event.keyCode<46) || (event.keyCode>57)) event.returnValue=false;">
						<button type="button" class="btn" id="membership_btn" onClick="check_membership()" style="width:122px; height:38px; cursor:pointer">GO</button>
						<button class="btn2" id="membership_loading" style="display:none;">&nbsp;<img src="img/ajax-loader.gif" style="margin-top:-13px;"></button>
						<p>*Membership point can be checked at the bottom of receipt.</p>
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