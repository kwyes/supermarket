<div class="content_wrapper">
	<div class="h1_wrapper stripped">
		<h1>Login</h1>
	</div>

	<div class="login_wrapper">
		<form name="login_form" method="post" action="login_process.php" autocomplete="off">
		<div class="login_form_cont">
			<div class="login_label fl">
				<div><label for="USERID">User ID</label></div>
				<div><label for="USERPW">Password</label></div>
			</div>
			<div class="login_form fl">
				<div><input type="text" name="USERID" required/></div>
				<div><input type="password" name="USERPW" required/></div>
			</div>
			<div class="login_submit fl">
				<input type="submit" value="Sign In" class="login_btn"/>
			</div>
			<br>
			<p class="warning"><?=$_SESSION['login_msg']; ?></p>
		</div>
		</form>
	</div>
</div>
<?
unset($_SESSION['login_msg']); 
?>