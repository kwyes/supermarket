<?php include 'header.php'; ?>

<style>
<?php include 'css/contact.css'; ?>
<?php include 'css/footer.css'; ?>
</style>

<link href='http://fonts.googleapis.com/css?family=Chango' rel='stylesheet' type='text/css'>

<script>
function input_focus(target) {
	target.style.borderColor = '#4d90fe';
}

function input_blur(target) {
	if(!target.value) {
		target.style.borderColor = '#dd4b39';
	} else {
		target.style.borderColor = '#999';
	}
}

function contactus_save_form() {
	var form = document.forms.form_contactus;
	var form_check = true;

	if(!form.contactus_subject.value) {
		form_check = false;
		form.contactus_subject.style.borderColor = "#dd4b39";
	}
	if(!form.contactus_name.value) {
		form_check = false;
		form.contactus_name.style.borderColor = "#dd4b39";
	}
	if(!form.contactus_email.value) {
		form_check = false;
		form.contactus_email.style.borderColor = "#dd4b39";
	}
	if(!form.contactus_content.value) {
		form_check = false;
		form.contactus_content.style.borderColor = "#dd4b39";
	}

	if(!form_check) {
		alert("Please fill out all mandatory fields.");
		return false;
	} else {
		var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if(!filter.test(form.contactus_email.value)) {
			alert('Invalid Email.');
			form.contactus_email.focus();
			form.contactus_email.style.borderColor = "#dd4b39";
			return false;
		}

		var answer = confirm("접수 하시겠습니까:?");
		if(answer) {
			form.mode.value = "contactus";
			form.submit();
		}
	}
}
</script>

<div id="gray_bg" style="background: #f5f5f5; height:100%; padding:10px 0px 0px 0px;">
	<div id="white_wrapper" >
		<nav id="side" class="contact"> <!--class to make it highlight-->
			<h3> Customer Service</h3>

			<? $menu = "menu3"; ?>
			<? include_once "leftMenu.php"; ?>
		</nav>

		<div class="content_wrapper">
			<div class="h1_wrapper stripped">
				<h1>Contact Us</h1>
			</div>

			<article>
				<img src="img/hannamsupermarket/circle_icon.jpg">
				<section>
					<H3>Contact Us<br></H3><br>
					<p>
						Thanks for visiting Hannam Supermarket.<br>
						We would love to hear any suggestions <br>
						regarding any products or service.<br>
						To help us better, please send us   <br>
						detailed message regarding experience with us.<br>
						<br>
					</p>
				</section>
			</article>

			<form name="form_contactus" action="admin/customer_handler.php" method="post" accept-charset="utf-8">
			<input type="hidden" name="mode">
			<section style="border: 1px dashed #5BA8F5; padding-left:30px;">
				<p style="line-height:25px;font-size:14px;font-weight:bold;">
					&#9632;&nbsp;Subject<em>*</em>&nbsp;&nbsp;<br><input class="textbox" type="text" name="contactus_subject" onFocus="input_focus(this)" onblur="input_blur(this)"><br>
					&#9632;&nbsp;Name<em>*</em>&nbsp;&nbsp;<br><input class="textbox" type="text" name="contactus_name" onFocus="input_focus(this)" onblur="input_blur(this)"><br>
					&#9632;&nbsp;E-mail<em>*</em>&nbsp;&nbsp;<br><input class="textbox" type="text" name="contactus_email" onFocus="input_focus(this)" onblur="input_blur(this)"><br>
					&#9632;&nbsp;Phone&nbsp;&nbsp;<br><input class="textbox" type="text" name="contactus_phone" onkeyup="this.value=this.value.replace(/[^0-9-]/g,'');" onkeypress='return ((event.charCode >= 48 && event.charCode <= 57) || event.charCode == 45)' maxlength=12><br>
					&#9632;&nbsp;Content<em>*</em>&nbsp;&nbsp;<br>
				</p>

				<textarea name="contactus_content" cols=90 rows=10 class="simpleform" style="resize:vertical; padding:20px;width:235px; height:150px;" onFocus="input_focus(this)" onblur="input_blur(this)"></textarea> 

				<button type="button" class="btn" style="margin:30px 0 0 170px; cursor:pointer" onClick="contactus_save_form()">Submit</button>
			</section>
			</form>
		</div><!-- content_wrapper  -->
	</div><!-- white_wrapper  -->
	<div class="tothetop" style="background: #f5f5f5;">
		<a href='#top'><img src="img/bottom/top_button.jpg" /></a>
	</div>
</div><!-- gray bg  -->

<?php include 'footer.php'; ?>