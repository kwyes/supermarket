<script>
function toggle_popup_checkbox() {
	if(document.getElementById("popup_checkbox").checked == true) {
		document.getElementById("popup_checkbox").checked = false;
	} else {
		document.getElementById("popup_checkbox").checked = true;
	}
}
function hide_popup() { 
	if(document.getElementById("popup_checkbox").checked == true) {
		notice_setCookie('popup_main', 'done', 1);
	}
	document.getElementById("popup_wrapper").style.display = "none"; 
}

function notice_setCookie(name, value, expiredays) { 
	var todayDate = new Date(); 
	todayDate.setDate(todayDate.getDate() + expiredays); 
	document.cookie = name + '=' + escape(value) + '; path=/; expires=' + todayDate.toGMTString() + ';' 
	return; 
}
</script>
<?
if($_COOKIE['popup_main'] != "done") {
	$popup_checked = "block";

	$popup_path = "upload/mainPage/";
	$db_type = 5;
	// width = subject, height = content
		
	$subject = "우우우우워워워워";
	$filename = "test.jpg";
	$content = "ttttttt";


?>

	<div id="popup_wrapper" style="display:<?=$popup_checked?>; z-index:99999; background-color:#CCCCCC;margin-left:19.7%; margin-top:0.7%; position:absolute; left:-50px; top:125px;">
		<div style="padding-top:5px; padding-left:5px;">
			<a target="_blank"><img src="middle.jpg"></a>			
		</div>

		<div style="padding-top:5px; padding-left:13px; padding-right:13px;">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="75%" align="left">
						<input type="checkbox" id="popup_checkbox" name="popupCookie"  style="cursor:pointer"> 
						<!--<span onclick="toggle_popup_checkbox();" style="cursor:pointer">오늘 하루 안보이기</span>-->
						<span onclick="notice_setCookie('popup_main', 'done', 1); hide_popup();" style="cursor:pointer">오늘 하루 안보이기</span>
					</td>
					<td width="25%" align="right">
						<a href="#" onClick="hide_popup();return false" style="text-decoration:none;"><font style="color:#222222;">X</font></a>
					</td>
				</tr>
			</table>
		</div>
	</div>
<?
}
?>